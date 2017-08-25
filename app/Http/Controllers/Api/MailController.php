<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Mail;
use Carbon\Carbon;
use App\Models\EmailDatum;
use App\Models\User;
use App\NanaSystem\CustomPresenter;
use App\NanaSystem\Common;


/**
 * Class MailController
 * @package App\Http\Controllers\Api\V1
 */
class MailController extends Controller
{

    const DISP_NUM = 10;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // 認証処理せ
        $this->middleware('auth');
    }

    /**
     * 検索結果から一覧を返す
     *
     * @param Request $request
     * @param App\Models\Product;
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(Request $request)
    {

        // ページを取得
        $page = $request->input("page");
        $kind = $request->input('kind');

        // クエリの作成
        $query = EmailDatum::query()->where('kind', $kind)->orderBy('id', 'desc');

        // ページング処理
        $pagination = $query->paginate(self::DISP_NUM, null, null, $page);

        // 取得結果を配列にする
        $items      = $pagination->toArray();

        foreach ($items["data"] as $key => $value) {
            $addressModel = User::where('email', $value['from_address'])->first();
            if(is_null($addressModel) || $addressModel->name == "") {
                $items["data"][$key]['from_name'] = $value['from_address'];
            } else {
                $items["data"][$key]['from_name'] = $addressModel->name;
            }

            $addressModel = User::where('email', $value['to_address'])->first();
            if(is_null($addressModel) || $addressModel->name == "") {
                $items["data"][$key]['to_name'] = $value['to_address'];
            } else {
                $items["data"][$key]['to_name'] = $addressModel->name;
            }
        }

        // 取得件数
        $allCnt    = $items["total"];

        // ページング表示のhtml
        $render = (new CustomPresenter($pagination))->render();

        // jsonで画面に返す
        return response()->json([
            'list'    => $items["data"],        // 一覧データ
            'render'  => $render,               // ページングデータ
            'allCnt'  => $allCnt,                // 全件数
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to_name' => 'required|string|max:50',
            'to_address' => 'email|string|max:50',
            'title' => 'required|string|max:50',
            'contents'     => 'required|string|max:2000',
        ]);

        // validateのチェック
        if ($validator->fails()) {
            // エラーがある場合はエラーを返す
            return response()->json([
                'errors' => $validator->messages()->all()
            ], 200, [], JSON_PRETTY_PRINT);
        } else if ($request->input('to_name') == 'manual' && trim($request->input('to_address')) == '') {
            return response()->json([
                'errors' => ['手動入力の際は宛先を必ず入力して下さい。']
            ], 200, [], JSON_PRETTY_PRINT);
        }

        $input = $request->all();
        $config = \Config::get('settings');

        $contents = $input['contents'];
        $errorAddress = [];

        if ($request->input('to_name') == 'all') {
            $members = User::get()->toArray();
            foreach ($members as $key => $value) {
                if (strpos($value['email'], '..') > -1 || strpos($value['email'], '.@') > -1) {
                    $errorAddress[] = $value['name'];
                } else {
                  Mail::send(['text' => 'email.reminder'], ['body' => $contents], function ($message) use ($config, $input, $value) {
                      $message->to($value['email'], $value['name'])
                          ->from($config['mail_address'], $config['mail_name'])
                          ->subject($input['title']);
                  });
                }
            }
        } else {
            if (strpos($input['to_address'], '..') > -1 && strpos($input['to_address'], '.@') > -1) {
                $errorAddress[] = $input['to_name'];
            } else {
              Mail::send(['text' => 'email.reminder'], ['body' => $contents], function ($message) use ($config, $input) {
                  $message->to($input['to_address'], $input['to_name'])
                      ->from($config['mail_address'], $config['mail_name'])
                      ->subject($input['title']);
              });
            }
        }

        $reply_id = null;
        if ($input['reply_id'] != '') {
            $reply_id = $input['reply_id'];
            $replyModel = EmailDatum::find($reply_id);
            $replyModel->is_reply = 1;
            $replyModel->save();
        }

        $emailModel = new EmailDatum();
        $emailModel->title = $input['title'];
        $emailModel->kind = 1;
        $emailModel->to_name = $input['to_name'];
        $emailModel->to_address = $input['to_address'];
        $emailModel->from_name = $config['mail_name'];
        $emailModel->from_address = $config['mail_address'];
        $emailModel->is_read = 0;
        $emailModel->is_reply = 0;
        $emailModel->contents = $input['contents'];
        $emailModel->date = Carbon::now();
        $emailModel->save();

        return response()->json([
            'reply_id' => $reply_id,
            'error_address' => $errorAddress
        ], 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * 詳細画面のデータを返す
     *
     * @param Request $request
     * @param App\Models\Product;
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request)
    {
        // メールデータを取得
        $mailListModel = EmailDatum::find($request->input('id'));
        $mailList = $mailListModel->toArray();
        $mailList['contents'] = Common::convertEOL($mailList['contents']);
        $addressModel = User::where('email', $mailList['from_address'])->first();
        if (is_null($addressModel) || $addressModel->name == "") {
            $mailList['from_name'] = $mailList['from_address'];
        } else {
            $mailList['from_name'] = $addressModel->name;
        }

        $addressModel = User::where('email', $mailList['to_address'])->first();
        if (is_null($addressModel) || $addressModel->name == "") {
            $mailList['to_name'] = $mailList['to_address'];
        } else {
            $mailList['to_name'] = $addressModel->name;
        }

        $mailListModel->is_read = 1;
        $mailListModel->save();

        // jsonでデータを返す
        return response()->json([
            'mailList'  => $mailList,
        ], 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * ユーザーのデータを返す
     *
     * @param Request $request
     * @param App\Models\Product;
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {

        $user = User::find($request->input('id'))->toArray();

        // jsonでデータを返す
        return response()->json([
            'user'  => $user,
        ], 200, [], JSON_PRETTY_PRINT);
    }

}

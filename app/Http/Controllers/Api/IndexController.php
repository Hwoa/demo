<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Carbon\Carbon;
use App\NanaSystem\CustomPresenter;
use App\NanaSystem\Common;
use App\Models\User;
use App\Models\PlanDatum;
use App\Models\PlaceDatum;
use App\Models\ScheduleDatum;

/**
 * Class IndexController
 *
 * @package App\Http\Controllers\Api\V1
 */
class IndexController extends Controller
{

    protected $loginPath = "login";

    const DISP_NUM = 3;

    public function __construct()
    {

    }

    public function lists(Request $request)
    {
        // 認証されていない場合はリダイレクト
        if (!$request->user()) {
            return redirect()->intended($this->loginPath);
        }

        // ページ番号の取得
        $page = $request->input("page");

        $userIds = [];
        $list = User::get()->toArray();

        foreach ($list as $value) {
            $userIds[] = $value['id'];
        }

        $datetime = Carbon::now();
        $query = PlanDatum::query()->where('start', '>', $datetime)->orderBy('start', 'asc');

        $pagination = $query->paginate(self::DISP_NUM, null, null, $page);

        // ページング表示のhtml
        $render = (new CustomPresenter($pagination))->render();

        // 取得結果を配列にする
        $data = $pagination->toArray();
        $plans = $data['data'];

        $planIds = [];

        // 場所データの紐付け
        foreach ($plans as $key => $value) {
            $placeModel = PlaceDatum::find($value['place_id']);
            if (is_null($placeModel)) {
                $plans[$key]['place_name'] = "";
                $plans[$key]['map'] = "";
            } else {
                $place = $placeModel->toArray();
                $plans[$key]['place_name'] = $place['name'];
                $plans[$key]['map'] = $place['map'];
            }
            $planIds[] = $value['id'];
        }

        $schedules = ScheduleDatum::whereIn('user_id', $userIds)->whereIn('plan_id', $planIds)->get()->toArray();

        foreach ($schedules as $key => $value) {
            $schedules[$key]['description'] = Common::convertEOL($value['description'], "\r");
        }

        // jsonで画面に返す
        return response()->json(['list' => $list, 'plans' => $plans, 'render'  => $render, 'schedules' => $schedules], 200, [], JSON_PRETTY_PRINT);
    }

    public function edit(Request $request)
    {
        $model = ScheduleDatum::where('user_id', $request->input('user_id'))->where('plan_id', $request->input('plan_id'))->first();
        $list = null;
        if (!is_null($model)) {
            $list = $model->toArray();
        }
        return response()->json(['list' => $list],
            200, [], JSON_PRETTY_PRINT);
    }

    public function save(Request $request) {

        $validator = Validator::make($request->all(), [
            "presence_id"         => "required",
            "description"         => "max:1000",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()->all()], 200, [], JSON_PRETTY_PRINT);
        }

        $model = ScheduleDatum::where('user_id', $request->input('user_id'))->where('plan_id', $request->input('plan_id'))->first();

        if (is_null($model)) {
            $model = new ScheduleDatum();
        }

        $model->user_id = $request->input('user_id');
        $model->plan_id = $request->input('plan_id');
        $model->presence_id = $request->input('presence_id');
        $model->description = $request->input('description');
        $model->save();

        return response()->json([],200, [], JSON_PRETTY_PRINT);
    }

    public function user(Request $request)
    {
        $model = User::find($request->input('id'));
        if (is_null($model)) {
            $list = null;
        } else {
            $list = $model->toArray();
        }
        return response()->json(['list' => $list],200, [], JSON_PRETTY_PRINT);
    }

    public function submit(Request $request) {
        
        $model = User::find($request->input('id'));
        
        $validateRules = [
            "name"   => "required|max:50",
            "email"  => "required|email|max:100|confirmed",
            
        ];
        
        $validateArray = [];
        $validateModel = User::where('email', $request->input('email'));

        if (is_null($model)) {
            $model = new User();
            $validateRules["password"] = "required|min:6|max:20|confirmed";
        } else {
            $validateModel->where('id', '<>', $request->input('id'));
        }
        $validateArray = $validateModel->get()->toArray();

        $validator = Validator::make($request->all(), $validateRules);
        
        if ($validator->fails() || count($validateArray) > 0) {
            $errors = [];
            if ($validator->fails()) {
                $errors = $validator->messages()->all();
            }
            if (count($validateArray) > 0) {
                $errors[] = "既に同一のメールアドレスが登録されています。";
            }
            return response()->json(['errors' => $errors], 200, [], JSON_PRETTY_PRINT);
        }

        $model->name = $request->input('name');
        $model->email = $request->input('email');
        if ($request->input('password') != "") {
            $model->password = bcrypt($request->input('password'));
        }

        if ($request->has('is_admin')) {
            $model->is_admin = $request->input('is_admin');
        }

        $model->save();

        return response()->json([],200, [], JSON_PRETTY_PRINT);
    }

    public function delete(Request $request) {

        $model = User::find($request->input('id'));
        $model->delete();

        return response()->json([],200, [], JSON_PRETTY_PRINT);
    }

    public function map(Request $request) {
        $list = PlaceDatum::find($request->input('id'))->toArray();
        return response()->json(['list' => $list],200, [], JSON_PRETTY_PRINT);
    }
}
<?php
namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;

/**
 * Class MailController
 *
 * @package App\Http\Controllers\Api\V1
 */
class MailController extends Controller
{
    use \App\Traits\NavigationMenuTrait;

    protected $loginPath = "login";

    public function __construct()
    {
        $this->addMenuTabItemsForViewShare();
    }

    /**
     * 検索画面を表示します
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        // 認証されていない場合はリダイレクト
        if (!$request->user()) {
            return redirect()->intended($this->loginPath);
        }

        $user = $request->user();

        if ($user['attributes']['is_admin'] != 1) {
            return redirect()->intended('index');
        }

        $users =  User::get()->toArray();

        $userSelect['all'] = '※全員に送信';
        $userSelect['manual'] = '※手動入力';
        foreach ($users as $key => $value) {
            $userSelect[$value['id']] = $value['name'];
        }

        $config = \Config::get('settings');

        return view('pages.mail', [
            'user_id' => json_encode($user['attributes']['id']),
            'is_admin' => $user['attributes']['is_admin'],
            'kind' => $request->input('kind'),
            'dt' => Carbon::now(),
            'isDone' => json_encode($config['is_done']),
            'userSelect' => $userSelect,
        ]);
    }
}
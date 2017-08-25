<?php
namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PlaceDatum;

/**
 * Class PlanController
 *
 * @package App\Http\Controllers\Api\V1
 */
class PlanController extends Controller
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

        $places = PlaceDatum::get()->toArray();

        $user = $request->user();

        if ($user['attributes']['is_admin'] != 1) {
            return redirect()->intended('index');
        }

        return view('pages.plan', [
            'user_id' => json_encode($user['attributes']['id']),
            'is_admin' => $user['attributes']['is_admin'],
            'places' => $places,
        ]);
    }
}
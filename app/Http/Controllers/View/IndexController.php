<?php
namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class IndexController
 *
 * @package App\Http\Controllers\Api\V1
 */
class IndexController extends Controller
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

        $config = \Config::get('settings');
        $presences = $config['presences'];
        $colors = $config['colors'];
        $presences_text = $config['presences_text'];
        $is_admin_text = $config['is_admin_text'];

        $user = $request->user();

        return view('pages.index', [
                                'user_id' => json_encode($user['attributes']['id']),
                                'is_admin' => $user['attributes']['is_admin'],
                                'presences' =>json_encode($presences),
                                'colors' => json_encode($colors),
                                'is_admin_text' => json_encode($is_admin_text),
                                'presences_text' => $presences_text,
        ]);
    }
}
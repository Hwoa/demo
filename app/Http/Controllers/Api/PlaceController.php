<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\PlaceDatum;

/**
 * Class PlaceController
 *
 * @package App\Http\Controllers\Api\V1
 */
class PlaceController extends Controller
{

    protected $loginPath = "login";

    const DISP_NUM = 4;

    public function __construct()
    {

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

        return view('index', [
        ]);
    }

    public function lists(Request $request)
    {
        // 認証されていない場合はリダイレクト
        if (!$request->user()) {
            return redirect()->intended($this->loginPath);
        }

        $list = PlaceDatum::get()->toArray();

        // jsonで画面に返す
        return response()->json(['list' => $list], 200, [], JSON_PRETTY_PRINT);
    }

    public function edit(Request $request)
    {
        $model = PlaceDatum::find($request->id);
        $list = null;
        if (!is_null($model)) {
            $list = $model->toArray();
        }
        return response()->json(['list' => $list],
            200, [], JSON_PRETTY_PRINT);
    }

    public function save(Request $request) {

        $validator = Validator::make($request->all(), [
            "name"          => "required|max:100",
            "map"           => "max:1000",
            "description"  => "max:1000",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()->all()], 200, [], JSON_PRETTY_PRINT);
        }

        if ($request->input('id') != "") {
            $model = PlaceDatum::find($request->input('id'));
        } else {
            $model = new PlaceDatum();
        }

        $model->name = $request->input('name');
        $model->map = $request->input('map');
        $model->description = $request->input('description');
        $model->save();

        return response()->json([],200, [], JSON_PRETTY_PRINT);
    }

    public function delete(Request $request) {

        $model = PlaceDatum::find($request->input('id'));
        $model->delete();

        return response()->json([],200, [], JSON_PRETTY_PRINT);
    }
}
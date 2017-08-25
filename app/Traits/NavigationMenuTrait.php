<?php
namespace App\Traits;

/**
 * ナビゲーションメニュー用の処理
 */
trait NavigationMenuTrait
{
    // メニュータブ項目保持用
    private $menuTabItems;

    /**
     * ViewShare にメニュータブ項目を追加
     */
    public function addMenuTabItemsForViewShare()
    {
        // メニュー項目を生成
        $this->menuTabItems = $this->generateMenuTabItems();

        // ViewShare に登録
        view()->share('shareTabMenuItems', $this->menuTabItems);
        view()->share('shareActiveTabName', $this->getActiveTabName($this->menuTabItems));
    }

    /**
     * メニュー項目を生成する。
     * @return string
     */
    private function generateMenuTabItems()
    {

        $items = [];// メニュー項目保持用
        //
        $items[] = ['name' => '一覧', 'url'  => 'index', 'is_admin' => 0];

        $items[] = ['name' => '日程', 'url'  => 'plan', 'is_admin' => 1];

        $items[] = ['name' => '会場', 'url'  => 'place', 'is_admin' => 1];

        $items[] = ['name' => 'メール', 'url'  => 'mail?kind=0', 'is_admin' => 1];

        $items[] = ['name' => '送信済', 'url'  => 'mail?kind=1', 'is_admin' => 1];

        return $items;
    }

    /**
     * リクエスト情報を元にメニュー項目の中からアクティブ表示すべきタブ名を返却する。
     * @return string
     */
    private function getActiveTabName($menuItems)
    {
        $fullUrl = \Request::fullUrl();
        $root = \Request::root();

        foreach ($menuItems as $item) {
            if ($fullUrl === $root . '/' . $item['url']) {
                return $item['name'];
            }
        }

        return 'none';
    }
}

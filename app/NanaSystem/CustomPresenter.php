<?php
namespace App\NanaSystem;

use \Illuminate\Pagination\BootstrapThreePresenter;

class CustomPresenter extends BootstrapThreePresenter
{
    /**
     * Convert the URL window into Bootstrap HTML.
     *
     * @return string
     */
    public function render()
    {
        if ($this->hasPages()) {
            return sprintf(
                '<ul class="pagination mt5 mb5">%s</ul>',
                $this->getLinks()
            );
        } else {
            return sprintf(
                '<ul class="pagination mt5 mb5">%s</ul>',
                '<li class="active"><span>1</span></li>'
            );
        }
    }

    /**
     * Get HTML wrapper for an available page link.
     *
     * @param  string  $url
     * @param  int  $page
     * @param  string|null  $rel
     * @return string
     */
    protected function getAvailablePageWrapper($url, $page, $rel = null)
    {
        return '<li onClick="paging('.$page.')"><a href="javascript:void(0);">'.$page.'</a></li>';
    }
}
<?php

namespace Modules\Pages\Controllers;

use Mindy\Base\Mindy;
use Mindy\Pagination\Pagination;
use Modules\Core\Controllers\CoreController;
use Modules\Feedback\Forms\HelpForm;
use Modules\Pages\Models\Page;

class PageController extends CoreController
{
    public $defaultAction = 'view';

    protected function getView(Page $model)
    {
        return "pages/" . $model->getView();
    }

    public function actionView($url = null)
    {
        if (empty($url)) {
            $qs = Page::objects()->filter(['is_index' => true, 'is_published' => true]);
        } else {
            $qs = Page::objects()->filter(['is_published' => true, 'url' => '/' . $url]);
        }

        $cache = Mindy::app()->cache;
        $model = $cache->get('page_' . $url, $qs->get());
        if($model === null) {
            $this->error(404);
        }

        $this->fetchBreadrumbs($model);

        echo $this->actionInternal($model);
    }

    protected function fetchBreadrumbs(Page $model)
    {
        if(!$model->is_index) {
            $pages = $model->tree()->ancestors()->all();
            foreach ($pages as $page) {
                $this->addTitle($page->name);
                $this->addBreadcrumb($page->name, $page->getAbsoluteUrl());
            }
            $this->addTitle($model->name);
            $this->addBreadcrumb($model->name, $model->getAbsoluteUrl());
        }
    }

    public function actionInternal(Page $model)
    {
        $qs = $model->tree()->children();
        if ($model->sorting) {
            $qs = $qs->filter([
                'is_published' => true
            ])->order([$model->sorting]);
        }

        $pager = new Pagination($qs);
        $children = $pager->paginate();
        return $this->render($this->getView($model), [
            'model' => $model,
            'children' => $children,
            'pager' => $pager
        ]);
    }
}

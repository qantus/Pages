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

    /**
     * @param Page $model
     * @return string
     */
    protected function getView(Page $model)
    {
        return "pages/" . $model->getView();
    }

    public function actionView($url = null)
    {
        $data = empty($url) ? ['is_index' => true] : ['url' => '/' . $url];
        $qs = $qs = Page::objects()->published()->filter($data);

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
            /** @var Page[] $pages */
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
        $pager = new Pagination($this->getQuerySet($model));
        $children = $pager->paginate();
        return $this->render($this->getView($model), [
            'model' => $model,
            'children' => $children,
            'pager' => $pager,
            'hasComments' => $model->hasField('comments')
        ]);
    }

    /**
     * @param Page $model
     * @return \Mindy\Orm\QuerySet
     */
    protected function getQuerySet(Page $model)
    {
        $qs = $model->objects()->published()->children();
        if ($model->sorting) {
            $qs = $qs->order([$model->sorting]);
        }
        return $qs;
    }
}

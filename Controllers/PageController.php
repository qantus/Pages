<?php

namespace Modules\Pages\Controllers;

use Mindy\Base\Mindy;
use Mindy\Pagination\Pagination;
use Modules\Core\Controllers\CoreController;
use Modules\Feedback\Forms\HelpForm;
use Modules\Pages\Models\Page;

/**
 * Class PageController
 * @package Modules\Pages
 */
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
        $data = empty($url) ? ['is_index' => true] : ['url' => '/' . ltrim($url, '/')];
        $qs = Page::objects()->published()->filter($data);

        $cache = Mindy::app()->cache;
        $model = $cache->get('page_' . $url, $qs->get());
        if($model === null) {
            $this->error(404);
        }

        $this->setCanonical($model);
        $this->fetchBreadrumbs($model);

        echo $this->actionInternal($model);
    }

    protected function fetchBreadrumbs(Page $model)
    {
        if(!$model->is_index) {
            /** @var Page[] $pages */
            $pages = $model->tree()->ancestors()->order(['level'])->all();
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
        $pager = new Pagination($model->getChildrenQuerySet());
        $children = $pager->paginate();
        return $this->render($this->getView($model), [
            'model' => $model,
            'children' => $children,
            'pager' => $pager,
            'hasComments' => $model->hasField('comments')
        ]);
    }
}

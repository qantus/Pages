<?php
/**
 * 
 *
 * All rights reserved.
 * 
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 11/09/14.09.2014 12:36
 */

namespace Modules\Pages\Controllers;

use Mindy\Base\Mindy;
use Mindy\Orm\Model;
use Modules\Comments\Controllers\BaseCommentController;
use Modules\Comments\Models\BaseComment;
use Modules\Pages\Models\Comment;
use Modules\Pages\Models\Page;

class CommentController extends BaseCommentController
{
    /**
     * @return \Modules\Comments\Models\BaseComment
     */
    public function getModel()
    {
        return new Comment;
    }

    public function actionView($url)
    {
        $qs = Page::objects()->published()->filter(['url' => '/' . $url]);

        $cache = Mindy::app()->cache;
        $model = $cache->get('page_' . $url . '_comments', $qs->get());
        if($model === null) {
            $this->error(404);
        }

        $this->internalActionList($model);
    }

    /**
     * @param Model $model
     * @param \Mindy\Orm\Manager|\Mindy\Orm\QuerySet $qs
     * @return \Mindy\Orm\Manager|\Mindy\Orm\QuerySet
     */
    public function processComments(Model $model, $qs)
    {
        return $qs->filter(['page' => $model]);
    }

    /**
     * @param Comment $model
     * @return Comment
     */
    public function processComment(BaseComment $model)
    {
        return $model;
    }
}

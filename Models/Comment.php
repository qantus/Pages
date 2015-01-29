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
 * @date 11/09/14.09.2014 12:34
 */

namespace Modules\Pages\Models;

use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Fields\TreeForeignField;
use Modules\Comments\Models\BaseComment;
use Modules\Pages\PagesModule;

/**
 * Class Comment
 * @package Modules\Pages
 * @method static \Modules\Comments\Models\CommentManager objects($instance = null)
 */
class Comment extends BaseComment
{
    public static function getFields()
    {
        return array_merge(parent::getFields(), [
            'page' => [
                'class' => TreeForeignField::className(),
                'modelClass' => Page::className(),
                'verboseName' => PagesModule::t('Page')
            ]
        ]);
    }

    /**
     * @return BaseComment
     */
    public function getRelation()
    {
        return $this->page;
    }
}

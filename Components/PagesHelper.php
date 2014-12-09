<?php

namespace Modules\Pages\Components;

use Mindy\Base\Mindy;
use Mindy\Utils\RenderTrait;

/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 14/05/14.05.2014 16:34
 */

/**
 * Class BlockHelper
 * @package Modules\Pages
 */
class PagesHelper
{
    public static function getPages($parentId, $limit = 10, $offset = 0, $order = [])
    {
        $modelClass = Mindy::app()->getModule('Pages')->pagesModel;
        return $modelClass::objects()->filter([
            'parent_id' => $parentId
        ])->limit($limit)->offset($offset)->order($order)->all();
    }
}

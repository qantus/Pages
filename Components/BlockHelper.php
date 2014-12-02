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
class BlockHelper
{
    public static function render($slug, $attribute = 'content')
    {
        $cls = Mindy::app()->getModule('Pages')->blockModel;
        $model = $cls::objects()->filter(['slug' => $slug])->get();
        return $model === null ? null : $model->{$attribute};
    }

    public static function fetch($slug)
    {
        $cls = Mindy::app()->getModule('Pages')->blockModel;
        return $cls::objects()->filter(['slug' => $slug])->get();
    }
}

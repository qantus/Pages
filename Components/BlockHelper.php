<?php

namespace Modules\Pages\Components;

use Mindy\Utils\RenderTrait;
use Modules\Pages\Models\Block;

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
class BlockHelper
{
    public static function render($slug, $attribute = 'content')
    {
        $model = Block::objects()->filter(['slug' => $slug])->get();
        return $model === null ? null : $model->{$attribute};
    }
}

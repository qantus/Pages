<?php

namespace Modules\Pages\Models;

use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;

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
 * @date 18/04/14.04.2014 20:08
 */
class Block extends Model
{
    public static function getFields()
    {
        return [
            'slug' => ['class' => CharField::className()],
            'content' => ['class' => TextField::className()],
        ];
    }
}

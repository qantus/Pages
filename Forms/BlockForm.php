<?php

namespace Modules\Pages\Forms;

use Mindy\Base\Mindy;
use Mindy\Form\Fields\WysiwygField;
use Mindy\Form\ModelForm;

/**
 * Class BlockForm
 * @package Modules\Pages
 */
class BlockForm extends ModelForm
{
    public function getFields()
    {
        return [
            'content' => [
                'class' => WysiwygField::className()
            ],
        ];

    }

    public function getModel()
    {
        $cls = Mindy::app()->getModule('Pages')->blockModel;
        return new $cls;
    }
}

<?php

namespace Modules\Pages\Forms;

use Mindy\Form\Fields\WysiwygField;
use Mindy\Form\ModelForm;
use Modules\Pages\Models\Block;

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
        return new Block;
    }
}

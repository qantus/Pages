<?php

namespace Modules\Pages\Forms;

use Mindy\Form\Fields\TextField;
use Mindy\Form\Fields\WysiwygField;
use Mindy\Form\ModelForm;
use Modules\Pages\Models\Block;

class BlockForm extends ModelForm
{
    public function getFields()
    {
        return [
            'slug' => ['class' => TextField::className()],
            'name' => ['class' => TextField::className()],
            'content' => ['class' => WysiwygField::className()],
        ];

    }

    public function getModel()
    {
        return new Block();
    }
}

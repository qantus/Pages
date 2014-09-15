<?php

namespace Modules\Pages\Forms;

use Mindy\Form\ManagedForm;
use Modules\Meta\Forms\MetaInlineForm;

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
 * @date 08/05/14.05.2014 19:33
 */

class PagesAdminForm extends ManagedForm
{
    /**
     * @return string form class
     */
    public function getFormClass()
    {
        return PagesForm::className();
    }

    public function getInlines()
    {
        return [
            'meta' => MetaInlineForm::className()
        ];
    }
}

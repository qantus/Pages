<?php

namespace Modules\Pages\Admin;

use Mindy\Base\Mindy;
use Modules\Admin\Components\ModelAdmin;
use Modules\Pages\PagesModule;

/**
 * Class BlockAdmin
 * @package Modules\User
 */
class BlockAdmin extends ModelAdmin
{
    public function getColumns()
    {
        return ['id', 'slug', 'name'];
    }

    public function getCreateForm()
    {
        return Mindy::app()->getModule('Pages')->blockForm;
    }

    public function getModel()
    {
        $modelClass = Mindy::app()->getModule('Pages')->blockModel;
        return new $modelClass;
    }

    public function getVerboseName()
    {
        return PagesModule::t('text block');
    }

    public function getVerboseNamePlural()
    {
        return PagesModule::t('text blocks');
    }
}


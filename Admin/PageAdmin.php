<?php

namespace Modules\Pages\Admin;

use Mindy\Base\Mindy;
use Modules\Admin\Components\NestedAdmin;
use Modules\Pages\PagesModule;

/**
 * Class PageAdmin
 * @package Modules\Pages
 */
class PageAdmin extends NestedAdmin
{
    public function getColumns()
    {
        return ['id', 'name'];
    }

    public function getSearchFields()
    {
        return ['name'];
    }

    public function getCreateForm()
    {
        return Mindy::app()->getModule('Pages')->pagesForm;
    }

    public function getModel()
    {
        $modelClass = Mindy::app()->getModule('Pages')->pagesModel;
        return new $modelClass;
    }

    public function getVerboseName()
    {
        return PagesModule::t('page');
    }

    public function getVerboseNamePlural()
    {
        return PagesModule::t('pages');
    }

    public function getActions()
    {
        return array_merge(parent::getActions(), [
            'publish' => PagesModule::t('Publish'),
            'unpublish' => PagesModule::t('Unpublish'),
        ]);
    }

    public function unpublish(array $data = [])
    {
        $modelClass = Mindy::app()->getModule('Pages')->pagesModel;
        $modelClass::objects()->filter(['pk' => $data])->update(['is_published' => false]);

        $this->redirect('admin.list', [
            'module' => $this->getModel()->getModuleName(),
            'adminClass' => $this->classNameShort()
        ]);
    }

    public function publish(array $data = [])
    {
        $modelClass = Mindy::app()->getModule('Pages')->pagesModel;
        $modelClass::objects()->filter(['pk' => $data])->update(['is_published' => true]);

        $this->redirect('admin.list', [
            'module' => $this->getModel()->getModuleName(),
            'adminClass' => $this->classNameShort()
        ]);
    }
}


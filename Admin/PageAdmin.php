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
    public $linkColumn = 'name';
    
    public function getColumns()
    {
        return ['name'];
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

    public function getNames($model = null)
    {
        return [
            PagesModule::t('Pages'),
            PagesModule::t('Create page'),
            PagesModule::t('Update page')
        ];
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
        $modelClass::objects()->filter(['pk' => $data['models']])->update(['is_published' => false]);

        $this->redirect('admin.list', [
            'module' => $this->getModel()->getModuleName(),
            'adminClass' => $this->classNameShort()
        ]);
    }

    public function publish(array $data = [])
    {
        $modelClass = Mindy::app()->getModule('Pages')->pagesModel;
        $modelClass::objects()->filter(['pk' => $data['models']])->update(['is_published' => true]);

        $this->redirect('admin.list', [
            'module' => $this->getModel()->getModuleName(),
            'adminClass' => $this->classNameShort()
        ]);
    }
}


<?php

namespace Modules\Pages\Admin;

use Modules\Admin\Components\NestedAdmin;
use Modules\Pages\Forms\PagesForm;
use Modules\Pages\Models\Page;
use Modules\Pages\PagesModule;

class PageAdmin extends NestedAdmin
{
    public function getColumns()
    {
        return ['id', 'name', 'published_at'];
    }

    public function getSearchFields()
    {
        return ['name'];
    }

    public function getCreateForm()
    {
        return PagesForm::className();
    }

    public function getModel()
    {
        return new Page;
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
        Page::objects()->filter(['pk' => $data])->update(['is_published' => false]);

        $this->redirect('admin.list', [
            'module' => $this->getModel()->getModuleName(),
            'adminClass' => $this->classNameShort()
        ]);
    }

    public function publish(array $data = [])
    {
        Page::objects()->filter(['pk' => $data])->update(['is_published' => true]);

        $this->redirect('admin.list', [
            'module' => $this->getModel()->getModuleName(),
            'adminClass' => $this->classNameShort()
        ]);
    }
}


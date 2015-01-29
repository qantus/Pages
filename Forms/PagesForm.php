<?php

namespace Modules\Pages\Forms;

use Mindy\Base\Mindy;
use Mindy\Form\Fields\DropDownField;
use Mindy\Form\Fields\EditorField;
use Mindy\Form\Fields\TextAreaField;
use Mindy\Form\ModelForm;
use Modules\Meta\Forms\MetaInlineForm;
use Modules\Pages\PagesModule;

/**
 * Class PagesForm
 * @package Modules\Pages
 */
class PagesForm extends ModelForm
{
    public $exclude = ['comments'];

//    public function getFieldsets()
//    {
//        $enableComments = Mindy::app()->getModule('Pages')->enableComments;
//        $fieldsets = [
//            PagesModule::t('Main information') => [
//                'name', 'url', 'content_short', 'content',
//                'parent', 'is_index', 'is_published', 'published_at', 'file'
//            ],
//            PagesModule::t('Display settings') => [
//                'view', 'view_children', 'sorting'
//            ]
//        ];
//        return $enableComments ? array_merge($fieldsets, [
//            PagesModule::t('Comments settings') => [
//                'enable_comments', 'enable_comments_form'
//            ],
//        ]) : $fieldsets;
//    }

    public function getFields()
    {
        $model = $this->getInstance();
        if ($model === null) {
            $model = $this->getModel();
        }
        return [
            'content_short' => [
                'class' => TextAreaField::className(),
                'label' => PagesModule::t('Short content')
            ],
            'content' => [
                'class' => EditorField::className(),
                'label' => PagesModule::t('Content')
            ],
            'view' => [
                'class' => DropDownField::className(),
                'choices' => $model->getViews(),
                'label' => PagesModule::t('View')
            ],
            'view_children' => [
                'class' => DropDownField::className(),
                'choices' => $model->getViews(),
                'hint' => PagesModule::t('View for children pages'),
                'label' => PagesModule::t('View children')
            ],
        ];
    }

//    public function getInlines()
//    {
//        return [
//            ['meta' => MetaInlineForm::className()]
//        ];
//    }

    public function getModel()
    {
        $cls = Mindy::app()->getModule('Pages')->pagesModel;
        return new $cls;
    }
}

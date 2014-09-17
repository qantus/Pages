<?php

namespace Modules\Pages\Forms;

use Mindy\Form\Fields\DropDownField;
use Mindy\Form\Fields\TextAreaField;
use Mindy\Form\Fields\WysiwygField;
use Mindy\Form\ModelForm;
use Modules\Core\Fields\Form\TimeStampField;
use Modules\Pages\Models\Page;
use Modules\Pages\PagesModule;

/**
 * Class PagesForm
 */
class PagesForm extends ModelForm
{
    public $exclude = ['comments'];

    public function getFieldsets()
    {
        return [
            PagesModule::t('Main information') => [
                'name', 'url', 'content_short', 'content',
                'parent', 'is_index', 'is_published', 'published_at', 'file'
            ],
            PagesModule::t('Comments settings') => [
                'enable_comments', 'enable_comments_form'
            ],
            PagesModule::t('Display settings') => [
                'view', 'view_children', 'sorting'
            ],
        ];
    }

    public function getFields()
    {
        $model = $this->getInstance();
        return [
            'published_at' => [
                'class' => TimeStampField::className(),
                'autoNow' => true,
                'label' => PagesModule::t('Published time')
            ],
            'content_short' => [
                'class' => TextAreaField::className(),
                'label' => PagesModule::t('Short content')
            ],
            'content' => [
                'class' => WysiwygField::className(),
                'label' => PagesModule::t('Content')
            ],
            'parent' => [
                'class' => DropDownField::className(),
                'choices' => function () use ($model) {
                        $list = ['' => ''];

                        $qs = $model->tree()->order(['root', 'lft']);
                        if ($model->getIsNewRecord()) {
                            $parents = $qs->all();
                        } else {
                            $parents = $qs->exclude(['pk' => $model->pk])->all();
                        }
                        foreach ($parents as $model) {
                            $level = $model->level ? $model->level - 1 : $model->level;
                            $list[$model->pk] = $level ? str_repeat("..", $level) . ' ' . $model->name : $model->name;
                        }

                        return $list;
                    },
                'label' => PagesModule::t('Parent')
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

    public function getModel()
    {
        return Page::className();
    }
}

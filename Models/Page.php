<?php

namespace Modules\Pages\Models;

use Mindy\Base\Mindy;
use Mindy\Helper\Alias;
use Mindy\Orm\Fields\AutoSlugField;
use Mindy\Orm\Fields\BooleanField;
use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\DateTimeField;
use Mindy\Orm\Fields\HasManyField;
use Mindy\Orm\Fields\ImageField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\TreeModel;
use Modules\Pages\PagesModule;
use Modules\User\Components\UserActionsTrait;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class Page
 * @package Modules\Pages
 * @method static \Modules\Pages\Models\PageManager objects($instance = null)
 */
class Page extends TreeModel
{
    use UserActionsTrait;

    const PAGE = 0;
    const PAGESET = 1;

    /**
     * Prefix for cache
     */
    const CACHE_PREFIX = 'pages_';

    public static function getFields()
    {
        $fields = array_merge(parent::getFields(), [
            'name' => [
                'class' => CharField::className(),
                'required' => true,
                'verboseName' => PagesModule::t('Name')
            ],
            'url' => [
                'class' => AutoSlugField::className(),
                'source' => 'name',
                'verboseName' => PagesModule::t('Url'),
                'unique' => true
            ],
            'content' => [
                'class' => TextField::className(),
                'null' => true,
                'verboseName' => PagesModule::t('Content')
            ],
            'content_short' => [
                'class' => TextField::className(),
                'null' => true,
                'verboseName' => PagesModule::t('Short content')
            ],
            'file' => [
                'class' => ImageField::className(),
                'null' => true,
                'sizes' => [
                    'thumb' => [
                        160, 104,
                        'method' => 'adaptiveResizeFromTop',
                        'options' => ['jpeg_quality' => 5]
                    ],
                    'resize' => [
                        978
                    ],
                ],
                'verboseName' => PagesModule::t('File'),
            ],
            'created_at' => [
                'class' => DateTimeField::className(),
                'autoNowAdd' => true
            ],
            'updated_at' => [
                'class' => DateTimeField::className(),
                'autoNow' => true
            ],
            'published_at' => [
                'class' => DateTimeField::className(),
                'null' => true
            ],
            'view' => [
                'class' => CharField::className(),
                'null' => true,
                'verboseName' => PagesModule::t('View')
            ],
            'view_children' => [
                'class' => CharField::className(),
                'null' => true,
                'verboseName' => PagesModule::t('View children')
            ],
            'is_index' => [
                'class' => BooleanField::className(),
                'verboseName' => PagesModule::t('Is index')
            ],
            'is_published' => [
                'class' => BooleanField::className(),
                'verboseName' => PagesModule::t('Is published'),
                'default' => true
            ],
            'enable_comments' => [
                'class' => BooleanField::className(),
                'verboseName' => PagesModule::t('Enable comments'),
                'default' => true
            ],
            'enable_comments_form' => [
                'class' => BooleanField::className(),
                'verboseName' => PagesModule::t('Enable comments form'),
                'default' => true
            ],
            'sorting' => [
                'class' => CharField::className(),
                'null' => true,
                'choices' => [
                    'published_at' => PagesModule::t('Published time ASC'),
                    '-published_at' => PagesModule::t('Published time DESC'),
                    'lft' => PagesModule::t('Position ASC'),
                    '-lft' => PagesModule::t('Position DESC'),
                ],
                'verboseName' => PagesModule::t("Sorting")
            ],
        ]);

        $app = Mindy::app();
        if ($app->hasModule('Comments') && $app->getModule('Pages')->enableComments) {
            $fields['comments'] = [
                'class' => HasManyField::className(),
                'modelClass' => Comment::className()
            ];
        }
        return $fields;
    }

    public static function objectsManager($instance = null)
    {
        $className = get_called_class();
        return new PageManager($instance ? $instance : new $className);
    }

    public function __toString()
    {
        return (string)$this->name;
    }

    /**
     * @return array of page types
     */
    public function getTypes()
    {
        return [
            self::PAGE => PagesModule::t('Page'),
            self::PAGESET => PagesModule::t('Set of pages'),
        ];
    }

    /**
     * Return view for this model
     * @return string
     */
    public function getView()
    {
        if (empty($this->view)) {
            // Если представления не найдены берем стандартные
            $parent = $this->objects()->ancestors()->filter(['view_children__isnull' => false])->exclude(['view_children' => ''])->limit(1)->get();
            if ($parent) {
                $this->view = $parent->view_children;
            } else {
                $this->view = $this->getIsLeaf() ? 'page.html' : 'pageset.html';
            }
        }

        return $this->view;
    }

    /**
     * Get available views
     * @return array
     */
    public function getViews()
    {
        $finder = Mindy::app()->getComponent('finder');
        $pathApp = Alias::get($finder->theme ? 'application.themes.' . $finder->theme . '.templates.pages' : 'application.templates.pages');
        $pathModule = Alias::get('pages.templates.pages');

        $templates_app = $this->getTemplates($pathApp);
        $templates_module = $this->getTemplates($pathModule);

        $templates = [null => ''];
        foreach ($templates_app as $template) {
            $templates[$template] = $template;
        }
        foreach ($templates_module as $template) {
            $templates[$template] = $template;
        }

        return $templates;
    }

    /**
     * Get templates
     * @param $dir
     * @return array
     */
    public function getTemplates($dir)
    {
        if (!is_dir($dir)) {
            return [];
        }

        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        $files = [];
        while ($it->valid()) {
            if (!$it->isDot() && substr($it->getSubPathName(), 0, 1) !== '_') {
                $files[] = $it->getSubPathName();
            }
            $it->next();
        }
        return $files;
    }

    /**
     * Find parent views if this view is not set
     * @return bool|mixed
     */
    protected function getParentView()
    {
        $model = $this->tree()
            ->filter([
                'lft__lt' => $this->lft,
                'rgt__gt' => $this->rgt,
                'root' => $this->root,
                'view_children__isnull' => false
            ])
            ->order('-lft')
            ->get();

        return $model ? $model->view_children : null;
    }

    public function getAbsoluteUrl()
    {
        return $this->reverse('page.view', ['url' => $this->url]);
    }

    public function save(array $fields = [])
    {
        if ($this->is_published) {
            $this->published_at = time();
        }

        if ($this->is_index) {
            $this->objects()->update(['is_index' => false]);
        }

        return parent::save($fields);
    }

    public function afterSave($owner, $isNew)
    {
        $this->recordAction(PagesModule::t('Page [[{url}|{name}]] was ' . ($isNew ? 'created' : 'updated'), [
            '{url}' => $this->getAbsoluteUrl(),
            '{name}' => $this->name
        ]));
    }
}

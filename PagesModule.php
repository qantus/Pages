<?php

namespace Modules\Pages;

use Mindy\Base\Mindy;
use Mindy\Base\Module;

/**
 * Class PagesModule
 * @package Modules\Pages
 */
class PagesModule extends Module
{
    public $enableComments;
    public $commentForm;
    public $pagesModel = '\Modules\Pages\Models\Page';
    public $pagesForm = '\Modules\Pages\Forms\PagesForm';

    public function init()
    {
        if ($this->enableComments === null) {
            $this->enableComments = Mindy::app()->hasModule('Comments');
        }
    }

    public static function preConfigure()
    {
        $tpl = Mindy::app()->template;
        $tpl->addHelper('get_block', ['\Modules\Pages\Components\BlockHelper', 'render']);
        $tpl->addHelper('get_pages', function ($parentId, $limit = 10, $offset = 0) {
            return \Modules\Pages\Models\Page::objects()->filter([
                'parent_id' => $parentId
            ])->limit($limit)->offset($offset)->all();
        });
    }

    public function getVersion()
    {
        return '1.0';
    }

    public function getMenu()
    {
        return [
            'name' => $this->getName(),
            'items' => [
                [
                    'name' => self::t('Pages'),
                    'adminClass' => 'PageAdmin',
                ],
                [
                    'name' => self::t('Text blocks'),
                    'adminClass' => 'BlockAdmin',
                ],
                [
                    'name' => self::t('Comments'),
                    'adminClass' => 'CommentAdmin',
                ]
            ]
        ];
    }
}

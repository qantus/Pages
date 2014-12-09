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
    public $blockModel = '\Modules\Pages\Models\Block';
    public $blockForm = '\Modules\Pages\Forms\BlockForm';
    public $sizes = [
        'thumb' => [
            160, 104,
            'method' => 'adaptiveResizeFromTop',
            'options' => ['jpeg_quality' => 5]
        ],
        'resize' => [
            978
        ],
    ];

    public function init()
    {
        if ($this->enableComments === null) {
            $this->enableComments = Mindy::app()->hasModule('Comments');
        }
    }

    public static function preConfigure()
    {
        $tpl = Mindy::app()->template;
        $tpl->addHelper('fetch_block', ['\Modules\Pages\Components\BlockHelper', 'fetch']);
        $tpl->addHelper('get_block', ['\Modules\Pages\Components\BlockHelper', 'render']);
        $tpl->addHelper('get_pages', ['\Modules\Pages\Components\PagesHelper', 'getPages']);
    }

    public function getVersion()
    {
        return '1.0';
    }

    public function getMenu()
    {
        $menu = [
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
            ]
        ];
        if (Mindy::app()->hasModule('Comments')) {
            $menu['items'][] = [
                'name' => self::t('Comments'),
                'adminClass' => 'CommentAdmin',
            ];
        }
        return $menu;
    }
}

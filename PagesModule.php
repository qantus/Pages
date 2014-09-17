<?php

namespace Modules\Pages;

use Mindy\Base\Mindy;
use Mindy\Base\Module;

class PagesModule extends Module
{
    public $enableComments;
    public $commentForm;

    public function init()
    {
        if($this->enableComments === null) {
            $this->enableComments = Mindy::app()->hasModule('Comments');
        }
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

<?php

namespace Modules\Pages;

use Mindy\Base\Mindy;
use Mindy\Base\Module;

class PagesModule extends Module
{
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
                ]
            ]
        ];
    }
}

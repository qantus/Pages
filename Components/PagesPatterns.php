<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 03/07/14.07.2014 15:31
 */

namespace Modules\Pages\Components;


use Mindy\Router\CustomPatterns;
use Modules\Pages\Models\Page;

/**
 * @DEPRECATED
 * Class PagesPatterns
 * @package Modules\Pages\Components
 */
class PagesPatterns extends CustomPatterns
{
    public function getPatterns()
    {
        $namespace = !empty($this->namespace) ? $this->namespace . '.' : '';
        $patterns = [];
        $callback = ['\Modules\Pages\Controllers\PageController', 'view'];

//        $urls = Pages::objects()->valuesList(['url'], true);
//        foreach ($urls as $url) {
//            $patterns['/' . $url] = [
//                'callback' => $callback
//            ];
//        }

        $patterns['/{url:[^?]+}'] = [
            'name' => $namespace . 'view',
            'callback' => $callback
        ];
        $patterns['/'] = [
            'name' => $namespace . 'index',
            'callback' => $callback
        ];
        return $patterns;
    }
}


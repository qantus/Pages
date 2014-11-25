<?php

/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 03/07/14.07.2014 16:41
 */

namespace Modules\Pages\Sitemap;

use Mindy\Base\Mindy;
use Modules\Sitemap\Components\Sitemap;

/**
 * Class PageSitemap
 * @package Modules\Pages
 */
class PageSitemap extends Sitemap
{
    public function getModelClass()
    {
        return Mindy::app()->getModule('Pages')->pagesModel;
    }

    public function getLastMod($data)
    {
        if(isset($data['updated_at'])) {
            $date = $data['updated_at'];
        } else {
            $date = $data['created_at'];
        }

        return $this->formatLastMod($date);
    }

    public function getQuerySet()
    {
        $qs = parent::getQuerySet();
        return $qs->order(['root', 'lft']);
    }

    public function getLoc($data)
    {
        $url = $data['url'];
        return $data['is_index'] ? $this->reverse('page.view', ['/']) : $this->reverse('page.view', [$url]);
    }
}

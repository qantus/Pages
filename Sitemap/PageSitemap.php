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
use Modules\Pages\Models\Page;
use Modules\Sitemap\Components\Sitemap;

class PageSitemap extends Sitemap
{
    public function getModelClass()
    {
        return Page::className();
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

    public function getLoc($data)
    {
        $url = $data['url'];
        return $data['is_index'] ? $this->reverse('page.view', ['/']) : $this->reverse('page.view', [$url]);
    }
}

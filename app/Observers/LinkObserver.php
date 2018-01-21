<?php
/**
 * Created by PhpStorm.
 * User: TANG
 * Date: 2018/1/21
 * Time: 14:42
 */

namespace App\Observers;

use App\Models\Link;
use Cache;

class LinkObserver
{
    // creating, created, updating, updated, saving,
    // saved,  deleting, deleted, restoring, restored
    public function saved(Link $link)
    {
        // 当有新的数据添加的时候，清楚之前的cache，然后重新cache
        Cache::forget($link->cache_key);

        $link->getAllCached();
    }
}
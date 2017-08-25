<?php

/**
 * This file is part of Novel
 * (c) Maple <copyrenzhe@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repositories\Snatch;

use App\Models\Novel;

Interface SnatchInterface
{
    public function ping();

    //获取小说列表
    public function getNovelList();

    //采集单本小说基本信息
    public function init($link);

    //更新小说
    public function update(Novel $novel);

    //采集小说章节
    public function snatch(Novel $novel);

}
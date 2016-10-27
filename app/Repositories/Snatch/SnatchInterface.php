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
    public function getNovelList();

    public function getSingleNovel($link);

    public function getNovelChapter(Novel $novel);

    public function getSource();
}
<?php

/**
 * This file is part of Novel
 * (c) Maple <copyrenzhe@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repositories\Snatch;

use ReflectionClass;

class Snatch
{
    protected $page_size = 200;

    /**
     * 单线程模拟请求
     * @param $url
     * @param string $type
     * @param bool $params
     * @param string $encoding
     * @return mixed|string
     */
    protected function send($url, $type = 'GET', $params = false, $encoding = 'gbk')
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,60);
        if($type == 'POST'){
            curl_setopt($ch, CURLOPT_POST, 1);
            $params && curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        $html = curl_exec($ch);
        if($html === false) {
            echo "curl error: " . curl_errno($ch);
        }
        curl_close($ch);
        return $encoding ? mb_convert_encoding($html, 'UTF-8', $encoding) : $html;
    }


    /**
     * 多线程模拟请求
     * @param $url_array
     * @param $append_url
     * @param int $page_count
     * @return array
     */
    protected function multi_send_test($url_array, $append_url, $page_count=200)
    {
        return async_get_url($url_array, $append_url, $page_count);
    }
}
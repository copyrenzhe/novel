<?php
if(!function_exists('remote')) {
    function remote($url_array, $type = 'GET', $params = false, $encoding='gbk', $refer='', $cookie='')
    {
        $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36';
        $handles = $contents = array();
        //初始化curl multi对象
        $mh = curl_multi_init();
        //添加curl 批处理会话
        foreach($url_array as $key => $url)
        {
            $handles[$key] = curl_init($url);
            curl_setopt($handles[$key], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($handles[$key], CURLOPT_TIMEOUT, 10);
            curl_setopt($handles[$key], CURLOPT_ENCODING, $encoding);
            if($cookie){
                curl_setopt($handles[$key], CURLOPT_COOKIEFILE, $cookie);
                curl_setopt($handles[$key], CURLOPT_COOKIEJAR, $cookie);
            }
            if($type == 'POST'){
                curl_setopt($handles[$key], CURLOPT_PORT, 1);
            }
            if(!empty($params) && is_array($params)) {
                curl_setopt($handles[$key], CURLOPT_POSTFIELDS, $params);
            }
            if($refer){
                curl_setopt($handles[$key], CURLOPT_REFERER, $refer);
            }
            curl_setopt($handles[$key], CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($handles[$key], CURLOPT_USERAGENT, $userAgent);
            curl_multi_add_handle($mh, $handles[$key]);
        }

        //======================执行批处理句柄=================================
        $active = null;
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);


        while ($active and $mrc == CURLM_OK) {

            if(curl_multi_select($mh) === -1){
                usleep(100);
            }
            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        }
        //====================================================================
        //获取批处理内容
        foreach($handles as $i => $ch)
        {
            $content = curl_multi_getcontent($ch);
            $contents[$i] = curl_errno($ch) == 0 ? mb_convert_encoding($content, 'UTF-8', $encoding) : '';
        }
        //移除批处理句柄
        foreach($handles as $ch)
        {
            curl_multi_remove_handle($mh, $ch);
        }
        //关闭批处理句柄
        curl_multi_close($mh);
        return $contents;
    }
}

if(!function_exists('microtime_float')) {
    function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return((float)$usec+ (float)$sec);
    }
}
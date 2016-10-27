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

if(!function_exists('async_get_url')) {
    function async_get_url($urls, $append_url='', $page_size=500)
    {
        $n = (count($urls) > $page_size) ? $page_size : count($urls);

        $options = [
            CURLOPT_RETURNTRANSFER => 1, // 返回内容不直接显示
            CURLOPT_TIMEOUT => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => 1,
        ];

        // 初始化批处理
        $mh = curl_multi_init();

        // 先添加 x 个会话资源到批处理中

        $tmp_num = $n > 100 ? 100 : $n;
        for( $i = 0; $i < $tmp_num; $i ++ )
        {
            // 初始化一个会话资源
            $ch = curl_init( $append_url . $urls[ $i ] );
            // 设置
            curl_setopt_array( $ch, $options );
            // 添加会话到批处理中
            curl_multi_add_handle( $mh, $ch );
        }

        // 记录当前应该添加的urls的索引
        $curI = $i;
        $recv = array();
        do
        {
            $mrc = curl_multi_exec( $mh, $active );
            // 获取当前连接的信息, $msgq是当前队列中还有多少条消息
            $info = curl_multi_info_read( $mh, $msgq );
            if( $info )
            {
                // 当前这条消息的资源
                $handle = $info[ 'handle' ];
                // 读取收到的内容
                $content = curl_multi_getcontent( $handle );
                $recv[] = curl_errno($handle) == 0 ? mb_convert_encoding($content, 'UTF-8', 'gbk') : '';
                // 移除本资源
                curl_multi_remove_handle( $mh, $handle );
                // 关闭资源
                curl_close( $handle );
                // 再添加一个新的, 如果还有urls未处理, 则添加
                if( $curI < $n )
                {
                    $url = $append_url . $urls[ $curI ];
                    $ch = curl_init( $url );
                    curl_setopt_array( $ch, $options );
                    curl_multi_add_handle( $mh, $ch );
                    $curI ++;
                }
            }

        // 判断是否还有会话未结束?
        // $active 还有 多少个会话
        // $mrc 未发生错误
        // $msgq 还有多少个消息未读
        } while( $active && $mrc == CURLM_OK || $msgq > 0 );
        return $recv;
    }
}

/**
 * 判断是否是微信浏览器
 */
if(!function_exists('is_weixin')) {
    function is_weixin(){
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }
}

    /*
     * ----------------------------------
     * update batch
     * ----------------------------------
     *
     * multiple update in one query
     *
     * tablename( required | string )
     * multipleData ( required | array of array )
     */
if(!function_exists('updateBatch')) {
    function updateBatch($tableName = "", $multipleData = array()){
        if( $tableName && !empty($multipleData) ) {
            // column or fields to update
            $updateColumn = array_keys($multipleData[0]);
            $referenceColumn = $updateColumn[0]; //e.g id
            unset($updateColumn[0]);
            $whereIn = "";
            $q = "UPDATE ".$tableName." SET ";
            foreach ( $updateColumn as $uColumn ) {
                $q .=  $uColumn." = CASE ";

                foreach( $multipleData as $data ) {
                    $q .= "WHEN ".$referenceColumn." = '".$data[$referenceColumn]."' THEN '".$data[$uColumn]."' ";
                }
                $q .= "ELSE ".$uColumn." END, ";
            }
            foreach( $multipleData as $data ) {
                $whereIn .= "'".$data[$referenceColumn]."', ";
            }
            $q = rtrim($q, ", ")." WHERE ".$referenceColumn." IN (".  rtrim($whereIn, ', ').")";
            // Update
            return DB::update(DB::raw($q));
        } else {
            return false;
        }
    }
}

if(!function_exists('category_maps')){
    function category_maps(){
        return [
            'xuanhuan'  =>  '玄幻小说',
            'xiuzhen'   =>  '修真小说',
            'dushi'     =>  '都市小说',
            'lishi'     =>  '历史小说',
            'wangyou'   =>  '网游小说',
            'kehuan'    =>  '科幻小说',
            'other'     =>  '其他'
        ];
    }
}

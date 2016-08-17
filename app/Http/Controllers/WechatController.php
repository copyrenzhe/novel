<?php

namespace App\Http\Controllers;

use App\Events\Event;
use App\Models\Novel;
use App\Models\User;
use EasyWeChat\Message\News;
use Log;

class WechatController extends Controller
{

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        $wechat = app('wechat');
        $user = $wechat->user;
        $wechat->server->setMessageHandler(function($message) use ($user) {
            /*switch ($message->MsgType) {
                case 'event':
                    if($message->Event=='subscribe'){
                        User::firstOrNew(['open_id'=>$message->FromUserName, 'is_subscribe'=>1]);
                        return '感谢您的关注，您可以直接输入小说名进行小说搜索。';
                    }
                    if($message->Event=='unsubscribe'){
                        $user = User::find($message->FromUserName);
                        $user->is_subscribe=0;
                        $user->save();
                    }
                    break;
                case 'text':
                    $novels = Novel::where('name', 'like', $message->Content)->orderBy('hot', 'desc')->get();
                    $news = [];
                    foreach($novels as $novel) {
                        $new = new News([
                            'title'         =>  $novel->name,
                            'description'   =>  $novel->description,
                            'url'           =>  '/book/'.$novel->id,
                            'image'         =>  $novel->cover
                        ]);
                        array_push($news, $new);
                        return $news;
                    }
                    break;
                case 'image':
                    break;
                case 'voice':
                    break;
                case 'video':
                    break;
                case 'location':
                    break;
                case 'link':
                    return 'http://'.env('APP_URL', '');
                    break;
                default:
                    break;
            }*/
            return '这是来自novel的信息';
        });

        return $wechat->server->serve();
    }
}
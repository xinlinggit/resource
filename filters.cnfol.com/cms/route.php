<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    'content/article_recommend/fetch_block/:block_id'   => ['content/article_recommend/fetch_block',['method' => 'get']],
    'live/content/fetch_type/:type_id'   => ['live/content/fetch_type',['method' => 'get']],
    'user/user_rss/fetch_list/:status'   => ['user/user_rss/fetch_list',['method' => 'get']],
    'content/article/fetch_list/:status'   => ['content/article/fetch_list',['method' => 'get']],
	// 汇金
    'content/articlehuijin/fetch_list/:status'   => ['content/articlehuijin/fetch_list',['method' => 'get']],
    'user/user_recommend/fetch_list/:block_id/:category_id'   => ['user/user_recommend/fetch_list',['method' => 'get']],
    'advert/advert/fetch_list/:status'   => ['advert/advert/fetch_list',['method' => 'get']],
    'advert/advert/operate/:field/:value'   => 'advert/advert/operate',
    'content/article/operate/:field/:value'   => 'content/article/operate',
	// 汇金
    'content/articlehuijin/operate/:field/:value'   => 'content/articlehuijin/operate',
    'user/user_rss/operate/:field/:value'   => 'user/user_rss/operate',
    'content/article/model/:operate'   => 'content/article/model',
    'advert/advert/model/:operate'   => 'advert/advert/model',
    'user/user_level/fetch_main_type/:main_type'   => 'user/user_level/fetch_main_type',
	'article/:ids' => 'index/article/detail',//文章列表页
	'user_live/:ids' => 'index/media.live/index',//直播页
	'user/:ids' => ['index/media.index/index',['method' => 'get'],['ids'=>'\d+']],//媒体主页
];

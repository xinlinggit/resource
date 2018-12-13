<?php
define("WXCOLLECT_PATH",'/home/jenkins/collect/sougou_weixin/');//搜狗微信脚本路径
return [
	// +----------------------------------------------------------------------
	// | 场景设置，用于在不同环境写进行不同配置(测试环境)
	// +----------------------------------------------------------------------
	// 应用调试模式
	'app_debug'              => true,
	// 应用Trace
	'app_trace'              => false,

	//云平台设置
	'cloud' => [
		'cookie' => [
			'backend_id' => 'Usr_ID'
		],
		//API地址
		//'api_url' => 'http://cloud.cnfol.com/index.php?g=Interface/quote',
		'api_url' => 'http://cloudtest.cnfol.com/?g=Interface/quote',
		//项目ID
		'project_id' => '291'
	],

	'root_url' => '/home/httpd/test.filters.cnfol.com/runtime/',

	/*****************同时使用多种缓存配置*****************/
	'cache' =>  [
		// 使用复合缓存类型
		'type'  =>  'complex',
		// 默认使用的缓存
		'default'   =>  [
			// 驱动方式
			'type'   => 'Memcache',
			// 服务器地址
			'host'     => '172.30.3.193',
			'port'     => 9101,
			'expire'   => 3600,
			'timeout'  => 0, // 超时时间（单位：毫秒）
			'prefix'   => '',
			'username' => '', //账号
			'password' => '', //密码
			'option'   => [],
		],

		// memcached缓存
		'file'   =>  [
			// 驱动方式  文件缓存
			'type'   => 'File',
			// 缓存保存目录
			'path'   => CACHE_PATH,
			// 缓存前缀
			'prefix' => '',
			// 缓存有效期 0表示永久缓存
			'expire' => 60*60*24,
		],
	],

	/****************同时使用多种缓存配置*******************/
];

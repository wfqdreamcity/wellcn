<?php
//定义回调URL通用的URL
define('URL_CALLBACK', 'http://localhost/Common/callback?type=');

return array(
	//'配置项'=>'配置值'
	'APP_GROUP_LIST' => 'Index,Console', //项目分组设定
    'DEFAULT_GROUP'  => 'Index', //默认分组
	'APP_GROUP_MODE'=>'1',
	'APP_GROUP_PATH'=>'wellcn',
    'URL_MODEL'=>'2',

	'DB_HOST' => 'sqld.duapp.com:4050',
   'DB_USER' => 'P6ikt8MVSnRdEX4WZsq3FcFa',
   'DB_PWD' => 'jIEHtY0AsBHHakNzn7ZbGjP7u1lZAS7i',
	'DB_NAME' => 'IMxnZtvlNbnHAyTSMABZ',
	'DB_PREFIX' => '',
	'DB_CHARSET' => 'utf8', 
    'URL_HTML_SUFFIX'=>'html',

	'TMPL_CACHE_ON'=>false,      // 默认开启模板缓存
	'TMPL_CACHE_ON'   => false,  // 默认开启模板编译缓存 false 的话每次都重新编译模板
    'ACTION_CACHE_ON'  => false,  // 默认关闭Action 缓存
    'HTML_CACHE_ON'   => false,   // 默认关闭静态缓存
    'DB_FIELDS_CACHE'=>false, //关闭DB字段缓存
    
	/*
	'URL_ROUTER_ON'   => true, //开启路由
    'URL_ROUTE_RULES' => array( //定义路由规则
		'news/:id\d'=>'News/show',
    ),
	*/
    
	'WEB_URL' => 'http://localhost',
	//'MOBILE_URL' => 'http://m.qctt.cn',
	'URL_CASE_INSENSITIVE' => 'true',	//URL大小写不敏感

	//'TMPL_EXCEPTION_FILE'=>'./Application/Modules/Index/Tpl/Common/error.html', // 定义公共错误模板
	//'ERROR_PAGE'=>'./Application/Modules/Index/Tpl/Common/error.html', // 定义错误跳转页面URL地址
);
?>

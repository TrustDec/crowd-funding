<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// 站点布署的配置
// DB分布时，不能使用第三方的DB缓存与SESSION_DB
// +----------------------------------------------------------------------



return array(
		"CACHE_TYPE"	=>	"File",	//File,Memcached,MemcacheSASL,Xcache,Db
		"CACHE_CLIENT"	=>	"", //备选配置,使用到的有memcached,memcacheSASL,DBCache
		"CACHE_PORT"	=>	"", //备选配置（memcache使用的端口，默认为11211,DB为3306）
		"CACHE_USERNAME"	=>	"",  //备选配置
		"CACHE_PASSWORD"	=>	"",  //备选配置
		"CACHE_DB"	=>	"",  //备选配置,用DB做缓存时的库名
		"CACHE_TABLE"	=>	"fanwe_auto_cache",  //备选配置,用DB做缓存时的表名
		"CACHE_LOG"	=>	false,  //是否需要在本地记录cache的key列表
		"SESSION_TIME" =>	3600, //session超时时间
		"SESSION_TYPE"	=>	"File", //"Db/MemcacheSASL/File"				
		"SESSION_CLIENT"	=>	"", //备选配置,使用到的有memcached,memcacheSASL,DBCache
		"SESSION_PORT"	=>	"", //备选配置（memcache使用的端口，默认为11211,DB为3306）
		"SESSION_USERNAME"	=>	"",  //备选配置
		"SESSION_PASSWORD"	=>	"",  //备选配置
		"SESSION_DB"	=>	"",  //备选配置,用DB做缓存时的库名
		"SESSION_TABLE"	=>	"fanwe_session",  //备选配置,用DB做缓存时的表名
		"DB_CACHE_APP"	=>	array(
			"index"
		),
		"DB_CACHE_TABLES"	=>	array(
			"adv",
			"api_login",
			"article",
			"article_cate",
			"bank",
			"conf",
			"deal",
			"deal_cate",
			"faq",
			"help",
			"index_image",
			"link",
			"link_group",
			"nav",
			
			),  //支持查询缓存的表
		"DB_DISTRIBUTION" => array(
// 			array(
// 				'DB_HOST'=>'localhost',
// 				'DB_PORT'=>'3306',
// 				'DB_NAME'=>'o2onew1',
// 				'DB_USER'=>'root',
// 				'DB_PWD'=>'',				
// 			),
// 			array(
// 				'DB_HOST'=>'localhost',
// 				'DB_PORT'=>'3306',
// 				'DB_NAME'=>'o2onew2',
// 				'DB_USER'=>'root',
// 				'DB_PWD'=>'',				
// 			),
		), //数据只读查询的分布
		"CSS_JS_OSS"	=>	false,  //脚本样式是否同步到oss
		"OSS_TYPE"	=>	"",  //同步文件存储的类型: ES_FILE,ALI_OSS,NONE 分别为原es_file.php同步,阿里云OSS,以及无OSS分布
		"OSS_DOMAIN"	=>	"",  //远程存储域名
		"OSS_FILE_DOMAIN"	=>	"",	//远程存储文件域名
		"OSS_BUCKET_NAME"	=>	"", //针对阿里oss的bucket_name
		"OSS_ACCESS_ID"	=>	"",
		"OSS_ACCESS_KEY"	=>	"",
		"ORDER_DISTRIBUTE_COUNT"	=>	"0", //订单表分片数量
		'DOMAIN_ROOT'	=>	'',  //域名根
		'COOKIE_PATH'	=>	'/',
);
?>
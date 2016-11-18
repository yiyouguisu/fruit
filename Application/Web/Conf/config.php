<?php
return array(
	/* 主题设置 */
    'TMPL_TEMPLATE_SUFFIX'  =>  '.php',   // 默认模板文件后缀
     /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__PUBLIC__' => __ROOT__ . '/Public',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
        '__Editor__' => __ROOT__ . '/Public/Editor',
		'__PLUG__'   => __ROOT__ . '/Public/' . MODULE_NAME . '/jsplug',
		'__IMAGES__' => __ROOT__,
		'__THEMS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/themes',
    ),
    /* 数据加密设置 */
    'AUTH_KEY' => '2b8L3j5b0H', //默认数据加密KEY
    'AUTO_TIME_LOGIN' =>"604800", //自动登录限制时间7*24*60*60
      /* SESSION 和 COOKIE 配置 */
    'SESSION_PREFIX' => 'web', //session前缀
    'COOKIE_PREFIX'  => 'web_', // Cookie前缀 避免冲突
    
    'TMPL_ACTION_ERROR'     =>  'Public:error',// 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  'Public:success', // 默认成功跳转对应的模板文件
    
    'WEB_URL' => 'http://' . $_SERVER['HTTP_HOST'] . '/', // 网站域名
    /*Auth权限配置*/
    'AUTH_CONFIG'=>array(
        'AUTH_ON' => true, //认证开关
        'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_GROUP' => 'zz_auth_group', //用户组数据表名
        'AUTH_GROUP_ACCESS' => 'zz_auth_group_access', //用户组明细表
        'AUTH_RULE' => 'zz_menu', //权限规则表
        'AUTH_USER' => 'zz_user'//用户信息表	
    ),
    //过滤规则,请书写小写
   'AUTH_Filter'=>array(
       'Web/index/index',
       'Web/index/main',
       'Web/index/message',
       'Web/menu/public_changyong',
       'Web/member/select',
       'Web/expand/getchildren',
       'Web/article/action',
    ),
     'DATA_BACKUP_PATH'=>'./Backup/',
     'DATA_BACKUP_PART_SIZE'=>'20971520',
     'DATA_BACKUP_COMPRESS'=>'1',
     'DATA_BACKUP_COMPRESS_LEVEL'=>'9',
    'upload_file_type'=> array(
      1=>"后台编辑器上传",
      2=>"后台涂鸦上传", 
      3=>"后台涂鸦作品", 
      4=>"后台上传附件",
      5=>"后台流转标swf上传", 
      6=>"会员上传身份证", 
      7=>"会员资料上传", 
      8=>"后台上传水印图片",
    ),
    'WEI_XIN_INFO' => array(
	'APP_ID'         => 'wxd479808c846605e6', 
	'APP_SECRET'   	 => '997bb16e6d7de04e56f318950e0acd14',
	),
    'ANDROID_DOWNLOAD_PATH'=>'http://android.myapp.com/myapp/detail.htm?apkName=com.snda.wifilocating',
    'IPHONE_DOWNLOAD_PATH'=>'https://appsto.re/cn/65Lj8.i'
);
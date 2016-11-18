<?php
return array(
  'SHOW_PAGE_TRACE' => false,
	/* 主题设置 */
    'TMPL_TEMPLATE_SUFFIX'  =>  '.php',   // 默认模板文件后缀
     /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__PUBLIC__' => __ROOT__ . '/Public',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
        '__Editor__' => __ROOT__ . '/Public/Editor',
    ),
      /* SESSION 和 COOKIE 配置 */
    'SESSION_PREFIX' => 'admin', //session前缀
    'COOKIE_PREFIX'  => 'admin_', // Cookie前缀 避免冲突
    /* 数据加密设置 */
    'AUTH_KEY' => '2b8L3j5b0H', //默认数据加密KEY
    'AUTO_TIME_LOGIN' =>"21600", //自动登录限制时间1*6*60*60
    
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
       'admin/index/index',
       'admin/manager/myinfo',
       'admin/manager/chanpass',
       'admin/index/main',
       'admin/index/message',
       'admin/menu/public_changyong',
       'admin/member/select',
       'admin/expand/getchildren',
       'admin/expand/getareachildren',
       'admin/order/getchildren',
       'admin/article/action',
       'admin/ad/ajax_getproduct',
       'admin/product/action',
       'admin/product/paction',
       'admin/party/ajax_getproduct',
       'admin/member/details',
       'admin/cacheproductunit/save',
       'admin/product/ajax_getproduct',
       'admin/order/update_integral',
       'admin/order/addmessage',
       'admin/package/update_integral',
       'admin/package/addmessage',
       'admin/product/ajax_unout',
       'admin/order/printorder',
       'admin/product/doout',
       'admin/package/deal',
       'admin/packageajax_getouser',
       'admin/package/packagedone',
       'admin/package/paynotice',
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

);
<?php
return array(
    /* 调试配置 */
    'SHOW_PAGE_TRACE' => false,
    // 允许访问的模块列表
    'MODULE_ALLOW_LIST' => array('Home','Admin', 'Api','Web'),
    'DEFAULT_MODULE' => 'Home', // 默认模块
    /* 用户相关设置 */
    'USER_MAX_CACHE' => 1000, //最大缓存用户数
    'USER_ADMINISTRATOR' => 1, //管理员用户ID
    'SESSION_AUTO_START' => true,
    
    /* URL配置 */
    'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL' => 1, //URL模式
    'VAR_URL_PARAMS' => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR' => '/', //PATHINFO URL分割符
    /* 全局过滤配置 */
    'DEFAULT_FILTER' => '', //全局过滤函数
    /* 数据库配置 */
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => '127.0.0.1', // 服务器地址
    'DB_NAME' => 'fruit', // 数据库名
    'DB_USER' => 'root', // 用户名
    'DB_PWD' => '123456', // 密码
    'DB_PORT' => '3306', // 端口
    'DB_PREFIX' => 'zz_', // 数据库表前缀
    'APP_SUB_DOMAIN_DEPLOY'   =>    1,
    'APP_SUB_DOMAIN_RULES'    =>    array(
         'admin.esugo.cn'  => 'Admin',
        'm.esugo.cn'  => 'Web',
    )
);
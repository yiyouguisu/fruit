<?php
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
/**
 * 系统调试设置
 * 项目正式部署后请设置为false
 */
define ('APP_DEBUG', true );
// 定义应用目录
define('APP_PATH','./Application/');
//定义静态目录
define('HTML_PATH', './Html/');
/**
 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define ('RUNTIME_PATH', './Runtime/' );

define('DATA_PATH', './Cacheconfig/');

define('CACHEDATA_PATH', './Data/');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

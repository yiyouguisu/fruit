<?php
return array(
	//'配置项'=>'配置值'
	'TMPL_TEMPLATE_SUFFIX'  =>  '.php',   // 默认模板文件后缀
	 /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__PUBLIC__' => __ROOT__ . '/Public',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
        '__Editor__' => __ROOT__ . '/Public/Editor',
    ),
);
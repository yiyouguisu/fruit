/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.language = 'zh-cn'; //配置语言   
	config.height = 400; //高度
	config.filebrowserBrowseUrl='/Public/Editor/ckfinder/ckfinder.html'; 
	config.filebrowserImageBrowseUrl='/Public/Editor/ckfinder/ckfinder.html?type=Images'; 
	config.filebrowserFlashBrowseUrl =  '/Public/Editor/ckfinder/ckfinder.html?type=Flash'; 
	config.filebrowserUploadUrl =  '/Public/Editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';    config.filebrowserImageUploadUrl =  '/Public/Editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';    config.filebrowserFlashUploadUrl =  '/Public/Editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'; 
	config.font_names = '宋体/宋体;黑体/黑体;仿宋/仿宋_GB2312;楷体/楷体_GB2312;隶书/隶书;幼圆/幼圆;微软雅黑/微软雅黑;'+ config.font_names ;
	config.toolbar = 'Full';
	config.toolbar = 'Basic';

config.toolbar_Basic =
[
	['Source','Format','Font','FontSize','lineheight','TextColor','BGColor','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Bold','Italic','Link', 'Unlink','-','About']
];
  config.extraPlugins += (config.extraPlugins ? ',lineheight': 'lineheight');
};

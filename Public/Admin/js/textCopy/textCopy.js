 /**
 * PHPWind util Library 
 * @Copyright Copyright 2011, phpwind.com
 * @Descript: ���ƹ���js��li�б��ֱ�Ӹ������ݣ�
 * @Author	: linhao87@gmail.com
 * @Depend	: core.js��jquery.js(1.7 or later)��common.js��dialog.js��zeroClipboard���
 * $Id: textCopy.js 3846 2012-01-13 02:56:43Z hao.lin $
 
 ***************************************************************
 
 * js���ô��룺
	Wind.use('textCopy', 'dialog', function() {
		$('a.J_copy_clipboard').textCopy({
			content : '��������',
			callback : function(){
				//�ɹ���ص�
			}
		});
	});

	
 ***************************************************************
 */
;(function ( $, window, document, undefined ) {
   var pluginName = 'textCopy',
    	defaults = {
    		callback : undefined
    	};

	function Plugin(element, options) {
		this.element = element;
		this.options = $.extend( {}, defaults, options) ;
		this.init();
	}
	
    Plugin.prototype.init = function () {
		var element = this.element,
			options = this.options,
			callback = options.callback,			//�ص�
			mouseover = options.mouseover,
			content = options.content,				//����������
			appendelem = (options.appendelem ? options.appendelem : undefined),		//�������
			addedstyle = (options.addedstyle ? options.addedstyle : undefined);		//�ֶ��޸���ʽ
		
		if($.browser.msie) {
			//ie����
			
			//������ư�ť
			element.on('click', function(e){
				e.preventDefault();
				
				//�ж������Ƿ�Ϊ��
				if( content === '') {
					if(Wind.dialog){
						Wind.dialog.alert('��������Ϊ��');
					}else{
						//��̨û��Wind.Util
						if(Wind.Util.resultTip) {
							Wind.Util.resultTip({
								error : true,
								elem : element,
								follow : true,
								msg : '��������Ϊ��'
							});
						}else{
							alert('��������Ϊ��');
						}
						
					}
					return false;
				}
				
				//��ɸ���
				if(window.clipboardData.setData("Text", content)) {
					//��̨û��Wind.Util
					if(Wind.Util.resultTip) {
						Wind.Util.resultTip({
							elem : element,
							follow : true,
							msg : '���Ƴɹ�'
						});
					}else{
						alert('���Ƴɹ�');
					}
					

					if(callback) {
						callback(element);
					}
				}
				
			});
			
		}else{
			//��ie���ƣ�����zeroClipboard���
			Wind.js(GV.JS_ROOT+ 'util_libs/textCopy/zeroClipboard/ZeroClipboard.js?v=' + GV.JS_VERSION, function(){
						
				element.clip = new ZeroClipboard.Client();
				ZeroClipboard.setMoviePath( GV.JS_ROOT + 'util_libs/textCopy/zeroClipboard/ZeroClipboard10.swf?v=' + GV.JS_VERSION); //flash�ļ���ַ
				element.clip.glue(element[0], appendelem, addedstyle); //flash��λ�����ְ�ť��
				element.clip.setHandCursor( true ); //flash���������
						
				//flash��������ύ����
				element.clip.addEventListener('mouseDown', function (client) {
						
					//�жϸ��������Ƿ�Ϊ��
					if(content === '') {
						if(Wind.dialog){
							Wind.dialog.alert('��������Ϊ��');
						}else{
							//��̨û��Wind.Util
							if(Wind.Util.resultTip) {
								Wind.Util.resultTip({
									error : true,
									elem : element,
									follow : true,
									msg : '��������Ϊ��'
								});
							}else{
								alert('��������Ϊ��');
							}
						}
						
						return false;
					}
						
					//��ʼ����
					element.clip.setText(content);
						
					
						
				});

				element.clip.addEventListener('mouseover', function (client) {
					if(mouseover) {
						mouseover(client);
					}
				});
				

				//��ɸ���
				element.clip.addEventListener('complete', function (client, text) {
					//��̨û��Wind.Util
					if(Wind.Util.resultTip) {
						Wind.Util.resultTip({
							elem : element,
							follow : true,
							msg : '���Ƴɹ�'
						});
					}else{
						alert('���Ƴɹ�');
					}
						

					if(callback) {
						callback(element);
					}
				});
				
				//��꾭������ʱ�Է������
				element.on('click', function(e){
					e.preventDefault();
				});
				
			});
			
		}
		
    };

		if(!$.isFunction(Wind.dialog)) {
			Wind.use('dialog');
		}

    $.fn[pluginName] = Wind[pluginName]= function (options ) {
      return this.each(function () {
				new Plugin( $(this), options );
      });
    };

})( jQuery, window ,document);

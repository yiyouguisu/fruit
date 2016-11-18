var SWFUpload;
if (typeof (SWFUpload) === "function") {
    SWFUpload.queue = {};

    SWFUpload.prototype.initSettings = (function (oldInitSettings) {
        return function () {
            if (typeof (oldInitSettings) === "function") {
                oldInitSettings.call(this);
            }

            this.customSettings.queue_cancelled_flag = false;
            this.customSettings.queue_upload_count = 0;

            this.settings.user_upload_complete_handler = this.settings.upload_complete_handler;
            this.settings.upload_complete_handler = SWFUpload.queue.uploadCompleteHandler;

            this.settings.queue_complete_handler = this.settings.queue_complete_handler || null;
        };
    })(SWFUpload.prototype.initSettings);

    SWFUpload.prototype.startUpload = function (fileID) {
        this.customSettings.queue_cancelled_flag = false;
        this.callFlash("StartUpload", false, [fileID]);
    };

    SWFUpload.prototype.cancelQueue = function () {
        this.customSettings.queue_cancelled_flag = true;
        this.stopUpload();

        var stats = this.getStats();
        while (stats.files_queued > 0) {
            this.cancelUpload();
            stats = this.getStats();
        }
    };

    SWFUpload.queue.uploadCompleteHandler = function (file) {
        var user_upload_complete_handler = this.settings.user_upload_complete_handler;
        var continueUpload;

        if (file.filestatus === SWFUpload.FILE_STATUS.COMPLETE) {
            this.customSettings.queue_upload_count++;
        }

        if (typeof (user_upload_complete_handler) === "function") {
            continueUpload = (user_upload_complete_handler.call(this, file) === false) ? false : true;
        } else {
            continueUpload = true;
        }

        if (continueUpload) {
            var stats = this.getStats();
            if (stats.files_queued > 0 && this.customSettings.queue_cancelled_flag === false) {
                this.startUpload();
            } else if (this.customSettings.queue_cancelled_flag === false) {
                this.queueEvent("queue_complete_handler", [this.customSettings.queue_upload_count]);
                this.customSettings.queue_upload_count = 0;
            } else {
                this.customSettings.queue_cancelled_flag = false;
                this.customSettings.queue_upload_count = 0;
            }
        }
    };
}
var swfObj = function (optns) {
    var settings = {
        debug: false,
        htmId: typeof (optns.htmId) == 'undefined' ? '' : optns.htmId,
        imgId: typeof (optns.imgId) == 'undefined' ? '' : optns.imgId,
        flash_url: "/jsplug/swfupload/swfupload.swf",
        upload_url: typeof (optns.uploadUrl) == 'undefined' ? '/Handlers/Upload.ashx' : optns.uploadUrl,
        post_params: { "PHPSESSID": "" },
        file_size_limit: "100 MB",
        file_types: typeof (optns.typs) == 'undefined' ? '*.*' : optns.limit,
        file_types_description: "所有文件",
        file_upload_limit: 100,
        file_queue_limit: typeof (optns.limit) == 'undefined' ? 1 : optns.limit,
        custom_settings: {
            progressTarget: typeof (optns.progressTarget) == 'undefined' ? "span_scjd" : optns.progressTarget,
            cancelButtonId: typeof (optns.limit) == 'undefined' ? 'btnCancel' : optns.cancelButtonId
        },
        //按钮设置
        button_image_url: "/jsplug/swfupload/swfupload.png",
        button_width: "65",
        button_height: "28",
        button_placeholder_id: typeof (optns.button_placeholder_id) == 'undefined' ? "spanPlace" : optns.button_placeholder_id,
        button_text_style: ".theFont { font-size: 12px; cursor:pointer; }",
        button_text: '<span class="theFont">选择文件</span>',
        button_text_top_padding: 4, button_text_left_padding: 6,
        //事件设置
        //swfupload_preload_handler: preLoad,
        swfupload_load_failed_handler: loadFailed,
        file_queued_handler: fileQueued,
        file_queue_error_handler: fileQueueError,
        file_dialog_complete_handler: fileDialogComplete,
        upload_start_handler: function (file) { uploadStart(file); $('#' + settings.custom_settings.progressTarget).html(''); },
        upload_progress_handler: uploadProgress,
        upload_error_handler: uploadError,
        upload_success_handler: function (file, serverData) {
            try {
                var progress = new FileProgress(file, this.customSettings.progressTarget);
                progress.setComplete();
                serverData = eval('(' + serverData + ')');
                if (typeof (optns.call) != 'undefined') {
                    progress.setStatus('上传成功！');
                    optns.call(serverData);
                }
                else {
                    progress.setStatus(serverData.err == '' ? serverData.msg : '');
                    progress.toggleCancel(false);
                    if (settings.htmId != '') {
                        $('#' + settings.htmId).val(serverData.msg);
                    }
                }

            } catch (ex) {
                this.debug(ex);
            }
        },
        upload_complete_handler: uploadComplete,
        queue_complete_handler: queueComplete
    };
    return new SWFUpload(settings);
};
<?php echo $this->element('head_frame'); ?>
<!-- page specific plugin styles -->
<link rel="stylesheet" href="/assets/css/dropzone.css" />
<style>
    .dropzone{width:520px;min-height:280px;}
</style>
    
<div class="main-container-inner" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:560px;margin:0 auto;'>
    <p class="btn btn-info btn-block" style="border-radius:4px 4px 0 0;padding:0 12px;"> <span style="font-size:16px;"> 上传任务书</span> <a class="close" id="step2_close" data-dismiss="modal">×</a></p>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <div id="dropzone">
                    <form action="/ResearchProject/step3" class="dropzone" id="upfiles" action="/ResearchProject/step3" >
                        <div class="fallback" >
                            <input name="file[]" type="file" multiple="" />
                        </div>
                    </form>
                </div><!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div><!-- /.row -->
        <input type="hidden" id="file_upload" value="" />
        <div class="space-4"></div>
        <div class="clearfix " style="text-align: center;">
            <div class=" col-md-9">
                
                <button class="btn btn-primary" type="reset" class="close" data-dismiss="modal">
                    <i class="icon-undo bigger-110"></i>
                    上一步
                </button>

                &nbsp; &nbsp; &nbsp;
                <button class="btn btn-info" type="button" onclick="ajax_submit();">
                    <i class="icon-ok bigger-110"></i>
                    下一步
                </button>
            </div>
        </div>                       

    </div><!-- /.page-content -->
</div><!-- /.main-container-inner -->

</div><!-- /.main-container -->


<!--<div class="modal fade" id="modal_left" tabindex="-1" role="dialog" aria-labelledby="modal" style='width:500px;  margin:10% auto 0px; overflow: hidden;border-radius:4px; height: 500px;overflow-y:auto;top:auto;'>
    <div class='modal-hader' > <button class='close' type='button' data-dismiss='modal'><span aria-hidden="true">×</span><span class="sr-only">Close</span></button> 
        <div class="modal-dialog" role="document">
            <div class="modal-content">

            </div>
        </div>
    </div>
</div>-->
<!-- basic scripts -->
<script src="/js/jquery-2.0.3.min.js"></script>
<script src="/assets/js/dropzone.min.js"></script>

<script type="text/javascript">
                     jQuery(function ($) {
                         try {
                             $(".dropzone").dropzone({
                                 url: '/ResearchProject/upload_file',
                                 paramName: "file", // The name that will be used to transfer the file
                                 maxFilesize: 0.5, // MB

                                 addRemoveLinks: true,
                                 dictDefaultMessage:
                                         '<span class="bigger-150 bolder"><i class="icon-caret-right red"></i> Drop files</span> to upload \
                <span class="smaller-80 grey">(or click)</span> <br /> \
                <i class="upload-icon icon-cloud-upload blue icon-3x"></i>'
                                 ,
                                 dictResponseError: 'Error while uploading file!',

                                 //change the previewTemplate to use Bootstrap progress bars
                                 previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-details\">\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n    <div class=\"dz-size\" data-dz-size></div>\n    <img data-dz-thumbnail />\n  </div>\n  <div class=\"progress progress-small progress-striped active\"><div class=\"progress-bar progress-bar-success\" data-dz-uploadprogress></div></div>\n  <div class=\"dz-success-mark\"><span></span></div>\n  <div class=\"dz-error-mark\"><span></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n</div>",
                                 removedfile: function (file) {
                                     //删除文件
                                     var file_name = file.name;
                                     file.previewElement.remove();
                                     var file_all_arr = $('#file_upload').val().split('|');
                                     var index_ = $.inArray(file_name, file_all_arr);
                                     if (index_ != -1) {
                                         //去掉他
                                         file_all_arr.splice(index_, 1);
                                     }
                                     $('#file_upload').val(file_all_arr.join('|'));
                                 },
                                 success: function (file, res) {
                                     //把json转成Obj
                                     var res = JSON.parse(res);
                                     if (res.code == -1) {
                                         //登录过期
                                         window.location.href = '/homes/index';
                                         return;
                                     }
                                     if (res.code == -2) {
                                         //权限不足
                                         alert('权限不足');
                                         return;
                                     }
                                     if (res.code == 1) {
                                         //重名
                                         (file.previewElement.classList.add("dz-error"));
                                         //file.previewElement.remove();
                                         alert(res.msg);

                                         return;
                                     }
                                     if (res.code == 0) {
                                         //上传成功什么也不干   
                                         (file.previewElement.classList.add("dz-success"));
                                         var file_all_str = $('#file_upload').val();
                                         if (file_all_str) {
                                             file_all_str += '|' + file.name;
                                         } else {
                                             file_all_str = file.name;
                                         }
                                         $('#file_upload').val(file_all_str)
                                         return;
                                     }
                                     if (res.code == 2) {
                                         //失败
                                         alert(res.msg);
                                         return;
                                     }
                                 }

                             });
                         } catch (e) {
                             alert('Dropzone.js does not support older browsers!');
                         }

                     });

                     //提交内容
                     function ajax_submit() {
                         $('#step2_close').click();
                         var data = {};
                         data.filename = $('#file_upload').val();
                         if (data.filename == '') {
                             alert('未上传文件');
                             return;
                         }
                         data.upstep = 'step2'
                         $.ajax({
                             url: '/ResearchProject/ajax_cookie',
                             type: 'post',
                             data: data,
                             dataType: 'json',
                             success: function (res) {
                                 if (res.code == -1) {
                                     //登录过期
                                     window.location.href = '/homes/index';
                                     return;
                                 }
                                 if (res.code == -2) {
                                     //权限不足
                                     alert('权限不足');
                                     return;
                                 }
                                 if (res.code == 1) {
                                     //说明有错误
                                     alert(res.msg);
                                     //清空之前的错误提示
                                     //                        $('.middle').removeClass('text-danger').text('');
                                     //                        show_error($(res.class), res.msg);
                                     return;
                                 }
                                 if (res.code == 0) {
                                     //说明添加或修改成功
                                     //location.href = '/user/index';
                                     //如果成功，则调step2
                                     $('.close').click();
                                     //$('.step3_js').click();
                                     return;
                                 }
                                 if (res.code == 2) {
                                     //失败
                                     alert(res.msg);
                                     return;
                                 }
                             }
                         });
                     }
</script>
<?php echo $this->element('foot_frame'); ?>
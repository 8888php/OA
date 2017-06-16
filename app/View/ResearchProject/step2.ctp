<!-- page specific plugin styles -->
<link rel="stylesheet" href="/assets/css/dropzone.css" />


<div class="main-container-inner">
    <p class="btn btn-info btn-block" > <span style="font-size:16px;"> 上传任务书</span> <a class="close" data-dismiss="modal">×</a></p>
    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <div id="dropzone">
                    <form action="/ResearchProject/step3" class="dropzone" id="upfiles" >
                        <div class="fallback" >
                            <input name="file[]" type="file" multiple="" />
                        </div>
                    </form>
                </div><!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="space-4"></div>
        <div class="clearfix " style="text-align: right;">
            <div class=" col-md-9">
                <button class="btn btn-info" type="button" onclick="ajax_submit();" data-toggle="modal" href="/ResearchProject/step3" data-target="#modal_left" >
                    <i class="icon-ok bigger-110"></i>
                    下一步
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset" class="close" data-dismiss="modal">
                    <i class="icon-undo bigger-110"></i>
                    取消
                </button>
            </div>
        </div>                       

    </div><!-- /.page-content -->
</div><!-- /.main-container-inner -->

</div><!-- /.main-container -->


<div class="modal fade" id="modal_left" tabindex="-1" role="dialog" aria-labelledby="modal" style='width:500px;  margin:10% auto 0px; overflow: hidden;border-radius:4px; height: 500px;overflow-y:auto;top:auto;'>
    <div class='modal-hader' > <button class='close' type='button' data-dismiss='modal'><span aria-hidden="true">×</span><span class="sr-only">Close</span></button> 
        <div class="modal-dialog" role="document">
            <div class="modal-content">

            </div>
        </div>
    </div>
</div>   
<!-- basic scripts -->
<script src="/js/jquery-2.0.3.min.js"></script>
<script src="/assets/js/dropzone.min.js"></script>

<script type="text/javascript">
                    jQuery(function ($) {
                        try {
                            $(".dropzone").dropzone({
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
                                previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-details\">\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n    <div class=\"dz-size\" data-dz-size></div>\n    <img data-dz-thumbnail />\n  </div>\n  <div class=\"progress progress-small progress-striped active\"><div class=\"progress-bar progress-bar-success\" data-dz-uploadprogress></div></div>\n  <div class=\"dz-success-mark\"><span></span></div>\n  <div class=\"dz-error-mark\"><span></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n</div>"
                            });
                        } catch (e) {
                            alert('Dropzone.js does not support older browsers!');
                        }

                    });

                    //提交内容
                    function ajax_submit() {
                        $('#upfiles').submit();
                    }
</script>

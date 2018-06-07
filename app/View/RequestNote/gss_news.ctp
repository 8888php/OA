
<?php //echo $this->element('head_frame'); ?>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="/assets/css/dropzone.css" />
<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:710px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" role="form">
                    <input type="hidden" name='declarename' class='declarename' value='山西省农业科学院果树研究所门户网站新闻信息发布审批卡' /> 
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;" >
                        <tbody>
                            <tr>
                                <td colspan="4" style="font-size:24px;font-weight: 600;border-color:#000;border-left-color: white;border-right-color: white; border-top-color: white;">  山西省农业科学院果树研究所门户网站新闻信息发布审批卡 </td>
                            </tr>
                            <tr>
                                <td  colspan='2' style="height: 150px;text-align: left;">
                                    <p>签发人（科室负责人）：</p>
                                    <p> &nbsp;&nbsp; </p>
                                    <p>分管领导：</p>
                                    <p> &nbsp;&nbsp; </p>
                                    <!--<p style="text-align:right;"> 年&nbsp;&nbsp;月&nbsp;&nbsp;日 </p>-->
                                </td>
                                <td colspan='2' style="height: 150px;text-align: left;">  
                                    <p>审核人：</p>
                                    <p> &nbsp;&nbsp; </p>
                                    <p> &nbsp;&nbsp; </p>
                                    <p> &nbsp;&nbsp; </p>
                                    <!--<p style="text-align:right;"> 年&nbsp;&nbsp;月&nbsp;&nbsp;日 </p>-->
                                </td>
                            </tr>
                            <tr>
                                <td  colspan='1'>拟稿部门</td>
                                <td  colspan='1'><input type="text" readonly="readonly" name="dep" class="dep" style="width: 96%;" value="<?php echo $department_arr['Department']['name'];?>" /></td>
                                <td  colspan='1'>拟稿人</td>
                                <td  colspan='1'><input type="text" readonly="readonly" name="user_name" class="user_name" style="width: 96%;" value="<?php echo $userInfo->name;?>" /></td>
                            </tr>
                            <tr>
                                <td  colspan='1'>标    题</td>
                                <td  colspan='3'><input type="text"  name="title" class="title" style="width: 96%;" /></td>
                            </tr>
                            <tr >
                                <td colspan='4' style='height:300px;/*line-height: 50px;*/'>
                                    <textarea name="content" class="content" style="min-width: 97%; min-height: 97%; max-width: 97%; max-height: 97%;"></textarea>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </form>
            </div>

<!-- PAGE CONTENT BEGINS -->
            <div id="dropzone">
                
                <?php if($mainInfo['id']){ ?>
                 <div class="alert alert-warning alert-dismissable center ">
                     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                         &times;
                     </button>
                     <strong>请再次提交附件！ </strong>
                 </div>
                 <?php   } ?>
                 
                <span>点击添加上传附件</span>
                <form  class="dropzone" style='min-height:100px;' enctype="multipart/form-data" id="upfiles"  method="post" >
                    
                    <div class="fallback" >
                        <input name="file[]" type="file" multiple="" />
                    </div>

                 <input type="hidden" id="file_upload" name="file_upload[]" value="" />  
                </form>
            </div><!-- PAGE CONTENT ENDS -->
            <!-- basic scripts -->
            <script src="/js/jquery-2.0.3.min.js"></script>
            <script src="/assets/js/dropzone.min.js"></script>
            <script type="text/javascript">
                      jQuery(function ($) {
                         try {
                             $(".dropzone").dropzone({
                                 url: '/ResearchProject/upload_file',
                                 paramName: "file", // The name that will be used to transfer the file
                                 maxFilesize: 5.0, // MB

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
            </script>




            <div class="modal-footer" style='background-color: #fff;'>
                <button style="margin-left:-50px;" type="button" class="btn btn-primary" onclick="window.parent.declares_close();" data-dismiss="modal"> <i class="icon-undo bigger-110"></i> 关闭</button>

                <button type="button" class="btn btn-primary" onclick="approve();"> <i class="icon-ok bigger-110"></i> 保存</button>
                <button type="button" class="btn btn-primary" onclick="printDIV();"><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
            </div>
            <script type="text/javascript">
                var class_name = 'not_right_tmp_8888';//定义一个没有的class
                function printDIV() {
                    $('.modal-footer').css('display', 'none');
                    $('#dropzone').css('display', 'none');
                    //隐藏下拉框
                    $('.' + class_name).css('display', 'none');
                    {
                        $('.navbar-default').css('display', 'none');
                        $('#sidebar').css('display', 'none');
                        $('.breadcrumbs').css('display', 'none');
                        $('.ace-settings-container').css('display', 'none');
                        $('#btn-scroll-up').css('display', 'none');
                        $('.right_content').css('display', 'none');
                    }
                    window.print();//打印刚才新建的网页
                    {
                        $('.navbar-default').css('display', '');
                        $('#sidebar').css('display', '');
                        $('.breadcrumbs').css('display', '');
                        $('.ace-settings-container').css('display', '');
                        $('#btn-scroll-up').css('display', '');
                        $('.right_content').css('display', '');
                    }
                    $('.modal-footer').css('display', '');
                    $('#dropzone').css('display', '');
                    $('.' + class_name).css('display', '');
                    return false;
                }
            </script>

        </div>
    </div><!-- /.row -->
</div>

<script type="text/javascript">

    function approve() {
        
        var declarename = $('.declarename').val();
        <?php if (empty($department_arr)) {?>
                alert('部门不存在')
                return;
        <?php }?>
        var title = $('.title').val();
        if (!title) {
            $('.title').focus();
            return;
        }
        var content = $('.content').val();
        if (!content) {
            $('.content').focus();
            return;
        }
        var data = {};
        data.title = title;
        data.content = content;
        data.declarename = declarename;
        data.attachment = $('#file_upload').val();
        $.ajax({
            url: '/RequestNote/gss_news',
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

                    return;
                }
                if (res.code == 0) {
                    //说明添加或修改成功
                    window.parent.declares_close();
                    window.location.reload();
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
    //添加错误信息
    function show_error(obj, msg) {
        obj.parent().find('.middle').addClass('text-danger').text(msg);
    }
    //去掉错误信息
    function hide_error(obj) {
        obj.parent().find('.middle').removeClass('text-danger').text('');
    }
    //为input框加事件
    $('input.col-xs-10').keyup(function () {
        if ($(this).val() != '') {
            hide_error($(this));
        }
    });
</script>

<?php echo $this->element('foot_frame'); ?>



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
                        <input type="hidden" name='declarename' class='declarename' value='山西省农科院果树所来文批办单' /> 
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;" >
                        <tbody>
                            <tr>
                                <td colspan='1' style="text-align: right;height:25px; line-height: 25px;">秘密等级</td>
                                <td colspan='1'>  <input  type="text" name='hierarchy' id='hierarchy' style='height:25px;width:80px;'> </td>  
                                <td style="border-bottom: 1px solid white;"></td>
                                <td><input class="text1" id="text1"  type="text"  style='height:25px;width:80px;'> </td>
                            </tr>
                            
                            <tr>
                                <td colspan='1' style="text-align: right;height:25px; line-height: 25px;">紧急程度</td>
                                <td colspan='1'>  <input  type="text" name='urgency' id='urgency' style='height:25px;width:80px;'> </td>  
                                <td style="border-top-color: white;border-bottom-color: white;"></td>
                                <td>第【<input  type="text"  name='num' id='num'  style='height:25px;width:80px;'>】号 </td>
                            </tr>
                            <tr>
                                <td colspan='1' style="text-align: right;height:25px; line-height: 25px;border-right-color: white;">  
                                    <div id='received_type'>
                                    <input type="radio"  checked="checked" name="type" value='1' />所办  &nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="type" value='2' />党办
                                    </div>
                                </td>
                                <td colspan='1' style="border-right-color: white;">  </td>  
                                <td style="border-top-color: white; border-left-color: white;"></td>
                                <td><input  readonly="true"  class="form_datetime1" name="datestr"  type="text" style='height:25px;width:125px;' value="<?php echo date('Y-m-d');?>"> </td>
                                <script type="text/javascript">
                                        $(".form_datetime1").datetimepicker({
                                            format: 'yyyy-mm-dd',
                                            minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                        });
                                </script>
                            </tr>
                            
                            <tr>
                                <td colspan="4" style="font-size:24px;font-weight: 600;border-color:#000;border-left-color: white;border-right-color: white;">  山西省农科院果树所来文批办单 </td>
                            </tr>
                            
                             <tr>
                                <td colspan='1' style="text-align: right;height:50px; line-height: 50px; text-align: center;">来文单位</td>
                                <td colspan='1' > <textarea style="height:50px;text-align: center;max-width: 150px;max-height: 50px;" name='company' id='company'></textarea>  </td>  
                                <td style="text-align: right;height:50px; line-height: 50px; text-align: center;">文号</td>
                                <td style="line-height: 50px;"><input type="text" name='document_number' id='document_number' style='height:25px;width:145px; text-align: center;line-height: 50px;'> </td>
                                
                            </tr>
                            <tr>
                                <td  colspan='1'>文件标题</td>
                                <td colspan='3'> <input type="text" name='file_title' id="file_title" style='width:470px;height:25px;'/> </td>
                            </tr>
                            <tr>
                                <td colspan='1' rowspan="2" style='height:50px;line-height: 100px;'>领导同志批示</td>
                                <td colspan='3'>   </td>
                            </tr>
                            <tr>
                                <td colspan='3'>   </td>
                            </tr>
                            <tr >
                                <td colspan='1' style='height:50px;/*line-height: 50px;*/'> 拟办意见</td>
                                <td colspan='3'>   </td>
                            </tr>
                            <tr >
                                <td colspan='1' style='height:50px;'> 业务科室意见 </td>
                                <td colspan='3'>   </td>
                            </tr>
                            <tr >
                                <td colspan='1' > 所长办公室承办人 </td>
                                <td colspan='1'> <input type="text" name='user_cbr' id='user_cbr' style='width: 150px;' />  </td>
                                <td colspan='1' > 联系电话 </td>
                                <td colspan='1'>  <input type="text" name='tel' id='tel' style='width: 150px;' /> </td>
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
            </script>
            
            <div class="modal-footer" style='background-color: #fff;'>
                <button style="margin-left:-50px;" type="button" class="btn btn-primary" onclick="window.parent.declares_close();" data-dismiss="modal"> <i class="icon-undo bigger-110"></i> 关闭</button>

                <button type="button" class="btn btn-primary" onclick="approve();"> <i class="icon-ok bigger-110"></i> 保存</button>
                <button type="button" class="btn btn-primary" onclick="printDIV();"><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
            </div>
<script type="text/javascript">
    var class_name = 'not_right_tmp_8888';//定义一个没有的class
function printDIV(){
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
        $('#received_type').css('display','none');
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
        var hierarchy = $('#hierarchy').val();
        var text1 = $('#text1').val();
        var urgency = $('#urgency').val();
        var num = $('#num').val();
        var type = $('input[type="radio"]:checked').val();
        var datestr = $("input[name = 'datestr']").val();
        var company = $('#company').val();
        var document_number = $('#document_number').val();
        var file_title = $('#file_title').val();
        var user_cbr = $('#user_cbr').val();
        var tel = $('#tel').val();
        var declarename = $('.declarename').val();
        
        if (hierarchy == '') {
            $('#hierarchy').focus();
            return;
        }
        if (text1 == '') {
            $('#text1').focus();
            return;
        }
        if (urgency == '') {
            $('#urgency').focus();
            return;
        }
        if (num == '') {
            $('#num').focus();
            return;
        }
        if (datestr == '') {
            $('#datestr').focus();
            return;
        }
        if (company == '') {
            $('#company').focus();
            return;
        }
        if (document_number == '') {
            $('#document_number').focus();
            return;
        }
        if (file_title == '') {
            $('#file_title').focus();
            return;
        }
        var attachment = $('#file_upload').val();
        var data = {};
        data.attachment = attachment,
        data.hierarchy = hierarchy;
        data.text1 = text1;
        data.urgency = urgency;
        data.num = num;
        data.type = type;
        data.datestr = datestr;
        data.company = company;
        data.document_number = document_number;
        data.file_title = file_title;
        data.tel = tel;
        data.user_cbr = user_cbr;
        data.declarename = declarename;
        $.ajax({
            url: '/RequestNote/gss_received',
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


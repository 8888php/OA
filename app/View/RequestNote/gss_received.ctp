
<?php //echo $this->element('head_frame'); ?>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:710px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" role="form">
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;" >
                        <input type="hidden" name='declarename' class='declarename' value='5、职工因公不休或不全休带薪假审批表' /> 
                        <tbody>
                            <tr>
                                <td colspan='1' style="text-align: right;height:25px; line-height: 25px;">秘密等级</td>
                                <td colspan='1'>  <input  type="text" style='height:25px;width:80px;'> </td>  
                                <td style="border-bottom: 1px solid white;"></td>
                                <td><input  type="text" style='height:25px;width:80px;'> </td>
                            </tr>
                            
                            <tr>
                                <td colspan='1' style="text-align: right;height:25px; line-height: 25px;">紧急程度</td>
                                <td colspan='1'>  <input  type="text" style='height:25px;width:80px;'> </td>  
                                <td style="border-top-color: white;border-bottom-color: white;"></td>
                                <td>第【<input  type="text" style='height:25px;width:80px;'>】号 </td>
                            </tr>
                            <tr>
                                <td colspan='1' style="text-align: right;height:25px; line-height: 25px;border-right-color: white;"></td>
                                <td colspan='1' style="border-right-color: white;">  </td>  
                                <td style="border-top-color: white; border-left-color: white;"></td>
                                <td><input  readonly="true"  class="form_datetime1" name="form_datetime1"  type="text" style='height:25px;width:80px;'> </td>
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
                                <td colspan='1' > <textarea style="height:50px;text-align: center;max-width: 150px;max-height: 50px;"></textarea>  </td>  
                                <td style="text-align: right;height:50px; line-height: 50px; text-align: center;">文号</td>
                                <td style="line-height: 50px;"><input type="text" style='height:25px;width:80px; text-align: center;line-height: 50px;'> </td>
                                
                            </tr>
                            
<!--                            <tr>
                                <td colspan='5' style="text-align: right;height:25px; line-height: 25px;">填表日期</td>
                                <td colspan='2'>  <input  type="text" class="ctime" name="ctime"  style='height:25px;width:180px;' readonly="readonly" value="<?php echo date('Y-m-d'); ?>"> </td>  
                            </tr>
                            <tr>
                                <td >姓名</td>
                                <td colspan='2'>  <input  type="text" class="applyname" name="applyname"  style='height:25px;width:180px;' readonly="readonly" value="<?php echo $userInfo->name;?>"> </td>
                                <td >所在单位</td>
                                <td colspan='3'>  
                                    <input  type="text" class="dep_pro" name="dep_pro"  style='height:25px;width:580px;'> 
                                    <select style="height:25px;width:270px;" name="dep_pro" class="dep_pro" onchange="">
                                        <?php foreach($department_arr as $v){?>
                                        <option value="0"><?php echo $v['name'];?></option>
                                        <?php }?>
                                        <?php foreach($team_arr as $v){?>
                                        <option value="<?php echo $v['team']['id'];?>"><?php echo $v['team']['name'];?></option>
                                        <?php }?>
                                    </select> 
                            </tr>
                            
                             <tr>
                                <td>参加工作时间</td>
                                <td colspan='2'>  <input readonly="readonly" type="text" class="start_work" name="start_work"  style='height:25px;width:180px;'>  
                                    <script type="text/javascript">
                                        $(".start_work").datetimepicker({
                                            format: 'yyyy-mm-dd',
                                            minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                        });
                                    </script> </td>
                                <td>工作年限</td>
                                <td colspan='3'> <input type="text" class="years" name="years" style='width:200px;height:25px;'/>  </td>
                             </tr>
                             <tr>
                                <td>应休假天数</td>
                                <td colspan='1' >  <input  type="text" class="vacation_days" name="vacation_days"  style='height:25px;width:80px;margin-top:5px;'> 
                                <td>已休假天数</td>
                                <td colspan='1'  align="center" valign="middle"> <input type="text" class="yx_vacation_days" name="yx_vacation_days" style='width:80px;height:25px;margin-top:5px;'/>  </td>
                                <td>余年假天数</td>
                                <td colspan='2'  align="center" valign="middle"> <input type="text" class="yx_vacation_days" name="yx_vacation_days" style='width:170px;height:25px;margin-top:5px;'/>  </td>
                             
                             </tr>                           
                            <tr>
                                <td >休假时间及天数</td>
                                <td colspan='4'>
                                    <input readonly="readonly" type="text" class=" form_datetime1 start_time" name="start_time"  style='height:25px;width:180px;'>  
                                    <script type="text/javascript">
                                        $(".form_datetime1").datetimepicker({
                                            format: 'yyyy-mm-dd',
                                            minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                        });
                                    </script>
                                    至
                                    <input readonly="readonly" type="text" class=" form_datetime2 end_time" name="end_time"  style='height:25px;width:180px;'>  
                                    <script type="text/javascript">
                                        $(".form_datetime2").datetimepicker({
                                            format: 'yyyy-mm-dd',
                                            minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                        });
                                    </script>
                                </td>
                                <td style="width:90px;">合计</td>
                                <td >  <input type="text" name='sum_days' class='sum_days' value='0'  style="width:50px;"  />  天 </td>
                            </tr>-->
                           
                            <tr>
                                <td  colspan='1'>文件标题</td>
                                <td colspan='3'> <input type="text" name='reason' class="reason" style='width:470px;height:25px;'/> </td>
                            </tr>
                            <tr>
                                <td colspan='1' rowspan="2" style='height:50px;    line-height: 100px;'>领导现场批示</td>
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
                                <td colspan='1' style='/*height:50px;*/'> 所长办公室承办人 </td>
                                <td colspan='1'> <input type="text" style='width: 150px;' />  </td>
                                <td colspan='1' style='/*height:50px;*/'> 联系电话 </td>
                                <td colspan='1'>  <input type="text" style='width: 150px;' /> </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </form>
            </div>

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
        /**
         * navbar-default
            id sidebar 
            breadcrumbs
            ace-settings-container
            id btn-scroll-up
            right_content
         */
        $('.navbar-default').css('display', 'none');
        $('#sidebar').css('display', 'none');
        $('.breadcrumbs').css('display', 'none');
        $('.ace-settings-container').css('display', 'none');
        $('#btn-scroll-up').css('display', 'none');
        $('.right_content').css('display', 'none');
    }
    window.print();//打印刚才新建的网页
    {
        /**
         * navbar-default
            id sidebar 
            breadcrumbs
            ace-settings-container
            id btn-scroll-up
            right_content
         */
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
        var applyname = $('.applyname').val();
        var depname = $('.dep_pro option:selected').val();
        var start_work = $('.start_work').val();
        var years = $('.years').val();
        var vacation_days = $('.vacation_days').val();
        var yx_vacation_days = $('.yx_vacation_days').val();
        var start_time = $('.start_time').val();
        var end_time = $('.end_time').val();
        var sum_days = $('.sum_days').val();
        var personal_apply = $('.personal_apply').val();
        var declarename = $('.declarename').val();
        
        if (applyname == '') {
            $('.applyname').focus();
            return;
        }
        if (depname == '') {
            $('.depname').focus();
            return;
        }
        if (start_work == '') {
            $('.start_work').focus();
            return;
        }
        if (years == '') {
            $('.years').focus();
            return;
        }
        if (vacation_days == '') {
            $('.vacation_days').focus();
            return;
        }
        if (yx_vacation_days == '') {
            $('.yx_vacation_days').focus();
            return;
        }
        if (start_time == '') {
            $('.start_time').focus();
            return;
        }
        if (end_time == '') {
            $('.end_time').focus();
            return;
        }
        if (sum_days == '') {
            $('.sum_days').focus();
            return;
        }
        var data = {};
        data.applyname = applyname;
        data.depname = depname;
        data.start_work = start_work;
        data.years = years;
        data.vacation_days = vacation_days;
        data.yx_vacation_days = yx_vacation_days;
        data.start_time = start_time;
        data.end_time = end_time;
        data.sum_days = sum_days;
        data.personal_apply = personal_apply;
        data.declarename = declarename;
        $.ajax({
            url: '/RequestNote/gss_furlough',
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


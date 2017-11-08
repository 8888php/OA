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
                        <input type="hidden" name='declarename' class='declarename' value='职工因公不休或不全休带薪假审批表' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:24px;font-weight: 600;border-color:#000;">  职工因公不休或不全休带薪假审批表 </td>
                            </tr>
                            <tr>
                                <td colspan='5' style="text-align: right;height:25px; line-height: 25px;">填表日期</td>
                                <td colspan='2'>  <input  type="text" class="ctime" name="ctime"  style='height:25px;width:180px;' readonly="readonly" value="<?php echo date('Y-m-d'); ?>"> </td>  
                            </tr>
                            <tr>
                                <td >姓名</td>
                                <td colspan='2'>  <input  type="text" class="applyname" name="applyname"  style='height:25px;width:180px;' readonly="readonly" value="<?php echo $userInfo->name;?>"> </td>
                                <td >所在单位</td>
                                <td colspan='3'>  
                                    <!--<input  type="text" class="dep_pro" name="dep_pro"  style='height:25px;width:580px;'>--> 
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
                                <td colspan='2'>  <input readonly="readonly" type="text" class="start_time" name="start_time"  style='height:25px;width:180px;'>  
                                    <script type="text/javascript">
                                        $(".start_time").datetimepicker({
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
                                <td colspan='1'  align="center" valign="middle"> <input type="text" class="days_off" name="days_off" style='width:80px;height:25px;margin-top:5px;'/>  </td>
                                <td>余年假天数</td>
                                <td colspan='2'  align="center" valign="middle"> <input type="text" class="rest_days" name="rest_days" style='width:170px;height:25px;margin-top:5px;'/>  </td>
                             
                             </tr>                           
                            
                           
                            <tr>
                                <td  colspan='2'>因公不能休息原因</td>
                                <td colspan='5'> <input type="text" name='reason' class="reason" style='width:470px;height:25px;'/> </td>
                            </tr>
                            <tr>
                                <td colspan='2' style='height:50px;'>所在单位意见</td>
                                <td  colspan='5' >   </td>
                            </tr>
                            <tr >
                                <td colspan='2' style='height:50px;/*line-height: 50px;*/'> 分管所长意见</td>
                                <td colspan='5'>   </td>
                            </tr>
                            <tr >
                                <td colspan='2' style='height:50px;'> 财务会研究审批意见 </td>
                                <td colspan='5' >   </td>
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
        var ctime = $('.ctime').val();
        var applyname = $('.applyname').val();
        var dep_pro = $('.dep_pro option:selected').val();
        var start_time = $('.start_time').val();
        var years = $('.years').val();
        var vacation_days = $('.vacation_days').val();
        var days_off = $('.days_off').val();
        var rest_days = $('.rest_days').val();
        var reason = $('.reason').val();
        var declarename = $('.declarename').val();
        var data = {};
        data.declarename = declarename;
        data.dep_pro = dep_pro;
        if (!ctime) {
           $('.ctime').focus();
            return; 
        }
        data.ctime = ctime;
        if (applyname == '') {
            $('.applyname').focus();
            return;
        }
        data.applyname = applyname;
        if (start_time == '') {
            $('.start_time').focus();
            return;
        }
        data.start_time = start_time;
        if (years == '') {
            $('.years').focus();
            return;
        }
        data.years = years;
        if (vacation_days == '') {
            $('.vacation_days').focus();
            return;
        }
        data.vacation_days = vacation_days;
        if (days_off == '') {
            $('.days_off').focus();
            return;
        }
        data.days_off = days_off;
        if (rest_days == '') {
            $('.rest_days').focus();
            return;
        }
        data.rest_days = rest_days;
        if (reason == '') {
            $('.reason').focus();
            return;
        }
        data.reason = reason;
        $.ajax({
            url: '/RequestNote/gss_endlessly',
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


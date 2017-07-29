<?php echo $this->element('head_frame'); ?>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:780px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" role="form">
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;" >
                        <input type="hidden" name='declarename' class='declarename' value='果树所职工带薪年休假审批单' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:14px;font-weight: 600;border-color:#000;">  果树所职工带薪年休假审批单 </td>
                            </tr>
                            <tr>
                                <td >姓名</td>
                                <td colspan='2'>  <input  type="text" class="username" name="username"  style='height:25px;width:190px;' readonly="readonly" value="<?php echo $userInfo->name;?>"> </td>
                                <td >所在单位</td>
                                <td colspan='3'>  <input  type="text" class="company" name="company"  style='height:25px;width:290px;'> </td>
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
                                <td>按规定享受年假天数</td>
                                <td colspan='2'>  <input  type="text" class="vacation_days" name="vacation_days"  style='height:25px;width:180px;'> 
                                <td>本年度已休年假天数</td>
                                <td colspan='3'> <input type="text" class="yx_vacation_days" name="yx_vacation_days" style='width:200px;height:25px;'/>  </td>
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
                                <td >  <input type="text" name='total_days' class='total_days' value=''  style="width:50px;" />  天 </td>
                            </tr>
                           
                            <tr>
                                <td>个人申请</td>
                                <td colspan='6'> <input type="text" name='grsq' class="grsq" style='width:600px;height:25px;'/> </td>
                            </tr>
                            <tr>
                                <td colspan='2' style="width:260px;" >部门负责人</td>
                                <td  colspan='2'  style="width:260px;">分管所领导</td>
                                <td  colspan='3' >所长</td>
                            </tr>
                            <tr style="height:60px;line-height: 20px;">
                                <td colspan='2'  >  </td>
                                <td colspan='2'  > </td>
                                <td colspan='3' > </td>
                            </tr>
                            
                            <tr >
                                <td colspan='7'>  填表说明：出差3天以内（含3天）需分管领导签字，出差3天以上需所长签字。 </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="modal-footer" style='background-color: #fff;'>
                <button style="margin-left:-50px;" type="button" class="btn btn-primary" onclick="window.parent.declares_close();"> <i class="icon-undo bigger-110"></i> 关闭</button>

                <button type="button" class="btn btn-primary" onclick="approve();"> <i class="icon-ok bigger-110"></i> 保存</button>
                <button type="button" class="btn btn-primary" onclick=""><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
            </div>


        </div>
    </div><!-- /.row -->
</div>

<script type="text/javascript">
  
    function approve() {
        var company = $('.company').val();
        var start_work = $('.start_work').val();
        var years = $('.years').val();
        var vacation_days = $('.vacation_days').val();
        var yx_vacation_days = $('.yx_vacation_days').val();
        var start_time = $('.start_time').val();
        var end_time = $('.end_time').val();
        var total_days = $('.total_days').val();
        var grsq = $('.grsq').val();
        
        if (company == '') {
            $('.company').focus();
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
        if (total_days == '') {
            $('.total_days').focus();
            return;
        }
        var data = {};
        data.company = company;
        data.start_work = start_work;
        data.years = years;
        data.vacation_days = vacation_days;
        data.yx_vacation_days = yx_vacation_days;
        data.start_time = start_time;
        data.end_time = end_time;
        data.grsq = grsq;
        data.total_days = total_days;
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


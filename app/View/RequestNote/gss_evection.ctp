<?php //echo $this->element('head_frame'); ?>
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
                        <input type="hidden" name='declarename' class='declarename' value='果树所差旅审批单' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:14px;font-weight: 600;border-color:#000;">  果树所差旅审批单 </td>
                            </tr>
                            <tr>
                                <!--td > </td>
                                <td colspan='4'>
                                    <input readonly="readonly" type="text" class="members" name="members"  style='height:25px;width:340px;'>   < span id='memnums'> 共0人</span >
                                </td-->
                                <td >填表时间</td>
                                <td colspan='6'>  <input readonly="readonly" type="text" class="ctime" name="ctime"   value="<?php echo date('Y-m-d'); ?>"  style='height:25px;width:590px;'>  </td>
                            </tr>
                            
                             <tr>
                                <td>出差事由</td>
                                <td colspan='6'> <input type="text" name='reason' class="reason" style='width:600px;height:25px;'/>  </td>
                             </tr>
                             <tr>
                                <td>出差人员</td>
                                <td colspan='6'> <input type="text" class="personnel" name="personnel" style='width:600px;height:25px;'/> </td>
                            </tr>
                            
                            <tr>
                                <td >出差时间</td>
                                <td colspan='4'>
                                    <input readonly="readonly" type="text" class=" form_datetime1 start_day" name="start_day"  style='height:25px;width:180px;'>  
                                    <script type="text/javascript">
                                        $(".form_datetime1").datetimepicker({
                                            format: 'yyyy-mm-dd',
                                            minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                        });
                                    </script>
                                    至
                                    <input readonly="readonly" type="text" class=" form_datetime2 end_day" name="end_day"  style='height:25px;width:180px;'>  
                                    <script type="text/javascript">
                                        $(".form_datetime2").datetimepicker({
                                            format: 'yyyy-mm-dd',
                                            minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                        });
                                    </script>
                                </td>
                                <td style="width:90px;">合计</td>
                                <td >  <input type="text" name='sum_day' class='sum_day' value=''  style="width:50px;" />  天 </td>
                            </tr>
                           
                            <tr>
                                <td>出差地点</td>
                                <td colspan='6'> <input type="text" name='address' class="address" style='width:600px;height:25px;'/> </td>
                            </tr>
                            <tr>
                                <td style="width:110px;">交通方式及路线</td>
                                <td colspan='6'> <input type="text" name='mode_route' class="mode_route" style='width:600px;height:25px;'/> </td>
                            </tr>
                            
                            <!--tr>
                                <td> <br />使用交通 <br />工具情况</td>
                                <td colspan='6' style='text-align: left;line-height: 25px;'>
                                1、乘坐营运交通工具 
                                <input name="vehicle" type="checkbox" value="火车" /> 火车 
                                <input name="vehicle" type="checkbox" value="汽车" /> 气车 
                                <input name="vehicle" type="checkbox" value="轮船" /> 轮船  
                                <input name="vehicle" type="checkbox" value="飞机" /> 飞机 
                                <input name="vehicle" type="checkbox" value="车辆租赁" /> 车辆租赁 
                                <br />
                                2、单位派车或租用汽车信息  &nbsp;&nbsp;车号&nbsp;<input type="text" name='wagon_number' class="wagon_number" style='width:160px;height:20px;'/>   &nbsp;&nbsp;司机&nbsp;<input type="text" name='driver' class="driver" style='width:160px;height:20px;'/> <br />
                                3、其他（需说明）：<input type="text" name='remark' class="remark" style='width:450px;height:20px;'/>
                                </td>
                            </tr-->

                            
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
                <button style="margin-left:-50px;" type="button" class="btn btn-primary" onclick="window.parent.declares_close();" data-dismiss="modal"> <i class="icon-undo bigger-110"></i> 关闭</button>

                <button type="button" class="btn btn-primary" onclick="approve();"> <i class="icon-ok bigger-110"></i> 保存</button>
                <button type="button" class="btn btn-primary" onclick=""><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
            </div>


        </div>
    </div><!-- /.row -->
</div>

<script type="text/javascript">
  
    function approve() {
        var ctime = $('.ctime').val();
        var reason = $('.reason').val();
        var personnel = $('.personnel').val();
        var start_day = $('.start_day').val();
        var end_day = $('.end_day').val();
        var sum_day = $('.sum_day').val();
        var address = $('.address').val();
        var mode_route = $('.mode_route').val();
        var declarename = $('.declarename').val();
        
        if (ctime == '') {
            $('.ctime').focus();
            return;
        }
        if (reason == '') {
            $('.reason').focus();
            return;
        }
        if (personnel == '') {
            $('.personnel').focus();
            return;
        }
        if (start_day == '') {
            $('.start_day').focus();
            return;
        }
        if (end_day == '') {
            $('.end_day').focus();
            return;
        }
        if (address == '') {
            $('.address').focus();
            return;
        }
        if (mode_route == '') {
            $('.mode_route').focus();
            return;
        }

        var data = {ctime: ctime, reason: reason, start_day: start_day, end_day: end_day, personnel: personnel, sum_day: sum_day, address: address,mode_route: mode_route,declarename: declarename};
        $.ajax({
            url: '/RequestNote/gss_evection',
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


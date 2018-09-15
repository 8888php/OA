<?php //echo $this->element('head_frame'); ?>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap-select.min.js"></script>
<link href="/assets/css/bootstrap-select.min.css" rel="stylesheet" />

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:710px;'>

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
                                <td colspan="7" style="font-size:24px;font-weight: 600;border-color:#000;">  果树所差旅审批单 </td>
                            </tr>
                            <tr>
                                <td > 单位或部门 </td>
                                <td colspan='3'>  
                                    <select style="height:25px;width: 280px;" name="dep_pro" class="dep_pro" onchange="change_personnel();">
                                        <?php foreach($department as $v){?>
                                        <option value="0"><?php echo $v['name'];?></option>
                                        <?php }?>
                                        <?php foreach($projectInfo as $k=>$v){?>
                                        <option value="<?php echo $k;?>"><?php echo $v;?></option>
                                        <?php }?>
                                    </select>
                                </td>
                        <script type="text/javascript">
                            //当更改  dep_pro的值时，personnel 的值清空
                            var dep_pro_sel_val = $('.dep_pro').val();
                            function change_personnel() {
                                $('.personnel').val('');
                                dep_pro_sel_val = $('.dep_pro').val();
                                var data = {};
                                data.pid = dep_pro_sel_val;
                                $.ajax({
                                    url: '/RequestNote/get_users_by_dep',
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
                                            var str = '';
                                            for(var i in res.data) {
                                                str += '<option data-tokens="'+res.data[i]['User']['name']+'">'+res.data[i]['User']['name']+'</option>';
                                            }
                                            $('#tokens').html(str);
                                            $('#tokens').selectpicker('destroy');
                                            $('#tokens').selectpicker({
                                                noneSelectedText:'请选择出差人员',
                                                noneResultsText:'未搜到{0}',
                                                actionsBox:true,
                                                selectAllText:'全选',
                                                deselectAllText:'清空',
                                                doneButton:true,
                                                doneButtonText: '关闭',
                                                width: 'css-width',
                                            });
                                            $('#tokens').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
                                                var sel_val = $(this).val();
                                                $('.personnel').val(sel_val);
                                            });
                                            
                                        }
                                        if (res.code == 2) {
                                            //失败
                                            alert(res.msg);
                                            return;
                                        }
                                    }
                                });
                            }
                            change_personnel();
                            
                            
                        </script>
                                <td >填表时间</td>
                                <td colspan='2'>  
                                    <input readonly="readonly" type="text" class="ctime" name="ctime"   value="<?php echo date('Y-m-d'); ?>"  style='height:25px;width:180px;'>  
                                    <script type="text/javascript">
                                        $(".ctime").datetimepicker({
                                            format: 'yyyy-mm-dd',
                                            minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                        });
                                    </script>
                                </td>
                            </tr>
                            
                             <tr>
                                <td>出差事由</td>
                                <td colspan='6'> <input type="text" name='reason' class="reason" style='width:575px;height:25px;'/>  </td>
                             </tr>
                             <tr>
                                
                                <td>出差人员</td>
                                <td colspan='6'> 
                                    <select id="tokens" class="selectpicker form-control" style="color: white; height: 190px; overflow:scroll;" multiple data-live-search="true">
                                        
                                    </select>
                                    <input type="hidden" class="personnel" name="personnel" style='width:575px;height:25px;' value=""  /> </td>
                            </tr>
                            <style type="text/css">
                                .dropdown-menu{
                                    max-height: 200px;
                                    overflow:scroll;
                                }
                            </style>
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
                                <td colspan='6'> <input type="text" name='address' class="address" style='width:575px;height:25px;'/> </td>
                            </tr>
                            <tr>
                                <td style="width:110px;">交通方式及路线</td>
                                <td colspan='6'> <input type="text" name='mode_route' class="mode_route" style='width:575px;height:25px;'/> </td>
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
        var dep_pro = $('.dep_pro  option:selected').val();
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
            $('.filter-option').click();
//            $('.personnel').focus();
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
        var data = {dep_pro: dep_pro, ctime: ctime, reason: reason, start_day: start_day, end_day: end_day, personnel: personnel, sum_day: sum_day, address: address,mode_route: mode_route,declarename: declarename};
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


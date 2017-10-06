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
                        <input type="hidden" name='declarename' class='declarename' value='果树所请假申请单' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:24px;font-weight: 600;border-color:#000;">  果树所请假申请单 </td>
                            </tr>
                            <tr>
                                <td colspan='3'>填表日期</td>
                                <td colspan='4'>  <?php echo $attr_arr[0][$table_name]['ctime'];?>  </td>
                             </tr>
                             <tr>
                                <td > 单位或部门 </td>
                                <td colspan='6'>   <?php echo $attr_arr[0][$table_name]['department_name'];?>  </td>
                            </tr>
                            
                             <tr>
                                <td> 请假类型 </td>
                                <td colspan='2'>   <?php echo Configure::read('apply_leave_type')[$attr_arr[0][$table_name]['type_id']];?>   </td>
                                <td> 请假人 </td>
                                <td colspan='3'> <?php echo $attr_arr[0][$table_name]['applyname'];?>   </td>
                             </tr>
                             <tr>
                                <td> 事由 </td>
                                <td colspan='6' >  <?php echo $attr_arr[0][$table_name]['about'];?>  </td> 
                             </tr>                           
                            <tr>
                                <td >请假天数</td>
                                <td colspan='4'>
                                    <?php echo $attr_arr[0][$table_name]['start_time'];?>
                                    至
                                    <?php echo $attr_arr[0][$table_name]['end_time'];?>
                                </td>
                                <td style="width:90px;">共</td>
                                <td >  <?php echo $attr_arr[0][$table_name]['total_days'];?>  天 </td>
                            </tr>
                           
                            <tr>
                                <td colspan='2'> 所在单位负责人 </td>
                                <td colspan='5'>    </td>
                            </tr>
                            <tr>
                                <td colspan='2'> 医务室 </td>
                                <td  colspan='5' >     </td>
                            </tr>
                            <tr >
                                <td colspan='2'> 分管领导 </td>
                                <td colspan='5'>     </td>
                            </tr>
                            <tr >
                                <td colspan='2'> 分管人事领导 </td>
                                <td colspan='5' >     </td>
                            </tr>
                            <tr >
                                <td colspan='2'> 所长 </td>
                                <td colspan='5' >    </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </form>
            </div>
            
             <?php if ($apply == 'apply') {?>
                <div class="modal-body" style="padding:0 20px;">
                    <input type="hidden" name="main_id" id="main_id" value="<?php echo $main_arr['ApplyMain']['id'];?>">
                    <textarea id="remarks" placeholder="审批意见" rows="2" cols="85"></textarea>
                </div>
            <?php }?>

            <div class="modal-footer" style='background-color: #fff;'>
                 <?php if ($apply == 'apply') {?>
                <button type="button" class="btn btn-primary" onclick="approve(2);"><i class="icon-undo bigger-110"></i> 拒绝</button>
                <button type="button" class="btn btn-primary" onclick="approve(1);"> <i class="icon-ok bigger-110"></i> 同意</button>
                <?php }?>
                
                <button type="button" class="btn btn-primary" onclick="printDIV();"><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
                <button  type="button" class="btn btn-primary" onclick="window.parent.declares_close();" data-dismiss="modal"> <i class="icon-undo bigger-110"></i> 关闭</button>
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
        var leave_type = $('.leave_type option:selected').val();
        var about = $('.about').val();
        var start_time = $('.start_time').val();
        var end_time = $('.end_time').val();
        var sum_days = $('.sum_days').val();
        var leading = $('.leading').val();
        var clinic = $('.clinic').val();
        var leadership = $('.leadership').val();
        var personnel = $('.personnel').val();
        var bureau_chief = $('.bureau_chief').val();
        var declarename = $('.declarename').val();
        
        if (applyname == '') {
            $('.applyname').focus();
            return;
        }
        if (dep_pro == '') {
            $('.dep_pro').focus();
            return;
        }
        if (leave_type == '') {
            $('.leave_type').focus();
            return;
        }
        if (about == '') {
            $('.about').focus();
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
        data.ctime = ctime;
        data.applyname = applyname;
        data.dep_pro = dep_pro;
        data.leave_type = leave_type;
        data.reason = about;
        data.start_time = start_time;
        data.end_time = end_time;
        data.sum_days = sum_days;
        data.leading = leading;
        data.clinic = clinic;
        data.leadership = leadership;
        data.personnel = personnel;
        data.bureau_chief = bureau_chief;
        data.declarename = declarename;
        $.ajax({
            url: '/RequestNote/gss_leave',
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


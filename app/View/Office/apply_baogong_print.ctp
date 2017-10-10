
<?php //echo $this->element('head_frame'); ?>
<!--<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>-->

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:710px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" enctype="multipart/form-data" action="" method="post" role="form">
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;" >
                        <input type="hidden" name='declarename' class='declarename' value='田间作业包工申请表' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:24px;font-weight: 600;border-color:#000;">  山西省农科院果树所田间作业包工申请表 </td>
                            </tr>
                             <tr>
                                 <td colspan="5" style="text-align: right;line-height: 25px;"> 编号 </td>
                                <td colspan='2'> <?php echo $attr_arr[0][$table_name]['number'];?></td>
                            </tr>
                            <tr>
                                <td colspan='2' style="height: 45px; line-height: 45px;"> 部门 </td>
                                <td colspan='5' style="height: 45px; line-height: 45px;">
                                    <?php echo $team_arr[0]['t_team']['name'];?>
                                </td>
                            </tr>
                            
                            <tr>
                                <td colspan='2' style="height: 45px; line-height: 45px;"> 包工人员 </td>
                                <td colspan='5' style="height: 45px; line-height: 45px;">
                                    <?php echo $attr_arr[0][$table_name]['personnel'];?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2' style="height: 45px; line-height: 45px;"> 包工时间及地点 </td>
                                <td colspan='5' style="height: 45px; line-height: 45px;"> 
                                   <?php echo $attr_arr[0][$table_name]['time_address'];?> 
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2' style="height: 45px; line-height: 45px;"> 包工内容及工作量 </td>
                                <td colspan='5' style="height: 45px; line-height: 45px;">
                                    <?php echo $attr_arr[0][$table_name]['content'];?> 
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2' style="height: 45px; line-height: 45px;"> 部门负责人意见 </td>
                                <td colspan='5' style="height: 45px; line-height: 45px;"> 
                                    <?php  echo $applyArr[20]['name'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[20]['ctime'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[20]['remarks'];  ?> 
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2' style="height: 45px; line-height: 45px;"> 科研办公室意见 </td>
                                <td colspan='5' style="height: 45px; line-height: 45px;"> 
                                    <?php  echo $applyArr[4]['name'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[4]['ctime'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[4]['remarks'];  ?> 
                                </td>
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
    function isPositiveInteger(s){//是否为正整数
        var re = /^[0-9]+$/ ;
        return re.test(s)
    }
    $('.nums,.price').blur(function(){
        var nums = $('.nums').val();
        var price = $('.price').val();
        var total = 0;
        if (!isPositiveInteger(nums)) {
            nums = 0;
        }
        if (!Number(price)) {
            price = 0;
        }
        total = parseFloat(nums) * parseFloat(price);
        $('.total').val(parseFloat(total));
    });
    function approve(type) {
        var text = '拒绝';
        if (type == 1) {
            text = '同意';
        } else {
            type = 2;
        }
        if (!confirm('您确认 ' + text + ' 该项目？')) {
            //取消
            return;
        }
        var data = {main_id: $('#main_id').val(), type: type, remarks: $('#remarks').val()};
        $.ajax({
            url: '/Office/ajax_approve_baogong',
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
                    $('.close').click();
                    window.parent.location.reload();
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


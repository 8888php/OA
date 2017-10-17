

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
                        <input type="hidden" name='declarename' class='declarename' value='档案借阅申请表' /> 
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;" >
                        <tbody>
                            <tr>
                                <td colspan="4" style="font-size:24px;font-weight: 600;border-color:#000;">  档案借阅申请表 </td>
                            </tr>
                            
                            <tr>
                                <td  colspan='1'>借阅单位</td>
                                <td colspan='1'>  
                                    <select style="height:25px;width:160px;" name="dep_pro" class="dep_pro" onchange="">
                                        <?php foreach($department_arr as $v){?>
                                        <option value="0"><?php echo $v['name'];?></option>
                                        <?php }?>
                                        <?php foreach($team_arr as $v){?>
                                        <option value="<?php echo $v['team']['id'];?>"><?php echo $v['team']['name'];?></option>
                                        <?php }?>
                                    </select>
                                    <td  colspan='1'>借阅时间</td>
                                    <td colspan='1'><input  readonly="true"  class="datestr" name="datestr"  type="text" style='height:25px;width:150px;' value="<?php echo date('Y-m-d H:i:s');?>"> </td>                               
                            <tr>
                                <td  colspan='1'>借阅内容</td>
                                <td colspan='3'> <input type="text" name='content' class="content" style='width:470px;height:25px;'/> </td>
                            </tr>
                            <tr>
                                <td  colspan='1'>借阅用途</td>
                                <td colspan='3'> <input type="text" name='purpose' class="purpose" style='width:470px;height:25px;'/> </td>
                            </tr>
                             <tr >
                                <td colspan='1' style='height:50px;'> 借阅单位负责人意见</td>
                                <td colspan='3'>   </td>
                            </tr>
                            <tr >
                                <td colspan='1' style='height:50px;'> 相关部门负责人意见</td>
                                <td colspan='3'>   </td>
                            </tr>
                            <tr >
                                <td colspan='1' style='height:50px;'> 分管所领导意见</td>
                                <td colspan='3'>   </td>
                            </tr>
                            <tr>
                                <td colspan='1'  style='height:50px;line-height: 50px;'>借阅人签字</td>
                                <td colspan='1'> <input type="text" name='borrow_user' class="borrow_user" style='width:130px;height:25px;' value="<?php echo $userInfo->name;?>"/>   </td>
                                <td colspan='1'  style='height:50px;line-height: 50px;'>档案室经办人签字</td>
                                <td colspan='1'>   </td>
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
        var dep_pro = $('.dep_pro option:selected').val();
        var datestr = $('.datestr').val();
        var content = $('.content').val();
        var purpose = $('.purpose').val();
        var borrow_user = $('.borrow_user').val();
        var declarename = $('.declarename').val();
        
        if (dep_pro == '') {
            $('.dep_pro').focus();
            return;
        }
        if (datestr == '') {
            $('.datestr').focus();
            return;
        }
        if (content == '') {
            $('.content').focus();
            return;
        }
        if (purpose == '') {
            $('.purpose').focus();
            return;
        }
        if (borrow_user == '') {
            $('.borrow_user').focus();
            return;
        }
        
        var data = {};
        data.dep_pro = dep_pro;
        data.company = $('.dep_pro option:selected').text();
        data.datestr = datestr;
        data.content = content;
        data.purpose = purpose;
        data.borrow_user = borrow_user;
        data.declarename = declarename;
        $.ajax({
            url: '/RequestNote/gss_borrow',
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


<?php echo $this->element('head_frame'); ?>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:660px;'>
    <p class="btn btn-info btn-block" style="border-radius:4px 4px 0 0;padding:0 12px;"> <span style="font-size:16px;"> 项目成员管理 </span> <a onclick="window.parent.step_close();" class="close" data-dismiss="modal" id='closemodel'>×</a></p>

    <div class="row" style='padding:20px 0;margin:0 auto;'>
        <div class="col-xs-12">
            <form class="form-horizontal"   role="form">
                <input type="hidden" name="pid" id='pid' value="<?php echo $pid; ?>" />
                <table class="table table-bordered table-striped" style=''>
                    <tbody>
                        <tr style='font-weight:600;' class="blue">
                            <td> NO· </td>
                            <td>姓名</td>
                            <td>手机</td>
                            <td>权限</td>
                            <td>日期</td>
                            <td>账号</td>
                            <td>备注</td>
                            <td>操作</td>
                        </tr>

                        <tr >
                            <td> &nbsp;&nbsp;<i class="glyphicon glyphicon-star-empty blue bigger-130"></i> </td>
                            <td> <select name="member" id='member' style='height:28px;line-height: 28px;' > 
                                    <option value=""> 请选 </option> 
                                <?php foreach($notInMember as $mk => $mv){  ?>
                                    <option value="<?php echo $mv['u']['id']; ?>"> <?php echo $mv['u']['name']; ?> </option> 
                                <?php } ?>
                                </select> 
                            </td>
                            <td>  </td>
                            <td> <select name="types"  id='types' style='height:28px;line-height: 28px;'> <option value="2"> 职员 </option> </select> </td>
                            <td> <?php echo date('Y-m-d'); ?> </td>
                            <td>  </td>
                            <td> <input type='text' name='remark' id='remark' style='width:50px;height:28px;line-height: 28px;' /> </td>
                            <td> &nbsp;&nbsp; <i class="icon-plus arrow blue" title='添加' onclick="mem_edit(8, 'add')" ></i>  </td>
                        </tr>

                        <?php  foreach($projectMember as $pk => $pv){  ?>
                        <tr>
                            <td><?php echo $pk;  ?></td>
                            <td>  <?php echo $pv['ProjectMember']['name'];  ?> </td>
                            <td>  <?php echo $pv['ProjectMember']['tel'];  ?> </td>
                            <td> <?php echo $pv['ProjectMember']['type'] == 1 ? '负责人':'职员';  ?> </td>
                            <td> <?php echo $pv['ProjectMember']['ctime'];  ?> </td>
                            <td> <?php echo $pv['ProjectMember']['user_name'];  ?> </td>
                            <td> <input type='text' name='remark' id="remark<?php echo $pv['ProjectMember']['id'];?>" style='width:50px;height:28px;line-height: 28px;' value="<?php echo $pv['ProjectMember']['remark'];?>" /> </td>
                            <td> <i class="glyphicon glyphicon-edit blue" title='修改' onclick="mem_edit(<?php echo $pv['ProjectMember']['id'];?>, 'edit')"></i>
                                &nbsp;&nbsp;
                                <i class="glyphicon glyphicon-trash red" title='删除' onclick="mem_edit(<?php echo $pv['ProjectMember']['id'];?>, 'del')" ></i> </td>
                        </tr>
                        <?php } ?>

                    </tbody>
                </table>   

        </div>

        </form>
    </div><!-- /.col -->
</div><!-- /.row -->
</div>

<script type="text/javascript">  
    function upstep() {
        $('#closemodel').click();
    }
    //提交内容
    var click_flag = true;//是否可以点击
    function mem_edit(mid, type) {
        if (!click_flag) {
            return;
        }
        var data_json = {};
        data_json.pid = $('#pid').val();
        data_json.type = type;
        if (type == 'add') {
            data_json.member = $('#member').val();
            data_json.types = $('#types').val();
            data_json.remark = $('#remark').val();
        }else{
            data_json.member = mid;
            data_json.remark = $('#remark'+mid).val();
        }

        click_flag = false;
        $.ajax({
            url: '/ResearchProject/member_operation',
            type: 'post',
            data: data_json,
            dataType: 'json',
            success: function (res) {
                click_flag = true;
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
                    //清空之前的错误提示
                    $('.middle').removeClass('text-danger').text('');
                    show_error($(res.class), res.msg);
                    return;
                }
                if (res.code == 0) {
                    //说明添加或修改成功
                   // location.reload() ;
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
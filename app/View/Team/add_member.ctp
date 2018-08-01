<?php echo $this->element('head_frame'); ?>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:660px;'>
    <p class="btn btn-info btn-block" style="border-radius:4px 4px 0 0;padding:0 12px;"> <span style="font-size:16px;"> 团队成员管理 </span> <a onclick="window.parent.member_close();" class="close" data-dismiss="modal" >×</a></p>

    <div class="row" style='padding:20px 0;margin:0 auto;'>
        <div class="col-xs-12">
            <form class="form-horizontal"   role="form">
                <input type="hidden" name="tid" id='tid' value="<?php echo $tid; ?>" />
                <table class="table table-bordered table-striped" style='text-align:left;'>
                    <tbody>
                        <tr style='font-weight:600;text-align:center;' class="blue">
                            <td> NO· </td>
                            <td>姓名</td>
                            <td>权限</td>
                            <td>日期</td>
                            <td>操作</td>
                        </tr>

                        <tr >
                            <td> &nbsp;&nbsp;<i class="glyphicon glyphicon-star-empty blue bigger-130"></i> </td>
                            <td> 
                                 <input type="text" style="font-size: 13px; width: 108px;height:28px;" onkeyup="change_member(this.value)"  placeholder="输入姓名筛选" />
                                <select name="member" id='member' style='width: 110px;height:28px;line-height: 28px;' > 
                                    <option value=""> 请选择 </option> 
                                <?php foreach($notInMember as $mk => $mv){  ?>
                                    <option value="<?php echo $mv['u']['id']; ?>"> <?php echo $mv['u']['name']; ?> </option> 
                                <?php } ?>
                                </select> 
                            </td>
                            <td> <select name="code"  id='code' style='height:28px;line-height: 28px;'> 
                                    <option value="0"> 职员 </option>
                                    <option value="1"> 负责人 </option>
                                    <option value="2"> 所领导 </option> 
                                </select> </td>
                            <td> <?php echo date('Y-m-d'); ?> </td>
                           
                            <td> &nbsp;&nbsp; <i class="icon-plus arrow blue" title='添加' onclick="mem_edit(<?php echo $tid; ?>, 'add')" ></i>  </td>
                        </tr>

                        <?php  foreach($teamMember as $pk => $pv){  ?>
                        <tr>
                            <td><?php echo $pk+1;  ?></td>
                            <td>  <?php echo $pv['TeamMember']['name'];  ?> </td>
                            <td> 
                                <select  id="<?php  echo 'code'.$pv['TeamMember']['id'];  ?>" style='height:28px;line-height: 28px;'> 
                                    <option value="0" <?php echo $pv['TeamMember']['code']== 0 ? 'selected':'';  ?> > 职员 </option>
                                    <option value="1" <?php echo $pv['TeamMember']['code']== 1 ? 'selected':'';  ?> > 负责人 </option>
                                    <option value="2" <?php echo $pv['TeamMember']['code']== 2 ? 'selected':'';  ?> > 所领导 </option> 
                                </select>
                                </td>
                            <td> <?php echo $pv['TeamMember']['create_time'];  ?> </td>
                            
                            <td> <i class="glyphicon glyphicon-edit blue" title='修改' onclick="mem_edit(<?php echo $pv['TeamMember']['id'];?>, 'edit')"></i>
                                &nbsp;&nbsp;
                                <?php if($pv['TeamMember']['code'] == 0){ ?>
                                <i class="glyphicon glyphicon-trash red" title='删除' onclick="mem_edit(<?php echo $pv['TeamMember']['id'];?>, 'del')" ></i> 
                                <?php } ?>
                                </td>
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
    //根据Input的值去搜索 申请人的
function change_member(val) {
        if (val == '') {
            $('#member option').each(function(i){
                $(this).css('display', 'block');
            });
             return;
        }
        $('#member option').each(function(i){
            if (i == 0) {
               return true;
            }
            var text = $(this).text();
            if (text.indexOf(val) != -1) {
                //说明包含
                $(this).css('display', 'block');
            } else {
                $(this).css('display', 'none');
            }
        });                                      
}
   
    
    //提交内容
    var click_flag = true;//是否可以点击
    function mem_edit(mid, type) {
        if (!click_flag) {
            return;
        }
        var data_json = {};
        data_json.tid = $('#tid').val();
        data_json.type = type;
        if (type == 'add') {
            data_json.member = $('#member').val();
            data_json.code = $('#code').val();
        }else{
            if(type == 'del' && !confirm('确定删除该成员？')){
                return;
            }
            data_json.code = $('#code'+mid).val();
            data_json.mid = mid;
        }

        $.ajax({
            url: '/Team/member_operation',
            type: 'post',
            data: data_json,
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
                    //清空之前的错误提示
                    $('.middle').removeClass('text-danger').text('');
                    show_error($(res.class), res.msg);
                    return;
                }
                if (res.code == 0) {
                    //说明添加或修改成功
                    $('#modal-body').load('/Team/add_member/<?php echo $tid;?>');
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
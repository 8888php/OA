<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:660px;'>
    <p class="btn btn-info btn-block" style="border-radius:4px 4px 0 0;padding:0 12px;"> <span style="font-size:16px;"> 审批单-加签 </span> <a onclick="window.parent.member_close();" class="close" data-dismiss="modal" >×</a></p>

    <div class="row" style='padding:20px 0;margin:0 auto;'>
        <div class="col-xs-12">
            <form class="form-horizontal"   role="form">
                <input type="hidden" name="pid" id='pid' value="<?php echo $pid; ?>" />
                <table class="table table-bordered table-striped" style=''>
                    <tbody>
                        <tr style='font-weight:600;' class="blue">
                            <td> 人员选择 </td>
                            <td colspan="2">
<!--                                <input type="text" style="width: 100px;" onkeyup="change_select(this.value);" />-->
                                <select name="types"  id='types' style='height:28px;line-height: 28px;'> 
                                    <option value="xingzheng"> 按行政部门选择 </option> 
                                    <option value="keyan"> 按科研部门选择 </option>
                                    <option value="zhiwu"> 按职务选择 </option>
                                </select>
                                <script type="text/javascript">
                                    //根据所搜，更改select内容
                                    var orig_val = ''; //原始值为空
                                    function change_select(search_val) {
                                    if (orig_val == search_val) {
                                    //如果上次的值和这次一样，就不做处理
                                    return;
                                    }
                                    orig_val = search_val; //把最新值赋给原来的值
                                    //去掉所有 selected
                                    $('#member option').removeAttr('selected');
                                    //遍历select
                                    $('#member option').each(function(i){
                                    if (i == 0) {
                                    //跳过
                                    return true;
                                    }
                                    var text = $(this).text();
                                    if (text.indexOf(search_val) != - 1) {
                                    //说明包含
                                    $(this).css('display', 'block');
                                    } else {
                                    $(this).css('display', 'none');
                                    }
                                    });
                                    }
                                    var selectType = '#' + $('#types').val();
                                    $('#types').change(function(){
                                    selectType = '#' + $('#types').val();
                                    $('select[name="depart"]').css('display', 'none');
                                    $(selectType).css('display', 'inline');
                                    if ($(selectType).val() > 0){
                                    typeChange();
                                    } else{
                                    $('#user').html('');
                                    }
                                    });
                                    function typeChange(){
                                    var depType = $('#types').val();
                                    var depVal = $('#' + depType).val();
                                    var ret = /^[1-9]\d*$/;
                                    if (!ret.test(depVal)){
                                    alter('部门或职务选择有误');
                                    return false;
                                    }
                                    $.ajax({
                                    url: '/Department/userlist',
                                            type: 'post',
                                            data: {type : depType, depval : depVal},
                                            dataType: 'json',
                                            success: function (res) {
                                            switch (res.code){
                                            case - 1 :  //登录过期
                                                    window.location.href = '/homes/index';
                                            return;
                                            case - 2 :  //权限不足
                                                    alert('权限不足');
                                            return;
                                            case 1 : //说明有错误
                                                    alert(res.msg);
                                            //清空之前的错误提示
                                            $('.middle').removeClass('text-danger').text('');
                                            show_error($(res.class), res.msg);
                                            return;
                                            case 0 : //说明添加或修改成功
                                                    var textStr = '';
                                            $.each(res.content, function(key, val){
                                            textStr += "<option value='" + key + "'>" + val + '</option>';
                                            });
                                            $('#user').html(textStr);
                                            return;
                                            case 2 :  //失败
                                                    alert(res.msg);
                                            return;
                                            }
                                            }
                                    });
                                    }
                                </script>
                                <select name="depart"  id='xingzheng' style='height:28px;line-height: 28px;' onchange="typeChange()" >
                                    <option value="0"> 请选择 </option>
                                    <?php foreach($deplist[1] as $k => $v){ ?>
                                    <option value="<?php echo $k; ?>"> <?php echo $v; ?> </option>
                                    <?php } ?>
                                </select>
                                <select name="depart"  id='keyan'  style='display:none;height:28px;line-height: 28px;' onchange="typeChange()">
                                    <option value="0"> 请选择 </option>
                                    <?php foreach($deplist[2] as $k => $v){ ?>
                                    <option value="<?php echo $k; ?>"> <?php echo $v; ?> </option>
                                    <?php } ?>
                                   </select>
                                <select name="depart"  id='zhiwu'  style='display:none;height:28px;line-height: 28px;' onchange="typeChange()">
                                    <option value="0"> 请选择 </option>
                                    <?php foreach($positionArr as $k => $v){ ?>
                                    <option value="<?php echo $k; ?>"> <?php echo $v; ?> </option>
                                    <?php } ?>
                                    </select>
                                
                                <select name="user" id='user' style='height:28px;line-height: 58px; min-width: 100px;' > </select> 
                            </td>
                            <td>  &nbsp;&nbsp; <i class="icon-plus arrow blue" title='添加' onclick="approve_edit('add')" ></i>  </td>
                        </tr>

                        <tr >
                            <td> &nbsp;&nbsp;<i class="glyphicon glyphicon-star-empty blue bigger-130"></i> </td>
                            <td> 
                            </td>
                            <td>  </td>
                            <!--td> <input type='text' name='remark' id='remark' style='width:50px;height:28px;line-height: 28px;' /> </td-->
                            <td> &nbsp;&nbsp; <!--i class="icon-plus arrow blue" title='添加' onclick="mem_edit(<?php echo $pid; ?>, 'add')" ></i-->  </td>
                        </tr>

                        <?php  foreach($projectMember as $pk => $pv){  ?>
                        <tr>
                            <td><?php echo $pk+1;  ?></td>
                            <td>  <?php echo $pv['ProjectMember']['name'];  ?> </td>
                            <td>   </td>
                            <td>
                                &nbsp;&nbsp;
                                <?php if($pv['ProjectMember']['type'] != 1){ ?>
                                    <i class="glyphicon glyphicon-trash red" title='删除' onclick="mem_edit( 'del',<?php echo $pv['ProjectMember']['id'];?>)" ></i> 
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
var click_flag = true;
function approve_edit(type , mid = 0) {
        if (!click_flag) {
             return;
        }
        var data_json = {};
        data_json.pid = $('#pid').val();
        data_json.type = type;
        if (type == 'add'){
            data_json.user = $('#user').val();
        }else{
            if (type == 'del' && !confirm('确定删除该成员？')){
                 return;
            }
            data_json.mid = mid;
        }

        $.ajax({
            url: '/Department/approve_jiaqian',
            type: 'post',
            data: data_json,
            dataType: 'json',
            success: function(res) {
                    switch(res.code){
                        case 1 : 
                            alert(res.msg);
                            $('.middle').removeClass('text-danger').text('');
                            show_error($(res.class), res.msg);
                            return;
                        case 0 : //成功
                            $('#modal-body').load('/Department/add_approval/<?php echo $pid;?>');
                            return;
                        case 2 : //失败
                             alert(res.msg);
                            return;
                        case -1 :
                            window.location.href = '/homes/index';
                            return;
                        case -2 : 
                            alert('权限不足');
                            return;
                    }
                }
        });
}
        
//添加错误信息
function show_error(obj, msg){
        obj.parent().find('.middle').addClass('text-danger').text(msg);
}
//去掉错误信息
function hide_error(obj){
        obj.parent().find('.middle').removeClass('text-danger').text('');
}
//为input框加事件
$('input.col-xs-10').keyup(function () {
        if ($(this).val() != ''){
           hide_error($(this));
        }
});
</script>

<?php echo $this->element('foot_frame'); ?>
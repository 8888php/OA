<?php echo $this->element('head_frame'); ?>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:360px;height:340px;'>
    <p class="btn btn-info btn-block" style="border-radius:4px 4px 0 0;padding:0 12px;"> <span style="font-size:16px;"> 项目经费 </span> <a onclick="window.parent.storage_close();" class="close" data-dismiss="modal" >×</a></p>

    <div class="row" style='padding:20px 0;margin:0 auto;'><?php //var_dump($storageInfo);?>
        <div class="col-xs-12">
            <form class="form-horizontal"   role="form">
                <input type="hidden" name="pid" id='pid' value="<?php echo @$pid; ?>" />
                <input type="hidden" name="sid" id='sid' value="<?php echo @$storageInfo['id']; ?>" />

                <ul class="form-ul" style='overflow-x: hidden;'>
                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">项目 &nbsp;&nbsp;</label>
                        <input type="text"  id="pname" name="pname" value="<?php echo @$pinfos['name']; ?>" disabled />  
                    </li>

                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">规格 &nbsp;&nbsp;</label>
                        <input type="text"  placeholder="规格" id="spec" name="spec" value="<?php echo @$storageInfo['spec']; ?>" />  
                    </li>

                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">数量 &nbsp;&nbsp;</label>
                        <input type="text"  id="nums" name="nums" placeholder="数量" value="<?php echo @$storageInfo['nums']; ?>" />  
                    </li>
                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">金额 &nbsp;&nbsp;</label>
                        <input type="text"  placeholder="金额" id="amount" name="amount" value="<?php echo @$storageInfo['amount']; ?>" />  
                    </li>
                    
                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">摘要 &nbsp;&nbsp;</label>
                        <textarea class="form-control"  id="abstract" name="abstract" placeholder="摘要" rows="2" style='width:180px;'> <?php echo @$storageInfo['abstract']; ?> </textarea>
                    </li>
                </ul>

                <div class="form-group" style="text-align: center;">
                    <div class="col-sm-offset-2 col-sm-9">
                        <button type="button" class="btn btn-primary" onclick="sub_storage();">
                            <i class="icon-ok bigger-110"></i>
                            <?php echo @$storageInfo['id'] ? '修改':'确定'; ?>  
                        </button>
                        &nbsp; &nbsp; &nbsp;
                        <button type="button" class="btn btn-info" onclick="window.parent.storage_close();" data-dismiss="modal" id="close" >
                            <i class="icon-undo bigger-110"></i>
                            取消
                        </button>
                    </div>
                </div>


            </form>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>

<script type="text/javascript">
    //提交内容
    var click_flag = true;//是否可以点击
    function sub_storage() {
        if (!click_flag) {
            return;
        }
        var data_json = {};
        data_json.pid = $('#pid').val();
        data_json.sid = $('#sid').val();
        data_json.pname = $('#pname').val();
         if ($('#spec').val() == '') {
            $('#spec').focus();
            return;
        }
        data_json.spec = $('#spec').val();
         if ($('#nums').val() == '') {
            $('#nums').focus();
            return;
        }
        data_json.nums = $('#nums').val();
         if ($('#amount').val() == '') {
            $('#amount').focus();
            return;
        }
        data_json.amount = $('#amount').val();
        data_json.abstract = $('#abstract').val();
        
        $.ajax({
            url: '/ResearchProject/sub_storage',
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
                    
                    alert(res.msg);
                    //if(res.class == 'edit'){ 
                        window.location.reload(true);
                    //}
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
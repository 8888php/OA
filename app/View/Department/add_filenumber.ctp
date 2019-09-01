<?php //echo $this->element('head_frame'); ?>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:660px;'>
    <p class="btn btn-info btn-block" style="border-radius:4px 4px 0 0;padding:0 12px;"> <span style="font-size:16px;"> 部门资金来源管理 </span> <a onclick="window.parent.member_close();" class="close" data-dismiss="modal" >×</a></p>

    <div class="row" style='padding:20px 0;margin:0 auto;'>
        <div class="col-xs-12">
            <form class="form-horizontal"   role="form">
                <input type="hidden" name="did" id='did' value="<?php echo $did; ?>" />
                <table class="table table-bordered table-striped" style=''>
                    <tbody>
                        <tr style='font-weight:600;' class="blue">
                            <td> NO· </td>
                            <td style='text-align:center;'>来源渠道</td>
                            <td style='text-align:center;'>年度</td>
                            <td style='text-align:center;'>文号</td>
                            <td style='text-align:center;'>金额</td>
                            <td style='text-align:center;'>操作</td>
                        </tr>

                        <tr >
                            <td> &nbsp;&nbsp;<i class="glyphicon glyphicon-star-empty blue bigger-130"></i> </td>
                            <?php
                                $source_channel_array = array(
                                    '所级',
                                    '院级',
                                    '省级',
                                    '国家级',
                                    '其它',
                                );
                            ?>
                            <td> 
                                <select style="height:28px;line-height: 28px;width:115px;" name="source_channel" class="source_channel"  >
                                    <?php foreach ($source_channel_array as $k=>$v) {?>
                                    <option value='<?php echo $v;?>' > <?php echo $v;?> </option>
                                    <?php }?>
                                </select>
                            </td>
                            <td> <input type="text"  name='year' class='year' placeholder="年度" style="height:28px;line-height: 28px;width:115px;" value="<?php echo date('Y'); ?>"  readonly="readonly" />  </td>
                            <td> <input type="text"  name='file_number' class='file_number'  placeholder="文号" style="height:28px;line-height: 28px;width:115px;"  value="" /> </td>
                            <td> <input type="text"  name='amount' class='amount'  placeholder="金额" style="height:28px;line-height: 28px;width:115px;" value="" />  </td>
                            
                            <td> &nbsp;&nbsp; <i class="icon-plus arrow blue" title='添加' onclick="source_edit(<?php echo $did; ?>, 'add')" ></i>  </td>
                        </tr>

                        <?php  foreach($proSource as $pk => $pv){  ?>
                        <tr>
                            <td><?php echo $pk+1;  ?></td>
                            <td> <?php echo $pv['ResearchSource']['source_channel'];  ?> </td>
                            <td> <?php echo $pv['ResearchSource']['year'] ;  ?> </td>
                            <td> <input type="text" class='file_number<?php echo $pv['ResearchSource']['id']; ?>'  placeholder="文号" style="height:28px;line-height: 28px;width:115px;"  value="<?php echo $pv['ResearchSource']['file_number'];  ?>" /> </td>
                            <td> <input type="text" class='amount<?php echo $pv['ResearchSource']['id']; ?>'  placeholder="金额" style="height:28px;line-height: 28px;width:115px;" value="<?php echo $pv['ResearchSource']['amount'];  ?>" />  </td>
                            <td>
                                <span class="glyphicon glyphicon-edit blue" onclick="source_edit(<?php echo $did; ?>,'edit',<?php echo $pv['ResearchSource']['id']; ?>);"  title='修改'></span>
                                &nbsp;&nbsp;
                                <a href="javascript:void(0);" onclick="source_edit(<?php echo $did; ?>,'del',<?php echo $pv['ResearchSource']['id']; ?>);"  title='删除'> <span class="glyphicon glyphicon-trash red" ></span> </a>   
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
    //提交内容
    var click_flag = true;//是否可以点击
    function source_edit(mid, type, sid = 0) {
        if (!click_flag) {
            return;
        }
        var data_json = {};
        data_json.did = $('#did').val();
        data_json.type = type;
        data_json.sid = sid;
        switch(type) {
            case 'add':
            if($('.file_number').val() == '' || $('.amount').val() == ''){
                alert('文号、金额不能为空！');
                return;
            }
            data_json.source_channel = $('.source_channel option:selected').val();
            data_json.year = $('.year').val();
            data_json.file_number = $('.file_number').val();
            data_json.amount = $('.amount').val();
            break;
            case 'edit':
            if(!confirm('确定修改？')){
                return ;
            }
            data_json.file_number = $(".file_number"+sid).val();
            data_json.amount = $(".amount"+sid).val();
            break;
            case 'del':
            if(!confirm('确定删除？')){
                return ;
            }
            break;
            default:
                return;
        }

        $.ajax({
            url: '/Department/sub_filenumber_dep',
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
                    $('#modal-body').load('/Department/add_filenumber/<?php echo $did;?>');
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
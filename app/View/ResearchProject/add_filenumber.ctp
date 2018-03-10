<?php echo $this->element('head_frame'); ?>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:660px;'>
    <p class="btn btn-info btn-block" style="border-radius:4px 4px 0 0;padding:0 12px;"> <span style="font-size:16px;"> 项目资金来源管理 </span> <a onclick="window.parent.member_close();" class="close" data-dismiss="modal" >×</a></p>

    <div class="row" style='padding:20px 0;margin:0 auto;'>
        <div class="col-xs-12">
            <form class="form-horizontal"   role="form">
                <input type="hidden" name="pid" id='pid' value="<?php echo $pid; ?>" />
                <table class="table table-bordered table-striped" style=''>
                    <tbody>
                        <tr style='font-weight:600;' class="blue">
                            <td> NO· </td>
                            <td>来源渠道</td>
                            <td>年度</td>
                            <td>文号</td>
                            <td>金额</td>
                            <td>操作</td>
                        </tr>

                        <tr >
                            <td> &nbsp;&nbsp;<i class="glyphicon glyphicon-star-empty blue bigger-130"></i> </td>
                            <td> <select style="height:28px;line-height: 28px;width:115px;" name="source_channel" class="source_channel"  >
                            <?php 
                            foreach(Configure::read('qd_arr') as $qd){?>
                            <option value="<?php  echo $qd;?>"><?php  echo $qd;?></option>
                            <?php }?>
                            </select>
                            </td>
                            <td> <input type="text"  name='year' class='year' placeholder="年度" style="height:28px;line-height: 28px;width:115px;"   />  </td>
                            <td> <input type="text"  name='file_number' class='file_number'  placeholder="文号" style="height:28px;line-height: 28px;width:115px;"  value="" /> </td>
                            <td> <input type="text"  name='amount' class='amount'  placeholder="金额" style="height:28px;line-height: 28px;width:115px;" value="" />  </td>
                            
                            <td> &nbsp;&nbsp; <i class="icon-plus arrow blue" title='添加' onclick="source_edit(<?php echo $pid; ?>, 'add')" ></i>  </td>
                        </tr>

                        <?php  foreach($proSource as $pk => $pv){  ?>
                        <tr>
                            <td><?php echo $pk+1;  ?></td>
                            <td> <?php echo $pv['ResearchSource']['source_channel'];  ?> </td>
                            <td> <?php echo $pv['ResearchSource']['year'] ;  ?> </td>
                            <td> <?php echo $pv['ResearchSource']['file_number'];  ?> </td>
                            <td> <?php echo $pv['ResearchSource']['amount'];  ?> </td>
                            <td> </td>                                
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
    function source_edit(mid, type) {
        if (!click_flag) {
            return;
        }
        var data_json = {};
        data_json.pid = $('#pid').val();
        data_json.type = type;
        if (type == 'add') {
            data_json.source_channel = $('.source_channel option:selected').val();
            data_json.year = $('.year').val();
            data_json.file_number = $('.file_number').val();
            data_json.amount = $('.amount').val();
        }else{
            if(type == 'del' && !confirm('确定删除？')){
                return;
            }
        }

        $.ajax({
            url: '/ResearchProject/sub_filenumber',
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
                    $('#modal-body').load('/ResearchProject/add_filenumber/<?php echo $pid;?>');
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
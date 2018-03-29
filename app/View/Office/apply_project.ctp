<?php echo $this->element('head_frame'); ?>


<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:560px;'>

    <p class="btn btn-info btn-block"  style="border-radius:4px 4px 0 0;padding:0 12px;"> <span style="font-size:16px;"> 审核项目</span> <a onclick="window.parent.wait_close();" class="close" data-dismiss="modal" >×</a></p>


    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">

                <table class="table table-striped table-bordered table-condensed" >
                    <tbody>
                        <tr>
                            <td>全称</td>
                            <td><?php echo $pinfos['name'];  ?></td>
                            <td>简称</td>
                            <td><?php echo $pinfos['alias'];  ?></td>
                        </tr>
                        <tr>
                            <td>资金性质</td>
                            <td><?php 
                                switch($pinfos['type']){
                                case 1 : echo '零余额';break; 
                                case 2 : echo '基本户';break; 
                                case 3 : echo '基地户';break; 
                                }  ?> </td>
                            <td>金额</td>
                            <td><?php echo $pinfos['amount'];  ?></td>
                        </tr>
                        <tr>
                            <td>开始日期</td>
                            <td> <?php echo $pinfos['start_date'];  ?> </td>
                            <td>结束日期</td>
                            <td> <?php echo $pinfos['end_date'];  ?> </td>
                        </tr>
                        <tr>
                            <td>申请人</td>
                            <td> <?php echo $create_user_info['User']['name'];  ?> </td>
                            <td>所属项目组</td>
                            <td> <?php echo $team_name;  ?> </td>
                        </tr>
                        <tr>
                            <td>任务书</td>
                            <td colspan="3"> 
                                <?php 
                                $filearr = explode('|',$pinfos['filename']);
                                foreach($filearr as $fv){
                                echo "<a href='/files/$fv' target='_blank' > $fv </a> <br/>";
                                }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td>资金来源</td>
                            <td colspan="3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>来源渠道</th>
                                            <th>文号</th>
                                            <th>金额</th>
                                            <th>年度</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php  foreach($source as $sk => $sv){  ?>
                                        <tr>
                                            <td><?php echo $sv['ResearchSource']['source_channel'];  ?></td>
                                            <td><?php echo $sv['ResearchSource']['file_number'];  ?></td>
                                            <td><?php echo $sv['ResearchSource']['amount'];  ?></td>
                                            <td><?php echo $sv['ResearchSource']['year'];  ?></td>
                                        </tr>
                                        <?php   } ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td>项目概述</td>
                            <td colspan='3'> <?php echo $pinfos['overview'];  ?> </td>
                        </tr>
                        <tr>
                            <td>备注</td>
                            <td colspan='3'> <?php echo $pinfos['remark'];  ?> </td>
                        </tr>
                    </tbody>

                    <tbody>
                        <tr>  <th colspan="4" class='blue' style='text-align: center;font-size:14px;'> 项目预算 </th> </tr>


                        <?php  
                        $all_money = 0;
                        foreach($costList as $ysk => $ysv){  ?>
                        <tr>
                            <?php foreach($ysv as $k => $v){ ?>
                            <td><?php echo $v;  ?></td>
                            <td><?php echo $cost[$k] ? $cost[$k] : '0.00';  ?></td>
                            <?php 
                            if ($cost[$k]) {
                                $all_money += $cost[$k];
                            }
                            ?>
                            <?php   } ?>
                        </tr>
                        <?php   } ?>
                        <tr>
                            <td>预算合计</td>
                            <td colspan="3"><?php echo sprintf('%.2f', $all_money); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="modal-body">
                <input type="hidden" name="p_id" id="p_id" value="<?php echo $pinfos['id'];?>" />
                <textarea id="remarks" placeholder="审批意见" rows='6' cols='60' ></textarea>
            </div>
            <div class="modal-footer" style='background-color: #fff;'>
                <button type="button" class="btn btn-primary" onclick="approve(1);"><i class="icon-undo bigger-110"></i> 拒绝</button>
                <button type="button" class="btn btn-primary" onclick="approve(2);"> <i class="icon-ok bigger-110"></i> 同意</button>
            </div>
        </div>
    </div><!-- /.row -->
</div>




<script type="text/javascript">
    function approve(type) {
        var remarks = $('#remarks').val();//备注
        if (remarks == '') {
            $('#remarks').focus();
            return;
        }
        var text = '拒绝';
        if (type == 2) {
            text = '同意';
        } else {
            type = 1;
        }
        if (!confirm('您确认 ' + text + ' 该项目？')) {
            //取消
            return;
        }
        var data = {p_id: $('#p_id').val(), remarks: remarks, type: type};
        $.ajax({
            url: '/Office/ajax_approve',
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
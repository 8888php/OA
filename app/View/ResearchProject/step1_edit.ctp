<?php //echo $this->element('head_frame'); ?>


<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:560px;'>

    <p class="btn btn-info btn-block"  style="border-radius:4px 4px 0 0;padding:0 12px;"> <span style="font-size:16px;">项目信息</span> <a onclick="close_window();" class="close" data-dismiss="modal" id='closemodel'>×</a></p>


    <div  style='padding:20px 0;'>
        <div >
            <form class="form-horizontal" role="form" id="formstep1" method="post" action="/ResearchProject/step2">
                <input type="hidden" name="step1" value="step1" />
                <ul class="form-ul">
                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">全称 &nbsp;&nbsp;</label>
                        <input type="text" id="form-field-1" placeholder="全称" class="name " name="name" value="<?php  echo $pro_arr['name'];?>" />  

                        <label class="input-group-addon " for="form-field-2">简称  &nbsp;&nbsp;</label> 
                        <input type="text" id="form-field-2" placeholder="简称" class="alias" name="alias" value="<?php  echo $pro_arr['alias'];?>" />           
                    </li> 

                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">资金性质 &nbsp;&nbsp;</label>
                        <select  name="type" class="type input-width" id="form-field-1" style="width:145px;">
                            <option value="1" <?php  echo $pro_arr['type'] == 1 ? 'selected' : '';?> >零余额</option>
                            <option value="2" <?php  echo $pro_arr['type'] == 2 ? 'selected' : '';?> >基本户</option>
                            <option value="3" <?php  echo $pro_arr['type'] == 3 ? 'selected' : '';?> >基地户</option>
                        </select>  

                        <label class="input-group-addon " for="form-field-2">金额 &nbsp;&nbsp;</label> 
                        <input type="text" id="form-field-2" placeholder="金额" class="sumamount" name="sumamount" value="<?php  echo $pro_arr['amount'];?>"  disabled="disabled" />                
                    </li> 
                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">所属项目组 </label>
                        <select  name="project_team_id" class="project_team_id input-width" id="form-field-1" style="width:140px;"   disabled="disabled">
                            <option value="0">单个项目</option>
                            <?php foreach($team as $pk=>$pv) {?>
                                <option value="<?php echo $pk;?>" <?php  echo $pro_arr['project_team_id'] == $pk ? 'selected' : '';?> ><?php echo $pv;?></option>
                            <?php }?>
                            
                        </select>                
                    
                        <label class="input-group-addon " for="form-field-1">项目性质 </label>
                        <select  name="sld" class="sld input-width" id="form-field-1" style="width:140px;" >
                            <?php foreach(Configure::read('approval_sld') as $sk=>$sv) {?>
                                <option value="<?php echo $sk;?>" <?php  echo $pro_arr['approval_sld'] == $sk ? 'selected' : '';?> ><?php echo $sv;?></option>
                            <?php }?>
                            
                        </select>                
                    </li> 

                    <script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>
                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">开始 &nbsp;&nbsp;</label>
                        <input readonly="readonly" type="text" class=" form_datetime1 start_date" name="start_date" value="<?php  echo $pro_arr['start_date'];?>">  
                        <script type="text/javascript">
                            $(".form_datetime1").datetimepicker({
                                format: 'yyyy-mm-dd',
                                minView: "month", //选择日期后，不会再跳转去选择时分秒 
                            });
                        </script>

                        <label class="input-group-addon " for="form-field-2">结束 &nbsp;&nbsp;</label>
                        <input readonly="readonly" type="text"  class="form_datetime2 end_date" name="end_date" value="<?php  echo $pro_arr['end_date'];?>"> 
                        <script type="text/javascript">
                            $(".form_datetime2").datetimepicker({
                                format: 'yyyy-mm-dd',
                                minView: "month", //选择日期后，不会再跳转去选择时分秒 
                            });
                        </script>
                    </li> 

                    <li class="input-group  qdly" >
                        <label class="input-group-addon " for="form-field-1">资金来源</label> 
                        <input type="text" readonly="readonly" placeholder="来源渠道" style="float: left;width:105px;" /> 
                        <input type="text" readonly="readonly" placeholder="年度" style="float: left;width:85px;"   /> 
                        <input type="text" readonly="readonly" id="form-field-2" placeholder="文号" style="width:105px;"  value="文号" />           
                        <input type="text" readonly="readonly" id="form-field-2" placeholder="金额" style="width:85px;" value="金额" />           
                        &nbsp;  
                        <script type="text/javascript">
                            //添加渠道来源
                            function add_qdly() {
                                $('.qdly').last().after($('.demo_hide').clone().removeClass('demo_hide').addClass('qdly_add').css('display', ''));
                            }
                            //删除当前接点
                            function del_qbly(obj) {
                                $(obj).parent().remove();
                            }
                        </script>
                    </li>     
                    <?php foreach ($res_arr as $k=>$v) {
                        $temp = $v['ResearchSource'];
                        ?>
                    <li class="input-group qdly qdly_add" style="">
                        <label  for="form-field-1" style="width:81px;float: left;"></label>
                        <input type="hidden" name="source_id" class="source_id" value="<?php echo $temp['id'];?>" />
                        <select style="float:left;width:105px;" name="source[source_channel][]" class="source_channel">
                            <?php 
                            foreach(Configure::read('qd_arr') as $qd){?>
                            <option value="<?php  echo $qd;?>" <?php echo $temp['source_channel'] == $qd ? 'selected' : '';?> ><?php  echo $qd;?></option>
                            <?php }?>
                        </select>
                        <select style="width:85px;" name="source[year][]" class="year col-sm-2" >
                            <?php foreach(range(2017,2030) as $n){?>
                            <option value="<?php echo $n;?>" <?php echo $temp['year'] == $n ? 'selected' : '';?>><?php echo $n;?></option>
                            <?php } ?>
                        </select>
                        <input type="text" placeholder="文号" style="width:105px;" class="file_number "  name="source[file_number][]" value="<?php echo $temp['file_number'];?>" />           
                        <input type="text" placeholder="金额"  class="amount" name="source[amount][]" style="width:85px;"  value="<?php echo $temp['amount'];?>" disabled="disabled" />
                        &nbsp;
                    </li>  
                    <?php }?>
                </ul>

                <div class="form-group" style="margin:10px auto;width:490px;">
                    <label class="control-label no-padding-right" style="width:100px;text-align: right;" for="form-field-1">项目概述 &nbsp;&nbsp;</label>
                    <textarea class="overview" name="overview" style="width:350px;" placeholder="项目概述" ><?php  echo $pro_arr['overview'];?></textarea>
                </div>
                <div class="form-group" style="margin:10px auto;width:500px;">
                    <label class="control-label no-padding-right" style="width:100px;text-align: right;" for="form-field-1">备注 &nbsp;&nbsp;</label>
                    <textarea class="remark" name="remark" style="width:350px;" placeholder="备注"><?php  echo $pro_arr['remark'];?></textarea>

                </div>

                <div class="space-4"></div>


                <div class="clearfix " style="text-align: center;">
                    <div class=" col-md-9">
                        <button class="btn btn-primary" type="button" onclick='/*close_window();*/' data-dismiss="modal" >
                            <i class="icon-undo bigger-110"></i>
                            取消
                        </button>
                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-info" type="button"  onclick="ajax_submit();">
                            <i class="icon-ok bigger-110"></i>
                            修改
                        </button>

                    </div>
                </div>


            </form>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>




<script type="text/javascript">
    //关闭窗口
//    function close_window() {
//        $('.close').click();
//    }
    //提交内容
    function ajax_submit() {
        var data_json = {};
        var name = $('.name').val();
        var alias = $('.alias').val();
        var start_date = $('.start_date').val();
        var end_date = $('.end_date').val();
        var qdly_add_length = $('.qdly_add').length;
        var project_team_id = $('.project_team_id option:selected').val();
        var sumamount = $('.sumamount').val();
        var sld = $('.sld option:selected').val();
        var type = $('.type option:selected').val();
        var overview = $('.overview').val();
        var remark = $('.remark').val();
        var pid = '<?php echo $pro_arr['id'];?>';
        data_json.project_team_id = project_team_id;
        data_json.pid = pid;
        data_json.type = type;
        if (name == '') {
            $('.name').focus();
            return;
        }
        data_json.name = name;
        if (alias == '') {
            $('.alias').focus();
            return;
        }
        data_json.alias = alias;
        if (sumamount == '') {
            $('.sumamount').focus();
            return;
        }
        data_json.sumamount = sumamount;
        if (sld == '') {
            $('.sld').focus();
            return;
        }
        data_json.sld = sld;
        if (start_date == '') {
            $('.start_date').focus();
            return;
        }
        data_json.start_date = start_date;
        if (end_date == '') {
            $('.end_date').focus();
            return;
        }
        data_json.end_date = end_date;
        //判断 资金来源
        if (qdly_add_length < 1) {
            alert('未添加资金来源');
            return;
        }
        data_json.qdly = [{}];
        for (var i = 0; i < qdly_add_length; i++) {
            var data_i = data_json.qdly[i];
            data_i = {};
            var obj = $('.qdly_add').eq(i);
            var s_id = obj.find('.source_id').val();
            data_i.s_id = s_id;
            var source_channel = obj.find('.source_channel option:selected').val();
            data_i.source_channel = source_channel;
            var year = obj.find('.year option:selected').val();
            data_i.year = year;
            var file_number = obj.find('.file_number').val();
            if (file_number == '') {
                obj.find('.file_number').focus();
                return;
            }
            data_i.file_number = file_number;
            var amount = obj.find('.amount').val();
            if (amount == '' || isNaN(amount) || amount < 0) {
                obj.find('.amount').focus();
                return;
            }
            data_i.amount = amount;
            data_json.qdly[i] = data_i;
        }
        
        if (overview == '') {
            $('.overview').focus();
            return;
        }
        data_json.overview = overview;
        data_json.remark = remark;
        data_json.upstep = 'step1';

        var data = data_json;
        $.ajax({
            url: '/ResearchProject/ajax_step1_edit',
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
                    //清空之前的错误提示
//                        $('.middle').removeClass('text-danger').text('');
//                        show_error($(res.class), res.msg);
                    return;
                }
                if (res.code == 0) {
                    //说明添加或修改成功
                    alert(res.msg);
                    $('button[data-dismiss="modal"]').click();
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

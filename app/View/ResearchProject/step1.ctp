<?php echo $this->element('head_frame'); ?>


<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:560px;'>

    <p class="btn btn-info btn-block"  style="border-radius:4px 4px 0 0;padding:0 12px;"> <span style="font-size:16px;"><?php echo !empty($user) ? '修改':'添加';?>项目</span> <a onclick="close_window();" class="close" data-dismiss="modal" id='closemodel'>×</a></p>


    <div  style='padding:20px 0;'>
        <div >
            <form class="form-horizontal" role="form" id="formstep1" method="post" action="/ResearchProject/step2">
                <input type="hidden" name="step1" value="step1" />
                <ul class="form-ul">
                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">全称 &nbsp;&nbsp;</label>
                        <input type="text" id="form-field-1" placeholder="全称" class="name " name="name" value="" />  

                        <label class="input-group-addon " for="form-field-2">简称  &nbsp;&nbsp;</label> 
                        <input type="text" id="form-field-2" placeholder="简称" class="alias" name="alias" value="" />           
                    </li> 

                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">资金性质 &nbsp;&nbsp;</label>
                        <select  name="type" class="type input-width" id="form-field-1" style="width:145px;">
                            <option value="1">零余额</option>
                            <option value="2">基本户</option>
                        </select>  

                        <label class="input-group-addon " for="form-field-2">金额 &nbsp;&nbsp;</label> 
                        <input type="text" id="form-field-2" placeholder="金额" class="sumamount" name="sumamount" value="" />                
                    </li> 
                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">所属项目组 </label>
                        <select  name="project_team_id" class="project_team_id input-width" id="form-field-1" style="width:145px;">
                            <!--option value="0">请选择项目组</option-->
                            <?php $project_team_arr = Configure::read('project_team');?>
                            <?php foreach($team as $pk=>$pv) {?>
                                <option value="<?php echo $pk;?>"><?php echo $pv;?></option>
                            <?php }?>
                            
                        </select>                
                    </li> 

                    <script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>
                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">开始 &nbsp;&nbsp;</label>
                        <input readonly="readonly" type="text" class=" form_datetime1 start_date" name="start_date">  
                        <script type="text/javascript">
                            $(".form_datetime1").datetimepicker({
                                format: 'yyyy-mm-dd',
                                minView: "month", //选择日期后，不会再跳转去选择时分秒 
                            });
                        </script>

                        <label class="input-group-addon " for="form-field-2">结束 &nbsp;&nbsp;</label>
                        <input readonly="readonly" type="text"  class="form_datetime2 end_date" name="end_date"> 
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
                        &nbsp; <span title="添加" class="glyphicon glyphicon-plus blue" aria-hidden="true" onclick="add_qdly();"></span> 
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

                    <li class="input-group qdly demo_hide" style="display:none;">
                        <label  for="form-field-1" style="width:81px;float: left;"></label>
                        <select style="float:left;width:105px;" name="source[source_channel][]" class="source_channel"  >
                            <?php $qd_arr = array('省级','中央','同级','企业','非本级','本级横向');
                            foreach($qd_arr as $qd){?>
                            <option value="<?php  echo $qd;?>"><?php  echo $qd;?></option>
                            <?php }?>
                        </select>
                        <select style="width:85px;" name="source[year][]" class="year col-sm-2" >
                            <?php foreach(range(2017,2030) as $n){?>
                            <option value="<?php echo $n;?>"><?php echo $n;?></option>
                            <?php } ?>
                        </select>
                        <input type="text" placeholder="文号" style="width:105px;" class="file_number "  name="source[file_number][]" value="" />           
                        <input type="text" placeholder="金额"  class="amount" name="source[amount][]" style="width:85px;"  value="" />
                        &nbsp;
                        <span title="删除" class="icon-trash bigger-130 red" onclick="del_qbly(this);"></span>  
                    </li>           
                </ul>

                <div class="form-group" style="margin:10px auto;width:490px;">
                    <label class="control-label no-padding-right" style="width:100px;text-align: right;" for="form-field-1">项目概述 &nbsp;&nbsp;</label>
                    <textarea class="overview" name="overview" style="width:350px;" placeholder="项目概述" ></textarea>
                </div>
                <div class="form-group" style="margin:10px auto;width:500px;">
                    <label class="control-label no-padding-right" style="width:100px;text-align: right;" for="form-field-1">备注 &nbsp;&nbsp;</label>
                    <textarea class="remark" name="remark" style="width:350px;" placeholder="备注"></textarea>

                </div>

                <div class="space-4"></div>


                <div class="clearfix " style="text-align: center;">
                    <div class=" col-md-9">
                        <button class="btn btn-primary" type="button" onclick='close_window();' data-dismiss="modal" >
                            <i class="icon-undo bigger-110"></i>
                            取消
                        </button>
                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-info" type="button"  onclick="ajax_submit();">
                            <i class="icon-ok bigger-110"></i>
                            下一步
                        </button>

                    </div>
                </div>


            </form>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>




<script type="text/javascript">
    //关闭窗口
    function close_window() {
        window.parent.step_close();
    }
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
        
        var type = $('.type option:selected').val();
        var overview = $('.overview').val();
        var remark = $('.remark').val();
        data_json.project_team_id = project_team_id;
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
            url: '/ResearchProject/ajax_cookie',
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
                    //location.href = '/user/index';
                    //如果成功，则调step2
                   // $('#modal-body').load('/ResearchProject/step2')
                    $('form').submit();
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
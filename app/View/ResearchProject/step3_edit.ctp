<?php //echo $this->element('head_frame'); ?>


<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:560px;'>
    <p class="btn btn-info btn-block" style="border-radius:4px 4px 0 0;padding:0 12px;"> <span style="font-size:16px;">项目预算</span> <a onclick="" class="close" data-dismiss="modal" id='closemodel'>×</a></p>

    <div class="row" style='padding:20px 0;margin:0 auto;'>
        <div class="col-xs-12">
            <form class="form-horizontal"   role="form">
                <input type="hidden" name="step3" value="step3" />
                <ul  class="form-ul">
                    <?php  foreach($list as $lk => $lv){ ?>
                    <li class="input-group">
                        <?php  foreach($lv as $k => $v){ ?>
                        <label class="input-group-addon " for="form-field-1" style="width:108px;"> <?php echo $v; ?> </label > 
                        <input type="text"  class="<?php echo $k; ?> unit" value="<?php echo $res_const[$k];?>" placeholder='0.00' style="width:100px;"/> 
                        <?php } ?>  
                    </li>  
                    <?php } ?>
                    <li class="input-group">
                        <label class="input-group-addon" for="form-field-1" style="width:108px;"> 合计 </label >
                        <input type="text" id="form-field-t" class="total" placeholder='0.00' value="<?php echo $res_const['total'];?>" disabled />
                    </li>
                </ul>     
                <div class="form-group" style="margin:10px auto;width:500px;">
                    <label class="control-label no-padding-right" style="width:100px;text-align: right;" for="form-field-1">备注 &nbsp;&nbsp;</label>
                    <textarea cols="40" rows="5" id='remarks' name='remarks' class="remarks"><?php echo $res_const['remarks'];?></textarea>
                </div>

                <div class="clearfix " style="text-align: center;" >
                    <div class=" col-md-9">
                        <button class="btn btn-primary" type="button" onclick='/*close_window();*/' data-dismiss="modal" >
                            <i class="icon-undo bigger-110"></i>
                            取消
                        </button>
                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-info" type="button"  onclick="ajax_submit();">
                            <i class="icon-ok bigger-110"></i>
                            提交
                        </button>

                    </div>
                </div>


            </form>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>

<script type="text/javascript">
    //相加
        function add_decimal(decimal_a, decimal_b) {
            if (!$.isNumeric(decimal_a)) {
                decimal_a = 0;
            }
            if (!$.isNumeric(decimal_b))
            {
                decimal_b = 0;
            }
            decimal_a = parseFloat(decimal_a);
            decimal_b = parseFloat(decimal_b);
            //判断他们小数据点位数，谁的大取谁的
            var len_a = decimal_a.toString().indexOf('.') == -1 ? 0 : decimal_a.toString().split(".")[1].length;
            var len_b = decimal_b.toString().indexOf('.') == -1 ? 0 : decimal_b.toString().split(".")[1].length;
            var max = len_a;//最大的小数位数
            if (len_b > len_a) 
            {
                max = len_b;
            }
            var tmp_sum = decimal_a * Math.pow(10, max) + decimal_b * Math.pow(10, max);
            tmp_sum = tmp_sum / Math.pow(10, max);
            return tmp_sum;
        }
    $('.unit').change(function(){
        var totals = 0;
       $('.unit').each(function(i){
         if($.isNumeric($(this).val())){
             totals = add_decimal(totals,$(this).val());
         }
        });
        $('.total').val(totals);
    });
    
    
    function upstep() {
        $('#closemodel').click();
    }

    //提交内容
    var click_flag = true;//是否可以点击
    function ajax_submit() {
        
        if (!click_flag) {
            return;
        }
        if($('.total').val() != <?php echo $pro_arr['amount'];?>){
            $('.total').focus();
            alert('合计金额与项目金额不符，请调整单科目金额！');
            return;
        }
        var data_json = {};
        data_json.c_id = '<?php echo $res_const['id'];?>';
        data_json.pro_id = '<?php echo $pro_arr['id'];?>';
        data_json.data_fee = $('.data_fee').val();
        data_json.collection = $('.collection').val();
        data_json.facility = $('.facility').val();
        data_json.material = $('.material').val();
        data_json.assay = $('.assay').val();
        data_json.elding = $('.elding').val();
        data_json.publish = $('.publish').val();
        data_json.property_right = $('.property_right').val();
        data_json.office = $('.office').val();
        data_json.vehicle = $('.vehicle').val();
        data_json.travel = $('.travel').val();
        data_json.meeting = $('.meeting').val();
        data_json.international = $('.international').val();
        data_json.cooperation = $('.cooperation').val();
        data_json.labour = $('.labour').val();
        data_json.consult = $('.consult').val();
        data_json.indirect_manage = $('.indirect_manage').val();
        data_json.indirect_performance = $('.indirect_performance').val();
        data_json.indirect_other = $('.indirect_other').val();
        data_json.other = $('.other').val();
        data_json.other2 = $('.other2').val();
        data_json.other3 = $('.other3').val();
        data_json.total = $('.total').val();
        data_json.remarks = $('.remarks').val();
        data_json.upstep = 'step3'; 
        click_flag = false;
        $.ajax({
            url: '/ResearchProject/ajax_step3_edit',
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
                    alert(res.msg);
                    upstep();
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
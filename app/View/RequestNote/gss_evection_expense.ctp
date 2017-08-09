<?php //echo $this->element('head_frame'); ?>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:780px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" role="form">
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;" >
                        <input type="hidden" name='declarename' class='declarename' value='果树所差旅费报销单' /> 
                        <tbody>
                            <tr>
                                <td colspan="8" style="font-size:14px;font-weight: 600;border-color:#000;">  果树所差旅费报销单 </td>
                            </tr>
                            <tr>
                                <td >填表时间</td>
                                <td colspan='3'>  <input readonly="readonly" type="text" class="ctime" name="ctime"   value="<?php echo date('Y-m-d'); ?>"  style='height:25px;width:260px;'>  </td>
                                <td>附单据张数</td>
                                <td colspan='3'> <input type="text" name='sheets_num' class="sheets_num" style='width:180px;height:25px;'/>  </td>
                            </tr>
                            
                             <tr>
                                <td>部门或项目</td>
                                <td colspan='7'>
                                    <select style="width:335px;height:25px;" name='dep_pro' class="dep_pro"  onchange="change_filenumber();" >
                                        <?php if ($is_department == 1){?>
                                        <option value="0"><?php echo $department_arr['Department']['name'];?></option>
                                        <?php }?>
                                        <?php foreach($projectInfo as $pk=>$pv) {?>
                                        <option value="<?php  echo $pk;?>"><?php  echo $pv;?></option>
                                        <?php }?>
                                    </select>
                                    <select style="width:215px;height:25px;" name="filenumber" class="filenumber"  >
                                        <option></option>
                                    </select>
                                </td>
                                </tr>
                            <script type="text/javascript">
                                    function change_filenumber() {
                                        var type = $('.dep_pro').val();
                                        if (type ==0) {
                                            //部门
                                            $('.filenumber').html('<option></option>');
                                        } else {
                                            //项目 去取项目所对应的souce
                                            var data = {pid:type};
                                            $.ajax({
                                                url:'/requestnote/ajax_get_souce',
                                                type:'post',
                                                data:data,
                                                dataType:'json',
                                                success:function(res){
                                                    var html = res['html'];
                                                    $('.filenumber').html(html);
                                                }
                                            });
                                        }
                                    }
                                </script>
                             <tr>
                                <td > 出差人姓名 </td>
                                <td colspan='6'>
                                    <input type="text" class="personnel" name="personnel"  style='height:25px;width:560px;'>  </td>
                                <td >
                                   共 <input  type="text" class="sums" name="sums"  style='height:25px;width:40px;'> 人 </td>
                             </tr>
                             <tr>
                                <td>起止日期</td>
                                <td>起讫地点</td>
                                <td>车船费</td>
                                <td>差旅补助天数</td>
                                <td>差旅补助标准</td>
                                <td>差旅补助金额</td>
                                <td>住宿费</td>
                                <td>其他费用</td>
                            </tr>
                            
                            <tr class="json_str">
                                <td > <input type="text" class="start_end_day0" name="dp[0]['start_end_day']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="start_end_address0" name="dp[0]['start_day']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="fare0" name="dp[0]['fare']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="allowance_days0" name="dp[0]['allowance_days']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="supply_needs0" name="dp[0]['supply_needs']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="subsidy_amount0" name="dp[0]['subsidy_amount']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="hotel_expense0" name="dp[0]['hotel_expense']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="other_expense0" name="dp[0]['other_expense']"  style='height:25px;width:85px;'> </td>
                                </tr>
                                
                            <tr class="json_str">
                                <td > <input type="text" class="start_end_day1" name="dp[1]['start_end_day']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="start_end_address1" name="dp[1]['start_day']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="fare1" name="dp[1]['fare']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="allowance_days1" name="dp[1]['allowance_days']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="supply_needs1" name="dp[1]['supply_needs']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="subsidy_amount1" name="dp[1]['subsidy_amount']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="hotel_expense1" name="dp[1]['hotel_expense']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="other_expense1" name="dp[1]['other_expense']"  style='height:25px;width:85px;'> </td>
                                </tr>
                                
                            <tr class="json_str">
                                <td > <input type="text" class="start_end_day2" name="dp[2]['start_end_day']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="start_end_address2" name="dp[2]['start_day']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="fare2" name="dp[2]['fare']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="allowance_days2" name="dp[2]['allowance_days']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="supply_needs2" name="dp[2]['supply_needs']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="subsidy_amount2" name="dp[2]['subsidy_amount']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="hotel_expense2" name="dp[2]['hotel_expense']"  style='height:25px;width:85px;'> </td>
                                <td> <input type="text" class="other_expense2" name="dp[2]['other_expense']"  style='height:25px;width:85px;'> </td>
                                </tr>
                                <tr class="json_str">
                                    <td colspan="2"> 小计</td>
                                    <td> <input readonly="readonly" type="text" class="fare3" name="dp[2]['fare']"  style='height:25px;width:85px;'> </td>
                                    <td> <input readonly="readonly" type="text" class="allowance_days3" name="dp[3]['allowance_days']"  style='height:25px;width:85px;'> </td>
                                    <td> <input readonly="readonly" type="text" class="supply_needs3" name="dp[3]['supply_needs']"  style='height:25px;width:85px;'> </td>
                                    <td> <input readonly="readonly" type="text" class="subsidy_amount3" name="dp[3]['subsidy_amount']"  style='height:25px;width:85px;'> </td>
                                    <td> <input readonly="readonly" type="text" class="hotel_expense3" name="dp[3]['hotel_expense']"  style='height:25px;width:85px;'> </td>
                                    <td> <input readonly="readonly" type="text" class="other_expense3" name="dp[3]['other_expense']"  style='height:25px;width:85px;'> </td>
                                </tr>
                           
                            <tr>
                                <td style="width:90px;">合计（大写）</td>
                                <td colspan='4'>  <input type="text" name='big_total' class='big_total' value=''  style="width:350px;" /> </td>
                                <td style="width:40px;"> ￥ </td>
                                <td colspan='2'>  <input type="text" name='small_total' class='small_total' value=''  style="width:150px;" /> </td>
                            </tr>
                           
                            <tr>
                                <td>事由</td>
                                <td colspan='7'> <input type="text" name='reason' class="reason" style='width:640px;height:25px;'/> </td>
                            </tr>
                            
                            <tr>
                                <td colspan='2' style="width:260px;" >申报人</td>
                                <td  style="width:260px;">项目负责人</td>
                                <td >科室负责人</td>
                                <td  style="width:260px;" >分管所领导</td>
                                <td  style="width:260px;">所长</td>
                                <td >分管财务所长</td>
                                <td  style="width:260px;" >财务科长</td>
                            </tr>
                            <tr style="height:60px;line-height: 20px;" >
                                <td colspan='2'> <?php 
                                    echo $userInfo->name . '<br />';
                                    echo date('Y-m-d');
                                ?> </td>
                                <td > </td>
                                <td > </td>
                                <td >  </td>
                                <td > </td>
                                <td > </td>
                                <td >  </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="modal-footer" style='background-color: #fff;'>
                <button style="margin-left:-50px;" type="button" class="btn btn-primary" onclick="window.parent.declares_close();" data-dismiss="modal"> <i class="icon-undo bigger-110"></i> 关闭</button>

                <button type="button" class="btn btn-primary" onclick="approve();"> <i class="icon-ok bigger-110"></i> 保存</button>
                <button type="button" class="btn btn-primary" onclick=""><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
            </div>


        </div>
    </div><!-- /.row -->
</div>

<script type="text/javascript">
  
    function approve() {
        var ctime = $('.ctime').val();
        var sheets_num = $('.sheets_num').val();
        var dep_pro = $('.dep_pro').val();
        var filenumber = $('.filenumber').val();
        var personnel = $('.personnel').val();
        var sums = $('.sums').val();
        var big_total = $('.big_total').val();
        var small_total = $('.small_total').val();
        var reason = $('.reason').val();
        var payee = $('.payee').val();
        var declarename = $('.declarename').val();
        
        if (ctime == '') {
            $('.ctime').focus();
            return;
        }
        if (sheets_num == '') {
            $('.sheets_num').focus();
            return;
        }
        
        if (personnel == '') {
            $('.personnel').focus();
            return;
        }
        if (sums == '') {
            $('.sums').focus();
            return;
        }
        
        var json_str = [{}];
        var mast_one = false;//必须有一个
        var error_flag = false;//记录错误标示
        var fare_xj = 0,
            allowance_days_xj = 0,
            supply_needs_xj = 0,
            subsidy_amount_xj = 0,
            hotel_expense_xj = 0,
            other_expense_xj = 0;//小计
        $('.json_str').each(function(i){
            var tmp_str = {};
            if (i == 3) {
                //计算小计
                if (!mast_one) {
                    return false;//中止掉
                }
                $(this).find('.fare' + i).val(fare_xj);
                tmp_str.fare = fare_xj;
                $(this).find('.allowance_days' + i).val(allowance_days_xj);
                tmp_str.allowance_days = allowance_days_xj;
                $(this).find('.supply_needs' + i).val(supply_needs_xj);
                tmp_str.supply_needs = supply_needs_xj;
                $(this).find('.subsidy_amount' + i).val(subsidy_amount_xj);
                tmp_str.subsidy_amount = subsidy_amount_xj;
                $(this).find('.hotel_expense' + i).val(hotel_expense_xj);
                tmp_str.hotel_expense = hotel_expense_xj;
                $(this).find('.other_expense' + i).val(other_expense_xj);
                tmp_str.other_expense = other_expense_xj;
                json_str[i] = tmp_str;
                return false;//中止，已经到结束了
            }
            var start_end_day = $(this).find('.start_end_day' + i).val();
            if (start_end_day == '') {
                //没填写跳过
                return true;
            } else {
                mast_one = true;
            }
            tmp_str.start_end_day = start_end_day;
            var start_end_address = $(this).find('.start_end_address' + i).val();
            if (start_end_address == '') {
                $(this).find('.start_end_address' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.start_end_address = start_end_address;
            var fare = $(this).find('.fare' + i).val();
            if (fare == '' || isNaN(fare)) {
                $(this).find('.fare' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.fare = fare;
            fare_xj += parseFloat(fare);
            var allowance_days = $(this).find('.allowance_days' + i).val();
            if (allowance_days == '' || isNaN(allowance_days)) {
                $(this).find('.allowance_days' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.allowance_days = allowance_days;
            allowance_days_xj += parseInt(allowance_days);
            var supply_needs = $(this).find('.supply_needs' + i).val();
            if (supply_needs == '' || isNaN(supply_needs)) {
                $(this).find('.supply_needs' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.supply_needs = supply_needs;
            supply_needs_xj += parseFloat(supply_needs)
            var subsidy_amount = $(this).find('.subsidy_amount' + i).val();
            if (subsidy_amount == '' || isNaN(subsidy_amount)) {
                $(this).find('.subsidy_amount' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.subsidy_amount = subsidy_amount;
            subsidy_amount_xj += parseFloat(subsidy_amount);
            var hotel_expense = $(this).find('.hotel_expense' + i).val();
            if (hotel_expense == '' || isNaN(hotel_expense)) {
                $(this).find('.hotel_expense' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.hotel_expense = hotel_expense;
            hotel_expense_xj += parseFloat(hotel_expense);
            var other_expense = $(this).find('.other_expense' + i).val();
            if (other_expense == '' || isNaN(other_expense)) {
                $(this).find('.other_expense' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.other_expense = other_expense;
            other_expense_xj += parseFloat(other_expense)
            json_str[i] = tmp_str;
        });
        
        if (!mast_one) {
            //说明没有写
            $('.json_str').find('.start_end_day' + 0).focus();
            return;
        }
        if (error_flag) {
            //说明有没写的
            return;
        }
        if (big_total == '') {
            $('.big_total').focus();
            return;
        }
        if (small_total == '') {
            $('.small_total').focus();
            return;
        }
        if (reason == '') {
            $('.reason').focus();
            return;
        }
        if (payee == '') {
            $('.payee').focus();
            return;
        }
        var data = {filenumber: filenumber, declarename: declarename, json_str: json_str,ctime: ctime, reason: reason, sheets_num: sheets_num, dep_pro: dep_pro, personnel: personnel, sums: sums, big_total: big_total,small_total: small_total,payee: payee,declarename: declarename};
        $.ajax({
            url: '/RequestNote/gss_evection_expense',
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
                    window.parent.declares_close();
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


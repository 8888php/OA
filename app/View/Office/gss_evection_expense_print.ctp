<?php //echo $this->element('head_frame'); ?>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:710px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
        .table-condensed tbody>tr>td{padding: 3px;}
    </style>

    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" role="form">
                    <table class="table  table-condensed" style="/*table-layout: fixed;*/text-align: center;border-color:#000;padding: 3px;" >
                        <input type="hidden" name='declarename' class='declarename' value='果树所差旅费报销单' /> 
                        <tbody>
                            <tr>
                                <td colspan="8" style="font-size:24px;font-weight: 600;border-color:#000;">  果树所差旅费报销单 </td>
                            </tr>
                            <tr>
                                <td style="width: 14%;">填表时间</td>
                                <td colspan='3'>  <?php echo $attr_arr[0][$table_name]['ctime'];?>  </td>
                                <td>附单据张数</td>
                                <td colspan='3'> <?php echo $attr_arr[0][$table_name]['page_number'];?>  </td>
                            </tr>
                            
                             <tr>
                                <td>部门或项目</td>
                                <td colspan='7'>
                                    <?php if ($attr_arr[0][$table_name]['project_id']) {?>
                                        <?php echo $project_arr['ResearchProject']['name'];?>  
                                        <?php if(!empty($source_arr)){
                                            echo '| 【'.$source_arr['ResearchSource']['source_channel'].' （'.$source_arr['ResearchSource']['file_number'].'） '.$source_arr['ResearchSource']['year'].'】';
                                        }?>
                                    <?php }else {?>
                                        <?php  echo $attr_arr[0][$table_name]['department_name'];?>
                                    <?php }?>
                                </td>
                                </tr>
                            
                             <tr>
                                <td > 出差人姓名 </td>
                                <td colspan='6'>
                                    <?php  echo $attr_arr[0][$table_name]['business_traveller_id'];?>
                                </td>
                                <td >
                                   共 <?php  echo $attr_arr[0][$table_name]['total_number'];?> 人 </td>
                             </tr>
                             <tr>
                                 <td>起止日期</td>
                                <td style="width: 13%;">起讫地点</td>
                                <td style="width: 11%;">车船费</td>
                                <td style="font-size: 12px;width: 13%;">差旅补助天数</td>
                                <td style="font-size: 12px;width: 13%;">差旅补助标准</td>
                                <td style="font-size: 12px;width: 13%;">差旅补助金额</td>
                                <td style="width: 11%;">住宿费</td>
                                <td>其他费用</td>
                            </tr>
                            <?php 
                                $json_str = $attr_arr[0][$table_name]['json_str'];
                                $json_arr = json_decode($json_str, true);
                                function get_value($json_arr, $key, $key_name, $default = '') {
                                    if (empty($json_arr)) {
                                        return $default;
                                    }
                                    if (empty($json_arr[$key])) {
                                        return $default;
                                    }
                                    if (empty($json_arr[$key][$key_name])) {
                                        return $default;
                                    }
                                    return $json_arr[$key][$key_name];
                                }
                            ?>
                            <tr class="json_str">
                                <td style="height:25px;"> <?php echo get_value($json_arr, 0, 'start_end_day');?></td>
                                <td> <?php echo get_value($json_arr, 0, 'start_end_address');?></td>
                                <td> <?php echo get_value($json_arr, 0, 'fare');?></td>
                                <td> <?php echo get_value($json_arr, 0, 'allowance_days');?></td>
                                <td> <?php echo get_value($json_arr, 0, 'supply_needs');?></td>
                                <td> <?php echo get_value($json_arr, 0, 'subsidy_amount');?></td>
                                <td> <?php echo get_value($json_arr, 0, 'hotel_expense');?></td>
                                <td> <?php echo get_value($json_arr, 0, 'other_expense');?></td>
                            </tr>
                                
                            <tr class="json_str">
                                <td style="height:25px;"> <?php echo get_value($json_arr, 1, 'start_end_day');?></td>
                                <td> <?php echo get_value($json_arr, 1, 'start_end_address');?></td>
                                <td> <?php echo get_value($json_arr, 1, 'fare');?></td>
                                <td> <?php echo get_value($json_arr, 1, 'allowance_days');?></td>
                                <td> <?php echo get_value($json_arr, 1, 'supply_needs');?></td>
                                <td> <?php echo get_value($json_arr, 1, 'subsidy_amount');?></td>
                                <td> <?php echo get_value($json_arr, 1, 'hotel_expense');?></td>
                                <td> <?php echo get_value($json_arr, 1, 'other_expense');?></td>
                            </tr>
                                
                            <tr class="json_str">
                                <td style="height:25px;"> <?php echo get_value($json_arr, 2, 'start_end_day');?></td>
                                <td> <?php echo get_value($json_arr, 2, 'start_end_address');?></td>
                                <td> <?php echo get_value($json_arr, 2, 'fare');?></td>
                                <td> <?php echo get_value($json_arr, 2, 'allowance_days');?></td>
                                <td> <?php echo get_value($json_arr, 2, 'supply_needs');?></td>
                                <td> <?php echo get_value($json_arr, 2, 'subsidy_amount');?></td>
                                <td> <?php echo get_value($json_arr, 2, 'hotel_expense');?></td>
                                <td> <?php echo get_value($json_arr, 2, 'other_expense');?></td>
                            </tr>
                                <tr class="json_str">
                                    <td colspan="2" style="height:25px;"> 小计</td>
                                    <td> <?php echo get_value($json_arr, 3, 'fare');?></td>
                                    <td> <?php echo get_value($json_arr, 3, 'allowance_days');?></td>
                                    <td> <?php echo get_value($json_arr, 3, 'supply_needs');?></td>
                                    <td> <?php echo get_value($json_arr, 3, 'subsidy_amount');?></td>
                                    <td> <?php echo get_value($json_arr, 3, 'hotel_expense');?></td>
                                    <td> <?php echo get_value($json_arr, 3, 'other_expense');?></td>
                                </tr>
                           
                            <tr>
                                <td style="width:90px;font-size: 12px;">合计（大写）</td>
                                <td colspan='4'>  <?php echo $attr_arr[0][$table_name]['total_capital'];?></td>
                                <td style="width:40px;"> ￥ </td>
                                <td colspan='2'>  <?php echo $attr_arr[0][$table_name]['total'];?></td>
                            </tr>
                           
                            <tr>
                                <td>事由</td>
                                <td colspan='7'> <?php echo $attr_arr[0][$table_name]['reason'];?> </td>
                            </tr>
                            
                            <tr>
                                <td  style="width:260px;" >申报人</td>
                                <td  style="width:260px;">项目负责人</td>
                                <td >科室负责人</td>
                                <td  style="width:260px;" >分管所领导</td>
                                <td  style="width:260px;">所长</td>
                                <td style="font-size: 12px;">分管财务所长</td>
                                <td colspan='2' style="width:260px;" >财务科长</td>
                            </tr>
                            <tr style="/*height:60px;line-height: 20px;*/" >
                                <td style=""> 
                                <?php 
                                    $applicant = $attr_arr[0][$table_name]['applicant'];
                                    if (!empty($applicant)) {
                                        $applicant_arr = explode(',', $applicant);
                                        foreach($applicant_arr as $ak=>$av) {
                                            echo "<span style='display: block;text-align: center; height: 17px;'>".$av."</span>";
                                        }
                                    }
                                ?>
                            </td>
                                <td > 
                                    <?php 
                                    echo @$applyArr['11']['name']; 
                                    echo '<br />';
                                    echo @$applyArr['11']['ctime'];
                                    echo '<br />';
                                    echo @$applyArr['11']['remarks'];
                                    ?>   
                                </td>
                                <td > 
                                    <?php 
                                    echo @$applyArr['ksfzr']['name']; 
                                    echo '<br />';
                                    echo @$applyArr['ksfzr']['ctime'];
                                    echo '<br />';
                                    echo @$applyArr['ksfzr']['remarks'];
                                    ?> 
                                </td>
                                <td >
                                    <?php 
                                    echo @$applyArr['5']['name']; 
                                    echo '<br />';
                                    echo @$applyArr['5']['ctime'];
                                    echo '<br />';
                                    echo @$applyArr['5']['remarks'];
                                    ?> 
                                </td>
                                <td >
                                    <?php 
                                    echo @$applyArr['6']['name']; 
                                    echo '<br />';
                                    echo @$applyArr['6']['ctime'];
                                    echo '<br />';
                                    echo @$applyArr['6']['remarks'];
                                    ?> 
                                </td>
                                <td >
                                    <?php 
                                    echo @$applyArr['13']['name']; 
                                    echo '<br />';
                                    echo @$applyArr['13']['ctime'];
                                    echo '<br />';
                                    echo @$applyArr['13']['remarks'];
                                    ?> 
                                </td>
                                <td colspan='2'>
                                    <?php 
                                    echo @$applyArr['14']['name']; 
                                    echo '<br />';
                                    echo @$applyArr['14']['ctime'];
                                    echo '<br />';
                                    echo @$applyArr['14']['remarks'];
                                    ?>
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </form>
            </div>
            <?php if ($apply == 'apply') {?>
                <div class="modal-body" style="padding:0 20px;">
                    <input type="hidden" name="main_id" id="main_id" value="<?php echo $main_arr['ApplyMain']['id'];?>">
                    <textarea id="remarks" placeholder="审批意见" rows="2" cols="90"></textarea>
                </div>
            <?php }?>
            <!--<hr class="hr" style="display: none; border: 1px solid #666666;" />-->
            <div class="modal-footer" style='background-color: #fff;'>
                <?php if($feedback['code']){ ?>
                 <div class="alert alert-danger alert-dismissable center ">
                     <button type="button" class="close" data-dismiss="alert"
                             aria-hidden="true">
                         &times;
                     </button>
                     警告！
                     <?php   echo $feedback['msg'];  ?>
                 </div>
                 <?php   } ?>
                        
                <?php if ($apply == 'apply') {?>
                <button type="button" class="btn btn-primary" onclick="approve(2);"><i class="icon-undo bigger-110"></i> 拒绝</button>
                <button type="button" class="btn btn-primary" onclick="approve(1);"> <i class="icon-ok bigger-110"></i> 同意</button>
                <?php }?>
                <button type="button" class="btn btn-primary" onclick="printDIV();"><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal"> <i class="icon-undo bigger-110"></i> 关闭</button>
            </div>
<script type="text/javascript">
    var class_name = 'not_right_tmp_8888';//定义一个没有的class
function printDIV(){
    var div_height = $('.container').height();
    $('.modal-footer').css('display', 'none');
    $('#dropzone').css('display', 'none');
    //隐藏下拉框
    $('.' + class_name).css('display', 'none');
    {
        /**
         * navbar-default
            id sidebar 
            breadcrumbs
            ace-settings-container
            id btn-scroll-up
            right_content
         */
        $('.navbar-default').css('display', 'none');
        $('#sidebar').css('display', 'none');
        $('.breadcrumbs').css('display', 'none');
        $('.ace-settings-container').css('display', 'none');
        $('#btn-scroll-up').css('display', 'none');
        $('.right_content').css('display', 'none');
        $('.table-striped').css('display', 'none');
        $('.right_list').css('display', 'none');
        //$('.hr').css('display', '');
        $('.container').css('border-bottom', '1px solid black');
        $('.container').css('height', '438px');
    }
    window.print();//打印刚才新建的网页
    {
        /**
         * navbar-default
            id sidebar 
            breadcrumbs
            ace-settings-container
            id btn-scroll-up
            right_content
         */
        $('.navbar-default').css('display', '');
        $('#sidebar').css('display', '');
        $('.breadcrumbs').css('display', '');
        $('.ace-settings-container').css('display', '');
        $('#btn-scroll-up').css('display', '');
        $('.right_content').css('display', '');
        $('.table-striped').css('display', '');
        $('.right_list').css('display', '');
        //$('.hr').css('display', 'none');
        $('.container').css('border-bottom', '0px');
        $('.container').css('height', div_height + 'px');
    }
    $('.modal-footer').css('display', '');
    $('#dropzone').css('display', '');
    $('.' + class_name).css('display', '');
    return false;
}
</script>

        </div>
    </div><!-- /.row -->
</div>
<script type="text/javascript">
    function jisuan() {
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
//                json_str[i] = tmp_str;
                
                //合计
                var total = fare_xj + subsidy_amount_xj + hotel_expense_xj + other_expense_xj;
                $('.small_total').val(total);
                $('.big_total').val(convertCurrency(total));
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
            var subsidy_amount = parseFloat(allowance_days) * parseFloat(supply_needs);
            $(this).find('.subsidy_amount' + i).val(subsidy_amount);
//            if (subsidy_amount == '' || isNaN(subsidy_amount)) {
//                $(this).find('.subsidy_amount' + i).focus();
//                error_flag = true;
//                return false;//中止
//            }
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
            other_expense_xj += parseFloat(other_expense);
        });
        if (!mast_one) {
            //说明没有写
            $('.json_str').find('.start_end_day' + 0).focus();
            //把合计和总计都清空
            $('.json_str').eq(3).find('input').val('');
            $('.big_total').val('');
            $('.small_total').val('');
            return;
        }
        if (error_flag) {
            //说明有没写的
            return;
        }
    }
    $('.json_str input').blur(function(){
        jisuan();
    });
    
    function approve(type) {
        var text = '拒绝';
        if (type == 1) {
            text = '同意';
        } else {
            type = 2;
        }
        if (!confirm('您确认 ' + text + ' 该项目？')) {
            //取消
            return;
        }
        var data = {main_id: $('#main_id').val(), type: type, remarks: $('#remarks').val()};
        $.ajax({
            url: '/Office/ajax_approve_reimbursement',
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
      //钱小写转大写
    function convertCurrency(money) {
        //汉字的数字
        var cnNums = new Array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
        //基本单位
        var cnIntRadice = new Array('', '拾', '佰', '仟');
        //对应整数部分扩展单位
        var cnIntUnits = new Array('', '万', '亿', '兆');
        //对应小数部分单位
        var cnDecUnits = new Array('角', '分', '毫', '厘');
        //整数金额时后面跟的字符
        var cnInteger = '整';
        //整型完以后的单位
        var cnIntLast = '元';
        //最大处理的数字
        var maxNum = 999999999999999.9999;
        //金额整数部分
        var integerNum;
        //金额小数部分
        var decimalNum;
        //输出的中文金额字符串
        var chineseStr = '';
        //分离金额后用的数组，预定义
        var parts;
        if (money == '') { return ''; }
        money = parseFloat(money);
        if (money >= maxNum) {
          //超出最大处理数字
          return '';
        }
        if (money == 0) {
          chineseStr = cnNums[0] + cnIntLast + cnInteger;
          return chineseStr;
        }
        //转换为字符串
        money = money.toString();
        if (money.indexOf('.') == -1) {
          integerNum = money;
          decimalNum = '';
        } else {
          parts = money.split('.');
          integerNum = parts[0];
          decimalNum = parts[1].substr(0, 4);
        }
        //获取整型部分转换
        if (parseInt(integerNum, 10) > 0) {
          var zeroCount = 0;
          var IntLen = integerNum.length;
          for (var i = 0; i < IntLen; i++) {
            var n = integerNum.substr(i, 1);
            var p = IntLen - i - 1;
            var q = p / 4;
            var m = p % 4;
            if (n == '0') {
              zeroCount++;
            } else {
              if (zeroCount > 0) {
                chineseStr += cnNums[0];
              }
              //归零
              zeroCount = 0;
              chineseStr += cnNums[parseInt(n)] + cnIntRadice[m];
            }
            if (m == 0 && zeroCount < 4) {
              chineseStr += cnIntUnits[q];
            }
          }
          chineseStr += cnIntLast;
        }
        //小数部分
        if (decimalNum != '') {
          var decLen = decimalNum.length;
          for (var i = 0; i < decLen; i++) {
            var n = decimalNum.substr(i, 1);
            if (n != '0') {
              chineseStr += cnNums[Number(n)] + cnDecUnits[i];
            }
          }
        }
        if (chineseStr == '') {
          chineseStr += cnNums[0] + cnIntLast + cnInteger;
        } else if (decimalNum == '') {
          chineseStr += cnInteger;
        }
        return chineseStr;
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


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
                        <input type="hidden" name='declarename' class='declarename' value='果树所领款单' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:14px;font-weight: 600;border-color:#000;">  果树所领款单 </td>
                            </tr>
                            <tr>
                                <td >填表日期</td>
                                <td colspan='4'>  <input readonly="readonly" type="text" class=" ctime" name="ctime"  value="<?php echo date('Y-m-d'); ?>" style='height:25px;width:290px;'>   </td>
                                <td > 附单据张数 </td>
                                <td > <input type="text" name='sheets_num' class="sheets_num" style='width:90px;height:25px;'/> </td>
                            </tr>
                            
                             <tr>
                                <td>部门或项目</td>
                                <td colspan='6'>  <select style="width:335px;height:25px;" name='dep_pro' class="dep_pro"  onchange="change_filenumber();" >
                                        <?php if ($is_department == 1){?>
                                        <option value="0"><?php echo $department_arr['Department']['name'];?></option>
                                        <?php }?>
                                        <?php foreach($projectInfo as $pk=>$pv) {?>
                                        <option value="<?php  echo $pk;?>"><?php  echo $pv;?></option>
                                        <?php }?>
                                    </select>
                                    <select style="width:255px;height:25px;" name="filenumber" class="filenumber"  >
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
                                <td colspan="2">项目</td>
                                <td>计算单位</td>
                                <td>数量</td>
                                <td>单价</td>
                                <td>金额</td>
                                <td>备注</td>
                            </tr>
                            <tr class="dp">
                                <td colspan="2"><input type="text" name="dp[0]['pro']" class="pro0" style='width:200px;height:25px;'/></td>
                                <td> <input type="text" name="dp[0]['unit']" class="unit0" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[0]['nums']" class="nums0" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[0]['unit_price']" class="unit_price0 jisuan" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[0]['amount']" class="amount0" readonly="readonly"  style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[0]['remarks']" class="remarks0" style='width:100px;height:25px;'/> </td>
                            </tr>
                            <tr class="dp">
                                <td colspan="2"><input type="text" name="dp[1]['pro']" class="pro1" style='width:200px;height:25px;'/></td>
                                <td> <input type="text" name="dp[1]['unit']" class="unit1" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[1]['nums']" class="nums1" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[1]['unit_price']" class="unit_price1 jisuan" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[1]['amount']" class="amount1" readonly="readonly"  style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[1]['remarks']" class="remarks1" style='width:100px;height:25px;'/> </td>
                            </tr>
                            <tr class="dp">
                                <td colspan="2"><input type="text" name="dp[2]['pro']" class="pro2" style='width:200px;height:25px;'/></td>
                                <td> <input type="text" name="dp[2]['unit']" class="unit2" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[2]['nums']" class="nums2" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[2]['unit_price']" class="unit_price2 jisuan" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[2]['amount']" class="amount2" readonly="readonly"  style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[2]['remarks']" class="remarks2" style='width:100px;height:25px;'/> </td>
                            </tr>
                            
                            <tr>
                                <td>合计</td>
                                <td colspan='4'> <input type="text" name='big_total' class="big_total" readonly="readonly" style='width:380px;height:25px;'/> </td>
                                <td colspan='2'> ￥ <input type="text" name='small_total' class="small_total" readonly="readonly"  style='width:170px;height:25px;'/> </td>
                            </tr>
                
                            <tr>
                                <td >领款人</td>
                                <td >项目负责人</td>
                                <td >科室负责人</td>
                                <td >分管所领导</td>
                                <td >所长</td>
                                <td >分管财务所长</td>
                                <td >财务科长</td>
                            </tr>
                            <tr style="min-height:60px;line-height: 20px;">
                                <td > <?php 
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
  
        var dp = $("input[name='dp']").val();  console.log(dp);
        $('.dp input').blur(function(){
            var total = 0;//总钱数
            $('.dp').each(function(i){
                var pro = $(this).find('.pro' + i).val();
                var unit = $(this).find('.unit' + i).val();
                var nums = $(this).find('.nums' + i).val();
                var unit_price = $(this).find('.unit_price' + i).val();
                var amount = $(this).find('.amount' + i).val();
                var remarks = $(this).find('.remarks' + i).val();
                if (pro != '' && !isNaN(nums) && nums != '' && !isNaN(unit_price) && unit_price != '') {
                    var tmp_total = parseFloat(nums)  * parseFloat(unit_price);
                    $(this).find('.amount' + i).val(tmp_total);
                    total += parseFloat(tmp_total);
                }
            });
            $('.big_total').val(convertCurrency(total));
            $('.small_total').val(total);
        });
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
    function approve() {
        var ctime = $('.ctime').val();
        var dep_pro = $('.dep_pro').val();
        var filenumber = $('.filenumber').val();
        var sheets_num = $('.sheets_num').val();
        var small_total = $('.small_total').val();
        var big_total = $('.big_total').val();
        var declarename = $('.declarename').val();
        var dp = $("input[name='dp']").val();  
        if (ctime == '') {
            $('.ctime').focus();
            return;
        }
        if (sheets_num == '') {
            $('.sheets_num').focus();
            return;
        }
        var dp_json_str = [{}];
        var mast_one = false;//必须有一个项目
        var error_flag = false;//错误标记
       $('.dp').each(function(i){
           var tmp_str = {};
           var pro = $(this).find('.pro' + i).val();
           if (pro == '') {
               //跳过
               return true;
           } else {
               mast_one = true;//标记这行有记录
           }
           var unit = $(this).find('.unit' + i).val();
           if (unit == '') {
               $(this).find('.unit' + i).focus();
               error_flag = true;
               return false;//中止
           }
           var nums = $(this).find('.nums' + i).val();
           if (nums == '' || isNaN(nums)) {
               $(this).find('.nums' + i).focus();
               error_flag = true;
               return false;//中止
           }
           var unit_price = $(this).find('.unit_price' + i).val();
           if (unit_price == '' || isNaN(unit_price)) {
               $(this).find('.unit_price' + i).focus();
               error_flag = true;
               return false;//中止
           }
           var amount = $(this).find('.amount' + i).val();
           var remarks = $(this).find('.remarks' + i).val();
           tmp_str.pro = pro;
           tmp_str.unit = unit;
           tmp_str.nums = nums;
           tmp_str.unit_price = unit_price;
           tmp_str.amount = amount;
           tmp_str.remarks = remarks;
           dp_json_str[i] = tmp_str;
       });
       if (!mast_one) {
           //说明他一行也没有写
           $('.dp').find('.pro' + 0).focus();
           return;
       }
       if (error_flag) {
           //说明所选有错误
           return;
       }
        var data = {declarename: declarename, filenumber: filenumber ,dp_json_str:dp_json_str,ctime: ctime, dep_pro: dep_pro, sheets_num: sheets_num, small_total: small_total, big_total: big_total,declarename: declarename};
        $.ajax({
            url: '/RequestNote/gss_draw_money',
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


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
                                <td colspan='6'>  <select style="width:335px;height:25px;" name='dep_pro' class="dep_pro"  onchange="change_f();" >
                                        <?php if ($is_department == 1){?>
                                        <option value="0"><?php echo $department_arr['Department']['name'];?></option>
                                        <?php }?>
                                        <?php foreach($projectInfo as $pk=>$pv) {?>
                                        <option value="<?php  echo $pk;?>"><?php  echo $pv;?></option>
                                        <?php }?>
                                    </select>
                                    <select style="width:255px;height:25px;" name="filenumber" class="filenumber"  >
                                        <?php  foreach($source as $qd){?>
                                        <option value="<?php  echo $qd['ResearchSource']['id'];?>"><?php  echo '【'.$qd['ResearchSource']['source_channel'].' （'.$qd['ResearchSource']['file_number'].'） '.$qd['ResearchSource']['year'].'】';?></option>
                                        <?php }?>
                                    </select>
                                </td>
                            </tr>
                            
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
                                <td> <input type="text" name="dp[0]['unit_price']" class="unit_price0" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[0]['amount']" class="amount0" readonly="readonly"  style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[0]['remarks']" class="remarks0" style='width:100px;height:25px;'/> </td>
                            </tr>
                            <tr class="dp">
                                <td colspan="2"><input type="text" name="dp[1]['pro']" class="pro1" style='width:200px;height:25px;'/></td>
                                <td> <input type="text" name="dp[1]['unit']" class="unit1" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[1]['nums']" class="nums1" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[1]['unit_price']" class="unit_price1" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[1]['amount']" class="amount1" readonly="readonly"  style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[1]['remarks']" class="remarks1" style='width:100px;height:25px;'/> </td>
                            </tr>
                            <tr class="dp">
                                <td colspan="2"><input type="text" name="dp[2]['pro']" class="pro2" style='width:200px;height:25px;'/></td>
                                <td> <input type="text" name="dp[2]['unit']" class="unit2" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[2]['nums']" class="nums2" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[2]['unit_price']" class="unit_price2" style='width:100px;height:25px;'/> </td>
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
    function approve() {
        var ctime = $('.ctime').val();
        var dep_pro = $('.dep_pro').val();
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
        if (dep_pro == '') {
            $('.dep_pro').focus();
            return;
        }
        var dp_json_str = [{}];
       $('.dp').each(function(i){
           var tmp_str = {};
           var pro = $(this).find('.pro' + i).val();
           var unit = $(this).find('.unit' + i).val();
           var nums = $(this).find('.nums' + i).val();
           var unit_price = $(this).find('.unit_price' + i).val();
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
        var data = {dp_json_str:dp_json_str,ctime: ctime, dep_pro: dep_pro, sheets_num: sheets_num, small_total: small_total, big_total: big_total,declarename: declarename};
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


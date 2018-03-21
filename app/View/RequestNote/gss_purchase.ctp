<?php echo $this->element('head_frame'); ?>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:710px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" enctype="multipart/form-data" action="/RequestNote/gss_purchase" method="post" role="form">
                    <input type="hidden" name='declarename' class='declarename' value='果树所采购申请单' /> 
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;table-layout: fixed;" >
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:24px;font-weight: 600;border-color:#000;">  果树所采购申请单 </td>
                            </tr>
                            
                             <tr>
                                <td > 支出项目 </td>
                                <td colspan='6'>  
                                    <select style="height:25px;width:580px;" name="team" class="dep_pro" >
                                        <?php if ($is_department == 1){?>
                                        <option value="0"><?php echo $department_arr['Department']['name'];?></option>
                                        <?php }?>

                                        <?php foreach($pro_arr as $v){?>
                                        <option value="<?php echo $v['p']['id'];?>"><?php echo $v['p']['name'];?></option>
                                        <?php }?>
                                    </select>
                                </td>
                             </tr>
                             <tr>
                                <td > 申报部门 </td>
                                <td colspan='6'>  <input  type="text" class="project" name="project"  style='height:25px;width:575px;'> </td>
                            </tr>
                            <tr>
                                <td >申报日期</td>
                                <td colspan='2'> 
                                    <input readonly="readonly" type="text" class="ctime" name="ctime" value="<?php echo date('Y-m-d'); ?>" style='height:25px;width:180px;'>
                                    <script type="text/javascript">
                                        $(".ctime").datetimepicker({
                                            format: 'yyyy-mm-dd',
                                            minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                        });
                                    </script>
                                <td >预算指标文号</td>
                                <td colspan='3'> 
                                    <input type="text" class="file_number" name="file_number"  style='height:25px;width:280px;'>
                             </tr>
                             <tr>
                                <td> 资金性质 </td>
                                <td colspan='6'>  
                                    <label> <input type='radio' checked="checked" name="type" class="type" value='1' > 财政公用经费 </label> &nbsp;&nbsp;
                                   <label> <input type='radio' name="type" class="type" value='2' > 财政专项资金 </label> &nbsp;&nbsp;
                                   <label> <input type='radio' name="type" class="type" value='3' > 国家级项目资金 </label>
                                   <label> <input type='radio' name="type" class="type" value='4' > 科研计划项目资金 </label>
                                   <label> <input type='radio' name="type" class="type" value='5' > 其他资金 </label>
                                </td>
                             </tr>
                             <tr>
                                <td> 采购物资名称 </td>
                                <td colspan='6'> <input  type="text" class="material_name" name="material_name"  style='height:25px;width:575px;'> </td>
                             </tr>
                             <tr>
                                <td> 规格型号及详细参数 </td>
                                <td colspan='6' >  <input  type="file" class="descripttion" name="descripttion"  > </td>
                             </tr> 
                              <tr>
                                <td colspan='1'> 单位：<input  type="text" class="unit" name="unit"  style='height:25px;width:47px;'> </td>
                                <td colspan='2'> 数量：<input  type="text" class="nums" name="nums"  style='height:25px;width:90px;'> </td>
                                <td colspan='2'> 单价：<input  type="text" class="price" name="price"  style='height:25px;width:90px;'> </td>
                                <td colspan='2'> 合计金额：<input readonly="readonly" type="text" class="total" name="total"  style='height:25px;width:90px;'> </td>
                             </tr>
                              <tr>
                                <td> 采购理由 </td>
                                <td colspan='6' >  <input  type="text" class="reason" name="reason"  style='height:25px;width:575px;'> </td>
                             </tr>
                             
                             <tr style="height: 100px;">
                                <td > 采购需求审核</td>
                                <td colspan='3'>
                                    需求部门负责人审核 <br /> &nbsp;&nbsp;
                                    
                                    </td>
                                    <td  colspan='3'>
                                        需求部门分管领导审核 <br /> &nbsp;&nbsp;
                                     
                                </td>
                            </tr>
                             <tr style="height: 100px;">
                                <td > 财务及采购审核 </td>
                                <td colspan='3'>
                                    财务科审核 <br /> &nbsp;&nbsp;
                                    
                                    </td>
                                    <td  colspan='3'>
                                        采购内容核对 <br /> &nbsp;&nbsp;
                                     
                                </td>
                            </tr>
                           
                            <tr>
                                <td colspan='2'> 采购中心审核 </td>
                                <td colspan='5'>  </td>
                            </tr>
                            <tr>
                                <td colspan='2'> 财务及采购分管领导审核 </td>
                                <td  colspan='5' >    </td>
                            </tr>
                            <tr >
                                <td colspan='2'> 所长审核 </td>
                                <td colspan='5'>    </td>
                            </tr>
                            <tr >
                                <td colspan='2' style="line-height: 90px;"> 备注 </td>
                                <td colspan='5' style="text-align:left;"> 
                                    1、详细的采购物资名称和规格参数，请点击“规格型号及详细参数”一栏的“选择文件”，将提前编辑好的《采购明细表》作为附件上传；<br />
				    2、购买农家肥时，必须在“采购理由”一栏注明施肥面积；<br />
				    3、科研项目填写此表时，支出项目名称必须准确填写，与计划任务书（或项目合同书）完全一致；<br />
				    4、科研项目和财政项目的预算指标文号必须准确填写，一般为“晋财……”；<br />
				    5、“资金性质”一栏必须根据项目性质准确选择。<br />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="modal-footer" style='background-color: #fff;'>
                <button style="margin-left:-50px;" type="button" class="btn btn-primary" onclick="window.parent.declares_close();" data-dismiss="modal"> <i class="icon-undo bigger-110"></i> 关闭</button>

                <button type="button" class="btn btn-primary" onclick="approve();"> <i class="icon-ok bigger-110"></i> 保存</button>
                <button type="button" class="btn btn-primary" onclick="printDIV();"><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
            </div>
<script type="text/javascript">
    var class_name = 'not_right_tmp_8888';//定义一个没有的class
function printDIV(){
    $('.modal-footer').css('display', 'none');
    $('#dropzone').css('display', 'none');
    //隐藏下拉框
    $('.' + class_name).css('display', 'none');
    {
        $('.navbar-default').css('display', 'none');
        $('#sidebar').css('display', 'none');
        $('.breadcrumbs').css('display', 'none');
        $('.ace-settings-container').css('display', 'none');
        $('#btn-scroll-up').css('display', 'none');
        $('.right_content').css('display', 'none');
    }
    window.print();//打印刚才新建的网页
    {
        $('.navbar-default').css('display', '');
        $('#sidebar').css('display', '');
        $('.breadcrumbs').css('display', '');
        $('.ace-settings-container').css('display', '');
        $('#btn-scroll-up').css('display', '');
        $('.right_content').css('display', '');
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
    function isPositiveInteger(s){//是否为正整数
        var re = /^[0-9]+$/ ;
        return re.test(s)
    }
    $('.nums,.price').blur(function(){
        var nums = $('.nums').val();
        var price = $('.price').val();
        var total = 0;
        if (!isPositiveInteger(nums)) {
            nums = 0;
        }
        if (!Number(price)) {
            price = 0;
        }
        total = parseFloat(nums) * parseFloat(price);
        $('.total').val(parseFloat(total));
    });
    function approve() {
        var ctime = $('.ctime').val();
        var department = $('.department').val();
        var project = $('.project').val();
        var type = $('.type:checked').val();
        var file_number = $('.file_number').val();
        var material_name = $('.material_name').val();
        var descripttion = $('.descripttion').val();
        var unit = $('.unit').val();
        var nums = $('.nums').val();
        var price = $('.price').val();
        var total = $('.total').val();
        var reason = $('.reason').val();
        var declarename = $('.declarename').val();
        var dep_team = $('.dep_pro').val();
        
        if (department == '') {
            $('.department').focus();
            return;
        }
        if (project == '') {
            $('.project').focus();
            return;
        }
        if (file_number == '') {
            $('.file_number').focus();
            return;
        }
        if (material_name == '') {
            $('.material_name').focus();
            return;
        }
//        if (descripttion == '') {
//            $('.descripttion').focus();
//            return;
//        }
        if (unit == '') {
            $('.unit').focus();
            return;
        }
        if (!isPositiveInteger(nums)) {
            $('.nums').focus();
            return;
        }
        if (!Number(price)) {
            $('.price').focus();
            return;
        }
        if (reason == '') {
            $('.reason').focus();
            return;
        }
        $('form').submit();
        return;
        var data = {};
        data.ctime = ctime;
        data.department = department;
        data.project = project;
        data.type = type;
        data.file_number = file_number;
        data.material_name = material_name;
        data.descripttion = descripttion;
        data.unit = unit;
        data.nums = nums;
        data.price = price;
        data.total = total;
        data.reason = reason;
        data.declarename = declarename;
        data.dep_team = dep_team;
        $.ajax({
            url: '/RequestNote/gss_purchase',
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


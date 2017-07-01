<?php echo $this->element('head_frame'); ?>


<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:560px;'>

    <p class="btn btn-info btn-block"  style="border-radius:4px 4px 0 0;padding:0 12px;"> <span style="font-size:16px;">固定资产</span> <a onclick="window.parent.fixed_close();" class="close" data-dismiss="modal" id='closemodel'>×</a></p>


    <div  style='padding:20px 0;'>
        <div >
            <form class="form-horizontal" role="form" id="formstep1" method="post" action="">
                
                <ul class="form-ul">
                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">项目 &nbsp;&nbsp;</label>
                        <select  name="pid" class="pid input-width" id="form-field-1" style="width:145px;">
                            <?php foreach($project as $p){?>
                            <option value="<?php echo $p['ResearchProject']['id'];?>"><?php echo $p['ResearchProject']['name'];?></option>
                            <?php }?>
                        </select> 

                        <label class="input-group-addon " for="form-field-2">资产名称  &nbsp;&nbsp;</label> 
                        <input type="text" id="form-field-2" placeholder="资产名称" class="asset_name" name="asset_name" value="" />           
                    </li> 

                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">分类 &nbsp;&nbsp;</label>
                        <select  name="category" class="category input-width" id="form-field-1" style="width:145px;">
                            <option value="1">第1个分类</option>
                            <option value="2">第2个分类</option>
                        </select>  

                        <label class="input-group-addon " for="form-field-2">购买日期 &nbsp;&nbsp;</label> 
                        <input readonly="readonly" type="text" class=" form_datetime1 purchase_date" name="purchase_date">
                        <script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>
                        <script type="text/javascript">
                            $(".form_datetime1").datetimepicker({
                                format: 'yyyy-mm-dd',
                                minView: "month", //选择日期后，不会再跳转去选择时分秒 
                            });
                        </script>
                    </li> 
                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">代码 &nbsp;&nbsp;</label>
                        <input type="text" id="form-field-2" placeholder="代码" class="code" name="code" value="" />

                        <label class="input-group-addon " for="form-field-2">国际分类  &nbsp;&nbsp;</label> 
                        <input type="text" id="form-field-2" placeholder="国际分类" class="international_classification" name="international_classification" value="" />           
                    </li> 
                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">型号 &nbsp;&nbsp;</label>
                        <input type="text" id="form-field-2" placeholder="型号" class="model" name="model" value="" />

                     </li> 
                     <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">数量 &nbsp;&nbsp;</label>
                        <input type="text" id="form-field-2" placeholder="数量" class="number" name="number" value="" />

                        <label class="input-group-addon " for="form-field-2">单位  &nbsp;&nbsp;</label> 
                        <input type="text" id="form-field-2" placeholder="单位" class="company" name="company" value="" />           
                    </li>
                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">单价 &nbsp;&nbsp;</label>
                        <input type="text" id="form-field-2" placeholder="单价" class="price" name="price" value="" />

                        <label class="input-group-addon " for="form-field-2">金额  &nbsp;&nbsp;</label> 
                        <input type="text" id="form-field-2" placeholder="金额" class="amount" name="amount" value="" />           
                    </li>
                    <li class="input-group">
                        <label class="input-group-addon " for="form-field-1">政府采购 &nbsp;&nbsp;</label>
                        <select  name="is_government" class="is_government input-width" id="form-field-1" style="width:145px;">
                            <option value="0">是</option>
                            <option value="1">否</option>
                        </select> 

                        <label class="input-group-addon " for="form-field-2">批准文号  &nbsp;&nbsp;</label> 
                        <input type="text" id="form-field-2" placeholder="批准文号" class="approval_number" name="approval_number" value="" />           
                    </li>
                </ul>

                <div class="form-group" style="margin:10px auto;width:490px;">
                    <label class="control-label no-padding-right" style="width:100px;text-align: right;" for="form-field-1">现况 &nbsp;&nbsp;</label>
                    <textarea class="current_situation" name="current_situation" style="width:350px;" placeholder="现况" ></textarea>
                </div>
                <div class="form-group" style="margin:10px auto;width:500px;">
                    <label class="control-label no-padding-right" style="width:100px;text-align: right;" for="form-field-1">备注 &nbsp;&nbsp;</label>
                    <textarea class="remarks" name="remarks" style="width:350px;" placeholder="备注"></textarea>

                </div>

                <div class="space-4"></div>


                <div class="clearfix " style="text-align: center;">
                    <div class=" col-md-9">
                        <button class="btn btn-primary" type="button" onclick='window.parent.fixed_close();' >
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
    //提交内容
    function ajax_submit() {
        var data_json = {};
        var pid = $('.pid option:selected').val();
        var asset_name = $('.asset_name').val();
        var category = $('.category option:selected').val();
        var purchase_date = $('.purchase_date').val();
        var code = $('.code').val();
        var international_classification = $('.international_classification').val();
        var model = $('.model').val();
        var number = $('.number').val();
        var company = $('.company').val();
        var price = $('.price').val();
        var is_government = $('.is_government option:selected').val();
        var approval_number = $('.approval_number').val();
        var current_situation = $('.current_situation').val();
        var remarks = $('.remarks').val();
        
        if (asset_name == '') {
            $('.asset_name').focus();
            return;
        }
        data_json.pid = pid;
        data_json.asset_name = asset_name;
        if (category == '') {
            $('.category').focus();
            return;
        }
        data_json.category = category;
        if (purchase_date == '') {
            $('.purchase_date').focus();
            return;
        }
        data_json.purchase_date = purchase_date;
        
        if (code == '') {
            $('.code').focus();
            return;
        }
        data_json.code = code;
        if (international_classification == '') {
            $('.international_classification').focus();
            return;
        }
        data_json.international_classification = international_classification;
        if (model == '') {
            $('.model').focus();
            return;
        }
        data_json.model = model;
        
        if (!number || isNaN(number)) {
            $('.number').focus();
            return;
        }
        data_json.number = number;
        if (company == '') {
            $('.company').focus();
            return;
        }
        data_json.company = company;
        if (!price || isNaN(price)) {
            $('.price').focus();
            return;
        }
        data_json.price = price;
        data_json.is_government = is_government;
        if (approval_number == '') {
            $('.approval_number').focus();
            return;
        }
        data_json.approval_number = approval_number;
        if (current_situation == '') {
            $('.current_situation').focus();
            return;
        }
        data_json.current_situation = current_situation;
        data_json.remarks = remarks;
        

        var data = data_json;
        $.ajax({
            url: '/Fixedassets/ajax_add',
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
                    window.parent.fixed_close();
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
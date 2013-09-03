<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>
<style>
    <!--
    .modal {
        background-clip: padding-box;
        background-color: #FFFFFF;
        border: 1px solid rgba(0, 0, 0, 0.3);
        border-radius: 6px 6px 6px 6px;
        box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
        left: 50%;
        margin-left: -375px;
        outline: 0 none;
        position: fixed;
        top: 10%;
        width: 746px;
        z-index: 1050;
    }
    .modal-body {
        max-height: 380px;
        overflow-y: auto;
        padding: 15px;
        position: relative;
    }
    -->
</style>

<script>

    function open_add_apply_content() {
        $('#add-apply-content-dialog').modal('show');
    }

    function open_edit_apply_content(id) {
        if (id == "") {
            openalert('请选择要删除的期货商品。');
            return;
        }
        var row = null;
        //获取要修改的期货商品内容
        $.ajax({
            async:false,
            type:"post",
            data: "id=" + id,
            url:"<?php echo site_url('apply/read_apply_content')?>",
            success: function(data){
                if (data) {
                    row = JSON.parse(data);
                }
                else {
                    openalert("读取期货商品出错，请重新尝试或与管理员联系。");
                }
            },
            error: function() {
                openalert("执行操作出错，请重新尝试或与管理员联系。");
            }
        });

        row = row[0];
        $('#e_title').val(row.title);
        $('#e_code').val(row.code);
        $('#e_factory').val(row.factoryid);
        $('#e_brand').val(row.brandid);
        $('#e_commoditytype').val(row.typeid);
        $('#e_color').val(row.color);
        $('#e_memo').val(row.memo);
        $('#e_number').val(row.number);
        $('#e_salesprice').val(row.salesprice);
        $('#e_remark').val(row.remark);
        $('#apply_content_id').val(row.id);


        $('#edit-apply-content-dialog').modal('show');
    }

    function editsave() {
        //获取填写的商品信息
        var id = $('#apply_content_id').val();
        var e_title = $('#e_title').val();
        var e_code = $('#e_code').val();
        var e_factory_value = $('#e_factory').val();
        var e_brand_value = $('#e_brand').val();
        var e_type_value = $('#e_commoditytype').val();
        var e_color = $('#e_color').val();
        var e_memo = $('#e_memo').val();
        var e_number = $('#e_number').val();
        var e_salesprice = $('#e_salesprice').val();
        var e_remark = $('#e_remark').val();


        var tmp = new Object();
        tmp.id = id;
        tmp.title = e_title;
        tmp.code = e_code;
        tmp.factoryid = e_factory_value;
        tmp.brandid = e_brand_value;
        tmp.typeid = e_type_value;
        tmp.color = e_color;
        tmp.memo = e_memo;
        tmp.number = e_number;
        tmp.salesprice = e_salesprice;
        tmp.remark = e_remark;

        var apply_content = JSON.stringify(tmp);

        $.ajax({
            type:"post",
            data: "apply_content_json=" + apply_content,
            url:"<?php echo site_url('apply/edit_save_apply_content')?>",
            success: function(data){
                if (data) {
                    window.location.reload();
                }
                else {
                    openalert("修改保存期货商品出错，请重新尝试或与管理员联系。");
                }
            },
            error: function() {
                openalert("执行操作出错，请重新尝试或与管理员联系。");
            }
        });
    }

    function addNew() {
        //获取填写的商品信息
        var s_title = $('#s_title').val();
        var s_code = $('#s_code').val();
        var s_factory_value = $('#s_factory').val();
//        var s_factory_text = $('#s_factory').find("option:selected").text();
        var s_brand_value = $('#s_brand').val();
//        var s_brand_text = $('#s_brand').find("option:selected").text();
        var s_type_value = $('#s_commoditytype').val();
//        var s_type_text = $('#s_commoditytype').find("option:selected").text();
        var s_color = $('#s_color').val();
        var s_memo = $('#s_memo').val();
        var s_number = $('#s_number').val();
        var s_salesprice = $('#s_salesprice').val();
        var s_remark = $('#s_remark').val();


        var tmp = new Object();
        tmp.applyid = $('#applyid').val();
        tmp.title = s_title;
        tmp.code = s_code;
        tmp.factoryid = s_factory_value;
        tmp.brandid = s_brand_value;
        tmp.typeid = s_type_value;
        tmp.color = s_color;
        tmp.memo = s_memo;
        tmp.number = s_number;
        tmp.salesprice = s_salesprice;
        tmp.remark = s_remark;

        var apply_content = JSON.stringify(tmp);
//        $('#apply_content_json').val(apply_content);

        $.ajax({
            type:"post",
            data: "apply_content_json=" + apply_content,
            url:"<?php echo site_url('apply/add_apply_content')?>",
            success: function(data){
                if (data) {
                    window.location.reload();
                }
                else {
                    openalert("添加期货商品出错，请重新尝试或与管理员联系。");
                }
            },
            error: function() {
                openalert("执行操作出错，请重新尝试或与管理员联系。");
            }
        });

    }
    function delete_apply_content(id) {
        if (id == "") {
            openalert('请选择要删除的期货商品。');
            return;
        }

        bootbox.confirm("确定要删除选择的期货商品吗？<br> <font color='red'>" +
            "注意：本操作不可恢复，请谨慎操作。</font> ", function(result) {
            if(result){

                $.ajax({
                    type:"post",
                    data: "id=" + id,
                    url:"<?php echo site_url('apply/delete_apply_content')?>",
                    success: function(data){
                        if (data) {
                            window.location.reload();
                        }
                        else {
                            openalert("删除期货商品出错，请重新尝试或与管理员联系。");
                        }
                    },
                    error: function() {
                        openalert("执行操作出错，请重新尝试或与管理员联系。");
                    }
                });
            }
        })
    }

    $(function () {
//        $('#totalmoney').val('0');
        $('#selldate_div').datepicker().on('changeDate', function(ev) {
            $(this).datepicker('hide')
        });
        $('#commitgetdate_div').datepicker().on('changeDate', function(ev) {
            $(this).datepicker('hide')
        });

        $('#storehouseid').change(function(){
            var checkText= $("#storehouseid").find("option:selected").text();   //获取Select选择的Text
            $('#storehousecode').val(checkText);
        })
    })

</script>

<!-- Modal -->
<div id="add-apply-content-dialog" class="modal hide fade" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        添加订货商品
    </div>
    <div class="modal-body">
        <form id="add_finance_form" method="post" action="">
            <input type="hidden" id="applyid" name="applyid" value="<?php echo $info->id ?>">
<!--            <input type="hidden" id="sellnumber" name="sellnumber" value="--><?php //echo $apply->applynumber ?><!--">-->
        <table class="table table-striped table-bordered">
            <tbody>
            <tr>
                <td style="vertical-align:middle">商品名称</td>
                <td><input type="text" class="span2" id="s_title"
                           name="s_title" style="margin-bottom: 0px;"
                           placeholder="请填写商品名称">
                </td>
                <td style="vertical-align:middle">商品代码</td>
                <td><input type="text" class="span2" id="s_code" style="margin-bottom: 0px;"
                           name="s_code" placeholder="请填写商品代码">
                </td>
            </tr>
            <tr>
                <td style="vertical-align:middle">厂家</td>
                <td>
                    <select class="span2" id="s_factory" name="s_factory" style="margin-bottom: 0px;">
                        <option value="">请选择</option>
                        <?php if($factorys):?>
                            <?php foreach($factorys as $factory):?>
                                <option value="<?php echo $factory->id ?>" <?php echo set_select('factory', $factory->id ); ?> ><?php echo $factory->factoryname ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </td>
                <td style="vertical-align:middle">品牌</td>
                <td>
                    <select class="span2" id="s_brand" name="s_brand" style="margin-bottom: 0px;">
                        <option value="">请选择</option>
                        <?php if($brands):?>
                            <?php foreach($brands as $brand):?>
                                <option value="<?php echo $brand->id ?>" <?php echo set_select('brand', $brand->id ); ?> ><?php echo $brand->brandname ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="vertical-align:middle">类别</td>
                <td>
                    <select class="span2" id="s_commoditytype" name="s_commoditytype" style="margin-bottom: 0px;">
                        <option value="">请选择</option>
                        <?php if($comtypes):?>
                            <?php foreach($comtypes as $type):?>
                                <option value="<?php echo $type->id ?>" <?php echo set_select('commoditytype', $type->id ); ?> ><?php echo $type->typename ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </td>
                <td style="vertical-align:middle">颜色</td>
                <td><input type="text" class="span2" id="s_color" name="s_color" style="margin-bottom: 0px;"></td>
            </tr>
            <tr>
                <td style="vertical-align:middle">商品描述</td>
                <td colspan="3">
                    <textarea rows="3" id="s_memo" name="s_memo" class="span3" style="margin-bottom: 0px; width: 536px; height: 72px;"></textarea>
                </td>
            </tr>
            <tr>
                <td style="vertical-align:middle">数量</td>
                <td>
                    <input type="text" class="span2" style="margin-bottom: 0px;" id="s_number" name="s_number" value="1" onkeypress="return isnumber(event)" >
                </td>
                <td style="vertical-align:middle">售价（单价）</td>
                <td>
                    <div class="input-prepend input-append" style="margin-bottom: 0px;">
                        <span class="add-on">￥</span><input type="text" style="width: 100px;" class="span2" value="0" onkeypress="return isfloat(event)" id="s_salesprice" name="s_salesprice"><span class="add-on">.00</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="vertical-align:middle">备注</td>
                <td colspan="3">
                    <input type="text" class="span5" id="s_remark" style="margin-bottom: 0px; width: 536px;" name="s_remark" >
                </td>
            </tr>
            </tbody>
        </table>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a href="javascript:;" onclick="addNew()" class="btn btn-primary">添加</a>
    </div>
</div>

<!-- Modal -->
<div id="edit-apply-content-dialog" class="modal hide fade" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        修改订货商品
    </div>
    <div class="modal-body">
        <form id="add_finance_form" method="post" action="">
            <input type="hidden" id="apply_content_id" name="apply_content_id" value="">
            <table class="table table-striped table-bordered">
                <tbody>
                <tr>
                    <td style="vertical-align:middle">商品名称</td>
                    <td><input type="text" class="span2" id="e_title"
                               name="e_title" style="margin-bottom: 0px;"
                               placeholder="请填写商品名称" value="">
                    </td>
                    <td style="vertical-align:middle">商品代码</td>
                    <td><input type="text" class="span2" id="e_code" style="margin-bottom: 0px;"
                               name="e_code" placeholder="请填写商品代码">
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:middle">厂家</td>
                    <td>
                        <select class="span2" id="e_factory" name="e_factory" style="margin-bottom: 0px;">
                            <option value="">请选择</option>
                            <?php if($factorys):?>
                                <?php foreach($factorys as $factory):?>
                                    <option value="<?php echo $factory->id ?>" <?php echo set_select('factory', $factory->id ); ?> ><?php echo $factory->factoryname ?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                    </td>
                    <td style="vertical-align:middle">品牌</td>
                    <td>
                        <select class="span2" id="e_brand" name="e_brand" style="margin-bottom: 0px;">
                            <option value="">请选择</option>
                            <?php if($brands):?>
                                <?php foreach($brands as $brand):?>
                                    <option value="<?php echo $brand->id ?>" <?php echo set_select('brand', $brand->id ); ?> ><?php echo $brand->brandname ?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:middle">类别</td>
                    <td>
                        <select class="span2" id="e_commoditytype" name="e_commoditytype" style="margin-bottom: 0px;">
                            <option value="">请选择</option>
                            <?php if($comtypes):?>
                                <?php foreach($comtypes as $type):?>
                                    <option value="<?php echo $type->id ?>" <?php echo set_select('commoditytype', $type->id ); ?> ><?php echo $type->typename ?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                    </td>
                    <td style="vertical-align:middle">颜色</td>
                    <td><input type="text" class="span2" id="e_color" name="e_color" style="margin-bottom: 0px;"></td>
                </tr>
                <tr>
                    <td style="vertical-align:middle">商品描述</td>
                    <td colspan="3">
                        <textarea rows="3" id="e_memo" name="e_memo" class="span3" style="margin-bottom: 0px; width: 536px; height: 72px;"></textarea>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:middle">数量</td>
                    <td>
                        <input type="text" class="span2" style="margin-bottom: 0px;" id="e_number" name="e_number" value="1" onkeypress="return isnumber(event)" >
                    </td>
                    <td style="vertical-align:middle">售价（单价）</td>
                    <td>
                        <div class="input-prepend input-append" style="margin-bottom: 0px;">
                            <span class="add-on">￥</span><input type="text" style="width: 100px;" class="span2" value="0" onkeypress="return isfloat(event)" id="e_salesprice" name="e_salesprice"><span class="add-on">.00</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:middle">备注</td>
                    <td colspan="3">
                        <input type="text" class="span5" id="e_remark" style="margin-bottom: 0px; width: 536px;" name="e_remark" >
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a href="javascript:;" onclick="editsave()" class="btn btn-primary">保存</a>
    </div>
</div>


<div id="content">
    <div class="container">
        <div class="row">
            <div class="span3">
                <?php $this->load->view('common/leftmenu'); ?>
            </div>
            <!-- /span3 -->
            <div class="span9">
                <h1 class="page-title">
                    <i class="icon-th-list"></i>
                    <a href="<?php echo site_url('buy') ?>" class="path-menu-a"> 采购管理</a> > <a
                        href="<?php echo site_url('apply') ?>" class="path-menu-a"> 期货订单管理</a> > 修改订单
                </h1>
                <div class="row">
                    <div class="span9">
                        <div class="alert alert-info">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>说明!</strong> 修改订单内容需要提交保存。增加、修改、删除订单商品将直接保存，不需要提交保存。增加、修改、删除订单商品会刷新页面，所以请首先处理订单商品，再修改订单内容。注意销售总价、折后总价、已付款、余款需要手动修改。
                        </div>
                        <form id="apply_form" method="post" action="<?php echo site_url("apply/doupdate") ?>">
                            <input type="hidden" value="<?php echo $info->id;?>" name="id">
                        <div class="widget widget-table">
                            <div class="widget-header">
                                <i class="icon-th-list"></i>
                                <h3>期货订单</h3>
                            </div> <!-- /widget-header -->
                            <div class="widget-content">
                                <table id="apply_table" class="table table-striped table-bordered">
                                    <tbody>
                                        <tr>
                                            <td style="vertical-align:middle">订单编号</td>
                                            <td colspan="3"><input type="text" class="span3" id="applynumber"
                                                       name="applynumber" readonly style="margin-bottom: 0px;" value="<?php echo $info->applynumber; ?>"
                                                       value=""> *
<!--                                                <input type="button" class="btn btn-primary btn-small" value="生成订单编号"-->
<!--                                                       onclick="get_buynumber()">-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle;width: 130px">销售日期</td>
                                            <td>
                                                <div style="margin-bottom: 0px;" class="input-append date" id="selldate_div" data-date="<?php echo date("Y-m-d")?>" data-date-format="yyyy-mm-dd">
                                                    <input style="margin-bottom: 0px;" id="selldate" name="selldate" class="span2" size="16" type="text" value="<?php echo $info->selldate ;?>" readonly>
                                                    <span class="add-on"><i title="点击选择日期" class="icon-calendar"></i></span>
                                                </div>
                                            </td>
                                            <td style="vertical-align:middle;width: 130px">销售店</td>
                                            <td>
                                                <select id="storehouseid" name="storehouseid" class="span2" style="margin-bottom: 0px;">
                                                    <?php if($houses):?>
                                                        <?php foreach($houses as $house):?>
                                                            <option value="<?php echo $house->id ?>" <?php echo set_select('storehouse',$house->id,($info->storehouseid == $house->id)) ?>><?php echo $house->storehousecode ?></option>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                </select>
                                                 *
                                                <input type="hidden" id="storehousecode" name="storehousecode" value="<?php echo $info->storehousecode; ?>" >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">销售总价（RMB）</td>
                                            <td><input type="text" class="span2" id="totalmoney"
                                                       name="totalmoney" value="<?php echo $info->totalmoney; ?>" required style="margin-bottom: 0px;" onkeypress="return isfloat(event)"
                                                       value="0"> *
                                            </td>
                                            <td style="vertical-align:middle">折后总价（RMB）</td>
                                            <td><input type="text" class="span2" required  value="<?php echo $info->discount; ?>" id="discount" style="margin-bottom: 0px;" onkeypress="return isfloat(event)"
                                                       name="discount" value="0">  *
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">已付款（RMB）</td>
                                            <td><input type="text" class="span2" id="paymoney"
                                                       name="paymoney" required style="margin-bottom: 0px;" value="<?php echo $info->paymoney; ?>" onkeypress="return isfloat(event)"
                                                       value="0"> *
                                            </td>
                                            <td style="vertical-align:middle">余款（RMB）</td>
                                            <td><input type="text" class="span2" required id="lastmoney" value="<?php echo $info->lastmoney; ?>" style="margin-bottom: 0px;" onkeypress="return isfloat(event)"
                                                       name="lastmoney" value="0">  *
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">客户名称</td>
                                            <td><input type="text" class="span2" id="clientname" required
                                                       name="clientname" required style="margin-bottom: 0px;"  placeholder="必须填写"
                                                       value="<?php echo $info->clientname; ?>"> *
                                            </td>
                                            <td style="vertical-align:middle">客户电话</td>
                                            <td><input type="text" class="span2" id="clientphone" style="margin-bottom: 0px;" required
                                                       name="clientphone" value="<?php echo $info->clientphone; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">客户地址</td>
                                            <td colspan="3"><input type="text" class="span5" id="clientadd" required
                                                       name="clientadd" required style="margin-bottom: 0px;"
                                                       value="<?php echo $info->clientadd; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">申请人</td>
                                            <td><input type="text" class="span2" id="applyby"
                                                       name="applyby" required style="margin-bottom: 0px;"
                                                       value="<?php echo $info->applyby ?>"> *
                                            </td>
                                            <td style="vertical-align:middle">申请日期</td>
                                            <td><input type="text" class="span2" readonly="" id="applydate" name="applydate" style="margin-bottom: 0px;"
                                                       value="<?php echo $info->applydate; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">承诺到货日期</td>
                                            <td>
                                                <div class="input-append date" style="margin-bottom: 0px;" id="commitgetdate_div" data-date="<?php echo date("Y-m-d")?>" data-date-format="yyyy-mm-dd">
                                                    <input id="commitgetdate"  name="commitgetdate" class="span2" size="16" type="text" value="<?php echo $info->commitgetdate; ?>" readonly>
                                                    <span class="add-on"><i title="点击选择日期" class="icon-calendar"></i></span>
                                                </div>
                                            </td>
                                            <td style="vertical-align:middle">电子邮件</td>
                                            <td><input type="text"  class="span2" id="email"
                                                       name="email" style="margin-bottom: 0px;"
                                                       value="<?php echo $info->email; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">备注</td>
                                            <td colspan="3"><input type="text" class="span5" id="remark" name="remark" style="margin-bottom: 0px;"
                                                       value="<?php echo $info->remark; ?>">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!-- /widget-content -->
                        </div> <!-- /widget -->

                        <div class="row">
                            <div class="span9">
                                <label class="pull-left">
                                    <input type="hidden" id="apply_content_json" name="apply_content_json" value="">
                                    <a href="javascript:;" class="btn btn-primary" onclick="open_add_apply_content()" ><i class="icon-plus"> 添加订货商品</i></a>
<!--                                    <a href="javascript:;" class="btn btn-primary" onclick="del()" ><i class="icon-minus"> 删除订货商品</i></a>-->
                                    <button class="btn btn-primary" type="submit" id="btn-save"><i class="icon-ok">提交并返回列表</i></button>
                                </label>
                            </div>
                        </div>
                        </form>
                        <div class="widget widget-table">
                            <div class="widget-header">
                                <i class="icon-th-list"></i>
                                <h3>订货商品列表</h3>
                            </div> <!-- /widget-header -->

                            <div class="widget-content">
                                <table id="apply_content" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all""></th>
                                        <th>序号</th>
                                        <th>名称</th>
                                        <th>代码</th>
                                        <th>厂家</th>
                                        <th>描述</th>
                                        <th>数量</th>
                                        <th>售价（单价）</th>
<!--                                        <th>经办人</th>-->
<!--                                        <th>预计到港日期</th>-->
<!--                                        <th>状态</th>-->
                                        <th>处理</th>
<!--                                        <th>查看</th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($apply_content)):?>
                                        <?php $n = 1; foreach($apply_content as $apply):?>
                                            <tr>
                                                <?php if ($apply->status == 3 || $apply->status == 4) : ?>
                                                    <td></td>
                                                <?php else : ?>
                                                    <td><input type="checkbox" name="checkbox" value="<?php echo $apply->id ?>"/></td>
                                                <?php endif ?>
                                                <td><?php echo $n ?></td>
                                                <td><?php echo $apply->title; ?></td>
                                                <td title="<?php echo $apply->code ?>"><?php echo Common::subStr($apply->code, 0, 6) ?></td>
                                                <td><?php echo $apply->factoryname; ?></td>
                                                <td title="<?php echo $apply->memo ?>"><?php echo Common::subStr($apply->memo, 0, 6) ?></td>
                                                <td><?php echo $apply->number; ?></td>
                                                <td><?php echo $apply->salesprice; $n++?></td>
<!--                                                <td>--><?php //echo $apply->checkby; $n++?><!--</td>-->
<!--                                                <td>--><?php //echo strtotime($apply->forecastgetdate)?$apply->forecastgetdate:''; ?><!--</td>-->
<!--                                                <td>--><?php //echo $apply->statusvalue; $n++?><!--</td>-->
                                                <td><a href="javascript:;" class="btn btn-small btn-info" onclick="open_edit_apply_content('<?php echo $apply->id ?>')" >修改</a>
                                                    <a href="javascript:;" class="btn btn-small btn-info" onclick="delete_apply_content('<?php echo $apply->id ?>')" >删除</a>
                                                </td>
<!--                                                <td><a href="javascript:;" class="btn btn-small btn-info" onclick="show_apply_content('--><?php //echo $apply->id ?><!--')">查看</a></td>-->
                                            </tr>
                                        <?php  endforeach;?>
                                    <?php endif;?>
                                    </tbody>
                                </table>
                            </div> <!-- /widget-content -->
                        </div> <!-- /widget -->

                    </div>
                    <!-- /span9 -->

                </div>
                <!-- /row -->
            </div>
            <!-- /span9 -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div> <!-- /content -->


<?php $this->load->view("common/footer"); ?>

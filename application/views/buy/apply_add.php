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

    //获取单号
    function get_buynumber() {
        $.ajax({
            type: "post",
            url: "<?php echo site_url('buy/get_number')?>",
            success: function (data) {
                $('#applynumber').val(data);
            },

            error: function () {
                alert("创建订单编号数据出错，请重新尝试或与管理员联系。");
            }
        });
    }

    function open_add_apply_content() {
        $('#add-apply-content-dialog').modal('show');
    }

    var add_data = {};

    var row_count = 0;
    function addNew() {
        //获取填写的商品信息
        var s_title = $('#s_title').val();
        var s_code = $('#s_code').val();
        var s_factory_value = $('#s_factory').val();
        var s_factory_text = $('#s_factory').find("option:selected").text();
        var s_brand_value = $('#s_brand').val();
        var s_brand_text = $('#s_brand').find("option:selected").text();
        var s_type_value = $('#s_commoditytype').val();
        var s_type_text = $('#s_commoditytype').find("option:selected").text();
        var s_color = $('#s_color').val();
        var s_memo = $('#s_memo').val();
        var s_number = $('#s_number').val();
        var s_salesprice = $('#s_salesprice').val();
        var s_remark = $('#s_remark').val();

        var table1 = $('#apply_content');
        var row = $("<tr></tr>");
        var td = $("<td></td>");
        td.append($("<input type='checkbox' name='count' value='"+row_count+"'>"));
        row.append(td);

        var td1 = $("<td></td>");
        td1.append(s_title);
        row.append(td1);

        var td2 = $("<td></td>");
        td2.append(s_code);
        row.append(td2);

        var td3 = $("<td></td>");
        if (s_factory_value == '') {
            s_factory_text = '';
        }
        td3.append(s_factory_text);
        row.append(td3);

        var td4 = $("<td></td>");
        if (s_brand_value == '') {
            s_brand_text = '';
        }
        td4.append(s_brand_text);
        row.append(td4);

        var td5 = $("<td></td>");
        if (s_type_value == '') {
            s_type_text = '';
        }
        td5.append(s_type_text);
        row.append(td5);

        var td6 = $("<td></td>");
        td6.append(s_color);
        row.append(td6);

        var td7 = $("<td></td>");
        td7.append(s_memo);
        row.append(td7);

        var td8 = $("<td></td>");
        if (s_number == '' || s_number == '0') {
            s_number = '1';
        }
        td8.append(s_number);
        row.append(td8);

        var td9 = $("<td></td>");
        if (s_salesprice == '') {
            s_salesprice = '0';
        }

        var total_sale = s_salesprice * s_number;
//        td9.append(s_salesprice);
        td9.append('<input  readonly type="hidden" class="myaddclass span1" name="price[' + row_count + ']" value="' + s_salesprice + '" />');
        td9.append('<input type="hidden" name="total_sale['+ row_count +']" value="'+ total_sale +'" />');
        td9.append(s_salesprice);
        row.append(td9);

        $('#totalmoney').val(parseFloat($('#totalmoney').val()) + parseFloat(s_salesprice) * s_number);

        var td10 = $("<td></td>");
        td10.append(s_remark);
        row.append(td10);

        table1.append(row);

        var tmp = new Object();
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

        add_data[row_count] = tmp;

        var apply_content = JSON.stringify(add_data);
        $('#apply_content_json').val(apply_content);

        row_count++;
    }
    function del() {
        var checked = $("input[type='checkbox'][name='count']");
        $(checked).each(function(){
            if($(this).attr("checked") == 'checked') { //注意：此处判断不能用$(this).attr("checked")==‘true'来判断。

                var price = parseFloat($("input[name='price["+this.value+"]']").val());
                var total_sale = parseFloat($("input[name='total_sale["+this.value+"]']").val());
                $('#totalmoney').val(parseFloat($('#totalmoney').val()) - total_sale);
                $(this).parent().parent().remove();
                delete add_data[$(this).val()];
            }
        });
        var apply_content = JSON.stringify(add_data);
        $('#apply_content_json').val(apply_content);
    }

    $(function () {
        $('#totalmoney').val('0');
        $('#selldate_div').datepicker().on('changeDate', function(ev) {
            $(this).datepicker('hide')
        });
        $('#commitgetdate_div').datepicker().on('changeDate', function(ev) {
            $(this).datepicker('hide')
        });


    })

</script>

<!-- Modal -->
<div id="add-apply-content-dialog" class="modal hide fade" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        添加订货商品
    </div>
    <div class="modal-body">
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
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a href="javascript:;" onclick="addNew()" class="btn btn-primary">添加</a>
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
                        href="<?php echo site_url('apply') ?>" class="path-menu-a"> 期货订单管理</a> > 添加订单
                </h1>
                <div class="row">
                    <div class="span9">
                        <form id="apply_form" method="post" action="<?php echo site_url("apply/add?stype=").$stype;?>">
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
                                                       name="applynumber" readonly style="margin-bottom: 0px;" placeholder="自动生成"
                                                       value=""> *
<!--                                                <input type="button" class="btn btn-primary btn-small" value="生成订单编号"-->
<!--                                                       onclick="get_buynumber()">-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle;width: 130px">销售日期</td>
                                            <td>
                                                <div style="margin-bottom: 0px;" class="input-append date" id="selldate_div" data-date="<?php echo date("Y-m-d")?>" data-date-format="yyyy-mm-dd">
                                                    <input style="margin-bottom: 0px;" id="selldate" name="selldate" class="span2" size="16" type="text" value="<?php echo date("Y-m-d")?>" readonly>
                                                    <span class="add-on"><i title="点击选择日期" class="icon-calendar"></i></span>
                                                </div>
                                            </td>
                                            <td style="vertical-align:middle;width: 130px">销售店</td>
                                            <td>
                                                <select id="storehouse" name="storehouse" class="span2" style="margin-bottom: 0px;">
                                                    <?php if($houses):?>
                                                        <?php foreach($houses as $house):?>
                                                            <option value="<?php echo $house->id ?>"><?php echo $house->storehousecode ?></option>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                </select>
                                                 *
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">销售总价（RMB）</td>
                                            <td><input type="text" class="span2" id="totalmoney"
                                                       name="totalmoney" required style="margin-bottom: 0px;" onkeypress="return isfloat(event)"
                                                       value="0"> *
                                            </td>
                                            <td style="vertical-align:middle">折后总价（RMB）</td>
                                            <td><input type="text" class="span2" required id="discount" style="margin-bottom: 0px;" onkeypress="return isfloat(event)"
                                                       name="discount" value="0">  *
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">已付款（RMB）</td>
                                            <td><input type="text" class="span2" id="paymoney"
                                                       name="paymoney" required style="margin-bottom: 0px;" onkeypress="return isfloat(event)"
                                                       value="0"> *
                                            </td>
                                            <td style="vertical-align:middle">余款（RMB）</td>
                                            <td><input type="text" class="span2" required id="lastmoney" style="margin-bottom: 0px;" onkeypress="return isfloat(event)"
                                                       name="lastmoney" value="0">  *
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">客户名称</td>
                                            <td><input type="text" class="span2" id="clientname" required
                                                       name="clientname" required style="margin-bottom: 0px;"  placeholder="必须填写"
                                                       value="<?php echo set_value('clientname'); ?>"> *
                                            </td>
                                            <td style="vertical-align:middle">客户电话</td>
                                            <td><input type="text" class="span2" id="clientphone" style="margin-bottom: 0px;" required
                                                       name="clientphone" value="<?php echo set_value('clientphone'); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">客户地址</td>
                                            <td colspan="3"><input type="text" class="span5" id="clientadd" required
                                                       name="clientadd" required style="margin-bottom: 0px;"
                                                       value="<?php echo set_value('clientadd'); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">申请人</td>
                                            <td><input type="text" class="span2" id="applyby"
                                                       name="applyby" required style="margin-bottom: 0px;"
                                                       value="<?php echo $this->account_info_lib->accountname ?>"> *
                                            </td>
                                            <td style="vertical-align:middle">申请日期</td>
                                            <td><input type="text" class="span2" readonly="" id="applydate" name="applydate" style="margin-bottom: 0px;"
                                                       value="<?php date_default_timezone_set('PRC'); echo date('Y-m-d H:i:s'); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">承诺到货日期</td>
                                            <td>
                                                <div class="input-append date" style="margin-bottom: 0px;" id="commitgetdate_div" data-date="<?php echo date("Y-m-d")?>" data-date-format="yyyy-mm-dd">
                                                    <input id="commitgetdate"  name="commitgetdate" class="span2" size="16" type="text" value="<?php echo date("Y-m-d")?>" readonly>
                                                    <span class="add-on"><i title="点击选择日期" class="icon-calendar"></i></span>
                                                </div>
                                            </td>
                                            <td style="vertical-align:middle">电子邮件</td>
                                            <td><input type="text"  class="span2" id="email"
                                                       name="email" style="margin-bottom: 0px;"
                                                       value="<?php echo $this->account_info_lib->email ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">备注</td>
                                            <td colspan="3"><input type="text" class="span5" id="remark" name="remark" style="margin-bottom: 0px;"
                                                       value="<?php echo set_value('remark'); ?>">
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
                                    <a href="javascript:;" class="btn btn-primary" onclick="del()" ><i class="icon-minus"> 删除订货商品</i></a>
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
                                        <th>#</th>
                                        <th>商品名称</th>
                                        <th>商品代码</th>
                                        <th>厂家</th>
                                        <th>品牌</th>
                                        <th>类别</th>
                                        <th>颜色</th>
                                        <th>商品描述</th>
                                        <th>数量</th>
                                        <th>售价（单价）</th>
                                        <th>备注</th>
                                    </tr>
                                    </thead>
                                    <tbody>
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

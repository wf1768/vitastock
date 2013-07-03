<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>
<style>
    <!--
    .dotables table td {
        height: 30px;
        vertical-align: middle;
    }

    .dotables table td input, .dotables table td select {
        margin-bottom: 0px;
    }

    -->
</style>
<!-- Modal -->
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
                    <a href="<?php echo site_url('buy') ?>" class="path-menu-a"> 销售单管理</a> > <a
                        href="<?php echo site_url('buy/buy_list') ?>" class="path-menu-a"> 销售单管理</a> > 浏览
                </h1>

                <div class="row">
                    <div class="span9">
                        <div class="widget">
                            <div class="widget-header">
                                <h3>添加销售单</h3>
                            </div>
                            <!-- /widget-header -->
                            <div class="widget-content">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#" data-toggle="tab">销售单信息</a>
                                        </li>
                                    </ul>
                                    <br>

                                    <div class="tab-content">
                                        <div class="tab-pane active" id="1">
                                            <form class="dotables" onsubmit="return submitform();"
                                                  action="<?php echo site_url('saleorder/doAddOrder') ?>" method="post">
                                                <input type="hidden" name="createby"
                                                       value="<?php echo $this->account_info_lib->accountname ?>"
                                                       readonly>
                                                <table class="table table-bordered" width="100%">
                                                    <tr>
                                                        <td>销售单编号</td>
                                                        <td>
                                                            <input type="text" id="remark" name="sellnumber"
                                                                   value="" placeholder="自动生成" readonly>
                                                        </td>
                                                        <td>销售者</td>
                                                        <td><input type="text" value="<?php echo $this->account_info_lib->accountname ?>" name="checkby"  required></td>

                                                    </tr>
                                                    <tr>
                                                        <td>销售日期</td>
                                                        <td>
                                                            <div class="input-append date" id="datepic"
                                                                 data-date="<?php echo date("Y-m-d") ?>"
                                                                 data-date-format="yyyy-mm-dd">
                                                                <input name="selldate" class="span2" style="width:176px"
                                                                       size="16" type="text"
                                                                       value="<?php echo date("Y-m-d") ?>" readonly>
                                                                <span class="add-on"><i title="点击选择日期"
                                                                                        class="icon-calendar"></i></span>
                                                            </div>
                                                        </td>
                                                        <td>销售店</td>
                                                        <td>
                                                            <select name="storehouseid">
                                                                <?php foreach ($storehose as $key => $val): ?>
                                                                    <option
                                                                        value="<?php echo $val->id ?>"><?php echo $val->storehousecode?></option>

                                                                <?php endforeach;?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>总价</td>
                                                        <td><input type="text" value="0" id="totalmoney" name="totalmoney"  required  onkeypress="return isfloat(event)"></td>
                                                        <td>折扣总价(RMB)</td>
                                                        <td><input type="text" value="0"
                                                                   onkeypress="return isfloat(event)" onblur="get_lastmoney()" id="discount" name="discount" onkeypress="return isfloat(event)"
                                                                   required></td>
                                                    </tr>
                                                    <tr>
                                                        <td>已付金额(RMB)</td>
                                                        <td><input type="text" value="0" onblur="get_lastmoney()" onkeypress="return isfloat(event)"
                                                                   id="paymoney" name="paymoney" required></td>
                                                        <td>未付金额(RMB)</td>
                                                        <td><input type="text" value="0" id="lastmoney" name="lastmoney" onkeypress="return isfloat(event)"
                                                                   required></td>
                                                    </tr>
                                                    <tr>
                                                        <td>备注</td>
                                                        <td colspan="3"><input type="text" placeholder="请输入备注，也可不填"
                                                                               class="span5" value="" name="remark">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>客户名称</td>
                                                        <td><input type="text" value="" placeholder="请输入客户名称" required
                                                                   name="clientname" ></td>
                                                        <td>客户电话</td>
                                                        <td><input type="text" value="" placeholder="请输入客户电话" required
                                                                   name="clientphone" ></td>
                                                    </tr>
                                                    <tr>
                                                        <td>客户地址</td>
                                                        <td colspan="3"><input type="text" value="" class="span5" required
                                                                               placeholder="请输入客户地址" name="clientadd"
                                                                               ></td>
                                                    </tr>
                                                </table>
                                                <div class="row">
                                                    <div class="span8">
                                                        <label class="pull-left">
                                                            <ul class="nav nav-pills">
                                                                <li class="active"><a href=""
                                                                                      id="delete_product">删除销售商品</a>
                                                                </li>
                                                                <li class="active">
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <input style="margin-top:3px; margin-right:5px"
                                                                           type="text" name="barcode" id="tiaomaadd"
                                                                           value="" placeholder="请输入条形码">

                                                                </li>
                                                                <li class="active">
                                                                    <a href="" id="tiaoadditem">添加商品</a>
                                                                </li>
                                                            </ul>
                                                        </label>
                                                        <label class="pull-right">
                                                            <div class="btn-group">
                                                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="c-666">修改配送方式：</span>
                                                                    <strong>选择</strong>
                                                                    <input type="hidden" name="category" value="娱乐">
                                                                    <b class="caret"></b></a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a href="javascript:;" onclick="edit_sendtype('0')"> 配送</a></li>
                                                                    <li><a href="javascript:;" onclick="edit_sendtype('1')"> 自提</a></li>
                                                                </ul>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="select-all""></th>
                                                        <th>名称</th>
                                                        <th>代码</th>
                                                        <th>描述</th>
                                                        <th>厂家</th>
                                                        <th>条形码</th>
                                                        <th>销售价</th>
                                                        <th>所在店</th>
                                                        <th>配送</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="addcontent">
                                                    <?php $salesprice = 0; foreach ($list as $row): ?>
                                                        <?php $salesprice = $salesprice + $row->salesprice ?>
                                                        <tr id="s<?php echo $row->id ?>">
                                                            <td><input type="checkbox" name="products"
                                                                       value="<?php echo $row->id ?>"/>
                                                                <input type="hidden" name="product[]"
                                                                       value="<?php echo $row->id ?>"/>
                                                            </td>
                                                            <td><?php echo $row->title ?></td>
                                                            <td><?php echo $row->code ?></td>
                                                            <td><?php echo $row->memo ?></td>
                                                            <td><?php echo $row->factoryname ?></td>
                                                            <td><?php echo $row->barcode ?>
                                                            <td>
                                                                <input readonly type="text" class="myaddclass span1"
                                                                       name="price[<?php echo $row->id ?>]"
                                                                       value="<?php echo $row->salesprice ?>"
                                                                       placeholder="留空则为原价格"/>
                                                                <input required type="hidden" class="myaddclass span1"
                                                                       name="price2[<?php echo $row->id ?>]"
                                                                       value="<?php echo $row->salesprice ?>"/>
                                                            </td>
                                                            <td><?php echo product::getStorehouse($row->storehouseid) ?></td>
                                                            <td name="sendtype_<?php echo $row->id ?>"><?php echo $row->sendtype ? '自提' : '配送' ?>
                                                        </tr>
                                                    <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                                <div class="row">
                                                    <div class="span8">
                                                        <label class="pull-right">
                                                            <ul class="nav nav-pills">
                                                                <li class="active">
                                                                    <input class="btn btn-primary" type="submit"
                                                                           value="提交销售单">
                                                                </li>
                                                            </ul>
                                                        </label>

                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /widget-content -->
                        </div>
                        <!-- /widget -->
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

<script>
    var josnData;
    var idAray = new Array();
    var goods = '';
    $(document).ready(function () {
        $('#totalmoney').val('0');
        var salesprice = '<?php echo $salesprice ?>';
        $('#totalmoney').val(parseFloat($('#totalmoney').val()) + parseFloat(salesprice));
        $("#delete_product").click(function () {
            var msg = '确定删除选中数据吗?';
            bootbox.confirm(msg, function (result) {
                if (result) {
                    $('input[name="products"]').filter(":checked").each(function (i, item) {
                        goods += item.value + '||';
                    });
                    if (goods != '') {
                        $.get("<?php echo site_url('product/delProToCart');?>", {id: goods}, function (data) {
                            if (data == 1) {
                                openalert("删除成功");
                                $('input[name="products"]').filter(":checked").each(function (i, item) {
                                    //总价减去删除的商品
                                    var price = parseFloat($("input[name='price["+item.value+"]']").val());
                                    $('#totalmoney').val(parseFloat($('#totalmoney').val()) - price);

                                    $("#s" + item.value).remove();
                                    _unset(item.value);
                                });
                            }
                        });
                    }
                    goods = '';
                    if ($('input[name="products"]').length == 0) {
                        //取消全选
                        $("#select-all").attr("checked", false)
                    }
                }
            });
            return false;
        });
        $('#datepic').datepicker().on('changeDate', function (ev) {
            $(this).datepicker('hide')
        });
        //全选删除
        $("#select-all").click(function () {
            if ($(this).attr("checked")) {
                $("input[name='products']").attr("checked", true)
            } else {
                $("input[name='products']").attr("checked", false)
            }
        });
        $("#tiaomaadd").keydown(function (e) {
            if (e.keyCode == 13) {
                autoAddToBill();
                return false;
            }

        });
        $("#tiaoadditem").click(autoAddToBill);
        function autoAddToBill() {
            var barcode = $("#tiaomaadd").val();
            var listCon = new Array();
            $.getJSON("<?php echo site_url('saleorder/dosearch')?>", {barcode: barcode}, function (data) {
                $.each(data, function (i, item) {
                    if (true) {
                        isHave = _search(item.id);
                        var sendtype = '';
                        if (item.sendtype == 1) {
                            sendtype = '自提';
                        }
                        else {
                            sendtype = '配送';
                        }
                        if (isHave == false) {
                            listCon.push('<tr id="s' + item.id + '">');
                            listCon.push('<td class="table-textcenter">');
                            listCon.push('<input type="checkbox" name="products" value="' + item.id + '"/>');
                            listCon.push('<input type="hidden" name="product[]"  value="' + item.id + '"/></td>');
                            listCon.push('<td class="table-textcenter">' + item.title + '</td>');
                            listCon.push('<td class="table-textcenter">' + item.code + '</td>');
                            listCon.push('<td class="table-textcenter">' + item.memo + '</td>');
                            listCon.push('<td class="table-textcenter">' + item.factoryname + '</td>');
                            listCon.push('<td class="table-textcenter">' + item.barcode + '</td>');
                            listCon.push('<td class="table-textcenter">');
                            listCon.push('<input  readonly type="text" placeholder="留空则为原价格"  class="myaddclass span1" name="price[' + item.id + ']" value="' + item.salesprice + '" />');
                            listCon.push('<input required  type="hidden" class="myaddclass" name="price2[' + item.id + ']" value="' + item.salesprice + '" />');
                            listCon.push('</td>');
                            listCon.push('<td class="table-textcenter">'+item.storehouse+'</td>');
                            listCon.push('<td name="sendtype_'+item.id+'" class="table-textcenter">'+sendtype+'</td>');
                            listCon.push('</tr>');
                            //添加到id 数组 记录
                            idAray.push(item.id);
                            $('#totalmoney').val(parseFloat($('#totalmoney').val()) + parseFloat(item.salesprice));
                        }
                    }
                });
                var stringCon = listCon.join('');
                // $("#addcontent").html('');
                $(stringCon).appendTo("#addcontent");
                $("#tiaomaadd").val('');
            });
            return false;
        }
    });

    function submitform() {
        if (!confirm("确定要提交销售单吗？注意：因销售单总价，是通过销售商品的售价加减的，可能出现错误，请谨慎操作。")) {
            return false;
        }
    }

    //计算余额
    function get_lastmoney() {
        var paymoney = $('#paymoney').val();
        var lastmoney = $('#lastmoney').val();
        var discount = $('#discount').val();
        var totalmoney = $('#totalmoney').val();

        if (parseFloat(discount) > parseFloat(totalmoney)) {
            openalert('您输入的折扣总价，大于总价，这不符合逻辑');
            $('#discount').val('0');
            return;
        }

        if (paymoney == '') {
            $('#paymoney').val('0');
        }


        if (parseFloat(paymoney) > parseFloat(discount)) {
            openalert('您输入的已付金额，大于折扣总价，这不符合逻辑');
            $('#paymoney').val('0');
            var paymoney = $('#paymoney').val();
            $('#lastmoney').val(parseFloat(discount) - parseFloat(paymoney));
        }
        else {
            $('#lastmoney').val(parseFloat(discount) - parseFloat(paymoney));
        }

    }

    //修改销售商品的配送方式
    function edit_sendtype(sendtype) {
        if (sendtype == '') {
            return;
        }

        if( $('input[name="products"]').filter(":checked").length==0){
            openalert("请选择要修改配送方式的销售商品。");
            return false;
        }
        if (sendtype == '1') {
            var typecode = '自提';
            var msg='确定修改选中数据配送方式为［自提］吗?<br /> <span style="color: red">注意：销售商品为自提，表示商品客户自己取货，在配送环节，将不再配送。</span> ';
        }
        else {
            var typecode = '配送';
            var msg='确定修改选中数据配送方式为需要［配送］吗?';
        }
        bootbox.confirm(msg, function(result) {
            if(result){
                var str = "";
                $("input[name='products']").each(function () {
                    if ($(this).attr("checked") == 'checked') {
                        str += $(this).val() + ",";
                    }
                })
                if (str == "") {
                    openalert('请选择要修改配送方式的销售商品。');
                    return;
                }
                str = str.substring(0, str.length - 1);

                $.ajax({
                    type: "post",
                    data: "ids=" + str + "&sendtype=" + sendtype,
                    url: "<?php echo site_url('stock/update_stock_sendtype')?>",
                    success: function (data) {
                        if (data) {
                            //如果保存通过，不刷新修改商品配送方式
                            $("input[name='products']").each(function () {
                                if ($(this).attr("checked") == 'checked') {
                                    $("td[name='sendtype_"+$(this).val()+"']").html(typecode);
                                }
                            })

                            $("#select-all").attr("checked",false);
                            $("input[name='products']").each(function () {
                                $(this).attr("checked",false);
                            })
//                            $("td[name='sendtype']").html(typecode);
                        }
                        else {
                            openalert("修改商品配送方式出错，请重新尝试或与管理员联系。");
                        }
                    },
                    error: function () {
                        openalert("执行操作出错，请重新尝试或与管理员联系。");
                    }
                });
            }
        });

    }

    function _search(key) {
        var m = idAray.length
        for (i = 0; i < m; i++) {
            if (idAray[i] == key) {
                return true;
            }
        }
        return false;
    }

    function _unset(key){
        var m=idAray.length
        for(i=0;i<m;i++){
            if(idAray[i]==key){
                idAray[i]='';
            }
        }
    }
</script>
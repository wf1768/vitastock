<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<script>

    function create_buy(id) {

        var str="";
        $("input[name='checkbox']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str != "") {
            str = str.substring(0,str.length-1);
        }

        bootbox.confirm("确定要将当前期货订单生成采购单吗？", function(result) {
            if(result){

                $.ajax({
                    type:"post",
                    data: "id=" + id + "&select_content_id=" + str,
                    url:"<?php echo site_url('apply/create_buy')?>",
                    success: function(data){
                        if (data) {
                            window.location.reload();
                        }
                        else {
                            openalert('生成采购单出错，请重新尝试或与管理员联系。');
                        }
                    },
                    error: function() {
                        openalert('执行操作出错，请重新尝试或与管理员联系。');
                    }
                });
            }
        })
    }

    function open_add_apply_deal_dialog() {
        var str="";
        $("input[name='checkbox']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择要处理的期货订单商品。');
            return;
        }
        str = str.substring(0,str.length-1);
        $('#select_content_id').val(str);
        $('#add_apply_content_deal_dialog').modal('show');
    }

    function open_apply_content_deal_dialog(id) {

        //ajax读取处理进度
        $.ajax({
            type:"get",
            data: "id=" + id + '&stype=<?php echo $stype ?>',
            url:"<?php echo site_url('apply/read_apply_content_deal')?>",
            success: function(data){
                if (data) {
                    var result = eval(data);
//                    var aa = str[0];
                    var str = '';
                    for(var i=0;i<result.length;i++) {
                        str += '<tr>';
                        str += '<td>'+ (i+1) + '</td>';
                        str += '<td>' + result[i].dealtime + '</td>';
                        str += '<td>' + result[i].dealby + '</td>';
                        str += '<td>' + result[i].value + '</td>';
                        str += '<td>' + result[i].remark + '</td>';
                        str += '</tr>';
                    }
                    $('#apply_content_deal').html(str);
                }
                else {
                    openalert('当前订单已生成过采购单或生成采购单出错，请重新尝试或与管理员联系。');
                }
            },
            error: function() {
                openalert('执行操作出错，请重新尝试或与管理员联系。');
            }
        });
        $('#apply_content_deal_dialog').modal('show');
    }


    //处理订单商品状态
    function add_apply_content_deal() {
        var stype = '确定要处理订单商品进度吗？';

        bootbox.confirm(stype, function(result) {
            if(result){
                var form_data = $('#add_apply_content_deal').serialize();

                $.ajax({
                    type:"post",
                    data: form_data,
                    url:"<?php echo site_url('apply/add_apply_content_deal')?>",
                    success: function(data){
                        if (data) {
                            $("input[name='checkbox']").attr("checked",false);
                            window.location.reload();
                        }
                        else {
                            openalert('处理订单商品出错，请重新尝试或与管理员联系。');
                        }
                    },
                    error: function() {
                        openalert('执行操作出错，请重新尝试或与管理员联系。');
                    }
                });
            }
        })

    }

    //处理订单状态
    function add_apply_deal(applyid,key) {
        if (applyid == '' || key == '') {
            openalert('执行操作出错，请重新尝试或与管理员联系。');
            return;
        }
        var stype = '<?php echo $stype ?>';
        if (stype == 'financial') {
            stype = "确定要审核通过当前订单吗？";
        }
        else {
            stype = "确定要结束订单吗？<br> <font color='red'>注意：结束期货订单将不能在修改，请谨慎操作。</font>";
        }
        bootbox.confirm(stype, function(result) {
            if(result){
                $.ajax({
                    type:"post",
                    data: 'applyid='+applyid+'&key='+key,
                    url:"<?php echo site_url('apply/add_apply_deal')?>",
                    success: function(data){
                        if (data) {
                            window.location.reload();
                        }
                        else {
                            openalert('处理订单出错，请重新尝试或与管理员联系。');
                        }
                    },
                    error: function() {
                        openalert('执行操作出错，请重新尝试或与管理员联系。');
                    }
                });
            }
        })

    }

    //显示订单商品
    function show_apply_content(id) {
        if (id == '') {
            openalert('执行操作出错，请重新尝试或与管理员联系。');
            return;
        }
        var stype = '<?php echo $stype ?>';
        var data= 'id='+id + '&stype=' + stype;
        $.getJSON("<?php echo site_url('apply/show_apply_content')?>",data,function(data){
            josnData=data;
            $.each(data, function(i,item){
                $('#title').html(item.title);
                $('#code').html(item.code);
                $('#factory').html(item.factoryname);
                $('#brand').html(item.brandname);
                $('#type').html(item.typename);
                $('#color').html(item.color);
                $('#memo').html(item.memo);
                $('#number').html(item.number);
                $('#salesprice').html(item.salesprice);
                $('#remark_content').html(item.remark);
            })
        })
        $('#apply-content-dialog').modal('show');
    }

    //显示入库商品
    function show_stock_content(id) {
        if (id == '') {
            openalert('执行操作出错，请重新尝试或与管理员联系。');
            return;
        }
        var stype = '<?php echo $stype ?>';
        var data= 'id='+id + '&stype=' + stype;
        $.getJSON("<?php echo site_url('apply/show_stock_content')?>",data,function(data){
            josnData=data;
            $.each(data, function(i,item){
                $('#stock_title').html(item.title);
                $('#stock_code').html(item.code);
                $('#stock_factory').html(item.factoryname);
                $('#stock_brand').html(item.brandname);
                $('#stock_type').html(item.typename);
                $('#stock_color').html(item.color);
                $('#stock_memo').html(item.memo);
                $('#stock_number').html(item.number);
                $('#stock_barcode').html(item.barcode);
                $('#stock_storehouse').html(item.storehouse);
                $('#stock_salesprice').html(item.salesprice);
                $('#stock_remark').html(item.remark);
            })
        })
        $('#stock-dialog').modal('show');
    }

    /**
     * 订单处理人员，有权移除已入库的订购商品。例如订购商品有损坏之类的。换成其他商品
     */
    function remove_stock(id) {
        if (id == '') {
            openalert('执行操作出错，请重新尝试或与管理员联系。');
            return;
        }
<!--        var stype = '--><?php //echo $stype ?><!--';-->

        bootbox.confirm("确认要移除选择的商品吗？<br /> <span style='color: red'>被移除的商品状态变为［在库］。不再被与当前期货订单关联。</span>", function(result) {
            if(result){
                $.ajax({
                    type:"post",
                    data: 'id='+id,
                    url:"<?php echo site_url('apply/remove_stock')?>",
                    success: function(data){
                        if (data) {
                            alert('商品移除成功。')
                            window.location.reload();
                        }
                        else {
                            openalert('处理出错，请重新尝试或与管理员联系。');
                        }
                    },
                    error: function() {
                        openalert('执行操作出错，请重新尝试或与管理员联系。');
                    }
                });
            }
        })
    }

    function handle_create_order(id) {
        if (id == "") {
            return;
        }

        var str="";
        $("input[name='checkbox_stock']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择生成销售合同单的期货商品。');
            return;
        }
        str = str.substring(0,str.length-1);

        bootbox.confirm("确定要将选中的期货商品生成销售合同单吗？<br><font color='red'>注意：选择的期货商品生成销售合同单，经财务审批后，直接办理送货。", function(result) {
            if(result){
                window.location.href= '<?php echo site_url() ?>/apply/cteate_sell?id=' + id + '&contentid=' + str;
            }
        })

    }

    $(function() {

        $("#select-all").click(function(){
            if ($(this).attr("checked") == 'checked') {
                $("input[name='checkbox']").attr("checked",$(this).attr("checked"));
            }
            else {
                $("input[name='checkbox']").attr("checked",false);
            }
        });

        $("#select-all-stock").click(function(){
            if ($(this).attr("checked") == 'checked') {
                $("input[name='checkbox_stock']").attr("checked",$(this).attr("checked"));
            }
            else {
                $("input[name='checkbox_stock']").attr("checked",false);
            }
        });

        //订单处理人员，通过条码添加商品
        $("#barcode").keydown(function(e){
            if(e.keyCode==13){
                var barcode = $('#barcode').val();
                var applyid = $('#applyid').val();
                if (barcode !='') {
                    $.ajax({
                        type:"post",
                        data: 'barcode='+barcode + '&applyid=' + applyid,
                        url:"<?php echo site_url('apply/add_stock')?>",
                        success: function(data){
                            if (data) {
                                alert('商品添加成功。')
                                window.location.reload();
                            }
                            else {
                                openalert('添加失败。<br /> 有以下可能造成：<br /> 1.商品状态不是［在库］。<br /> 2.商品已在当前期货订单下。<br /> 3.没有找到要添加d商品。');
                            }
                        },
                        error: function() {
                            openalert('执行操作出错，请重新尝试或与管理员联系。');
                        }
                    });
                }

            }
        });

        $('#add_apply_content_deal_dialog').on('hidden', function () {
            window.location.reload();
        })
        $('#datepic').datepicker().on('changeDate', function(ev) {
            $(this).datepicker('hide')
        });
        $('#datepic').datepicker().on('show', function(ev) {
            $('.datepicker').css('z-index','1052');
        });

        $("a[data-toggle=popover]").popover();
    })

    function onPrint() {
        $(".my_show").jqprint({
            importCSS:false,
            debug:false
        });
    }
</script>
<!-- Modal -->
<div id="add_apply_content_deal_dialog" class="modal hide fade" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        订单商品处理
    </div>
    <div class="modal-body">
        <form id="add_apply_content_deal" method="post" action="">
            <input type="hidden" id="select_content_id" name="select_content_id">
            <input type="hidden" id="applyid" name="applyid" value="<?php echo $row[0]->id ?>">
        <table class="table table-striped table-bordered" width="100%">
            <tr>
                <td>处理时间</td>
                <td><input type="text" readonly="" class="span2" id="dealtime" name="dealtime" style="margin-bottom: 0px;"
                           value="<?php date_default_timezone_set('PRC'); echo date('Y-m-d H:i:s'); ?>">
                </td>
            </tr>
            <tr>
                <td>预计到港日期</td>
                <td>
                    <div class="input-append date" id="datepic" data-date="<?php echo date("Y-m-d")?>" data-date-format="yyyy-mm-dd">
                        <input id="forecastgetdate" name="forecastgetdate" class="span2" style="width:176px" size="16" type="text" value="<?php echo date("Y-m-d")?>" readonly>
                        <span class="add-on"><i title="点击选择日期" class="icon-calendar"></i></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>经办人</td>
                <td><input type="text" class="span2" id="dealby"
                           name="dealby" readonly="" required style="margin-bottom: 0px;"
                           value="<?php echo $this->account_info_lib->accountname ?>">
                    <input type="hidden" id="dealbyid" name="dealbyid" value="<?php echo $this->account_info_lib->id ?>" >
                </td>
            </tr>
            <tr>
                <td>处理进度</td>
                <td>
                    <select class="span2" id="apply_content_status" name="apply_content_status" style="margin-bottom: 0px;">
                        <?php if($apply_content_status):?>
                            <?php foreach($apply_content_status as $s):?>
                                <option value="<?php echo $s->id;?>" ><?php echo $s->svalue;?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select><br>
                    <font color="red">注意：已入库后，订单商品将不能再处理和生成采购单。</font>
                </td>
            </tr>
            <tr>
                <td>备注</td>
                <td><input type="text" class="span4" id="remark" name="remark" style="margin-bottom: 0px;"
                           value="">
                </td>
            </tr>
        </table>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a href="javascript:;" onclick="add_apply_content_deal()" class="btn btn-primary">处理</a>
    </div>
</div>

<!-- Modal -->
<div id="apply_content_deal_dialog" class="modal hide fade" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        订单商品处理进度
    </div>
    <div class="modal-body">
        <!-- /widget-header -->
        <div class="widget-content">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>处理时间</th>
                    <th>经办人</th>
                    <th>进度</th>
                    <th>备注</th>
                </tr>
                </thead>
                <tbody id='apply_content_deal'>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
<!--        <a href="javascript:;" onclick="add_apply_content_deal()" class="btn btn-primary">处理</a>-->
    </div>
</div>

<!-- Modal -->
<div id="apply-content-dialog" class="modal hide fade" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        订货商品详细
    </div>
    <div class="modal-body">
        <table class="table table-striped table-bordered">
            <tbody>
            <tr>
                <td style="vertical-align:middle">商品名称</td>
                <td id="title"></td>
                <td style="vertical-align:middle">商品代码</td>
                <td id="code"></td>
            </tr>
            <tr>
                <td style="vertical-align:middle">厂家</td>
                <td id="factory"></td>
                <td style="vertical-align:middle">品牌</td>
                <td id="brand"></td>
            </tr>
            <tr>
                <td style="vertical-align:middle">类别</td>
                <td id="type"></td>
                <td style="vertical-align:middle">颜色</td>
                <td id="color"></td>
            </tr>
            <tr>
                <td style="vertical-align:middle">商品描述</td>
                <td colspan="3" id="memo"></td>
            </tr>
            <tr>
                <td style="vertical-align:middle">数量</td>
                <td id="number"></td>
                <td style="vertical-align:middle">售价（单价）</td>
                <td id="salesprice"></td>
            </tr>
            <tr>
                <td style="vertical-align:middle">备注</td>
                <td colspan="3" id="remark_content"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    </div>
</div>

<!-- Modal -->
<div id="stock-dialog" class="modal hide fade" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        订货商品入库信息详细
    </div>
    <div class="modal-body">
        <table class="table table-striped table-bordered">
            <tbody>
            <tr>
                <td style="vertical-align:middle">商品名称</td>
                <td id="stock_title"></td>
                <td style="vertical-align:middle">商品代码</td>
                <td id="stock_code"></td>
            </tr>
            <tr>
                <td style="vertical-align:middle">厂家</td>
                <td id="stock_factory"></td>
                <td style="vertical-align:middle">品牌</td>
                <td id="stock_brand"></td>
            </tr>
            <tr>
                <td style="vertical-align:middle">类别</td>
                <td id="stock_type"></td>
                <td style="vertical-align:middle">颜色</td>
                <td id="stock_color"></td>
            </tr>
            <tr>
                <td style="vertical-align:middle">商品描述</td>
                <td colspan="3" id="stock_memo"></td>
            </tr>
            <tr>
                <td style="vertical-align:middle">库房</td>
                <td id="stock_storehouse"></td>
                <td style="vertical-align:middle">条形码</td>
                <td id="stock_barcode"></td>
            </tr>
            <tr>
                <td style="vertical-align:middle">数量</td>
                <td id="stock_number"></td>
                <td style="vertical-align:middle">售价（单价）</td>
                <td id="stock_salesprice"></td>
            </tr>
            <tr>
                <td style="vertical-align:middle">备注</td>
                <td colspan="3" id="stock_remark"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
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
                    <?php if ($stype == 'apply') : ?>
                        <a href="<?php echo site_url('buy') ?>" class="path-menu-a"> 采购管理</a> > <a href="<?php echo site_url('apply/pages?status='.$status.'&stype='.$stype.'&p='.$p) ?>" class="path-menu-a"> 期货订单管理</a> > 浏览
                    <?php elseif ($stype == 'sale') : ?>
                        <a href="javascript:;" class="path-menu-a"> 销售管理</a> > 期货订单管理 > 浏览
                    <?php elseif ($stype == 'financial') : ?>
                        <a href="javascript:;" class="path-menu-a"> 财务管理</a> > 期货订单审核 > 浏览
                    <?php endif ?>

                </h1>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>期货订单信息</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                    <?php if ($row) : ?>
                        <table class="table table-striped table-bordered" width="100%">
                            <tr>
                                <td>订单编号</td>
                                <td colspan="3"><?php echo $row[0]->applynumber ?></td>
                            </tr>
                            <tr>
                                <td>销售店</td>
                                <td><?php echo $row[0]->storehousecode ?></td>
                                <td>销售日期</td>
                                <td><?php echo strtotime($row[0]->selldate)?$row[0]->selldate:''; ?></td>
                            </tr>
                            <tr>
                                <td>销售总价（RMB）</td>
                                <td><?php echo $row[0]->totalmoney ?></td>
                                <td>折后总价（RMB）</td>
                                <td><?php echo $row[0]->discount ?></td>
                            </tr>
                            <tr>
                                <td>已付款（RMB）</td>
                                <td><?php echo $row[0]->paymoney ?></td>
                                <td>余款（RMB）</td>
                                <td><?php echo $row[0]->lastmoney ?></td>
                            </tr>
                            <tr>
                                <td>客户名称</td>
                                <td><?php echo $row[0]->clientname ?></td>
                                <td>客户电话</td>
                                <td><?php echo $row[0]->clientphone ?></td>
                            </tr>
                            <tr>
                                <td>客户地址</td>
                                <td colspan="3"><?php echo $row[0]->clientadd ?></td>
                            </tr>
                            <tr>
                                <td>申请人</td>
                                <td><?php echo $row[0]->applyby ?></td>
                                <td>申请日期</td>
                                <td><?php echo strtotime($row[0]->applydate)?$row[0]->applydate:''; ?></td>
                            </tr>

                            <tr>
                                <td>承诺到货日期</td>
                                <td><?php echo strtotime($row[0]->commitgetdate)?$row[0]->commitgetdate:''; ?></td>
                                <td>折扣率</td>
                                <td><?php
                                    if ($row[0]->discount >0 && $row[0]->totalmoney >0) {
                                        echo ((round($row[0]->discount/$row[0]->totalmoney, 2))*100).'%';
                                    }
                                    else {
                                        echo '0%';
                                    }
                                    ?></td>
                            </tr>

                            <tr>
                                <td>经办人</td>
                                <td><?php echo $row[0]->checkby ?></td>
                                <td>状态</td>
                                <td><?php
                                    if ($row[0]->status == 1) {
                                        echo '<font color="red">'.$row[0]->statusvalue.'</font>';
                                    }
                                    else if ($row[0]->status == 2 || $row[0]->status == 3) {
                                        echo '<font color="blue">'.$row[0]->statusvalue.'</font>';
                                    }
                                    else if ($row[0]->status == 4) {
                                        echo '<font color="green">'.$row[0]->statusvalue.'</font>';
                                    }
                                    else {

                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>备注</td>
                                <td colspan="3"><?php echo $row[0]->remark ?></td>
                            </tr>
                        </table>
                    <?php endif ?>
                    </div>
                    <!-- /widget-content -->
                </div>
                <?php if ($stype == 'sale') :?>
                <div class="row">
                    <div class="span9">
                        <label class="pull-left">
                            <a href="javascript:;" class="btn btn-primary" onclick="onPrint()" ><i class="icon-print"> 打印期货销售单</i></a>
                        </label>
                    </div>
                </div>
                <?php endif ?>
                <?php if ($row[0]->status < 4 && $oper) : ?>
                    <div class="row">
                        <div class="span9">
                            <label class="pull-left">
<!--                                --><?php //if ($stype == 'sale') :?>
<!--                                <a href="javascript:;" class="btn btn-primary" onclick="print_apply()" ><i class="icon-print"> 订单商品处理</i></a>-->
<!--                                --><?php //endif ?>
                                <?php if ($stype == 'apply' && ($row[0]->status == 2 || $row[0]->status == 3)) :?>
                                <a href="javascript:;" class="btn btn-primary" onclick="open_add_apply_deal_dialog()" ><i class="icon-plus"> 订单商品处理</i></a>
                                <a href="javascript:;" class="btn btn-primary" onclick="create_buy('<?php echo $row[0]->id ?>')" ><i class="icon-ok"> 生成采购单</i></a>
                                <a href="javascript:;" class="btn btn-primary" onclick="add_apply_deal('<?php echo $row[0]->id ?>','4')" ><i class="icon-ok"> 结束订单</i></a>
                                <a href="javascript:;" class="btn btn-primary" onclick="onPrint()" ><i class="icon-print"> 打印期货销售单</i></a>
                                <?php endif ?>
                                <?php if ($stype == 'financial' && $row[0]->status == 1) : ?>
                                <a href="javascript:;" class="btn btn-primary" onclick="add_apply_deal('<?php echo $row[0]->id ?>','2')" ><i class="icon-ok"> 财务审核</i></a>
                                <a href="javascript:;" class="btn btn-primary" onclick="onPrint()" ><i class="icon-print"> 打印期货销售单</i></a>
                                <?php endif ?>
                            </label>
                        </div>
                    </div>
                <?php endif ?>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>订单商品信息</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all""></th>
                                <th>#</th>
                                <th>名称</th>
                                <th>代码</th>
                                <th>厂家</th>
                                <th>描述</th>
                                <th>数量</th>
                                <th>售价（单价）</th>
                                <th>经办人</th>
                                <th>预计到港日期</th>
                                <th>状态</th>
                                <th>处理</th>
                                <th>查看</th>
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
                                        <td><?php echo $apply->salesprice; ?></td>
                                        <td><?php echo $apply->checkby; ?></td>
                                        <td><?php echo strtotime($apply->forecastgetdate)?$apply->forecastgetdate:''; ?></td>
                                        <td><?php echo $apply->statusvalue; $n++?></td>
                                        <td><a href="javascript:;" class="btn btn-small btn-info" onclick="open_apply_content_deal_dialog('<?php echo $apply->id ?>')" >详细</a></td>
                                        <td><a href="javascript:;" class="btn btn-small btn-info" onclick="show_apply_content('<?php echo $apply->id ?>')">查看</a></td>
                                    </tr>
                                <?php  endforeach;?>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /widget-content -->
                </div>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3> 审核意见列表</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th> 审核时间</th>
                                <th> 收款金额</th>
                                <th> 余款</th>
                                <th> 审核人</th>
                                <th> 审核意见</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($finance_check as $check):?>
                                <tr>
                                    <td><?php echo $check->financetime ?></td>
                                    <td><?php echo $check->paymoney ?></td>
                                    <td><?php echo $check->lastmoney ?></td>
                                    <td><?php echo $check->financeman ?></td>
                                    <td><?php echo $check->remark ?></td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div> <!-- /widget-content -->
                </div> <!-- /widget -->
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>订单处理进度</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>处理时间</th>
                                <th>处理结果</th>
                                <th>经办人</th>
                                <th>备注</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($apply_deal)):?>
                                <?php $n = 1; foreach($apply_deal as $deal):?>
                                    <tr>
                                        <td><?php echo $n ?></td>
                                        <td><?php echo $deal->dealtime; ?></td>
                                        <td><?php echo $deal->dealstatusvalue ?></td>
                                        <td><?php echo $deal->dealby;  ?></td>
                                        <td><?php echo $deal->remark;  $n++?></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /widget-content -->
                </div>
                <?php if ($oper) : ?>
                    <div class="widget widget-table">
                        <div class="widget-header">
                            <i class="icon-th-list"></i>
                            <h3>订单生成采购单</h3>
                        </div>
                        <!-- /widget-header -->
                        <div class="widget-content">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>采购单编号</th>
                                    <th>采购负责人</th>
                                    <th>采购日期</th>
                                    <th>采购单状态</th>
                                    <th>采购单来源（订货单号）</th>
                                    <th>备注</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(isset($buys)):?>
                                    <?php $n = 1; foreach($buys as $buy):?>
                                        <tr>
                                            <td><?php echo $n ?></td>
                                            <td><a href="<?php echo site_url('buy/show?id='.$buy->id) ?>"><?php echo $buy->buynumber ?></a></td>
                                            <td><?php echo $buy->buyman ?></td>
                                            <td><?php echo $buy->buydate ?></td>
                                            <td><?php echo ($buy->status == 0)?'<font color="red">未结束</font>':'已入库' ?></td>
                                            <td><?php echo $buy->applynumber ?></td>
                                            <td><?php echo $buy->remark;  $n++?></td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /widget-content -->
                    </div>
                <?php endif ?>
                <?php if ($stype == 'sale' && $row[0]->status < 4) :?>
                    <div class="row">
                        <div class="span9">
                            <label class="pull-left">
                                <a href="javascript:;" class="btn btn-primary" onclick="handle_create_order('<?php echo $row[0]->id ?>')" ><i class="icon-list"> 生成销售单</i></a>
                            </label>
                        </div>
                    </div>
                <?php endif ?>
                <?php if ($stype == 'apply' && $row[0]->status < 4) : ?>
                    <div class="row">
                        <div class="span8">
                            <label class="pull-right">
                                <ul class="nav nav-pills">
                                    <input style="margin-top:3px; margin-right:5px; margin-left:30px" type="text" name="barcode"  id="barcode" value="" placeholder="请输入条形码">
                                    <a href="javascript:;" class="btn btn-primary">添加商品</a>
                                </ul>
                            </label>
                        </div>
                    </div>
                <?php endif ?>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>订单商品入库情况</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all-stock""></th>
                                <th>序号</th>
                                <th>缩略图</th>
                                <th>名称</th>
                                <th>代码</th>
                                <th>描述</th>
                                <th>厂家</th>
                                <th>条形码</th>
                                <td>售价</td>
                                <th>库房</th>
                                <th>状态</th>
                                <?php if ($stype == 'apply') : ?>
                                    <th>操作</th>
                                <?php endif ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($stock): ?>
                                <?php $num=1; foreach ($stock as $stock_row): ?>
                                    <tr>
                                        <?php
                                            if ($stock_row->statuskey == 3 || $stock_row->statuskey == 4) {
                                                echo '<td></td>';
                                            }
                                            else {
                                                echo '<td><input type="checkbox" name="checkbox_stock" value="'.$stock_row->id.'"/></td>';
                                            }
                                        ?>
                                        <td><?php echo $num; ?></td>
                                        <td><a href="javascript:;" data-html="true" data-trigger="hover"
                                               data-toggle="popover"
                                               data-content="<img src='<?php echo base_url($stock_row->picpath) ?>' />"
                                               ><img
                                                    src="<?php echo base_url($stock_row->picpath) ?>" alt=""
                                                    class="thumbnail smallImg"/></a></td>
                                        <td>
                                            <a href="javascript:;" onclick="show_stock_content('<?php echo $stock_row->id ?>')"><?php echo $stock_row->title ?></a>
                                        </td>
                                        <td title="<?php echo $stock_row->code ?>"><?php echo Common::subStr($stock_row->code, 0, 10) ?></td>
                                        <td title="<?php echo $stock_row->memo ?>"><?php echo Common::subStr($stock_row->memo, 0, 20) ?></td>
                                        <td><?php echo $stock_row->factoryname ?></td>
                                        <td><?php echo $stock_row->barcode ?></td>
                                        <td><?php echo $stock_row->salesprice;$num++; ?></td>
                                        <td><?php
                                            foreach ($storehouse as $house) {
                                                if ($stock_row->storehouseid == $house->id) {
                                                    echo $house->storehousecode;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $stock_row->statusvalue ?></td>
                                        <?php if ($stype == 'apply') : ?>
                                            <?php if ($stock_row->statuskey == 10 && $row[0]->status != 4) : ?>
                                                    <td><a class="btn btn-mini btn-primary"
                                                           href="javascript:;" onclick="remove_stock('<?php echo $stock_row->id ?>')">移除</a>
                                                    </td>
                                            <?php else :?>
                                                    <td></td>
                                            <?php endif ?>
                                        <?php endif ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /widget-content -->
                </div>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>订单商品生成销售合同单</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th> 销售单编号</th>
                                <th> 销售日期</th>
                                <th> 客户名称</th>
                                <th> 销售者</th>
                                <th> 总价</th>
                                <th> 折扣总价</th>
                                <th> 已付金额</th>
                                <th> 余额</th>
                                <th> 销售库房</th>
                                <th> 状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($sells as $row):?>
                                <tr>
                                    <td><?php echo $row->sellnumber ?></td>
                                    <td><?php echo $row->selldate ?></td>
                                    <td><?php echo $row->clientname ?></td>
                                    <td><?php echo $row->checkby ?></td>
                                    <td><?php echo $row->totalmoney ?></td>
                                    <td><?php echo $row->discount ?></td>
                                    <td><?php echo $row->paymoney ?></td>
                                    <td><?php echo $row->lastmoney ?></td>
                                    <td><?php echo $row->storehousecode ?></td>
                                    <td><?php $status=array('0'=>'待审核','1'=>'退单','2'=>'已审核','3'=>'已配送','6'=>'期货部分配送');echo $status[$row->status] ?></td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /widget-content -->
                </div>
            </div>
            <!-- /span9 -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div> <!-- /content -->
<?php $this->load->view("common/footer"); ?>
<style>
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
</style>



<div style="height:0px;width:0px;overflow:hidden">
    <?php for ($i=0;$i<ceil(count($apply_content)/10);$i++) : ?>
    <div class="my_show" style="page-break-after: always;">
        <style>
            .print_font {
                font-size: 10px;
                margin:0px 0px 10px 0px;
            }
            .tr_height {
                height: 20px;
            }
            .foorer_font {
                font-size: 12px;
                font-weight:blod;
            }

            .content_tr {}

            .content_tr td {
                height: 10px;
                overflow: hidden;
                font-size: 12px;
            }
        </style>
        <table  cellspacing="0" style="border-collapse: collapse; border-spacing: 0;background-color: transparent;max-width: 100%" cellpadding="0" width="100%" class="print_font">
            <thead>
            <tr>
                <th colspan="7">
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tr align="center">
                            <td style="text-align: right; width: 150px;"><img src='<?php echo base_url('public/img/logo.jpg') ?>' style="width: 90px;"></td>
                            <td style="text-align:left; font-size: 26px;padding-bottom: 5px;padding-top: 0px">&nbsp;&nbsp;北京丰意德工贸有限公司销售合同</td>
                        </tr>
                        <tr align="center">
                            <td colspan="2" style="font-size: 14px;padding-bottom: 2px;padding-top: 0px">(期&nbsp;&nbsp;&nbsp;&nbsp;货)</td>
                        </tr>
                    </table>
                    <table border="0" cellspacing="0" cellpadding="0" width="100%" class="print_font">
                        <tr>
<!--                            <td style="width: 40%"></td>-->
                            <td colspan="2" style="font-size:14px;text-align: right">合同编号：<span style="font-size: 20px;color: red;"><?php echo $row[0]->applynumber;?></span></td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px;">一.&nbsp;&nbsp;买方决定购买卖方提供的如下</td>
                            <td style="font-size:14px;text-align: right">销售店：<?php echo $row[0]->storehousecode ?> 签订日期：<?php echo $row[0]->applydate; ?></td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr style="border:1px #000 solid;text-align: center;height: 40px">
                <th style="border:1px #000 solid;">序号</th>
                <th style="border:1px #000 solid;">生产厂家</th>
                <th style="border:1px #000 solid;">产品名称</th>
                <th style="border:1px #000 solid;">数量</th>
                <th style="border:1px #000 solid;">条形码</th>
                <th style="border:1px #000 solid;">单价(RMB)</th>
            </tr>
            </thead>
            <tbody >
            <?php for ($j=$i*10;$j<$i*10+10;$j++) :?>
                <?php if ($j<count($apply_content)) :?>
                <tr class="content_tr" style="height:30px;border:1px #000 solid;text-align: center">
                    <td style="border:1px #000 solid;"><?php echo $j+1; ?></td>
                    <td style="border:1px #000 solid;"><?php echo $apply_content[$j]->factoryname ?></td>
                    <td style="border:1px #000 solid;"><?php echo $apply_content[$j]->title ?></td>
                    <td style="border:1px #000 solid;"><?php echo $apply_content[$j]->number ?></td>
                    <td style="border:1px #000 solid;"></td>
                    <td style="border:1px #000 solid;"><?php echo $apply_content[$j]->salesprice; ?></td>
                </tr>
                <?php else :?>
                    <tr class="content_tr" style="height:30px;border:1px #000 solid;text-align: center">
                        <td style="border:1px #000 solid;"></td>
                        <td style="border:1px #000 solid;"></td>
                        <td style="border:1px #000 solid;"></td>
                        <td style="border:1px #000 solid;"></td>
                        <td style="border:1px #000 solid;"></td>
                        <td style="border:1px #000 solid;"></td>
                    </tr>
                <?php endif ?>
            <?php endfor ?>
            <tr class="content_tr" style="height:30px;border:1px #000 solid;text-align: center">
                <td style="border:1px #000 solid;"></td>
                <td style="border:1px #000 solid;">总价(RMB)</td>
                <td style="border:1px #000 solid;"></td>
                <td style="border:1px #000 solid;"></td>
                <td style="border:1px #000 solid;"></td>
                <td style="border:1px #000 solid;">
                    <?php
                    if ($i+1 == ceil(count($apply_content)/10)) {
                        echo $row[0]->totalmoney;
                    }
                    else {
                        echo '';
                    }
                    ?>
                </td>
            </tr>
            <tr class="content_tr" style="height:30px;border:1px #000 solid;text-align: center">
                <td style="border:1px #000 solid;"></td>
                <td style="border:1px #000 solid;">折扣价(RMB)</td>
                <td style="border:1px #000 solid;"></td>
                <td style="border:1px #000 solid;"></td>
                <td style="border:1px #000 solid;"></td>
                <td style="border:1px #000 solid;">
                    <?php
                    if ($i+1 == ceil(count($apply_content)/10)) {
                        if ($row[0]->totalmoney == 0) {
                            echo $row[0]->discount .'(折扣率:0%)';
                        }
                        else {
                            echo $row[0]->discount .'(折扣率:'.((round($row[0]->discount/$row[0]->totalmoney, 2))*100).'%'.')';
                        }

                    }
                    else {
                        echo '见尾页';
                    }
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" width="100%" class="print_font">
            <tr class="tr_height">
                <td valign="top" style="width: 25px;line-height:1.5em;" >二.</td>
                <td valign="top" style="line-height:1.5em;">付款方式：买方需于签订合同当日交纳合同总额的50%作为订金，在送货之前付清余款，如付支票卖方在银行兑现之后送货。买方订购期货时，只有买方按合同规定交付足额定金后卖方才开始执行本合同。
                </td>
            </tr>
            <tr class="tr_height">
                <td style="width: 15px;line-height:1.5em;"></td>
                <td style="font-size: 12px;">首付(RMB)：<span style="text-decoration:underline;"><?php echo $row[0]->paymoney?></span> 余款(RMB)： <span style="text-decoration:underline;"><?php echo $row[0]->lastmoney?></span> 订金收据号：______________
                </td>
            </tr>
            <tr class="tr_height">
                <td valign="top" style="width: 15px;line-height:1.5em;">三.</td>
                <td valign="top" style="line-height:1.5em;">商品一年内出现质量问题，由卖方负责免费维修，因买方使用不当，或超过交货时间一年的，卖方维修时，收取成本费和服务费。
                </td>
            </tr>
            <tr class="tr_height">
                <td valign="top" style="width: 15px;line-height:1.5em;">四.</td>
                <td valign="top" style="line-height:1.5em;">买方需要退货，应在订金交付后三日内办理，特殊要求订购的期货，不予退货。如买方坚持退货，卖方不退还订金。
                </td>
            </tr>
            <tr class="tr_height">
                <td valign="top" style="width: 15px;line-height:1.5em;">五.</td>
                <td valign="top" style="line-height:1.5em;">买方应在签订的交货期十五天内提货，超过此期限，由于仓库存储造成的破损卖方不承担责任：超过三个月不提货，卖方收取存货总值的1%/月作为仓库保管费。
                </td>
            </tr>
            <tr class="tr_height">
                <td style="width: 15px">六.</td>
                <td>由于卖方其自身原因未能按期交货，每天按未到货货款的1‰，作为违约金。
                </td>
            </tr>
            <tr class="tr_height">
                <td style="width: 15px;line-height:1.5em;">七.</td>
                <td style="line-height:1.5em;">买方如需空运卖方加收需空运货物总值的15%作为加急服务费。
                </td>
            </tr>
            <tr class="tr_height">
                <td valign="top" style="width: 15px;line-height:1.5em;">八.</td>
                <td valign="top" style="line-height:1.5em;">因不可抗力因素造成合同不能正常履行时，发生方应立即通知对方合同中止或终止执行，另一方不得追究其违约责任。不能执行一方必须在发生不可抗力事件
                    两周内就不可抗力提出公证证明，不可抗力结束后，双方可就合同能否继续执行提出书面协议。
                </td>
            </tr>
            <tr class="tr_height">
                <td style="width: 15px">九.</td>
                <td>交货时间：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日
                </td>
            </tr>
            <tr class="tr_height">
                <td style="width: 15px">十.</td>
                <td>期货商品详细描述，见期货商品附件。
                </td>
            </tr>
            <tr class="tr_height">
                <td style="width: 15px">十一.</td>
                <td>其他约定事项：
                </td>
            </tr>
            <tr class="tr_height">
                <td style="width: 30px">十二.</td>
                <td>本合同一式贰份，买方执壹份，卖方执壹份。
                </td>
            </tr>
        </table>
        <div style="margin:-20px 0px 0px 0px">
            <table border="0" cellspacing="0" cellpadding="0" width="100%" class="foorer_font" >
                <tr class="tr_height">
                    <td style="width: 60px;line-height:1.5em;padding-top: 30px" >买&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;方：</td>
                    <td style="text-align: left;width: 300px;padding-top: 30px">____________________________</td>
                    <td style="width: 60px;line-height:1.5em;padding-top: 30px" >卖&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;方：</td>
                    <td style="line-height:1.5em;padding-top: 30px">北京丰意德工贸有限公司</td>
                </tr>
                <tr class="tr_height">
                    <td></td>
                    <td></td>
                    <td style="line-height:1.5em;">经办人：</td>
                    <td style="line-height:1.5em;">__________________________</td>
                </tr>
                <tr class="tr_height">
                    <td>联系电话：</td>
                    <td>____________________________</td>
                    <td style="line-height:1.5em;">审核人：</td>
                    <td style="line-height:1.5em;">__________________________</td>
                </tr>
                <tr class="tr_height">
                    <td></td>
                    <td></td>
                    <td colspan="2" style="line-height:1.5em;">旗舰店：85370666&nbsp;&nbsp;&nbsp;中粮店：85111616</td>
                </tr>
                <tr class="tr_height">
                    <td>送货地址：</td>
                    <td>____________________________</td>
                    <td colspan="2" style="line-height:1.5em;padding-bottom: 0px;">天津店：022-88259818&nbsp;&nbsp;居然店：84636618</td>
                </tr>
            </table>
            <div style="margin-top: -190px;margin-right: 0px;float: right;"><img src="<?php echo base_url('public/img/vita_seal.png') ?>" width="180px" height="180px" /></div>
        </div>
    </div>
    <?php endfor ?>
    <div class="my_show" style="page-break-after: always;">
        <style>
            .print_font {
                font-size: 10px;
                margin:0px 0px 10px 0px;
            }
            .tr_height {
                height: 20px;
            }
            .foorer_font {
                font-size: 12px;
                font-weight:blod;
            }

            .content_tr {}

            .content_tr td {
                height: 10px;
                overflow: hidden;
                font-size: 12px;
            }
        </style>
        <table  cellspacing="0" style="border-collapse: collapse; border-spacing: 0;background-color: transparent;max-width: 100%" cellpadding="0" width="100%" class="print_font">
            <thead>
            <tr>
                <th colspan="7">
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tr align="center">
                            <td style="text-align: right; width: 150px;"><img src='<?php echo base_url('public/img/logo.jpg') ?>' style="width: 90px;"></td>
                            <td style="text-align:left; font-size: 26px;padding-bottom: 5px;padding-top: 0px">&nbsp;&nbsp;北京丰意德工贸有限公司销售合同</td>
                        </tr>
                        <tr align="center">
                            <td colspan="2" style="font-size: 14px;padding-bottom: 2px;padding-top: 0px">(期&nbsp;&nbsp;货&nbsp;&nbsp;订&nbsp;&nbsp;单&nbsp;&nbsp;附&nbsp;&nbsp;件)</td>
                        </tr>
                    </table>
                    <table border="0" cellspacing="0" cellpadding="0" width="100%" class="print_font">
                        <tr>
                            <!--                            <td style="width: 40%"></td>-->
                            <td colspan="2" style="font-size:14px;text-align: right">合同编号：<span style="font-size: 20px;color: red;"><?php echo $row[0]->applynumber;?></span></td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px;">一.&nbsp;&nbsp;买方决定购买卖方提供的如下</td>
                            <td style="font-size:14px;text-align: right">销售店：<?php echo $row[0]->storehousecode ?> 签订日期：<?php echo $row[0]->applydate; ?></td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr style="border:1px #000 solid;text-align: center;height: 40px">
                <th style="width:30px;border:1px #000 solid;">序号</th>
                <th style="width:100px;border:1px #000 solid;">产品名称</th>
                <th style="border:1px #000 solid;">产品描述</th>
                <th style="width:30px;border:1px #000 solid;">数量</th>
            </tr>
            </thead>
            <tbody >
            <?php for ($i=0;$i<count($apply_content);$i++) :?>
                <tr class="content_tr" style="height:30px;border:1px #000 solid;text-align: center">
                    <td style="border:1px #000 solid;"><?php echo $i+1; ?></td>
                    <td style="border:1px #000 solid;"><?php echo $apply_content[$i]->title ?></td>
                    <td style="border:1px #000 solid;"><?php echo $apply_content[$i]->memo ?></td>
                    <td style="border:1px #000 solid;"><?php echo $apply_content[$i]->number ?></td>
                </tr>
            <?php endfor ?>
            </tbody>
        </table>
    </div>
</div>
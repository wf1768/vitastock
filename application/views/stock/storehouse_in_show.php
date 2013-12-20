<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<script>
    function remove() {
        var str="";
        $("input[name='checkbox']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择要删除的入库商品。');
            return;
        }

        bootbox.confirm("确定要删除选择的入库商品吗？", function(result) {
            if(result){
                str = str.substring(0,str.length-1);

                $.ajax({
                    type:"post",
                    data: "id=" + str,
                    url:"<?php echo site_url('storehouse_in_content/remove')?>",
                    success: function(data){
                        if (data) {
                            $("input[name='checkbox']").attr("checked",false);
                            window.location.reload();
                        }
                        else {
                            openalert('删除入库商品出错，请重新尝试或与管理员联系。');
                        }
                    },
                    error: function() {
                        openalert('执行操作出错，请重新尝试或与管理员联系。');
                    }
                });
            }
        })
    }

    function createbarcode() {
        var str="";
        $("input[name='checkbox']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择要生成条码的入库商品。');
            return;
        }
        bootbox.confirm("确定要对选择的入库商品生成条形码吗？", function(result) {
            if(result){
                openloading('正在处理生成条码 ，请稍后.....');
                str = str.substring(0,str.length-1);
                $.ajax({
                    type:"post",
                    data: "id=" + str,
                    url:"<?php echo site_url('storehouse_in_content/createbarcode')?>",
                    success: function(data){
                        if (data) {
                            $("input[name='checkbox']").attr("checked",false);
                            closeloading();
                            window.location.reload();
                        }
                        else {
                            openalert('入库商品生成条形码出错，请重新尝试或与管理员联系。');
                        }
                    },
                    error: function() {
                        openalert('执行操作出错，请重新尝试或与管理员联系。');
                    }
                });
            }
        })

    }

    function handle_in(id) {
        if (id == "") {
            return;
        }

        var str="";
        $("input[name='checkbox']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择要办理入库的商品。');
            return;
        }
        str = str.substring(0,str.length-1);

        bootbox.confirm("确定要对办理入库吗？<br><font color='red'>注意：选择的商品办理入库后，将不能在办理入库。", function(result) {
            if(result){
                $.ajax({
                    type:"post",
                    data: "storehouse_inid=" + id + '&contentid=' + str,
                    url:"<?php echo site_url('storehouse_in/handle_in')?>",
                    success: function(data){
                        if (data) {
                            $("input[name='checkbox']").attr("checked",false);
                            alert('办理入库完毕。')
                            window.location.reload();
                        }
                        else {
                            openalert('办理入库出错，请重新尝试或与管理员联系。');
                        }
                    },
                    error: function() {
                        openalert('执行操作出错，请重新尝试或与管理员联系。');
                    }
                });
            }
        })
    }

    function single_barcode(id) {
        var rowid = id;
        var barcode = $('#barcode_'+rowid).val();
        var title = $('#title_'+rowid).html();
        var code = $('#code_'+rowid).val();
        var factoryname = $('#factoryname_'+rowid).val();
        var memo = $('#memo_'+rowid).val();
        var itemnumber = $('#itemnumber_' + rowid).val();


        $('#barcode_print').html('');
        var barcode_str = '';
        if (barcode != '') {
            barcode_str += '<div class="my_show">';
            barcode_str += '<table>';
            barcode_str += '<tr>';
            barcode_str += '<td colspan="2"><img id="barcode-image" src="<?php echo site_url('barcode/buildcode/BCGcode128/') ?>/'+barcode+'"/></td>';
            barcode_str += '</tr>';
            barcode_str += '<tr>';
            barcode_str += '<td style="height: 5px"></td>';
            barcode_str += '<td></td>';
            barcode_str += '</tr>';
            barcode_str += '<tr>';
            barcode_str += '<td style="width: 2cm">条形码号:</td>';
            barcode_str += '<td>'+barcode+'</td>';
            barcode_str += '</tr>';
            barcode_str += '<tr>';
            barcode_str += '<td>名称:</td>';
            barcode_str += '<td>'+title+'</td>';
            barcode_str += '</tr>';
            barcode_str += '<tr>';
            barcode_str += '<td>代码:</td>';
            barcode_str += '<td>'+code+'</td>';
            barcode_str += '</tr>';
            barcode_str += '<tr>';
            barcode_str += '<td>厂家:</td>';
            barcode_str += '<td>'+factoryname +'</td>';
            barcode_str += '</tr>';
            barcode_str += '<tr>';
            barcode_str += '<td>件数:</td>';
            barcode_str += '<td>'+itemnumber+'</td>';
            barcode_str += '</tr>';
            barcode_str += '<tr>';
            barcode_str += '<td>描述:</td>';
            barcode_str += '<td>'+memo+'</td>';
            barcode_str += '</tr>';
            barcode_str += '</table>';
            barcode_str += '</div>';
        }
        if (barcode_str != '') {
            $('#barcode_print').html(barcode_str);
//            closeloading();
            $("input[name='checkbox']").attr("checked", false);
            $(".my_show").jqprint({
                importCSS:true,
                debug:false
            });

        }
        else {
            openalert('没有找到要打印的条形码，请重新选择。');
        }
    }

    function barcode_print() {

        var str = "";
        $("input[name='checkbox']").each(function () {
            if ($(this).attr("checked") == 'checked') {
                str += $(this).val() + ",";
            }
        })
        if (str == "") {
            openalert('请选择要批量打印条码的商品。');
            return;
        }
        str = str.substring(0, str.length - 1);

        str = str.split(',');
        alert(str);

        $('#barcode_print').html('');
        var barcode_str = '';
        for (var i=0;i<str.length;i++) {
            var rowid = str[i];

            var barcode = $('#barcode_'+rowid).val();
            var title = $('#title_'+rowid).html();
            var code = $('#code_'+rowid).val();
            var factoryname = $('#factoryname_'+rowid).val();
            var memo = $('#memo_'+rowid).val();
            var itemnumber = $('#itemnumber_' + rowid).val();
            var boxno = $('#boxno_'+rowid).val();

            if (barcode != '') {
                if (itemnumber == 0 || itemnumber == null) {
                    itemnumber = 1;
                }
                for (var j=0;j<parseInt(itemnumber);j++) {
                    barcode_str += '<div class="my_show" style="page-break-after: always;">';
                    barcode_str += '<table>';
                    barcode_str += '<tr>';
                    barcode_str += '<td colspan="2"><img id="barcode-image" src="<?php echo site_url('barcode/buildcode/BCGcode128/') ?>/'+barcode+'"/></td>';
                    barcode_str += '</tr>';
                    barcode_str += '<tr>';
                    barcode_str += '<td style="height: 5px"></td>';
                    barcode_str += '<td></td>';
                    barcode_str += '</tr>';
                    barcode_str += '<tr>';
                    barcode_str += '<td style="width: 2cm">条形码号:</td>';
                    barcode_str += '<td>'+barcode+'</td>';
                    barcode_str += '</tr>';
                    barcode_str += '<tr>';
                    barcode_str += '<td>名称:</td>';
                    barcode_str += '<td>'+title+'</td>';
                    barcode_str += '</tr>';
                    barcode_str += '<tr>';
                    barcode_str += '<td>代码:</td>';
                    barcode_str += '<td>'+code+'</td>';
                    barcode_str += '</tr>';
                    barcode_str += '<tr>';
                    barcode_str += '<td>厂家:</td>';
                    barcode_str += '<td>'+factoryname +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;箱号: '+ boxno +'</td>';
                    barcode_str += '</tr>';
                    barcode_str += '<tr>';
                    barcode_str += '<td>件数:</td>';
                    barcode_str += '<td>'+itemnumber+'  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;件号: -'+ parseInt(j+1) +'</td>';
                    barcode_str += '</tr>';
                    barcode_str += '<tr>';
                    barcode_str += '<td>描述:</td>';
                    barcode_str += '<td>'+memo+'</td>';
                    barcode_str += '</tr>';
                    barcode_str += '</table>';
                    barcode_str += '</div>';
                }

            }
        }

        if (barcode_str != '') {
            openloading('正在生成打印的条形码，请稍后...');
            closeloading();
            $('#barcode_print').html(barcode_str);

            $("input[name='checkbox']").attr("checked", false);
            $(".my_show").jqprint({
                importCSS:true,
                debug:false
            });

        }
        else {
            openalert('没有找到要打印的条形码，请重新选择。');
        }
    }

    function multi_barcode_print() {
        var str = "";
        $("input[name='checkbox']").each(function () {
            if ($(this).attr("checked") == 'checked') {
                str += $(this).val() + ",";
            }
        })
        if (str == "") {
            openalert('请选择要批量打印条码的商品。');
            return;
        }
        str = str.substring(0, str.length - 1);

        $('#stockids').val(str);
        $('#multi_barcode_form').submit();

    }

    function handle_create_order(id) {
        if (id == "") {
            return;
        }

        var str="";
        $("input[name='checkbox_apply']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择直接生成现货销售单的期货商品。');
            return;
        }
        str = str.substring(0,str.length-1);

        bootbox.confirm("确定要将选中的期货商品生成销售单吗？<br><font color='red'>注意：选择的期货商品生成现货销售单，经财务审批后，直接办理送货。", function(result) {
            if(result){
                $.ajax({
                    type:"post",
                    data: "storehouse_inid=" + id + '&contentid=' + str,
                    url:"<?php echo site_url('stock/create_order')?>",
                    success: function(data){
                        if (data) {
                            $("input[name='checkbox_apply']").attr("checked",false);
                            alert('期货商品生成现货销售单完毕，等待财务审核后，送货。')
                            window.location.reload();
                        }
                        else {
                            openalert('期货商品生成现货销售单出错，请重新尝试或与管理员联系。');
                        }
                    },
                    error: function() {
                        openalert('执行操作出错，请重新尝试或与管理员联系。');
                    }
                });
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

        $("#select-apply").click(function(){
            if ($(this).attr("checked") == 'checked') {
                $("input[name='checkbox_apply']").attr("checked",$(this).attr("checked"));
            }
            else {
                $("input[name='checkbox_apply']").attr("checked",false);
            }
        });

        $("a[data-toggle=popover]").popover();
    })
</script>


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
                    <a href="<?php echo site_url('stock') ?>" class="path-menu-a"> 库存管理</a> > <a href="<?php echo site_url('storehouse_in') ?>" class="path-menu-a"> 入库单管理</a> > 浏览
                </h1>
                <div class="row">
                    <div class="span9">
                        <div class="widget">
                            <div class="widget-header">
                                <h3>浏览入库单</h3>
                            </div>
                            <!-- /widget-header -->
                            <div class="widget-content">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#" data-toggle="tab">入库单信息</a>
                                        </li>
                                    </ul>
                                    <br>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="1" >
                                            <?php if ($row) : ?>
                                            <table class="table table-bordered" width="100%">
                                                <tr>
                                                    <td>入库单编号</td>
                                                    <td colspan="3"><?php echo $row[0]->innumber ?></td>
                                                </tr>
                                                <tr>
                                                    <td>创建人</td>
                                                    <td><?php echo $row[0]->createby ?></td>
                                                    <td>创建时间</td>
                                                    <td><?php echo $row[0]->createtime ?></td>
                                                </tr>
                                                <tr>
                                                    <td>处理人</td>
                                                    <td><?php echo $row[0]->checkby ?></td>
                                                    <td>处理时间</td>
                                                    <td><?php echo strtotime($row[0]->overtime)?$row[0]->overtime:'';  ?></td>
                                                </tr>
                                                <tr>
                                                    <td>入库单状态</td>
                                                    <td><?php echo ($row[0]->instatus == 0)?'<font color="red">未结束</font>':'已入库' ?></td>
                                                    <td>入库来源</td>
                                                    <td><?php echo $row[0]->fromcode ?></td>
                                                </tr>
                                                <tr>
                                                    <td>备注</td>
                                                    <td colspan="3"><?php echo $row[0]->remark ?></td>
                                                </tr>
                                            </table>
                                            <?php endif ?>
                                            <?php if ($row[0]->frombuy == 3) :?>
                                                <div class="alert alert-info">
                                                    <button data-dismiss="alert" class="close" type="button">×</button>
                                                    当前入库商品是由期货订货办理入库，商品在办理入库后，商品的状态将为［已销售］，请提醒销售人员，在期货订单里自主生成销售合同单，经财务审核后，直接办理送货。
                                                </div>
<!--                                                <form id="apply_order" method="post" action="--><?php //echo site_url('storehouse_in/create_order') ?><!--">-->
<!--                                                    <input type="hidden" id="stocks" name="stocks" value="">-->
<!--                                                    <div class="row">-->
<!--                                                        <div class="span8">-->
<!--                                                            <label class="pull-left">-->
<!--                                                                <a href="javascript:;" class="btn btn-primary" onclick="handle_create_order('--><?php //echo $row[0]->id ?><!--')" ><i class="icon-list"> 生成销售单</i></a>-->
<!--                                                            </label>-->
<!--                                                        </div>-->
<!--                                                    </div>-->
<!--                                                </form>-->
                                            <?php endif ?>
                                            <?php if ($row[0]->instatus == 0) : ?>
                                            <form id="multi_barcode_form" method="post" action="<?php echo site_url('stock/multi_barcode') ?>">
<!--                                                <input type="hidden" id="templet_type" name="templet_type" value="storehouse_in">-->
                                                <input type="hidden" id="stockids" name="stockids" value="">
                                                <input type="hidden" id="path" name="path" value="storehouse_in/show?id=<?php echo $storehouseid ?>">
                                                <div class="row">
                                                    <div class="span8">
                                                        <label class="pull-left">
                                                            <a href="javascript:;" class="btn btn-primary" onclick="createbarcode()" ><i class="icon-barcode"> 生成条形码</i></a>
                                                            <a href="javascript:;" class="btn btn-primary" onclick="barcode_print()" ><i class="icon-barcode"> 打印条形码</i></a>
                                                            <a href="javascript:;" class="btn btn-primary" onclick="handle_in('<?php echo $row[0]->id ?>')" ><i class="icon-list"> 办理入库</i></a>
                                                        </label>
                                                    </div>
                                                </div>
                                            </form>
                                            <?php endif ?>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="select-all""></th>
                                                    <th>#</th>
                                                    <th>缩略图</th>
                                                    <th>打印条码</th>
                                                    <th>名称</th>
                                                    <th>代码</th>
<!--                                                    <th>描述</th>-->
                                                    <th>厂家</th>
<!--                                                    <th>品牌</th>-->
<!--                                                    <th>类别</th>-->
                                                    <?php if ($this->account_info_lib->power == 2) : ?>
                                                    <th>单价(€)</th>
                                                    <th>标准单价(€)</th>
                                                    <?php endif ?>
                                                    <th>售价(￥)</th>
                                                    <th>颜色</th>
<!--                                                    <th>材质等级</th>-->
                                                    <th>数量</th>
                                                    <th>箱号</th>
                                                    <th>件数</th>
                                                    <th>条形码</th>
                                                    <th>备注</th>
                                                    <th>状态</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if(isset($stock_content)):?>
                                                    <?php $num = 1; foreach($stock_content as $stock):?>
                                                        <tr>
                                                            <td><?php if ($stock->statuskey == 0) : ?><input type="checkbox" name="checkbox" value="<?php echo $stock->id ?>"/><?php endif ?></td>
                                                            <td><?php echo $num ?></td>
                                                            <td><a href="javascript:;" data-html="true" data-trigger="hover" data-toggle="popover" data-content="<img src='<?php echo base_url($stock->picpath) ?>' />" ><img src="<?php echo base_url($stock->picpath) ?>"  alt="" class="thumbnail smallImg" /></a></td>
                                                            <td><a class="btn btn-small" href="javascript:;" onclick="single_barcode('<?php echo $stock->id ?>')">
                                                                    <i class="icon-barcode"></i>
                                                                </a></td>
                                                            <td><?php echo "<a href='".site_url('storehouse_in_content/show_content?storehouseid='.$row[0]->id.'&id='.$stock->id)."'>".$stock->title."</a>"; ?>
                                                            <span id="title_<?php echo $stock->id ?>" style="display: none"><?php echo $stock->title ?></span></td>
                                                            <td><?php echo $stock->code ?><input type="hidden" id="code_<?php echo $stock->id ?>" value="<?php echo $stock->code ?>" ></td>
<!--                                                            <td>--><?php //echo $stock->memo ?><!--</td>-->
                                                            <td><?php echo $stock->factoryname ?><input type="hidden" id="factoryname_<?php echo $stock->id ?>" value="<?php echo $stock->factoryname ?>" ></td>
<!--                                                            <td>--><?php //echo $stock->brandname ?><!--</td>-->
<!--                                                            <td>--><?php //echo $stock->typename ?><!--</td>-->
                                                            <?php if ($this->account_info_lib->power == 2) : ?>
                                                            <td><?php echo $stock->cost ?></td>
                                                            <td><?php echo $stock->standardcost ?></td>
                                                            <?php endif ?>
                                                            <td><?php echo $stock->salesprice ?><input type="hidden" id="memo_<?php echo $stock->id ?>" value="<?php echo $stock->memo ?>" ></td>
                                                            <td><?php echo $stock->color ?></td>
<!--                                                            <td>--><?php //echo $stock->format ?><!--</td>-->
                                                            <td><?php echo $stock->number ?></td>
                                                            <td><?php echo $stock->boxno ?></td>
                                                            <td><?php echo $stock->itemnumber ?></td>
                                                            <td><?php echo $stock->barcode;$num++ ?>
                                                                <input type="hidden" id="barcode_<?php echo $stock->id ?>" name="barcode_<?php echo $stock->id ?>" value="<?php echo $stock->barcode ?>" >
                                                                <input type="hidden" id="itemnumber_<?php echo $stock->id ?>" name="itemnumber_<?php echo $stock->id ?>" value="<?php echo $stock->itemnumber ?>" >
                                                                <input type="hidden" id="boxno_<?php echo $stock->id ?>" name="boxno_<?php echo $stock->id ?>" value="<?php echo $stock->boxno ?>" >
                                                            </td>
                                                            <td><?php echo $stock->remark;  ?></td>
                                                            <td><?php echo ($stock->statuskey == 0)?'<font color="red">'.$stock->statusvalue.'</font>':$stock->statusvalue ?></td>
                                                        </tr>
                                                    <?php endforeach;?>
                                                <?php endif;?>
                                                </tbody>
                                            </table>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <?php if ($row[0]->frombuy == 3) :?>
                                                    <th><input type="checkbox" id="select-apply""></th>
                                                    <?php endif ?>
                                                    <th>#</th>
                                                    <th>缩略图</th>
                                                    <th>名称</th>
                                                    <th>代码</th>
                                                    <th>描述</th>
                                                    <th>厂家</th>
                                                    <th>售价(￥)</th>
                                                    <th>数量</th>
                                                    <th>条形码</th>
                                                    <th>状态</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if(isset($stock_content_in)):?>
                                                    <?php $num = 1; foreach($stock_content_in as $stock_in):?>
                                                        <tr>
                                                            <?php if ($row[0]->frombuy == 3) :?>
                                                            <td><?php if ($stock_in->statuskey == 1) : ?><input type="checkbox" name="checkbox_apply" value="<?php echo $stock_in->id ?>"/><?php endif ?></td>
                                                            <?php endif ?>
                                                            <td><?php echo $num ?></td>
                                                            <td><a href="javascript:;" data-html="true" data-trigger="hover" data-toggle="popover" data-content="<img src='<?php echo base_url($stock_in->picpath) ?>' />" ><img src="<?php echo base_url($stock_in->picpath) ?>"  alt="" class="thumbnail smallImg" /></a></td>
                                                            <td><?php echo "<a href='".site_url('storehouse_in_content/show_content?storehouseid='.$row[0]->id.'&id='.$stock_in->id)."'>".$stock_in->title."</a>"; ?>
                                                                </td>
                                                            <td><?php echo $stock_in->code ?></td>
                                                            <td><?php echo $stock_in->memo ?></td>
                                                            <td><?php echo $stock_in->factoryname ?></td>
                                                            <td><?php echo $stock_in->salesprice ?></td>
                                                            <td><?php echo $stock_in->number ?></td>
                                                            <td><?php echo $stock_in->barcode;$num++ ?></td>
                                                            <td><?php echo ($stock_in->statuskey == 0)?'<font color="red">'.$stock_in->statusvalue.'</font>':$stock_in->statusvalue ?></td>
                                                        </tr>
                                                    <?php endforeach;?>
                                                <?php endif;?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div id="barcode_print" style="height:0px;width:0px;overflow:hidden">
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

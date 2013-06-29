<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<script>

    var array_num = 0;
    var barcodes = {};

    var num = 0;

    function remove() {
        var str="";
        $("input[name='checkbox_move']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择要移除的调拨商品。');
            return;
        }

        bootbox.confirm("确定要移除选择的调拨商品吗？", function(result) {
            if(result){
                str = str.substring(0,str.length-1);

                $.ajax({
                    type:"post",
                    data: "id=" + str + "&storehouse_moveid=<?php echo $row[0]->id ?>",
                    url:"<?php echo site_url('storehouse_move/remove_stock')?>",
                    success: function(data){
                        if (data) {
                            var rows = eval(data);
                            var str = '';
                            for(var i=0;i<rows.length;i++) {
                                str += '<tr>';
                                str += '<td><input type="checkbox" name="checkbox_move" value="'+rows[i].id+'"/></td>';
                                str += '<td>'+ (num+1) + '</td>';
                                str += '<td><a href="javascript:;" data-html="true" data-trigger="hover" data-toggle="popover" data-content="<img src=\'<?php echo base_url('') ?>'+rows[i].picpath+'\' >" ><img src="<?php echo base_url('') ?>'+rows[i].picpath+'"  alt="" class="thumbnail smallImg" /></a></td>';
                                str += '<td>' + rows[i].title + '</td>';
                                str += '<td>' + rows[i].code + '</td>';
                                str += '<td>' + rows[i].memo + '</td>';
                                str += '<td>' + rows[i].factoryname + '</td>';
                                str += '<td>' + rows[i].typename + '</td>';
                                str += '<td>' + rows[i].salesprice + '</td>';
                                str += '<td>' + rows[i].color + '</td>';
                                str += '<td>' + rows[i].number + '</td>';
                                str += '<td>' + rows[i].barcode + '</td>';
                                str += '<td>' + rows[i].storehouse + '</td>';
                                str += '<td>' + rows[i].statusvalue + '</td>';
                                str += '</tr>';
                                num++;
                            }
                            $('#move_content').html(str);
                            $('#barcode').val('');
                            barcodes = {};
                            array_num = 0;
                            num = 0;
                            $("a[data-toggle=popover]").popover();
//                            $("input[name='checkbox_move']").attr("checked",false);
//                            window.location.reload();
                        }
                        else {
                            openalert('删除采购商品出错，请重新尝试或与管理员联系。');
                        }
                    },
                    error: function() {
                        openalert('执行操作出错，请重新尝试或与管理员联系。');
                    }
                });
            }
        })
    }

    function add_mode(mode,str) {
        $('#super_search').hide();
        $('#barcode_search').hide();
        $('#multi_barcode_search').hide();


        $('#'+mode).toggle();
        $('#mode').html(str);

        if (mode == 'barcode_search') {
            $('#tiaoma').focus();
        }
    }

    function begin_move(id) {
        bootbox.confirm("确定要执行调拨吗？<br> <font color='red'>" +
            "注意：执行调拨，将不能再添加调拨商品，只能打印调拨单。</font> ", function(result) {
            if(result){
                $.ajax({
                    type:"post",
                    data: "id=" + id,
                    url:"<?php echo site_url('storehouse_move/begin_move')?>",
                    success: function(data){
                        if (data) {
                            window.location.reload();
                        }
                        else {
                            openalert('调拨商品出错，请重新尝试或与管理员联系。');
                        }
                    },
                    error: function() {
                        openalert('执行操作出错，请重新尝试或与管理员联系。');
                    }
                });
            }
        })
    }

    function add_barcode(tmp) {
        barcodes[array_num] = tmp;
        array_num++;
    }

    function submit_barcode() {
        var jsonStr = JSON.stringify(barcodes);

        if (jsonStr == '{}') {
            return;
        }
        $.ajax({
            type:"post",
            data: "barcodes=" + jsonStr + "&storehouse_moveid=<?php echo $row[0]->id ?>",
            url:"<?php echo site_url('storehouse_move/add_stock') ?>",
            success: function(data){
                if (data) {
                    var rows = eval(data);
                    var str = '';
                    for(var i=0;i<rows.length;i++) {
                        str += '<tr>';
                        str += '<td><input type="checkbox" name="checkbox_move" value="'+rows[i].id+'"/></td>';
                        str += '<td>'+ (num+1) + '</td>';
                        str += '<td><a href="javascript:;" data-html="true" data-trigger="hover" data-toggle="popover" data-content="<img src=\'<?php echo base_url('') ?>'+rows[i].picpath+'\' >" ><img src="<?php echo base_url('') ?>'+rows[i].picpath+'"  alt="" class="thumbnail smallImg" /></a></td>';
                        str += '<td>' + rows[i].title + '</td>';
                        str += '<td>' + rows[i].code + '</td>';
                        str += '<td>' + rows[i].memo + '</td>';
                        str += '<td>' + rows[i].factoryname + '</td>';
                        str += '<td>' + rows[i].typename + '</td>';
                        str += '<td>' + rows[i].salesprice + '</td>';
                        str += '<td>' + rows[i].color + '</td>';
                        str += '<td>' + rows[i].number + '</td>';
                        str += '<td>' + rows[i].barcode + '</td>';
                        str += '<td>' + rows[i].storehouse + '</td>';
                        str += '<td>' + rows[i].statusvalue + '</td>';
                        str += '</tr>';
                        num++;
                    }
                    $('#move_content').html(str);
                    $('#barcode').val('');
                    barcodes = {};
                    array_num = 0;
                    num = 0;
                    $("a[data-toggle=popover]").popover();
                }
                else {
                    openalert('调拨单添加商品出错，请重新尝试或与管理员联系。');
                }
            },
            error: function() {
                openalert('执行操作出错，请重新尝试或与管理员联系。');
            }
        });
    }

    function onPrint() {
        $(".my_show").jqprint({
            importCSS:false,
            debug:false
        });
    }

    function multi_barcode_add() {
//        alert($('#multi_barcode').val());
//        alert($("#multi_barcode").val().split("\n").length);

        var temp = $('#multi_barcode').val();
        var barcodes = temp.split("\n") ;

//        alert(barcodes);

        for (var i=0;i<barcodes.length;i++) {
            var barcode = $.trim(barcodes[i])//trim(barcodes[i]);
            if (barcode != '') {
                var tmp = new Object();
                tmp.barcode = barcodes[i];
                add_barcode(tmp);
            }
        }
        $('#multi_barcode').val('');
        //提交
        submit_barcode();
    }

    function search_stock_add() {
        var str="";
        $("input[name='checkbox_search']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择要调拨的商品。');
            return;
        }
        str = str.substring(0,str.length-1);

        var barcodes = str.split(",") ;

        for (var i=0;i<barcodes.length;i++) {
            var barcode = $.trim(barcodes[i])//trim(barcodes[i]);
            if (barcode != '') {
                var tmp = new Object();
                tmp.barcode = barcodes[i];
                add_barcode(tmp);
            }
        }
        //提交
        submit_barcode();
    }

    function search_stock() {
        //get form data
        var search_data = $("#search_content_from").serialize();

        $('#search_content').html('');

        $.ajax({
            type:"post",
            data: search_data,
            url:"<?php echo site_url('storehouse_move/search_stock') ?>",
            success: function(data){
                if (data) {
                    var rows = eval(data);
                    var str = '';
                    var search_num = 0;
                    for(var i=0;i<rows.length;i++) {
                        str += '<tr>';
                        str += '<td><input type="checkbox" name="checkbox_search" value="'+rows[i].barcode+'"/></td>';
                        str += '<td>'+ (search_num+1) + '</td>';
                        str += '<td><a href="javascript:;" data-html="true" data-trigger="hover" data-toggle="popover" data-content="<img src=\'<?php echo base_url('') ?>'+rows[i].picpath+'\' >" ><img src="<?php echo base_url('') ?>'+rows[i].picpath+'"  alt="" class="thumbnail smallImg" /></a></td>';
                        str += '<td>' + rows[i].title + '</td>';
                        str += '<td>' + rows[i].code + '</td>';
                        str += '<td>' + rows[i].memo + '</td>';
                        str += '<td>' + rows[i].factoryname + '</td>';
                        str += '<td>' + rows[i].brandname + '</td>';
                        str += '<td>' + rows[i].typename + '</td>';
                        str += '<td>' + rows[i].salesprice + '</td>';
                        str += '<td>' + rows[i].color + '</td>';
                        str += '<td>' + rows[i].number + '</td>';
                        str += '<td>' + rows[i].barcode + '</td>';
                        str += '</tr>';
                        search_num++;
                    }
                    $('#search_content').html(str);
                    $("a[data-toggle=popover]").popover();
                }
                else {
                    openalert('查询商品出错，请重新尝试或与管理员联系。');
                }
            },
            error: function() {
                openalert('执行操作出错，请重新尝试或与管理员联系。');
            }
        });
    }

    $(function() {
        //控制form里的文本框回车不提交
        $(".navbar-form").keydown(function(e) {
//            var e = event || window.event; //浏览器兼容
            var e = e || event;
            var keyNum = e.which || e.keyCode;
            return keyNum==13 ? false : true;
        })
        add_mode('barcode_search');

        $("#select-all-move").click(function(){
            if ($(this).attr("checked") == 'checked') {
                $("input[name='checkbox_move']").attr("checked",$(this).attr("checked"));
            }
            else {
                $("input[name='checkbox_move']").attr("checked",false);
            }
        });

        $("#select-all-search-content").click(function(){
            if ($(this).attr("checked") == 'checked') {
                $("input[name='checkbox_search']").attr("checked",$(this).attr("checked"));
            }
            else {
                $("input[name='checkbox_search']").attr("checked",false);
            }
        });



        $("#supsearchit").click(function () {
            $("#supsearch").toggle();
            $("#default_search").toggle();
            $("#multi_barcode_search").toggle();


        });

        $("input[name='barcode']").keyup(function(event){
            var e = event || window.event; //浏览器兼容
            if (e.keyCode == 13) {
                if ($("input[name='barcode']").val() == '') {
                    return;
                }
                else {
                    //临时存barcode集合。
                    var tmp = new Object();
                    tmp.barcode = $("input[name='barcode']").val();
                    add_barcode(tmp);
                    $("input[name='barcode']").val('')
                    //提交
                    submit_barcode();
                }
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
                    <a href="<?php echo site_url('storehouse_move') ?>" class="path-menu-a"> 调拨管理</a> > <a href="<?php echo site_url('storehouse_move') ?>" class="path-menu-a"> 调拨单管理</a> > 浏览
                </h1>
                <div class="widget widget-table my_show1">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>调拨单信息</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                        <?php if ($row) : ?>
                            <table class="table table-bordered" width="100%">
                                <tr>
                                    <td>调拨单编号</td>
                                    <td colspan="3"><?php echo $row[0]->movenumber ?></td>
                                </tr>
                                <tr>
                                    <td>创建人</td>
                                    <td><?php echo $row[0]->createby ?></td>
                                    <td>创建时间</td>
                                    <td><?php echo strtotime($row[0]->createtime)?$row[0]->createtime:''; ?></td>
                                </tr>
                                <tr>
                                    <td>运输负责人</td>
                                    <td><?php echo $row[0]->moveby ?></td>
                                    <td>调拨日期</td>
                                    <td><?php echo strtotime($row[0]->movedate)?$row[0]->movedate:''; ?></td>
                                </tr>
                                <tr>
                                    <td>原库房</td>
                                    <td><?php echo $row[0]->oldhouse ?></td>
                                    <td>目标库房</td>
                                    <td><?php echo $row[0]->targethouse ?></td>
                                </tr>
                                <tr>
                                    <td>调拨单状态</td>
                                    <td colspan="3"><?php
                                        if ($row[0]->status == 0) {
                                            echo '<font color="red">未开始</font>';
                                        }
                                        else if ($row[0]->status == 1) {
                                            echo '<font color="green">调拨中</font>';
                                        }
                                        else if ($row[0]->status == 2) {
                                            echo '已结束';
                                        }
                                        else {
                                            echo '未知状态，请与管理员联系。';
                                        }
                                        ?>
                                </tr>

                                <tr>
                                    <td>备注</td>
                                    <td colspan="3"><?php echo $row[0]->remark ?></td>
                                </tr>
                            </table>
                        <?php endif ?>
                    </div> <!-- /widget-content -->
                </div>
                <?php if ($row[0]->status == 1) : ?>
                    <div class="row">
                        <div class="span9">
                            <label class="pull-right">
                                <a href="javascript:;" onclick="onPrint()" class="btn btn-primary">打印调拨单</a>
                                <a href="<?php echo site_url('storehouse_move/pages?status=0') ?>" class="btn">返回</a>
                            </label>
                        </div>
                    </div>
                <?php endif ?>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>调拨商品信息</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all-move""></th>
                                <th>序号</th>
                                <th>缩略图</th>
                                <th>名称</th>
                                <th>代码</th>
                                <th>描述</th>
                                <th>厂家</th>
                                <th>类别</th>
                                <th>售价(￥)</th>
                                <th>颜色</th>
                                <th>数量</th>
                                <th>条形码</th>
                                <th>库房</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody id="move_content">
                            <?php if(isset($stock_move_content)):?>
                                <?php $num = 1; foreach($stock_move_content as $content):?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox_move" value="<?php echo $content->id ?>"/></td>
                                        <td><?php echo $num ?></td>
                                        <td><a href="javascript:;" data-html="true" data-trigger="hover" data-toggle="popover" data-content="<img src='<?php echo base_url($content->picpath) ?>' />" ><img src="<?php echo base_url($content->picpath) ?>"  alt="" class="thumbnail smallImg" /></a></td>
                                        <td><?php echo $content->title ?></td>
                                        <td><?php echo $content->code ?></td>
                                        <td><?php echo $content->memo ?></td>
                                        <td><?php echo $content->factoryname ?></td>
                                        <td><?php echo $content->typename ?></td>
                                        <td><?php echo $content->salesprice ?></td>
                                        <td><?php echo $content->color ?></td>
                                        <td><?php echo $content->number ?></td>
                                        <td><?php echo $content->barcode;$num++ ?></td>
                                        <td><?php echo storehouse_move::getStorehouse($content->storehouseid);?></td>
                                        <td><?php echo $content->statusvalue;?></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div> <!-- /widget-content -->
                </div>
                <?php if ($row[0]->status == 0) : ?>
                <div class="row">
                    <div class="span9">
                        <label class="pull-right">
                            <div class="btn-group" class="pull-right">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="c-666">添加方式：</span>
                                    <strong id='mode'>条码扫描</strong>
                                    <input type="hidden" name="category" value="">
                                    <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:;" onclick="add_mode('barcode_search','条码扫描')"> 条码扫描</a></li>
                                    <li><a href="javascript:;" onclick="add_mode('multi_barcode_search','条码批量')"> 条码批量</a></li>
                                    <li><a href="javascript:;" onclick="add_mode('super_search','条件选择')"> 条件选择</a></li>
                                </ul>
                            </div>
                        </label>
                    </div>
                </div>
                <span id="barcode_search" ><!-- style="display:none" -->
                <div class="minbox">
                    <div class="part_search">
                        <div class="navbar">
                            <div class="navbar-inner">
                                <form class="navbar-form" method="post" id="searchfrom" style="margin-bottom:5px">
<!--                                    <input type="hidden"  name="search_type" value="1">-->
                                    <font class="myfont">&nbsp;&nbsp;&nbsp;&nbsp;条形码：</font>
                                    <input type="text" name="barcode" id="tiaoma" value="" placeholder="请输入条形码">
                                    <a href="javascript:;" onclick="remove()" class="btn btn-primary">移除商品</a>
                                    <a href="javascript:;" onclick="begin_move('<?php echo $row[0]->id ?>')" class="btn btn-primary">开始调拨</a>
                                    <a href="<?php echo site_url('storehouse_move/pages?status=0') ?>" class="btn">返回</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </span>
                <span id="multi_barcode_search" ><!-- style="display:none" -->
                <div class="minbox">
                    <div class="part_search">
                        <div class="navbar">
                            <div class="navbar-inner">
                                <form class="navbar-form1" method="post" id="searchfrom" style="margin-bottom:5px">
<!--                                    <input type="hidden"  name="search_type" value="1">-->
                                    <font class="myfont">&nbsp;&nbsp;&nbsp;&nbsp;条形码：</font>
                                    <textarea id="multi_barcode" style="margin-top: 10px;">

                                    </textarea>
                                    <input type="button" class="btn btn-primary" name="multi_add" id="" value="添加调拨商品" onclick="multi_barcode_add()">
<!--                                    <a href="javascript:;" onclick="multi_barcode_add()" class="btn btn-primary">添加调拨商品</a>-->
                                    <a href="javascript:;" onclick="remove()" class="btn btn-primary">移除商品</a>
                                    <a href="javascript:;" onclick="begin_move('<?php echo $row[0]->id ?>')" class="btn btn-primary">开始调拨</a>
                                    <a href="<?php echo site_url('storehouse_move/pages?status=0') ?>" class="btn">返回</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </span>
                <span id="super_search" >
                <div class="minbox">
                    <div class="part_search">
                        <div class="navbar">
                            <div class="navbar-inner">
                                <form class="navbar-form" method="post" id="search_content_from" style="margin-bottom:5px">
<!--                                    <input type="hidden"  name="search_type" value="1">-->
                                    <input type="hidden" name="storehouseid" id="storehouseid"
                                           value="<?php echo $row[0]->oldhouseid ?>">
                                    <font class="myfont">所在库房：</font>
                                    <input type="text" name="storehouse" id="storehouse" readonly=""
                                           value="<?php echo $row[0]->oldhouse ?>">
                                    <font class="myfont">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;条形码：</font>
                                    <input type="text" name="barcode" id="barcode"
                                           value="<?php echo isset($_REQUEST['barcode']) ? $_REQUEST['barcode'] : ''; ?>"
                                           placeholder="请输入条形码">
                                    <br/>
                                    <font class="myfont">商品代码：</font>
                                    <input type="text" id="" name="code"
                                           value="<?php echo isset($_REQUEST['code']) ? $_REQUEST['code'] : ''; ?>"
                                           placeholder="请输入条产品名称">

                                    &nbsp;&nbsp;<font class="myfont">产品名称：</font>
                                    <input type="text" id="" name="title"
                                           value="<?php echo isset($_REQUEST['title']) ? $_REQUEST['title'] : ''; ?>"
                                           placeholder="请输入条产品名称"><br/>
                                    <font class="myfont">产品厂家：</font>
                                    <select name="factoryid">
                                        <option value="">请选择</option>
                                        <?php foreach ($factorys as $val): ?>
                                            <option value="<?php echo $val->id; ?>"
                                                <?php if (isset($_REQUEST['factorycode']) && $_REQUEST['factorycode'] == $val->factorycode) {
                                                echo "selected";
                                            }?>
                                                ><?php echo $val->factoryname;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    &nbsp;&nbsp;<font class="myfont">产品颜色：</font>
                                    <select name="color">
                                        <option value="">请选择</option>
                                        <?php foreach ($colors as $val): ?>
                                            <option value="<?php echo $val->colorname; ?>"
                                                <?php if (isset($_REQUEST['color']) && $_REQUEST['color'] == $val->colorname) {
                                                echo "selected";
                                            }?>><?php echo $val->colorname;?></option>
                                        <?php endforeach;?>
                                    </select><br/>
                                    <font class="myfont">产品品牌：</font>
                                    <select name="brandid">
                                        <option value="">请选择</option>
                                        <?php foreach ($brands as $val): ?>
                                            <option value="<?php echo $val->id; ?>"
                                                <?php if (isset($_REQUEST['brandcode']) && $_REQUEST['brandcode'] == $val->brandcode) {
                                                echo "selected";
                                            }?>><?php echo $val->brandname;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    &nbsp;&nbsp;<font class="myfont">产品类别：</font>
                                    <select name="typeid">
                                        <option value="">请选择</option>
                                        <?php foreach ($types as $val): ?>
                                            <option value="<?php echo $val->id; ?>"
                                                <?php if (isset($_REQUEST['typecode']) && $_REQUEST['typecode'] == $val->typecode) {
                                                echo "selected";
                                            }?>><?php echo $val->typename;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <button style="margin-left:20px" id="search" type="button" class="btn btn-primary" onclick="search_stock()">&nbsp;&nbsp;搜&nbsp;&nbsp;索&nbsp;&nbsp;</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($row[0]->status == 0) : ?>
                    <div class="row">
                        <div class="span9">
                            <label class="pull-left">
                                <label class="pull-left">
                                    <input type="hidden" id="apply_content_json" name="apply_content_json" value="">
                                    <button class="btn btn-primary" type="button" onclick="search_stock_add()" id="btn-save"><i class="icon-ok">添加</i></button>
                                    <a href="javascript:;" class="btn btn-primary" id="delete_product" onclick="remove()">移除调拨商品</a>
                                    <a href="javascript:;" onclick="begin_move('<?php echo $row[0]->id ?>')" class="btn btn-primary">开始调拨</a>
                                    <a href="<?php echo site_url('storehouse_move/pages?status=0') ?>" class="btn">返回</a>
                                </label>
                            </label>
                        </div>
                    </div>
                <?php endif ?>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>商品列表</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all-search-content""></th>
                                <th>序号</th>
                                <th>缩略图</th>
                                <th>名称</th>
                                <th>代码</th>
                                <th>描述</th>
                                <th>厂家</th>
                                <th>品牌</th>
                                <th>类别</th>
                                <th>售价(￥)</th>
                                <th>颜色</th>
                                <th>数量</th>
                                <th>条形码</th>
                            </tr>
                            </thead>
                            <tbody id="search_content">

                            </tbody>
                        </table>
                    </div> <!-- /widget-content -->
                </div>
                </span>
                <?php endif ?>
                <?php if (count($move_content_deal) > 0) : ?>
                    <div class="widget widget-table">
                        <div class="widget-header">
                            <i class="icon-th-list"></i>
                            <h3>商品接收结果</h3>
                        </div>
                        <!-- /widget-header -->
                        <div class="widget-content">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>条形码</th>
                                    <th>接收时间</th>
                                    <th>接收人</th>
                                    <th>接收描述</th>
                                </tr>
                                </thead>
                                <tbody id="handle_move_content">
                                    <?php $num = 1; foreach($move_content_deal as $content):?>
                                        <tr>
                                            <td><?php echo $num ?></td>
                                            <td><?php echo $content['barcode'] ?></td>
                                            <td><?php echo $content['dealtime'] ?></td>
                                            <td><?php echo $content['dealby'] ?></td>
                                            <td><?php echo $content['remark'];$num++ ?></td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div> <!-- /widget-content -->
                    </div>
                <?php endif ?>
            </div>
            <!-- /span9 -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div> <!-- /content -->

<?php $this->load->view("common/footer"); ?>
<div style="height:0px;width:0px;overflow:hidden">
    <div class="my_show">
        <style>
            .print_font {
                font-size: 10px;
                margin:0px 0px 10px 0px;
            }
        </style>
        <table  cellspacing="0" style="border-collapse: collapse; border-spacing: 0;background-color: transparent;max-width: 100%" cellpadding="0" width="100%" class="print_font">
            <thead>
            <tr>
                <th colspan="7">
                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr align="center">
                        <td style="text-align: right; width: 250px;"><img src='<?php echo base_url('public/img/logo.jpg') ?>' style="width: 90px;"></td>
                        <td style="text-align:left; font-size: 20px;padding-bottom: 30px;padding-top: 30px">&nbsp;&nbsp;丰意德公司-商品调拨单</td>
                    </tr>
                </table>
                <table border="0" cellspacing="0" cellpadding="0" width="100%" class="print_font">
                    <tr>
                        <td>调拨单编号：<?php echo $row[0]->movenumber ?> </td>
                        <td>原库房：<?php echo $row[0]->oldhouse ?> </td>
                        <td>目标库房：<?php echo $row[0]->targethouse ?>
                        <td style="text-align: right">调拨日期：<?php echo strtotime($row[0]->movedate)?$row[0]->movedate:''; ?></td>
                    </tr>
                </table>
                </th>
            </tr>
            <tr style="border:1px #000 solid;text-align: center;height: 40px">
                <th style="border:1px #000 solid;">序号</th>
                <th style="border:1px #000 solid;">缩略图</th>
                <th style="border:1px #000 solid;">名称</th>
                <th style="border:1px #000 solid;">代码</th>
                <th style="border:1px #000 solid;">描述</th>
                <th style="border:1px #000 solid;">数量</th>
                <th style="border:1px #000 solid;">条形码</th>
                <th style="border:1px #000 solid;">库房</th>
                <th style="border:1px #000 solid;">状态</th>
            </tr>
            </thead>
            <tbody >
            <?php if(isset($stock_move_content)):?>
                <?php $num = 1; foreach($stock_move_content as $content):?>
                    <tr style="border:1px #000 solid;text-align: center">
                        <td style="border:1px #000 solid;"><?php echo $num ?></td>
                        <td style="border:1px #000 solid;"><img src="<?php echo base_url($content->picpath) ?>" width="26px" height="26px" /></td>
                        <td style="border:1px #000 solid;"><?php echo $content->title ?></td>
                        <td style="border:1px #000 solid;"><?php echo $content->code ?></td>
                        <td style="border:1px #000 solid;"><?php echo $content->memo ?></td>
                        <td style="border:1px #000 solid;"><?php echo $content->number ?></td>
                        <td style="border:1px #000 solid;"><?php echo $content->barcode;$num++ ?></td>
                        <td style="border:1px #000 solid;"><?php echo storehouse_move::getStorehouse($content->storehouseid) ?></td>
                        <td style="border:1px #000 solid;"><?php echo $content->statusvalue ?></td>
                    </tr>
                <?php endforeach;?>
            <?php endif;?>
            </tbody>
            <tfoot>
            <tr >
                <td colspan="2" style="padding-top: 40px"></td>
                <td colspan="2">运输人: <?php echo $row[0]->moveby ?></td>
                <td colspan="2" style="text-align: left">接收人: </td>
                <td>接收日期: </td>
            </tr>
            </tfoot>
        </table>
<!--        <table border="0" cellspacing="0" cellpadding="0" width="100%">-->
<!--            <tr>-->
<!--                <td>备注:</td>-->
<!--                <td>--><?php //echo $row[0]->remark ?><!--</td>-->
<!--            </tr>-->
<!--        </table>-->
    </div>
</div>


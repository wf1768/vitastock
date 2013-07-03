<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<script>

    var num = 0;
    var barcodes = {};

    function no_auto_handle_in() {
        var jsonStr = JSON.stringify(barcodes);

        if (jsonStr == '{}') {
            return;
        }
        bootbox.confirm("确定要将下列商品对办理入库吗？<br><font color='red'>注意：选择的商品办理入库后，将不能在办理入库。", function(result) {
            if(result){
                $.ajax({
                    type:"post",
                    data: "barcodes=" + jsonStr,
                    url:"<?php echo site_url('storehouse_in_content/no_auto_handle_in') ?>",
                    success: function(data){
                        if (data) {
                            alert('办理入库完毕。');
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

    function add_mode(mode,str) {
        $('#barcode_handle').hide();
        $('#multi_barcode_handle').hide();


        $('#'+mode).toggle();
        $('#mode').html(str);

        if (mode == 'barcode_handle') {
            $('#barcode').focus();
        }
        if (mode == 'multi_barcode_handle') {
            $('#multi_barcode').focus();
        }
    }

    function submit_barcode() {
        var jsonStr = JSON.stringify(barcodes);

        if (jsonStr == '{}') {
            return;
        }
        $.ajax({
            type:"post",
            data: "barcodes=" + jsonStr,
            url:"<?php echo site_url('storehouse_in_content/handle_in_by_multibarcode') ?>",
            success: function(data){
                if (data) {
                    var row = eval(data);

                    for (var i=0;i<row.length;i++) {
                        var str = '';
                        str += '<tr>';
                        str += '<td>'+ (num+1) + '</td>';
                        str += '<td><img class="thumbnail smallImg" src="<?php echo base_url('') ?>' + row[i].picpath + '"></td>';
                        str += '<td>' + row[i].title + '</td>';
                        str += '<td>' + row[i].code + '</td>';
//                        str += '<td>' + row[i].memo + '</td>';
//                        str += '<td>' + row[i].factoryname + '</td>';
                        str += '<td>' + row[i].brandname + '</td>';
                        str += '<td>' + row[i].typename + '</td>';
                        str += '<td>' + row[i].color + '</td>';
//                        str += '<td>' + row[i].format + '</td>';
                        str += '<td>' + row[i].number + '</td>';
                        str += '<td>' + row[i].barcode + '</td>';
                        str += '<td>' + row[i].boxno + '</td>';
                        if (row[i].statuskey == 0) {
                            str += '<td><font color="red">' + row[i].statusvalue + '</font></td>';
                        }
                        else {
                            str += '<td>' + row[i].statusvalue + '</td>';
                        }

                        str += '</tr>';
                        $('#handle_content').append(str);

                        var print_str = '';
                        print_str += '<tr>';
                        print_str += '<td>'+ (num+1) + '</td>';
                        print_str += '<td><img class="thumbnail smallImg" src="<?php echo base_url('') ?>' + row[i].picpath + '"></td>';
                        print_str += '<td>' + row[i].title + '</td>';
                        print_str += '<td>' + row[i].code + '</td>';
                        print_str += '<td>' + row[i].brandname + '</td>';
                        print_str += '<td>' + row[i].typename + '</td>';
                        print_str += '<td>' + row[i].number + '</td>';
                        print_str += '<td>' + row[i].color + '</td>';
                        print_str += '<td>' + row[i].barcode + '</td>';
                        print_str += '<td>' + row[i].boxno + '</td>';
                        print_str += '</tr>';
                        $('#print_content').append(print_str);


                        num++;
                    }
                    barcodes = {};
                }
                else {
                    openalert('处理商品出错，请重新尝试或与管理员联系。');
                }
            },
            error: function() {
                openalert('执行操作出错，请重新尝试或与管理员联系。');
            }
        });
    }

    /*
        刷新页面
     */
    function refresh_list() {
        $('#handle_content').html('');
        barcodes = {};
        $('#multi_barcode').val('');
        num = 0;
    }

    function print_list() {
        $(".my_show").jqprint({
            importCSS:true,
            debug:false
        });
    }

    function multi_barcode_handle() {
        bootbox.confirm("确定要将选择的商品办理入库吗？", function(result) {
            if(result){
                barcodes = {};
                var temp = $('#multi_barcode').val();
                var tmpbarcodes = temp.split("\n") ;

                if (tmpbarcodes.length > 0) {
                    var array_num = 0;
                    for (var i=0;i<tmpbarcodes.length;i++) {
                        var tmpbarcode = $.trim(tmpbarcodes[i]);
                        if (tmpbarcode != '') {
                            var tmp = new Object();
                            tmp.barcode = tmpbarcode;
                            barcodes[array_num] = tmp;
                            array_num++;
                        }
                    }
                    $('#multi_barcode').val('');
                    submit_barcode();
                }
            }
        })
    }

    $(function() {
        //控制文本框回车不提交
        document.getElementsByTagName('form')[0].onkeydown = function(e){
            var e = e || event;
            var keyNum = e.which || e.keyCode;
            return keyNum==13 ? false : true;
        };

        add_mode('barcode_handle');

        $("#auto_handle").click(function(){
            if($("#auto_handle").attr("checked") == 'checked'){
                $('#hand_in_btn').addClass('disabled');
            }else{
                $('#hand_in_btn').removeClass('disabled');
            }
        });
        $('#barcode').keyup(function(event){
            var e = event || window.event; //浏览器兼容
            if ($('#barcode').val() == '') {
                return;
            }
            var auto = 0;
            if($("#auto_handle").attr("checked") == 'checked'){
                auto = 1;
            }
            if (e.keyCode == 13) {
                $.ajax({
                    type:"post",
                    data: "barcode=" + $('#barcode').val() + '&auto=' + auto,
                    url:"<?php echo site_url('storehouse_in_content/handle_in_by_barcode')?>",
                    success: function(data){
                        if (data) {
                            var row = eval('['+data+']');
                            var str = '';
                            str += '<tr>';
                            str += '<td>'+ (num+1) + '</td>';
                            str += '<td><img class="thumbnail smallImg" src="<?php echo base_url('') ?>' + row[0].picpath + '"></td>';
                            str += '<td>' + row[0].title + '</td>';
                            str += '<td>' + row[0].code + '</td>';
//                            str += '<td>' + row[0].memo + '</td>';
//                            str += '<td>' + row[0].factoryname + '</td>';
                            str += '<td>' + row[0].brandname + '</td>';
                            str += '<td>' + row[0].typename + '</td>';
                            str += '<td>' + row[0].color + '</td>';
//                            str += '<td>' + row[0].format + '</td>';
                            str += '<td>' + row[0].number + '</td>';
                            str += '<td>' + row[0].barcode + '</td>';
                            str += '<td>' + row[0].boxno + '</td>';
                            if (row[0].statuskey == 0) {
                                str += '<td><font color="red">' + row[0].statusvalue + '</font></td>';
                            }
                            else {
                                str += '<td>' + row[0].statusvalue + '</td>';
                            }

                            str += '</tr>';
                            $('#handle_content').append(str);

                            var print_str = '';
                            print_str += '<tr>';
                            print_str += '<td>'+ (num+1) + '</td>';
                            print_str += '<td><img class="thumbnail smallImg" src="<?php echo base_url('') ?>' + row[0].picpath + '"></td>';
                            print_str += '<td>' + row[0].title + '</td>';
                            print_str += '<td>' + row[0].code + '</td>';
                            print_str += '<td>' + row[0].brandname + '</td>';
                            print_str += '<td>' + row[0].typename + '</td>';
                            print_str += '<td>' + row[0].number + '</td>';
                            print_str += '<td>' + row[0].color + '</td>';
                            print_str += '<td>' + row[0].barcode + '</td>';
                            print_str += '<td>' + row[0].boxno + '</td>';
                            print_str += '</tr>';
                            $('#print_content').append(print_str);

                            $('#barcode').val('');
                            //临时存barcode集合。手动入库用。
                            var tmp = new Object();
                            tmp.barcode = row[0].barcode;

                            barcodes[num] = tmp;
                            num++;

                        }
                        else {
                            $('#barcode').val('');
                        }
                    },
                    error: function() {
                        openalert("执行操作出错，请重新尝试或与管理员联系。");
                    }
                });
            }
        });

    })
</script>


<div id="content">
    <div class="container">
        <div class="row">
            <div class="span3">
                <?php $this->load->view('common/leftmenu'); ?>
            </div> <!-- /span3 -->
            <div class="span9">
                <h1 class="page-title">
                    <i class="icon-th-list"></i>
                    <a href="<?php echo site_url('stock') ?>" class="path-menu-a"> 库存管理</a> > <a href="<?php echo site_url('storehouse_in') ?>" class="path-menu-a"> 入库单管理</a> > 条形码办理入库
                </h1>
                <div class="row">
                    <div class="span9">
                        <label class="pull-right">
                            <div class="btn-group" class="pull-right">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="c-666">接收方式：</span>
                                    <strong id='mode'>条码扫描</strong>
                                    <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:;" onclick="add_mode('barcode_handle','条码扫描')"> 条码扫描</a></li>
                                    <li><a href="javascript:;" onclick="add_mode('multi_barcode_handle','条码批量')"> 条码批量</a></li>
                                </ul>
                            </div>
                        </label>
                    </div>
                </div>
                <span id="barcode_handle" >
                <div class="row">
                    <form method="GET" id="" class="form-inline">
                        <div class="span6">
                            <label >条形码: <input id="barcode" name="barcode" type="text" class="span3 input-medium" placeholder="扫描条形码" value=""></label>
                            <label class="checkbox">
                                <input id="auto_handle" type="checkbox"> 自动入库
                            </label>
                        </div>
                        <div class="span3">
                            <label class="pull-right">
                            <a href="javascript:;" id="hand_in_btn" name="hand_in_btn" class="btn" onclick="no_auto_handle_in()">
                                <i class="icon-barcode"> 办理入库</i>
                            </a>
                            <a href="javascript:;" class="btn btn-primary" onclick="refresh_list()">
                                <i class="icon-refresh"> 刷新</i>
                            </a>
                            <a href="javascript:;" class="btn btn-primary" onclick="print_list()">
                                <i class="icon-print"> 打印</i>
                            </a>
                            </label>
                        </div>
                    </form>
                </div>
                </span>
                <span id="multi_barcode_handle" >
                    <div class="row">
                        <form method="GET" id="" class="form-inline">
                            <div class="span9">
                                <label >条形码:
                                    <textarea id="multi_barcode" class="span3" style="margin-top: 10px;">

                                    </textarea>
                                    <a href="javascript:;" id="multi_hand_in_btn" name="hand_in_btn" class="btn btn-primary" onclick="multi_barcode_handle()">
                                        <i class="icon-barcode"> 办理入库</i>
                                    </a>
                                    <a href="javascript:;" class="btn btn-primary" onclick="refresh_list()">
                                        <i class="icon-refresh"> 刷新</i>
                                    </a>
                                    <a href="javascript:;" class="btn btn-primary" onclick="print_list()">
                                        <i class="icon-print"> 打印</i>
                                    </a>
                                </label>
                            </div>
                        </form>
                    </div>
                </span>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>入库商品列表</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                         <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>缩略图</th>
                                <th>名称</th>
                                <th>代码</th>
<!--                                <th>描述</th>-->
<!--                                <th>厂家</th>-->
                                <th>品牌</th>
                                <th>类别</th>
                                <th>颜色</th>
<!--                                <th>材质等级</th>-->
                                <th>数量</th>
                                <th>条形码</th>
                                <th>箱号</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody id="handle_content">
                            <?php if(isset($list)):?>
                                <?php foreach($list as $row):?>
                                <tr>
                                    <td><input type="checkbox" name="checkbox" value="<?php echo $row->id ?>"/></td>
                                    <td><a href="<?php echo site_url('storehouse_in/show?id='.$row->id) ?>"><?php echo $row->innumber ?></a></td>
                                    <td><?php echo $row->createtime ?></td>
                                    <td><?php echo $row->createby ?></td>
                                    <td><?php echo $row->checkby ?></td>
                                    <td><?php echo strtotime($row->overtime)?$row->overtime:'';  ?></td>
                                    <td><?php echo $row->fromcode ?></td>
                                    <td><?php echo ($row->instatus == 0)? '<font color="red">未结束</font>':'已结束' ?></td>
                                </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div> <!-- /widget-content -->
                </div> <!-- /widget -->
            </div> <!-- /span9 -->
        </div> <!-- /row -->
    </div> <!-- /container -->
</div> <!-- /content -->

<?php $this->load->view("common/footer"); ?>

<div id="print" style="height:0px;width:0px;overflow:hidden">
    <div class="my_show">
        <div class="widget widget-table">
            <div class="widget-header">
                <i class="icon-th-list"></i>
                <h3>商品列表</h3>
            </div>
            <div class="widget-content">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>缩略图</th>
                        <th>商品名称</th>
                        <th>代码</th>
                        <th>品牌</th>
                        <th>类别</th>
                        <th>数量</th>
                        <th>颜色</th>
                        <th>条形码</th>
                        <th>箱号</th>
                    </tr>
                    </thead>
                    <tbody id="print_content">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

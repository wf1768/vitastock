<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<script>

    var array_num = 0;
    var barcodes = {};

    var num = 0;

    function add_mode(mode,str) {
        $('#self_handle').hide();
        $('#barcode_handle').hide();
        $('#multi_barcode_handle').hide();


        $('#'+mode).toggle();
        $('#mode').html(str);

        if (mode == 'barcode_handle') {
            $('#tiaoma').focus();
        }
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
            url:"<?php echo site_url('storehouse_move/handle_move_stock') ?>",
            success: function(data){
                if (data) {
                    if (data == 'over') {
                        window.location.reload();
                        return;
                    }
                    if (data != '[]') {
                        var rows = eval(data);
                        var str = '';
                        for(var i=0;i<rows.length;i++) {
                            str += '<tr>';
                            str += '<td>'+ (num+1) + '</td>';
                            str += '<td>' + rows[i].barcode + '</td>';
                            str += '<td>' + rows[i].dealtime + '</td>';
                            str += '<td>' + rows[i].dealby + '</td>';
                            str += '<td>' + rows[i].remark + '</td>';
                            str += '</tr>';
                            num++;
                        }
                        $('#handle_move_content').html(str);
                    }
                    barcodes = {};
                    array_num = 0;
                    num = 0;
                }
                else {
                    openalert('调拨单接收商品出错，请重新尝试或与管理员联系。');
                }
            },
            error: function() {
                openalert('执行操作出错，请重新尝试或与管理员联系。');
            }
        });
    }

    function multi_barcode_handle() {

        bootbox.confirm("确定要接收商品吗？", function(result) {
            if(result){
                var temp = $('#multi_barcode').val();
                var barcodes = temp.split("\n") ;


                for (var i=0;i<barcodes.length;i++) {
                    var barcode = $.trim(barcodes[i]);
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
        })
    }

    function self_barcode_handle() {
        var str="";
        $("input[name='checkbox_move']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择要接收的调拨商品。');
            return;
        }
        str = str.substring(0,str.length-1);

        var barcodes = str.split(",") ;


        for (var i=0;i<barcodes.length;i++) {
            var barcode = $.trim(barcodes[i]);
            if (barcode != '') {
                var tmp = new Object();
                tmp.barcode = barcodes[i];
                add_barcode(tmp);
            }
        }
        //提交
        submit_barcode();
    }

    $(function() {
        //控制form里的文本框回车不提交
        $(".navbar-form").keydown(function(e) {
//            var e = event || window.event; //浏览器兼容
            var e = e || event;
            var keyNum = e.which || e.keyCode;
            return keyNum==13 ? false : true;
        })
        add_mode('barcode_handle');

        $("#select-all-content").click(function(){
            if ($(this).attr("checked") == 'checked') {
                $("input[name='checkbox_move']").attr("checked",$(this).attr("checked"));
            }
            else {
                $("input[name='checkbox_move']").attr("checked",false);
            }
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
                    <a href="<?php echo site_url('storehouse_move') ?>" class="path-menu-a"> 调拨管理</a> > <a href="<?php echo site_url('storehouse_move/handle') ?>" class="path-menu-a"> 调拨商品管理</a> > 浏览
                </h1>
                <div class="widget widget-table my_show">
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
                                <th><input type="checkbox" id="select-all-content""></th>
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
                            <tbody id="move_content">
                            <?php if(isset($stock_move_content)):?>
                                <?php $num = 1; foreach($stock_move_content as $content):?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox_move" value="<?php echo $content->barcode ?>"/></td>
                                        <td><?php echo $num ?></td>
                                        <td><a href="javascript:;" data-html="true" data-trigger="hover" data-toggle="popover" data-content="<img src='<?php echo base_url($content->picpath) ?>' />" ><img src="<?php echo base_url($content->picpath) ?>"  alt="" class="thumbnail smallImg" /></a></td>
                                        <td><?php echo $content->title ?></td>
                                        <td><?php echo $content->code ?></td>
                                        <td><?php echo $content->memo ?></td>
                                        <td><?php echo $content->factoryname ?></td>
                                        <td><?php echo $content->brandname ?></td>
                                        <td><?php echo $content->typename ?></td>
                                        <td><?php echo $content->salesprice ?></td>
                                        <td><?php echo $content->color ?></td>
                                        <td><?php echo $content->number ?></td>
                                        <td><?php echo $content->barcode;$num++ ?></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div> <!-- /widget-content -->
                </div>
                <?php if ($row[0]->status == 1) : ?>
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
                                    <li><a href="javascript:;" onclick="add_mode('self_handle','手动接收')"> 手动接收</a></li>
                                </ul>
                            </div>
                        </label>
                    </div>
                </div>
                <span id="barcode_handle" >
                <div class="minbox">
                    <div class="part_search">
                        <div class="navbar">
                            <div class="navbar-inner">
                                <form class="navbar-form" method="post" id="searchfrom" style="margin-bottom:5px">
                                    <font class="myfont">&nbsp;&nbsp;&nbsp;&nbsp;条形码：</font>
                                    <input type="text" name="barcode" id="tiaoma" value="" placeholder="请输入条形码">
                                    <a href="<?php echo site_url('storehouse_move/handle') ?>" class="btn">返回</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </span>
                <span id="multi_barcode_handle" >
                <div class="minbox">
                    <div class="part_search">
                        <div class="navbar">
                            <div class="navbar-inner">
                                <form class="navbar-form1" method="post" id="searchfrom" style="margin-bottom:5px">
<!--                                    <input type="hidden"  name="search_type" value="1">-->
                                    <font class="myfont">&nbsp;&nbsp;&nbsp;&nbsp;条形码：</font>
                                    <textarea id="multi_barcode" style="margin-top: 10px;">

                                    </textarea>
                                    <a href="javascript:;" onclick="multi_barcode_handle('<?php echo $row[0]->id ?>')" class="btn btn-primary">接收商品</a>
                                    <a href="<?php echo site_url('storehouse_move/handle') ?>" class="btn">返回</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </span>
                <span id="self_handle" >
                <div class="minbox">
                    <div class="part_search">
                        <div class="navbar">
                            <div class="navbar-inner">
                                    <button style="margin-left:20px" id="search" type="button" class="btn btn-primary" onclick="self_barcode_handle()">接收</button>
                            </div>
                        </div>
                    </div>
                </div>

                </span>
                <?php endif ?>
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
                                <?php if(isset($move_content_deal)):?>
                                    <?php $num = 1; foreach($move_content_deal as $content):?>
                                        <tr>
                                            <td><?php echo $num ?></td>
                                            <td><?php echo $content['barcode'] ?></td>
                                            <td><?php echo $content['dealtime'] ?></td>
                                            <td><?php echo $content['dealby'] ?></td>
                                            <td><?php echo $content['remark'];$num++ ?></td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div> <!-- /widget-content -->
                </div>
            </div>
            <!-- /span9 -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div> <!-- /content -->

<?php $this->load->view("common/footer"); ?>

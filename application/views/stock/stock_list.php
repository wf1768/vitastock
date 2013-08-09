<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<script type="text/javascript" src="<?php echo base_url('stock_plugins/plupload/js/plupload.full.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('stock_plugins/plupload/js/i18n/zh-cn.js') ?>"></script>

<script>

    function remove_stock() {
        var str = "";
        $("input[name='checkbox']").each(function () {
            if ($(this).attr("checked") == 'checked') {
                str += $(this).val() + ",";
            }
        })
        if (str == "") {
            openalert('请选择要删除的商品。');
            return;
        }

        if (confirm("确定要删除选择的商品吗？")) {

            str = str.substring(0, str.length - 1);

            $.ajax({
                type: "post",
                data: "id=" + str,
                url: "<?php echo site_url('stock/remove_stock')?>",
                success: function (data) {
                    if (data) {
                        window.location.reload();
                    }
                    else {
                        openalert("删除商品出错，请重新尝试或与管理员联系。");
                    }
                },
                error: function () {
                    openalert("执行操作出错，请重新尝试或与管理员联系。");
                }
            });
        }
        else {
            return;
        }
    }

    function upload_pic(id) {
        var uploader = new plupload.Uploader({
            runtimes: 'html5,flash',
            browse_button: 'selectpic', // 单击时间的id
            max_file_size: '2mb',
            unique_names: true,
            url: '<?php echo site_url('stock/upload_stock_image?id=') ?>' + id,
            multiple_queues: false,
            multi_selection: false,
            multipart_params: {},
            flash_swf_url: '<?php echo base_url('stock_plugins/plupload/js/plupload.flash.swf') ?>',
            filters: [
                {title: "图片文件", extensions: "png,jpg,jpeg,gif"}
            ]// 允许的文件后缀
        });
        uploader.bind('UploadProgress', function (up, file) {
            //上传的进度
            $('#' + file.id + " b").html(file.percent + "%");
        });

        uploader.bind('QueueChanged', function () {
            //上传的队列改变 也就是选择文件的完成
        });
        uploader.bind('FilesAdded', function (up, files) {
            $.each(files, function (i, file) {
                $('#filelist').append(
                    '<div id="' + file.id + '">' +
                        file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                        '</div>');
            });

            up.refresh(); // Reposition Flash/Silverlight
//            for (var i in files) {
//                alert(files[i].name);
//                $('#filelist').innerHTML += '<div id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>';
//            }
        });

        uploader.bind('FileUploaded', function (up, file, response) {
            //上传图片已改名，ajax传回新文件名
            var data = $.parseJSON(response.response);
            $('#pic').attr('src', '<?php echo base_url('') ?>/' + data.newfilename);
        });


        uploader.bind("Error", function (up, err) {
            $('#filelist').append("<div>Error: " + err.code +
                ", Message: " + err.message +
                (err.file ? ", File: " + err.file.name : "") +
                "</div>"
            );

            up.refresh(); // Reposition Flash/Silverlight
            // 上传失败
        });

        $('#uploadfiles').click(function (e) {
            uploader.start();
            e.preventDefault();
        });

        uploader.init();//初始化
        //uploader.start();// 开始上传 自己定义事件

        $('#upload-pic-dialog').modal('show');
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

    function print_list() {
        $(".my_show").jqprint({
            importCSS:true,
            debug:false
        });
    }

    $(function () {
        var storehouseid = '<?php echo $storehouseid ?>'; //$('#storehouse').val();
        $('#add').attr('href', '<?php echo site_url() ?>' + '/stock/add?houseid=' + storehouseid);

        $('#houseid').change(function () {
            $('#stock_list_btn_form').submit();
        });

        $("#select-all").click(function () {
            if ($(this).attr("checked") == 'checked') {
                $("input[name='checkbox']").attr("checked", $(this).attr("checked"));
            }
            else {
                $("input[name='checkbox']").attr("checked", false);
            }
        });

        $('#barcode').keyup(function (event) {
            var e = event || window.event; //浏览器兼容
            if (e.keyCode == 13) {
                $('#stock_list_btn_form').submit();
            }
        });

        $('#upload-pic-dialog').on('hidden', function () {
            window.location.reload();
        })

        $("a[data-toggle=popover]").popover();

        $("#supsearchit").click(function () {
            $("#supsearch").toggle();
            $("#default_search").toggle();

        });

        var search_type = <?php echo $search_type ?>;
        if (search_type == 1) {
            $("#supsearch").toggle();
            $("#default_search").toggle();
        }
    })
</script>
<div id="upload-pic-dialog" class="modal hide fade" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        上传商品图片
    </div>
    <div class="modal-body">
        <div id="filelist" name="filelist"></div>
        <br/>
        <img src="" id="pic">
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a href="javascript:;" id="selectpic" class="btn btn-primary">选择图片</a>
        <a href="javascript:;" id="uploadfiles" onclick="" class="btn btn-primary">上传</a>
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
                    <a href="<?php echo site_url('stock') ?>" class="path-menu-a"> 库存管理</a> > 商品管理
                </h1>
                <form id="multi_barcode_form" method="post" action="<?php echo site_url('stock/multi_barcode') ?>">
                    <input type="hidden" id="stockids" name="stockids" value="">
                    <input type="hidden" id="path" name="path" value="stock/stock_pages?houseid=<?php echo $storehouseid ?>&status=1">
                <div class="row">
                    <div class="span9">
                        <label class="pull-right">
                            <?php if ($oper) : ?>
                            <a href="#" id="add" class="btn btn-small">
                                <i class="icon-plus"> 添加</i>
                            </a>

                            <a href="javascript:;" class="btn btn-small" onclick="remove_stock()">
                                <i class="icon-minus"> 删除</i>
                            </a>
                            <a href="javascript:;" class="btn btn-small" onclick="multi_barcode_print()">
                                <i class="icon-barcode"> 批量打印条码</i>
                            </a>
                            <?php endif ?>
<!--                            <a href="javascript:;" id="supsearchit" class="btn btn-small">-->
<!--                                <i class="icon-search"> 高级检索</i>-->
<!--                            </a>-->
                            <?php if ($oper) : ?>
                            <a href="javascript:;" class="btn btn-small" onclick="print_list()">
                                <i class="icon-print"> 打印</i>
                            </a>
                            <?php endif ?>
                        </label>
                    </div>
                </div>
                </form>
                <div id="default_search" class="row">
                    <form id="stock_list_btn_form" action="<?php echo site_url('stock/stock_pages') ?>" name="stock_list_btn_form" method="POST" class="form-inline">
                        <div class="span5">
                            <label>
                                <input type="hidden"  name="search_type" value="0">
                                切换地点
                                <select id="houseid" name="houseid" class="span2">
                                    <option value="0" <?php if ('0' == $storehouseid): ?>selected="true"<?php endif; ?>>全部</option>
                                    <?php if ($houses): ?>
                                        <?php foreach ($houses as $house): ?>
                                            <option value="<?php echo $house->id ?>"
                                                    <?php if ($house->id == $storehouseid): ?>selected="true"<?php endif; ?> ><?php echo $house->storehousecode ?></option>
                                        <?php endforeach; ?>
                                    <?php endif;?>
                                </select>
                            </label>
                        </div>
                        <div class="span4">
                            <input type="text" style="display:none">
                            <label class="pull-right">查询条码: <input id="barcode" name="barcode" type="text"
                                                                   value="<?php echo $search ?>"></label>
                        </div>
                    </form>
                </div>
                <span id="supsearch" style="display:none">
                <div class="minbox">
                    <div class="part_search">
                        <div class="navbar">
                            <div class="navbar-inner">
                                <form class="navbar-form" method="get" id="searchfrom" style="margin-bottom:5px">
                                    <input type="hidden"  name="search_type" value="1">
                                    <input type="hidden" id="houseid" name="houseid" value="<?php echo $storehouseid ?>">
                                    <font class="myfont">&nbsp;&nbsp;&nbsp;&nbsp;条形码：</font>
                                    <input type="text" name="barcode" id="tiaoma"
                                           value="<?php echo isset($_REQUEST['barcode']) ? $_REQUEST['barcode'] : ''; ?>"
                                           placeholder="请输入条形码">

                                    &nbsp;&nbsp;<font class="myfont">商品名称：</font>
                                    <input type="text" id="" name="title"
                                           value="<?php echo isset($_REQUEST['title']) ? $_REQUEST['title'] : ''; ?>"
                                           placeholder="请输入商品名称"><br/>
                                    <font class="myfont">商品厂家：</font>
                                    <select name="factorycode">
                                        <option value="">请选择</option>
                                        <?php foreach ($factory as $val): ?>
                                            <option value="<?php echo $val->factorycode; ?>"
                                                <?php if (isset($_REQUEST['factorycode']) && $_REQUEST['factorycode'] == $val->factorycode) {
                                                echo "selected";
                                            }?>><?php echo $val->factoryname;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    &nbsp;&nbsp;<font class="myfont">商品颜色：</font>
                                    <input type="text" name="color" id="color"
                                           value="<?php echo isset($_REQUEST['color']) ? $_REQUEST['color'] : ''; ?>"
                                           placeholder="请输入商品颜色">
                                    <br/>
                                    <font class="myfont">商品箱号：</font>
                                    <input type="text" name="boxno" id="boxno"
                                           value="<?php echo isset($_REQUEST['boxno']) ? $_REQUEST['boxno'] : ''; ?>"
                                           placeholder="请输入商品箱号">
                                    &nbsp;&nbsp;<font class="myfont">商品类别：</font>
                                    <select name="typecode">
                                        <option value="">请选择</option>
                                        <?php foreach ($type as $val): ?>
                                            <option value="<?php echo $val->typecode; ?>"
                                                <?php if (isset($_REQUEST['typecode']) && $_REQUEST['typecode'] == $val->typecode) {
                                                echo "selected";
                                            }?>><?php echo $val->typename;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <br />
                                    <font class="myfont">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;库房：</font>
                                    <select id="houseid" name="houseid" ">
                                        <option value="0" <?php if ('0' == $storehouseid): ?>selected="true"<?php endif; ?>>全部</option>
                                        <?php if ($houses): ?>
                                            <?php foreach ($houses as $house): ?>
                                                <option value="<?php echo $house->id ?>"
                                                        <?php if ($house->id == $storehouseid): ?>selected="true"<?php endif; ?> ><?php echo $house->storehousecode ?></option>
                                            <?php endforeach; ?>
                                        <?php endif;?>
                                    </select>
                                    &nbsp;&nbsp;<font class="myfont">商品状态：</font>
                                    <select name="status" id="status">
                                        <option value="1000" <?php if ($status == '1000' ): ?>selected="true"<?php endif; ?>>全部</option>
                                        <option value="0" <?php if ($status == '0') { echo "selected"; }?> >未入库</option>
                                        <option value="1" <?php if ($status == '1') { echo "selected"; }?> >在库</option>
                                        <option value="3" <?php if ($status == '3') { echo "selected"; }?> >已销售</option>
                                        <option value="4" <?php if ($status == '4') { echo "selected"; }?> >已配送</option>
                                    </select>
                                    <br />
                                    <button style="margin-left:20px" id="search" type="submit" class="btn btn-primary">&nbsp;&nbsp;搜&nbsp;&nbsp;索&nbsp;&nbsp;</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                </span>
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
                                <th><input type="checkbox" id="select-all""></th>
                                <th>缩略图</th>
                                <th>商品名称</th>
                                <th>代码</th>
                                <th>商品描述</th>
                                <th>厂家</th>
<!--                                <th>品牌</th>-->
                                <th>类别</th>
<!--                                <th>数量</th>-->
<!--                                <th>规格</th>-->
                                <th>颜色</th>
                                <th>条形码</th>
                                <th>售价</th>
                                <th>库房</th>
                                <th>状态</th>
                                <?php if ($oper) : ?>
                                <th>操作</th>
                                <?php endif ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($list)): ?>
                                <?php foreach ($list as $row): ?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox" value="<?php echo $row->id ?>"/></td>
                                        <td><a href="javascript:;" data-html="true" data-trigger="hover"
                                               data-toggle="popover"
                                               data-content="<img src='<?php echo base_url($row->picpath) ?>' />"
                                               onclick="upload_pic('<?php echo $row->id ?>')"><img
                                                    src="<?php echo base_url($row->picpath) ?>" alt=""
                                                    class="thumbnail smallImg"/></a></td>
                                        <td>
                                            <?php if ($search_type == 0) : ?>
                                                <a href="<?php echo site_url('stock/stock_show?houseid=' . $storehouseid . '&stockid=' . $row->id) . '&p=' . $p . '&barcode=' . $search ?>"><?php echo $row->title ?></a>
                                            <?php endif ?>
                                            <?php if ($search_type == 1) : ?>
                                                <a href="<?php echo site_url('stock/stock_show?houseid=' . $storehouseid . '&stockid=' . $row->id) . $super_search ?>"><?php echo $row->title ?></a>
                                            <?php endif ?>
                                        </td>
                                        <td title="<?php echo $row->code ?>"><?php echo Common::subStr($row->code, 0, 10) ?></td>
                                        <td title="<?php echo $row->memo ?>"><?php echo Common::subStr($row->memo, 0, 20) ?></td>
                                        <td><?php echo $row->factoryname ?></td>
<!--                                        <td>--><?php //echo $row->brandname ?><!--</td>-->
                                        <td><?php echo $row->typename ?></td>
<!--                                        <td>--><?php //echo $row->number ?><!--</td>-->
<!--                                        <td>--><?php //echo $row->format ?><!--</td>-->
                                        <td><?php echo $row->color ?></td>
                                        <td><?php echo $row->barcode ?></td>
                                        <td><?php echo $row->salesprice ?></td>
                                        <td><?php
                                            foreach ($storehouses as $house) {
                                                if ($row->storehouseid == $house->id) {
                                                    echo $house->storehousecode;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $row->statusvalue ?></td>
                                        <?php if ($oper) : ?>
                                        <?php if ($search_type == 0) : ?>
                                        <td><a class="btn btn-mini btn-primary"
                                               href="<?php echo site_url('stock/edit?houseid=' . $storehouseid . '&stockid=' . $row->id . '&p=' . $p . '&search=' . $search) ?>">修改</a>
                                        </td>
                                        <?php endif ?>
                                        <?php if ($search_type == 1) : ?>
                                            <td><a class="btn btn-mini btn-primary"
                                                   href="<?php echo site_url('stock/edit?houseid=' . $storehouseid . '&stockid=' . $row->id . $super_search) ?>">修改</a>
                                            </td>
                                        <?php endif ?>
                                        <?php endif ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /widget-content -->
                    <div class="row">
                        <div class="span4" style="margin-top:20px ">
                            <?php echo (isset($info)) ? $info : '' ?>
                        </div>
                        <div class=" pagination pagination-right">
                            <?php
                            echo (isset($page)) ? $page : '';
                            ?>
                        </div>
                    </div>
                </div>
                <!-- /widget -->
            </div>
            <!-- /span9 -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div> <!-- /content -->

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
<!--                    <th>商品描述</th>-->
                    <th>品牌</th>
                    <th>类别</th>
                    <th>数量</th>
                    <th>颜色</th>
                    <th>条形码</th>
<!--                    <th>状态</th>-->
                    <th>箱号</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($list)): ?>
                    <?php $num=0; foreach ($list as $row): ?>
                        <tr>
                            <td><?php echo $num+1 ?></td>
                            <td><img src="<?php echo base_url($row->picpath) ?>" alt="" class="thumbnail smallImg"/></td>
                            <td><?php echo $row->title ?></td>
                            <td><?php echo $row->code ?></td>
<!--                            <td>--><?php //echo $row->memo ?><!--</td>-->
                            <td><?php echo $row->brandname ?></td>
                            <td><?php echo $row->typename ?></td>
                            <td><?php echo $row->number ?></td>
                            <td><?php echo $row->color ?></td>
                            <td><?php echo $row->barcode ?></td>
<!--                            <td>--><?php //echo $row->statusvalue ?><!--</td>-->
                            <td><?php echo $row->boxno;$num++ ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
<?php $this->load->view("common/footer"); ?>

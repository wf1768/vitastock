<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<script type="text/javascript" src="<?php echo base_url('stock_plugins/plupload/js/plupload.full.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('stock_plugins/plupload/js/i18n/zh-cn.js') ?>"></script>

<script>

    //获取条形码
    function get_barcode() {
        $.ajax({
            type:"post",
            data: "factoryid=" + $('#factory').val() + "&brandid=" + $('#brand').val() + '&commoditytypeid=' + $('#commoditytype').val(),
            url:"<?php echo site_url('barcode/get_uniqid')?>",
            success: function(data){
                $('#barcode').val(data);
                $('#barcode-image').attr('src',$('#path').val() + '/barcode/buildcode/BCGcode128/' + data);
            },

            error: function() {
                openalert("读取条形码数据出错，请重新尝试或与管理员联系。");
            }
        });
    }

    //作废
    function remove_pic(picid) {
        if (picid == '') {
            return;
        }
        if(confirm("确定要删除选择的商品图片吗？")) {
            $.ajax({
                type:"post",
                data: "picid=" + picid,
                url:"<?php echo site_url('stock/remove_pic')?>",
                success: function(data){
                    if (data) {
                        window.location.reload();
                    }
                    else {
                        openalert("删除商品图片出错，请重新尝试或与管理员联系。");
                    }
                },

                error: function() {
                    openalert("删除商品图片出错，请重新尝试或与管理员联系。");
                }
            });
        }
        else {
            return;
        }

    }

    //作废
    function set_pic_main(stockid,picid) {
        if (picid == '' || stockid == '') {
            return;
        }
        if(confirm("确定要设置选择的商品图片为默认封皮图片吗？")) {
            $.ajax({
                type:"post",
                data: "picid=" + picid + '&stockid=' + stockid,
                url:"<?php echo site_url('stock/set_pic_main')?>",
                success: function(data){
                    if (data) {
                        window.location.reload();
                    }
                    else {
                        openalert("设置商品图片出错，请重新尝试或与管理员联系。");
                    }
                },

                error: function() {
                    openalert("设置商品图片出错，请重新尝试或与管理员联系。");
                }
            });
        }
        else {
            return;
        }
    }

    //作废
    function open_upload_dialog() {
        /**
         * 初始化上传控件
         * @type {plupload.Uploader}
         */
        $('#upload-form').submit(function(e) {
            var uploader = $('#uploader').pluploadQueue();
             // Files in queue upload them first
            if (uploader.files.length > 0) {
                // When all files are uploaded submit form
                uploader.bind('StateChanged', function() {
                    if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                        $('#upload-form')[0].submit();
                    }
                });
                uploader.start();
            } else {
                openalert('请先上传数据文件!');
            }
            return false;
        });
        $('#upload-dialog').modal('show');
    }


    function upload_pic(id) {
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash',
            browse_button : 'selectpic', // 单击时间的id
            max_file_size : '2mb',
            unique_names : true,
            url : '<?php echo site_url('stock/upload_stock_image?id=') ?>' + id,
            multiple_queues : false,
            multi_selection : false,
            multipart_params:{},
            flash_swf_url : '<?php echo base_url('stock_plugins/plupload/js/plupload.flash.swf') ?>',
            filters : [{title : "图片文件", extensions : "png,jpg,jpeg,gif"}]// 允许的文件后缀
        });
        uploader.bind('UploadProgress', function(up, file) {
            //上传的进度
            $('#' + file.id + " b").html(file.percent + "%");
        });

        uploader.bind('QueueChanged', function(){
            //上传的队列改变 也就是选择文件的完成
        });
        uploader.bind('FilesAdded', function(up, files) {
            $.each(files, function(i, file) {
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

        uploader.bind('FileUploaded', function(up, file, response) {
            //上传图片已改名，ajax传回新文件名
            var data = $.parseJSON(response.response);
            $('#pic').attr('src','<?php echo base_url('') ?>/' + data.newfilename);
        });


        uploader.bind("Error", function(up, err) {
            $('#filelist').append("<div>Error: " + err.code +
                ", Message: " + err.message +
                (err.file ? ", File: " + err.file.name : "") +
                "</div>"
            );

            up.refresh(); // Reposition Flash/Silverlight
            // 上传失败
        });

        $('#uploadfiles').click(function(e) {
            uploader.start();
            e.preventDefault();
        });

        uploader.init();//初始化
        //uploader.start();// 开始上传 自己定义事件

        $('#upload-pic-dialog').modal('show');
    }

    $(function() {
        $('#upload-pic-dialog').on('hidden', function () {
            window.location.reload();
        })

        $("#doback").click(function(){
            history.back();
            return false;
        });

        //准备按件数打印
        $('#print_item').html('');
        var barcode_str = '';

        //获得件数
        var itemnumber = '<?php echo $row[0]->itemnumber ?>';
        var barcode = '<?php echo $row[0]->barcode ?>';

        var title = '<?php echo $row[0]->title ?>';
        var code = '<?php echo $row[0]->code ?>';
        var factoryname = '<?php echo $row[0]->factoryname ?>';
        var memo = '<?php echo $row[0]->memo ?>';

        for (var i=0;i<parseInt(itemnumber);i++) {
            barcode_str += '<div class="print_item_show">';
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
            barcode_str += '<td>'+itemnumber+'  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;件号: -'+ parseInt(i+1) +'</td>';
            barcode_str += '</tr>';
            barcode_str += '<tr>';
            barcode_str += '<td>描述:</td>';
            barcode_str += '<td>'+memo+'</td>';
            barcode_str += '</tr>';
            barcode_str += '</table>';
            barcode_str += '</div>';
        }

        if (barcode_str != '') {
            $('#print_item').html(barcode_str);
        }
    })

    function onPrint() {
        $(".my_show").jqprint({
            importCSS:true,
            debug:false
        });
    }

    function onPrint_item() {
        $(".print_item_show").jqprint({
            importCSS:true,
            debug:false
        });
    }

</script>
<div id="upload-pic-dialog" class="modal hide fade" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        上传商品图片
    </div>
    <div class="modal-body">
        <div id="filelist" name="filelist" ></div>
        <br />
        <img src="" id="pic" >
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
                    <a href="<?php echo site_url('stock') ?>" class="path-menu-a"> 库存管理</a> > <a href="<?php echo site_url('stock/stock_pages?houseid='.$storehouseid.'&p='.$p.'&barcode='.$search) ?>" class="path-menu-a"> 商品管理</a> > 浏览
                </h1>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>商品信息</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                        <?php if ($row) : ?>
                            <table class="table table-bordered" width="100%">
                                <tr>
                                    <td>名称</td>
                                    <td colspan="3"><?php echo $row[0]->title ?></td>
                                    <td rowspan="6" width="260px">
                                        <ul class="thumbnails">
                                            <li class="span3">
                                                <a class="thumbnail" href="javascript:;" onclick="upload_pic('<?php echo $row[0]->id ?>')">
                                                    <img style="width: 260px; height: 180px;" src="<?php echo base_url().$row[0]->picpath ?>">
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>代码</td>
                                    <td><?php echo $row[0]->code ?></td>
                                    <td>所属库房</td>
                                    <td><?php echo $housecode ?></td>
                                </tr>
                                <tr>
                                    <td>描述</td>
                                    <td colspan="3"><?php echo $row[0]->memo ?></td>
                                </tr>
                                <tr>
                                    <td>厂家</td>
                                    <td><?php echo $row[0]->factoryname ?></td>
                                    <td>品牌</td>
                                    <td><?php echo $row[0]->brandname ?></td>
                                </tr>
                                <tr>
                                    <td>类别</td>
                                    <td><?php echo $row[0]->typename ?></td>
                                    <td>数量</td>
                                    <td><?php echo $row[0]->number ?></td>
                                </tr>
                                <tr>
                                    <td>售价(￥)</td>
                                    <td><?php echo $row[0]->salesprice ?></td>
                                    <td>颜色</td>
                                    <td><?php echo $row[0]->color ?></td>
                                </tr>
                                <?php if ($this->account_info_lib->power == 2) : ?>
                                    <tr>
                                        <td>单价(€)</td>
                                        <td><?php echo $row[0]->cost ?></td>
                                        <td>标准单价(€)</td>
                                        <td colspan="2"><?php echo $row[0]->standardcost ?></td>
                                    </tr>
                                <?php endif ?>
                                <tr>
                                    <td>材质等级</td>
                                    <td><?php echo $row[0]->format ?></td>
                                    <td>箱号</td>
                                    <td colspan="2"><?php echo $row[0]->boxno ?></td>
                                </tr>
                                <tr>
                                    <td>件数</td>
                                    <td><?php echo $row[0]->itemnumber ?></td>
                                    <td>状态</td>
                                    <td colspan="2"><?php echo ($row[0]->statuskey == 0)? '<font color="red">'.$row[0]->statusvalue.'</font>':$row[0]->statusvalue ?></td>
                                </tr>
                                <tr>
                                    <td>条形码</td>
                                    <td colspan="4"><?php echo $row[0]->barcode ?></td>
                                </tr>
                                <tr>
                                    <td>条形码样式</td>
                                    <td colspan="4"><img id="barcode-image" src="<?php echo (!empty($row[0]->barcode)) ? site_url('barcode/buildcode/BCGcode128/'.$row[0]->barcode):'' ?>" alt="" /></td>
                                </tr>
                                <tr>
                                    <td>备注</td>
                                    <td colspan="4"><?php echo $row[0]->remark ?></td>
                                </tr>
                            </table>
                        <?php endif ?>
                    </div> <!-- /widget-content -->
                    <div class="form-actions">
                        <?php if ($oper) : ?>
                        <button id="btn-p" type="button" onclick="onPrint()" class="btn btn-primary"> 打印条码</button>
                        <button id="btn-p" type="button" onclick="onPrint_item()" class="btn btn-primary"> 件数打印条码</button>
                        <?php endif ?>
                        <?php if ($this->session->userdata('search_type') == '0') : ?>
                            <a href="<?php echo site_url('stock/stock_pages?houseid='.$storehouseid.'&p='.$p.'&barcode='.$search) ?>"
                               class="btn">返回</a>
                        <?php endif ?>
                        <?php if ($this->session->userdata('search_type') == '1') : ?>
<!--                            <a href="--><?php //echo site_url('stock/stock_pages?houseid='.$storehouseid.$this->session->userdata('super_search')) ?><!--"-->
<!--                               class="btn">返回</a>-->
                            <input class="btn btn-primary"  type="button" id="doback" value="返回">
                        <?php endif ?>

                    </div>
                </div>
                <!-- /row style="width: 100px;height: 60px"  style="display: none"-->

                <div style="height:0px;width:0px;overflow:hidden">
                    <div class="my_show">
                        <table>
                            <tr>
                                <td colspan="2"><img id="barcode-image" src="<?php echo (!empty($row[0]->barcode)) ? site_url('barcode/buildcode/BCGcode128/'.$row[0]->barcode):'' ?>"/></td>
                            </tr>
                            <tr>
                                <td style="height: 5px"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="width: 2cm">条形码号:</td>
                                <td><?php echo $row[0]->barcode ?></td>
                            </tr>
                            <tr>
                                <td>名称:</td>
                                <td><?php echo $row[0]->title ?></td>
                            </tr>
                            <tr>
                                <td>代码:</td>
                                <td><?php echo $row[0]->code ?></td>
                            </tr>
                            <tr>
                                <td>厂家:</td>
                                <td><?php echo $row[0]->factoryname ?></td>
                            </tr>
                            <tr>
                                <td>件数:</td>
                                <td><?php echo $row[0]->itemnumber ?></td>
                            </tr>
                            <tr>
                                <td>描述:</td>
                                <td><?php echo $row[0]->memo ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /span9 -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div> <!-- /content -->

<?php $this->load->view("common/footer"); ?>

<div id="print_item" style="height:0px;width:0px;overflow:hidden">
</div>

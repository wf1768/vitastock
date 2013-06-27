<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>


<link type="text/css" href="<?php echo base_url('stock_plugins/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css') ?>" rel="stylesheet">

<script type="text/javascript" src="<?php echo base_url('stock_plugins/plupload/js/plupload.full.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('stock_plugins/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('stock_plugins/plupload/js/i18n/zh-cn.js') ?>"></script>

<script>

    function openDialog() {

        $("#uploader").pluploadQueue({
            runtimes : 'html5,flash,html4,gears',
            browse_button : 'selectfile',
            max_file_size : '50mb',
            chunk_size: '2mb',
            url : '<?php echo site_url('buy_product/upload_buy_product?id='.$buyid) ?>',
            unique_names : true,
            flash_swf_url : '../js/plupload.flash.swf',
            silverlight_xap_url : '../js/plupload.silverlight.xap',
            filters : [
                {title : "Excel files", extensions : "xls,xlsx"}
            ]
        });
        // Client side form validation
        $('#upload-form').submit(function(e) {
            var uploader = $('#uploader').pluploadQueue();
            // Files in queue upload them first
            if (uploader.files.length > 0) {
                if (uploader.files.length >1) {
                    openalert('每次只能上传一个Excel进行导入。请删除多余文件再上传。')
                }
                else {
//                    alert('2');
                    // When all files are uploaded submit form
                    uploader.bind('StateChanged', function() {
                        if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                            $('#upload-form')[0].submit();
                        }
                    });
                    uploader.start();
                }
            } else {
                openalert('请先上传数据文件!');
            }
            return false;
        });
        $('#upload-dialog').modal('show');
    }

    function save_import() {
        openloading('正在处理导入数据，请稍后.....');
        var tmpStr = '<?php echo $json_data ?>';
        if (tmpStr == '') {
            openalert('没有得到导入数据，请重新尝试或与管理员联系。');
            return;
        }
        $.ajax({
            async:false,
            type:"post",
            data: 'buyid=<?php echo $buyid ?>&json_data='+tmpStr,
            url:"<?php echo site_url('buy_product/saveImport')?>",
            success: function(data){
                if (data) {
                    closeloading();
                    openalert('采购商品导入完毕。');
                }
                else {
                    openalert('采购商品导入出错，请重新尝试或与管理员联系。');
                }
            },
            error: function() {
                openalert('执行操作出错，请重新尝试或与管理员联系。');
            }
        });
    }

    $(function() {
        $('#upload-dialog').on('hidden', function () {
            window.location.reload();
        })
    })
</script>

<!-- Modal -->
<div id="upload-dialog" class="modal hide fade" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        上传采购商品Excel文件
    </div>
    <div id="uploadFile1">
        <div style="width: 100%;height:90%;">
            <form id="upload-form" action="<?php echo site_url('buy_product/upload_buy_product') ?>" method="post">
                <div id="uploader" style="width: 100%;height: 100%">
                    <p>您的浏览器未安装 Flash, Silverlight, Gears, BrowserPlus 或者支持 HTML5 .</p>
                </div>
            </form>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    </div>
</div>

<div id="content"
    <div class="container">
        <div class="row">
            <div class="span3">
                <?php $this->load->view('common/leftmenu'); ?>
            </div> <!-- /span3 -->
            <div class="span9">
                <h1 class="page-title">
                    <i class="icon-th-list"></i>
                    <a href="<?php echo site_url('buy') ?>" class="path-menu-a"> 采购管理</a> > <a href="<?php echo site_url('buy/buy_list') ?>" class="path-menu-a"> 采购单管理</a> > 导入采购商品
                </h1>
                <div class="row">
                    <div class="span9">
                        <label class="pull-right">
                            <a href="javascript:;" id="openDialog" class="btn btn-small" onclick="openDialog()">
                                <i class="icon-upload-alt"> 上传Excel文件</i>
                            </a>
                            <a href="javascript:;" class="btn btn-small" onclick="save_import()">
                                <i class="icon-ok"> 保存</i>
                            </a>
                            <a href="javascript:;" class="btn btn-small" onclick="window.location.href= '<?php echo site_url('') ?>/buy/show?id=<?php echo $buyid ?>';">
                                <i class="icon-repeat"> 返回</i>
                            </a>
                        </label>
                    </div>
                </div>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>采购商品列表</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                         <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>代码</th>
                                <th>名称</th>
                                <th>描述</th>
                                <th>数量</th>
                                <th>单价(€)</th>
                                <th>标准单价(€)</th>
                                <th>总价(€)</th>
                                <th>标准总价(€)</th>
                                <th>售价(￥)</th>
                                <th>备注</th>
                                <th>箱号</th>
                                <th>件数</th>
                                <th>颜色</th>
                                <th>材质等级</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($excel_data)):?>
                                <?php $num=0; foreach($excel_data as $row):?>
                                <tr>
                                    <td><?php echo $num+1 ?></td>
                                    <td><?php echo $row['代码'] ?></td>
                                    <td><?php echo $row['名称'] ?></td>
                                    <td><?php echo $row['描述'] ?></td>
                                    <td><?php echo $row['数量'] ?></td>
                                    <td><?php echo $row['单价'] ?></td>
                                    <td><?php echo $row['标准单价'] ?></td>
                                    <td><?php echo $row['总价'] ?></td>
                                    <td><?php echo $row['标准总价'] ?></td>
                                    <td><?php echo $row['售价'] ?></td>
                                    <td><?php echo $row['备注'] ?></td>
                                    <td><?php echo $row['箱号'] ?></td>
                                    <td><?php echo $row['件数'] ?></td>
                                    <td><?php echo $row['颜色'] ?></td>
                                    <td><?php echo $row['材质等级'];$num++ ?></td>
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

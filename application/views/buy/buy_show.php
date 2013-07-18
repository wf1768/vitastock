<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<script type="text/javascript" src="<?php echo base_url('stock_plugins/plupload/js/plupload.full.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('stock_plugins/plupload/js/i18n/zh-cn.js') ?>"></script>

<script>

    function remove() {
        var str="";
        $("input[name='checkbox']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择要删除的采购商品。');
            return;
        }

        bootbox.confirm("确定要删除选择的采购商品吗？", function(result) {
            if(result){
                str = str.substring(0,str.length-1);

                $.ajax({
                    type:"post",
                    data: "id=" + str,
                    url:"<?php echo site_url('buy_product/remove')?>",
                    success: function(data){
                        if (data) {
                            $("input[name='checkbox']").attr("checked",false);
                            window.location.reload();
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

    function create_storehouse_in(id) {

        var str="";
        $("input[name='checkbox']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })

        if (str == "") {
            openalert('请选择要入库的采购商品。');
            return;
        }
        str = str.substring(0,str.length-1);

        bootbox.confirm("确定要将选择的采购商品生成入库单吗？<br><font color='red'>注意：采购商品生成入库单代表该商品采购过程结束，将不能再编辑该采购商品。</font>", function(result) {
            if(result){
                openloading('正在生成入库单，请稍等...');
                $.ajax({
                    type:"post",
                    data: "id=" + id + '&productids=' + str,
                    url:"<?php echo site_url('buy/create_storehouse_in')?>",
                    success: function(data){
                        if (data) {
                            closeloading();
                            alert('生成入库单成功。');
                            window.location.reload();
                        }
                        else {
                            openalert('生成入库单出错，请重新尝试或与管理员联系。');
                        }
                    },
                    error: function() {
                        openalert('执行操作出错，请重新尝试或与管理员联系。');
                    }
                });
            }
        })

    }

    function upload_pic(id) {
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash',
            browse_button : 'selectpic', // 单击时间的id
            max_file_size : '2mb',
            unique_names : true,
            url : '<?php echo site_url('buy_product/upload_buy_product_image?id=') ?>' + id,
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

    function edit(btn) {


        var str="";
        $("input[name='checkbox']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择要批量修改的采购商品。');
            return;
        }
        str = str.substring(0,str.length-1);

        if (btn == 'factory') {
            $('#edit-factory-dialog').modal('show');
        }
        else if (btn == 'brand') {
            $('#edit-brand-dialog').modal('show');
        }
        else if (btn == 'commoditytype') {
            $('#edit-commoditytype-dialog').modal('show');
        }
        else {
            return;
        }
    }

    function save_edit(btn) {
        var str="";
        $("input[name='checkbox']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择要批量修改的采购商品。');
            return;
        }
        str = str.substring(0,str.length-1);

        //获取选择的批量修改值
        var value = $('#'+btn).val();

        $.ajax({
            type:"post",
            data: "btn="+btn+"&value="+value+"&ids=" + str,
            url:"<?php echo site_url('buy_product/batchEdit')?>",
            success: function(data){
                if (data) {
                    $("input[name='checkbox']").attr("checked",false);
                    openalert('批量修改成功。');
                }
                else {
                    openalert('批量修改采购商品出错，请重新尝试或与管理员联系。');
                }
            },
            error: function() {
                openalert('执行操作出错，请重新尝试或与管理员联系。');
            }
        });
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
        $("div[id ^= 'edit']").on('hidden', function () {
            window.location.reload();
        })
        $('#upload-pic-dialog').on('hidden', function () {
            window.location.reload();
        })

        $("a[data-toggle=popover]").popover();

    })
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
<!-- Modal -->
<div id="edit-factory-dialog" class="modal hide fade" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        批量修改采购商品-厂家
    </div>
    <div class="modal-body">
        <form class="form-horizontal">
            <!-- /control-group -->
            <div class="control-group">
                <label class="control-label" for="factory">厂家</label>
                <div class="controls">
                    <select class="span2" id="factory" name="factory">
                        <?php if($factorys):?>
                        <?php foreach($factorys as $factory):?>
                            <option value="<?php echo $factory->id;?>" ><?php echo $factory->factoryname;?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </div>
                <!-- /controls -->
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a href="javascript:;" onclick="save_edit('factory')" class="btn btn-primary">更改</a>
    </div>
</div>
<div id="edit-brand-dialog" class="modal hide fade" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        批量修改采购商品-品牌
    </div>
    <div class="modal-body">
        <form class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="brand">品牌</label>
                <div class="controls">
                    <select class="span2" id="brand" name="brand">
                        <?php if($brands):?>
                        <?php foreach($brands as $brand):?>
                            <option value="<?php echo $brand->id;?>" ><?php echo $brand->brandname;?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </div>
                <!-- /controls -->
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a href="javascript:;" onclick="save_edit('brand')" class="btn btn-primary">更改</a>
    </div>
</div>
<div id="edit-commoditytype-dialog" class="modal hide fade" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        批量修改采购商品-类别
    </div>
    <div class="modal-body">
        <form class="form-horizontal">
            <!-- /control-group -->
            <div class="control-group">
                <label class="control-label" for="commoditytype">商品类别</label>
                <div class="controls">
                    <select class="span2" id="commoditytype" name="commoditytype">
                        <?php if($comtypes):?>
                        <?php foreach($comtypes as $type):?>
                            <option value="<?php echo $type->id;?>" ><?php echo $type->typename;?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </div>
                <!-- /controls -->
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a href="javascript:;" onclick="save_edit('commoditytype')" class="btn btn-primary">更改</a>
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
                    <a href="<?php echo site_url('buy') ?>" class="path-menu-a"> 采购管理</a> > <a href="<?php echo site_url('buy/buy_list') ?>" class="path-menu-a"> 采购单管理</a> > 浏览
                </h1>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>采购单信息</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                        <?php if ($row) : ?>
                            <table class="table table-bordered" width="100%">
                                <tr>
                                    <td>采购单编号</td>
                                    <td colspan="3"><?php echo $row[0]->buynumber ?></td>
                                </tr>
                                <tr>
                                    <td>创建人</td>
                                    <td><?php echo $row[0]->createby ?></td>
                                    <td>创建时间</td>
                                    <td><?php echo strtotime($row[0]->createtime)?$row[0]->createtime:''; ?></td>
                                </tr>
                                <tr>
                                    <td>采购负责人</td>
                                    <td><?php echo $row[0]->buyman ?></td>
                                    <td>采购日期</td>
                                    <td><?php echo strtotime($row[0]->buydate)?$row[0]->buydate:''; ?></td>
                                </tr>
                                <tr>
                                    <td>采购单状态</td>
                                    <td><?php echo ($row[0]->status == 0)?'<font color="red">未结束</font>':'已入库' ?></td>
                                    <td>采购单来源（订货单号）</td>
                                    <td><?php echo $row[0]->applynumber ?></td>
                                </tr>

                                <tr>
                                    <td>备注</td>
                                    <td colspan="3"><?php echo $row[0]->remark ?></td>
                                </tr>
                            </table>
                        <?php endif ?>
                    </div> <!-- /widget-content -->
                </div>
                <?php if ($row[0]->status == 0) : ?>
                    <div class="row">
                        <div class="span9">
                            <label class="pull-left">
                                <ul class="nav nav-pills">
                                    <li class="active"><a href="<?php echo site_url('buy_product/add?id='.$row[0]->id) ?>" id="addbuy-product">添加采购商品</a></li>
                                    <li class="active"><a href="javascript:;" id="delete_product" onclick="remove()">删除商品</a></li>
                                    <li class="active"><a href="<?php echo site_url('buy_product/import?id='.$row[0]->id) ?>">导入</a></li>
                                    <li class="active"><a href="javascript:;" onclick="create_storehouse_in('<?php echo $row[0]->id ?>')">生成入库单</a></li>
                                </ul>
                            </label>
                            <label class="pull-right">
                                <div class="btn-group">
<!--                                    <a class="btn btn-primary" href="javascript:;"><i class="icon-pencil icon-white"></i> 批量修改</a>-->
<!--                                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>-->
                                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="c-666">批量修改：</span>
                                        <strong>选择</strong>
                                        <input type="hidden" name="category" value="娱乐">
                                        <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:;" onclick="edit('factory')"> 修改厂家</a></li>
                                        <li><a href="javascript:;" onclick="edit('brand')"> 修改品牌</a></li>
                                        <li><a href="javascript:;" onclick="edit('commoditytype')"> 修改类别</a></li>
                                    </ul>
                                </div>
                            </label>
                        </div>
                    </div>
                <?php endif ?>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>采购商品信息</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all""></th>
                                <th>序号</th>
                                <th>缩略图</th>
                                <th>名称</th>
                                <th>代码</th>
<!--                                <th>描述</th>-->
                                <th>厂家</th>
                                <th>品牌</th>
                                <th>类别</th>
                                <th>单价(€)</th>
<!--                                <th>标准单价(€)</th>-->
<!--                                <th>总价(€)</th>-->
<!--                                <th>标准总价(€)</th>-->
                                <th>售价(￥)</th>
                                <th>颜色</th>
<!--                                <th>材质等级</th>-->
                                <th>数量</th>
<!--                                <th>箱号</th>-->
                                <th>件数</th>
<!--                                <th>备注</th>-->
                                <th>状态</th>
                                <th>编辑</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($productlist)):?>
                                <?php $num=0; foreach($productlist as $product):?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox" value="<?php echo $product->id ?>"/></td>
                                        <td><?php echo $num+1 ?></td>
                                        <td><a href="javascript:;" data-html="true" data-trigger="hover" data-toggle="popover" data-content="<img src='<?php echo base_url($product->picpath) ?>' />" onclick="upload_pic('<?php echo $product->id ?>')" ><img src="<?php echo base_url($product->picpath) ?>"  alt="" class="thumbnail smallImg" /></a></td>
<!--                                        <td><a href="javascript:;" data-title="sdfasdf" data-trigger="hover" data-toggle="popover" data-content="asdfasdfasdfasdfasdfasdf" onclick="upload_pic('--><?php //echo $product->id ?><!--')" ><img src="--><?php //echo base_url($product->picpath) ?><!--"  alt="" class="thumbnail smallImg" /></a></td>-->
                                        <td>
                                            <?php
                                            if ($row[0]->status == 0) {
                                                echo "<a href='".site_url('buy/show_product?buyid='.$row[0]->id.'&id='.$product->id)."'>".$product->title."</a>";
                                            }
                                            else {
                                                echo $product->title;
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $product->code ?></td>
<!--                                        <td>--><?php //echo $product->memo ?><!--</td>-->
                                        <td><?php echo $product->factoryname ?></td>
                                        <td><?php echo $product->brandname ?></td>
                                        <td><?php echo $product->typename ?></td>
                                        <td><?php echo $product->cost ?></td>
<!--                                        <td>--><?php //echo $product->standardcost ?><!--</td>-->
<!--                                        <td>--><?php //echo $product->totalprice ?><!--</td>-->
<!--                                        <td>--><?php //echo $product->standardtotalprice ?><!--</td>-->
                                        <td><?php echo $product->salesprice ?></td>
                                        <td><?php echo $product->color ?></td>
<!--                                        <td>--><?php //echo $product->format ?><!--</td>-->
                                        <td><?php echo $product->number;$num++ ?></td>
<!--                                        <td>--><?php //echo $product->boxno ?><!--</td>-->
                                        <td><?php echo $product->itemnumber ?></td>
<!--                                        <td>--><?php //echo $product->remark ?><!--</td>-->
                                        <td><?php echo ($product->status == 0)? '<font color="red">'.$product->statusvalue.'</font>':$product->statusvalue ?></a></td>
                                        <td><?php echo ($product->status == 0)? '<a href="'.site_url('buy_product/edit?buyid='.$row[0]->id.'&id='.$product->id).'" class="btn btn-small btn-info" onclick="aa('.$product->id.')" ><i class="icon-check">编辑</i></a>':'' ?></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div> <!-- /widget-content -->
                </div>
                <?php if ($storehouse_ins) : ?>
                    <div class="widget widget-table">
                        <div class="widget-header">
                            <i class="icon-th-list"></i>
                            <h3>入库单信息</h3>
                        </div>
                        <!-- /widget-header -->
                        <div class="widget-content">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>入库单编号</th>
                                    <th>创建人</th>
                                    <th>创建时间</th>
                                    <th>经办人</th>
                                    <th>办理日期</th>
                                    <th>入库单来源</th>
                                    <th>备注</th>
                                    <th>状态</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php if(isset($storehouse_ins)):?>
                                    <?php $num = 1; foreach($storehouse_ins as $storehouse_in):?>
                                        <tr>
                                            <td><?php echo $num ?></td>
                                            <td><a href="<?php echo site_url('storehouse_in/show?id='.$storehouse_in->id) ?>"><?php echo $storehouse_in->innumber ?></td>
                                            <td><?php echo $storehouse_in->createby ?></td>
                                            <td><?php echo strtotime($storehouse_in->createtime)?$storehouse_in->createtime:''; ?></td>
                                            <td><?php echo $storehouse_in->checkby ?></td>
                                            <td><?php echo strtotime($storehouse_in->overtime)?$storehouse_in->overtime:''; ?></td>
                                            <td><?php echo $storehouse_in->fromcode ?></td>
                                            <td><?php echo $storehouse_in->remark ?></td>
                                            <td><?php echo ($storehouse_in->instatus == 0)?'<font color="red">未结束</font>':'已入库'; $num++ ?></td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
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

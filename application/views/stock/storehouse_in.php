<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<script>

    function remove_stock() {
        var str="";
        $("input[name='checkbox']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择要删除的商品。');
            return;
        }

        if(confirm("确定要删除选择的商品图片吗？")) {

            str = str.substring(0,str.length-1);

            $.ajax({
                type:"post",
                data: "id=" + str,
                url:"<?php echo site_url('stock/remove_stock')?>",
                success: function(data){
                    if (data) {
                        window.location.reload();
                    }
                    else {
                        openalert("删除商品出错，请重新尝试或与管理员联系。");
                    }
                },
                error: function() {
                    openalert("执行操作出错，请重新尝试或与管理员联系。");
                }
            });
        }
        else {
            return;
        }
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
        //提交状态切换
        $("input[name='instatus']").click(function() {
            window.location.href= '<?php echo site_url() ?>/storehouse_in/pages?instatus=' + this.value;
        });

        $('#searchTxt').keyup(function(event){
            var e = event || window.event; //浏览器兼容
            if (e.keyCode == 13) {
                window.location.href= '<?php echo site_url('') ?>/storehouse_in/pages?instatus=<?php echo $instatus ?>&page=<?php echo $page ?>&search=' + $('#searchTxt').val();
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
                    <a href="<?php echo site_url('stock') ?>" class="path-menu-a"> 库存管理</a> > 入库单管理
                </h1>
                <div class="row">
                    <div class="span9">
                        <label class="pull-right">
<!--                            <a href="--><?php //echo site_url('storehouse_in/add') ?><!--" id="add" class="btn btn-small">-->
<!--                                <i class="icon-plus"> 添加</i>-->
<!--                            </a>-->
<!--                            <a href="javascript:;" class="btn btn-small" onclick="remove_stock()">-->
<!--                                <i class="icon-minus"> 删除</i>-->
<!--                            </a>-->
                            <a href="<?php echo site_url('storehouse_in_content/show_hand_in_by_barcode') ?>" class="btn btn-small">
                                <i class="icon-barcode"> 条码办理入库</i>
                            </a>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <form method="GET" id="search-form" class="form-inline">
                        <div class="span5">
                            <label class="radio inline">
                                <input type="radio" name="instatus" id="instatus1" value="0" <?php if ($instatus == 0): ?>checked<?php endif; ?>>未结束
                            </label>
                            <label class="radio inline">
                                <input type="radio" name="instatus" id="instatus2" value="1" <?php if ($instatus == 1): ?>checked<?php endif; ?>>已结束
                            </label>
                            <label class="radio inline">
                                <input type="radio" name="instatus" id="instatus3" value="2" <?php if ($instatus == 2): ?>checked<?php endif; ?>>全部
                            </label>
                        </div>
                        <div class="span4">
                            <input type="hidden" id='instatus' name="instatus" value="<?php echo $instatus ?>">
                            <label class="pull-right">入库单号: <input id="innumber" name="innumber" type="text" class="input-medium" placeholder="入库单编号查询" value="<?php echo $search ?>"></label>
                        </div>
                    </form>
                </div>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>入库单列表</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                         <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all""></th>
                                <th>入库单编号</th>
                                <th>创建时间</th>
                                <th>创建人</th>
                                <th>处理人</th>
                                <th>处理时间</th>
                                <th>入库来源</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
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
                    <div class="row">
                        <div class="span4" style="margin-top:20px ">
                            <?php echo (isset($info))?$info:'' ?>
                        </div>
                        <div class=" pagination pagination-right">
                            <?php echo (isset($page))?$page:''; ?>
                        </div>
                    </div>
                </div> <!-- /widget -->
            </div> <!-- /span9 -->
        </div> <!-- /row -->
    </div> <!-- /container -->
</div> <!-- /content -->


<?php $this->load->view("common/footer"); ?>

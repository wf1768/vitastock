<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<div id="content">
<div class="container">
<div class="row">
<div class="span3">

    <?php $this->load->view('common/leftmenu'); ?>


</div> <!-- /span3 -->
<div class="span9">
    <h1 class="page-title">
        <i class="icon-th-list"></i>
        <a href="<?php echo site_url('stock') ?>" class="path-menu-a"> 库存管理</a> > 商品管理
    </h1>
    <div class="row">
        <div class="span9">
            <label class="pull-right">
                <input type="hidden" id="path" value="<?php echo site_url('') ?>">
                <a href="<?php echo site_url("brand/brandAdd")?>" id="add" class="btn btn-small">
                    <i class="icon-plus"> 添加</i>
                </a>
                <a href="javascript:;" class="btn btn-small" onclick="remove_stock()">
                    <i class="icon-minus"> 删除</i>
                </a>


            </label>
        </div>
    </div>
    <div class="row">
        <form id="stock_list_btn_form" name="stock_list_btn_form" method="POST" class="form-inline">
            <div class="span5">
                <label>
                    切换库房
                    <select id="storehouse" name="storehouse" class="span2">
                        <?php if($houses):?>
                        <?php foreach($houses as $house):?>
                            <option value="<?php echo $house['id'];?>" <?php if ($house['id'] == $storehouseid): ?>selected="true"<?php endif; ?> ><?php echo $house["storehousecode"];?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </label>
            </div>
            <div class="span4">
                <input type="text" style="display:none">
                <label class="pull-right">查询: <input id="searchTxt" type="text" value=""></label>
            </div>
        </form>
    </div>
    <div class="widget widget-table">
        <div class="widget-header">
            <i class="icon-th-list"></i>
            <h3>商品列表</h3>
        </div> <!-- /widget-header -->
        <div class="widget-content">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th><input type="checkbox" id="select-all""></th>

                    <th>品牌名称</th>
                    <th>品牌代码</th>

                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($list as $row):?>
                    <tr>
                        <td><input type="checkbox" name="checkbox" value="<?php echo $row->id ?>"/></td>
                        <td><?php echo $row->brandname ?></td>
                        <td><?php echo $row->brandcode ?></td>

                        <td><a href="<?php echo site_url("brand/brandEdit?id=".$row->id)?>" class="btn btn-primary">修改数据</a> </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div> <!-- /widget-content -->
        <div class="row">
            <div class="span4" style="margin-top:20px ">
<?php echo $info;?>
            </div>
            <div class=" pagination pagination-right">
            <?php
               echo $page;
            ?>
            </div>
        </div>
    </div> <!-- /widget -->
</div> <!-- /span9 -->
</div> <!-- /row -->
</div> <!-- /container -->
</div> <!-- /content -->


<?php $this->load->view("common/footer"); ?>

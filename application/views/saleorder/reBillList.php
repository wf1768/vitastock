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
        <a href="<?php echo site_url('saleorder/orderList') ?>" class="path-menu-a">销售单管理</a> > 退单列表
    </h1>
    <div class="row">
        <div class="span9 doconfirm">
            <label class="pull-right">
                <input type="hidden" id="path" value="<?php echo site_url('') ?>">
              

            </label>
        </div>
    </div>
    <div class="row">
        <form id="stock_list_btn_form" name="stock_list_btn_form" action="<?php echo site_url('saleorder/reBillList')?>" method="POST" class="form-inline">
            <div class="span5">
                <label>
                </label>
            </div>
            <div class="span4">
                <input type="text" style="display:none">
                <label class="pull-right">单号: 
                <input id="searchTxt" type="text" value="<?php echo isset($_REQUEST['returnnumber'])?$_REQUEST['returnnumber']:'';?>"  name="returnnumber" placeholder="请输销售退单号">
                <input type="submit" value="查询" class="btn btn-primary" /> 
                </label>
            </div>
        </form>
    </div>
    <div class="widget widget-table">
        <div class="widget-header">
            <i class="icon-th-list"></i>
            <h3> 销售单退单列表</h3>
        </div> <!-- /widget-header -->
        <div class="widget-content">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
<!--                    <th><input type="checkbox" id="select-all""></th>-->

                    <th> 退订单编号</th>
                    <th> 创建者</th>
                    <th> 创建时间</th>
                    <th>退单描述</th>
                    <th> 退单备注</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($list as $row):?>
                    <tr>
                        <td><?php echo $row->returnnumber ?></td>
                        <td><?php echo $row->createby ?></td>
                        <td><?php echo $row->createtime ?></td>
                        <td><?php echo $row->returnmemo ?></td>
                        <td><?php echo $row->remark ?></td>
                        <td>
                         <a href="<?php echo site_url("saleorder/showReturnOrder?id=".$row->id)?>" class="btn btn-primary">退单详情</a>
                         </td>
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
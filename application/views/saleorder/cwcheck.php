<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<div id="content">
    <div class="container">
        <div class="row">
            <div class="span3">
                <?php $this->load->view('common/leftmenu'); ?>
            </div>
            <div class="span9">
                <h1 class="page-title">
                    <i class="icon-th-list"></i>
                    <a href="<?php echo site_url('saleorder/orderList') ?>" class="path-menu-a">销售单管理</a> > 销售单列表
                </h1>
                <div class="row">
                    <form id="stock_list_btn_form" name="stock_list_btn_form"
                          action="<?php echo site_url('saleorder/orderList') ?>" method="POST" class="form-inline">
                        <div class="span5">
                            <label>
                                审核类型：
                                <select id="types">
                                    <option
                                        value="0" <?php if (isset($_GET['type']) && $_GET['type'] == 0) echo "selected"?>>
                                        已结束
                                    </option>
                                    <option
                                        value="1" <?php if (isset($_GET['type']) && $_GET['type'] == 1) echo "selected"?>>
                                        未结束
                                    </option>
                                    <option
                                        value="2" <?php if (isset($_GET['type']) && $_GET['type'] == 2) echo "selected"?>>
                                        未审核
                                    </option>
                                </select>
                            </label>
                        </div>
                        <div class="span4">
                            <input type="text" style="display:none">
                            <label class="pull-right">单号:
                                <input id="searchTxt" type="text"
                                       value="<?php echo isset($_REQUEST['sellnumber']) ? $_REQUEST['sellnumber'] : ''; ?>"
                                       name="sellnumber" placeholder="请输销售单号">
                                <input type="submit" value="查询" class="btn btn-primary"/>
                            </label>
                        </div>
                    </form>
                </div>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>

                        <h3> 销售单列表</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th> 销售单编号</th>
                                <th> 创建者</th>
                                <th> 创建时间</th>
                                <th> 销售日期</th>
                                <th> 客户名称</th>
                                <th> 客户电话</th>
                                <th> 操作人</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($list as $row): ?>
                                <tr>
                                    <td><?php echo $row->sellnumber ?></td>
                                    <td><?php echo $row->createby ?></td>
                                    <td><?php echo $row->createtime ?></td>
                                    <td><?php echo $row->selldate ?></td>
                                    <td><?php echo $row->clientname ?></td>
                                    <td><?php echo $row->clientphone ?></td>
                                    <td><?php echo $row->checkby ?></td>
                                    <td><?php $status = array('0' => '待审核','1' => '退单', '2' => '已审核', '3' => '已配送','6' => '期货部分配送');echo $status[$row->status] ?></td>
                                    <td>
                                        <?php if ($row->status == 0 || $row->financestatus == 0): ?>
                                            <a href="<?php echo site_url("saleorder/shCwCheck?id=" . $row->id.'&type='.$_GET['type']) ?>"
                                               class="btn btn-primary">审核</a>
                                        <?php else: ?>
                                            <a href="<?php echo site_url("saleorder/shCwCheck?id=" . $row->id.'&type='.$_GET['type']) ?>"
                                               class="btn btn-primary">详细</a>
<!--                                            <button class="btn ">审核</button>-->
                                        <?php endif;?>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /widget-content -->
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
                </div>
                <!-- /widget -->
            </div>
            <!-- /span9 -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div> <!-- /content -->
<script>
    $("#types").change(function () {
        var type = $("#types").val();
        var url = "<?php echo site_url("saleorder/cwCheck?type=")?>";
        location.href = url + type;
    });
</script>

<?php $this->load->view("common/footer"); ?>


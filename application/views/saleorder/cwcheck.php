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
                <div class="minbox">
                    <div class="part_search">
                        <div class="navbar">
                            <div class="navbar-inner">
                                <form class="navbar-form" method="get" id="searchfrom" style="margin-bottom:5px">
                                    <font class="myfont">销售单号：</font>
                                    <input type="text" name="sellnumber" id="sellnumber"
                                           value="<?php echo isset($_REQUEST['sellnumber']) ? $_REQUEST['sellnumber'] : ''; ?>"
                                           placeholder="请输入销售单编号">

                                    &nbsp;&nbsp;<font class="myfont">&nbsp;&nbsp;&nbsp;销售店：</font>
                                    <select name="storehouseid">
                                        <option value="">请选择</option>
                                        <?php foreach ($storehouse as $val): ?>
                                            <option value="<?php echo $val->id; ?>"
                                                <?php if (isset($_REQUEST['storehouseid']) && $_REQUEST['storehouseid'] == $val->id) {
                                                echo "selected";
                                            }?>><?php echo $val->storehousecode;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <br />
                                    <font class="myfont">客户名称：</font>
                                    <input type="text" name="clientname" id="clientname"
                                           value="<?php echo isset($_REQUEST['clientname']) ? $_REQUEST['clientname'] : ''; ?>"
                                           placeholder="请输入客户名称">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font class="myfont">状态：</font>
                                    <select name="type">
                                        <option value="2" <?php if ($type == 2) echo 'selected' ?>>未审核</option>
                                        <option value="1" <?php if ($type == 1) echo 'selected' ?>>未结束</option>
                                        <option value="0" <?php if ($type == 0) echo 'selected' ?>>已结束</option>
                                        <option value="3" <?php if ($type == 3) echo 'selected' ?>>已退单</option>
                                    </select>
                                    <button style="margin-left:20px" id="search" type="submit" class="btn btn-primary">&nbsp;&nbsp;搜&nbsp;&nbsp;索&nbsp;&nbsp;</button>
                                </form>

                            </div>
                        </div>
                    </div>
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
                                <th>销售店</th>
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
                                    <td><?php echo $row->storehousecode ?></td>
                                    <td>
                                        <?php if ($row->status == 0 || $row->financestatus == 0): ?>
                                            <a href="<?php echo site_url("saleorder/shCwCheck?id=" . $row->id.'&type='.$_GET['type'].'&p='.$p) ?>"
                                               class="btn btn-primary">审核</a>
                                        <?php else: ?>
                                            <a href="<?php echo site_url("saleorder/shCwCheck?id=" . $row->id.'&type='.$_GET['type'].'&p='.$p) ?>"
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


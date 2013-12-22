<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<script>
    $(function() {
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
                    <a href="javascript:;" class="path-menu-a"> 财务管理</a> > 期货订单审核列表
                </h1>
                <div class="minbox">
                    <div class="part_search">
                        <div class="navbar">
                            <div class="navbar-inner">
                                <form class="navbar-form" method="get" id="searchfrom" style="margin-bottom:5px">
                                    <font class="myfont">订单编号：</font>
                                    <input type="text" name="applynumber" id="applynumber"
                                           value="<?php echo isset($_REQUEST['applynumber']) ? $_REQUEST['applynumber'] : ''; ?>"
                                           placeholder="请输入订单编号">

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
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font class="myfont">申请人：</font>
                                    <input type="text" name="applyby" id="applyby"
                                           value="<?php echo isset($_REQUEST['applyby']) ? $_REQUEST['applyby'] : ''; ?>"
                                           placeholder="请输入申请人">
                                    <br/>
                                    <font class="myfont">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;备注：</font>
                                    <input type="text" name="remark" id="remark"
                                           value="<?php echo isset($_REQUEST['remark']) ? $_REQUEST['remark'] : ''; ?>"
                                           placeholder="请输入备注">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font class="myfont">状态：</font>
                                    <select name="type">
                                        <option value="0" <?php if ($type == 0) echo 'selected' ?>>未审核</option>
                                        <option value="1" <?php if ($type == 1) echo 'selected' ?>>未结束</option>
                                        <option value="2" <?php if ($type == 2) echo 'selected' ?>>已结束</option>
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
                        <h3>期货订单列表</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                         <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>订单编号</th>
                                <th>销售店</th>
                                <th>销售日期</th>
                                <th>客户名称</th>
                                <th>申请人</th>
                                <th>申请日期</th>
                                <th>备注</th>
                                <th>状态</th>
                                <th>操作</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($list)):?>
                                <?php foreach($list as $row):?>
                                <tr>
                                    <td><?php echo $row->applynumber ?></td>
<!--                                    <td><a href="--><?php //echo site_url('apply/show?id='.$row->id.'&stype='.$stype) ?><!--">--><?php //echo $row->applynumber ?><!--</a></td>-->
                                    <td><?php echo $row->storehousecode ?></td>
                                    <td><?php echo strtotime($row->selldate)?$row->selldate:'';  ?></td>
                                    <td><?php echo $row->clientname ?></td>
                                    <td><?php echo $row->applyby ?></td>
                                    <td><?php echo strtotime($row->applydate)?$row->applydate:'';  ?></td>
<!--                                    <td>--><?php //echo $row->checkby ?><!--</td>-->
<!--                                    <td>--><?php //echo strtotime($row->commitgetdate)?$row->commitgetdate:'';  ?><!--</td>-->
                                    <td><?php echo $row->remark ?></td>
                                    <td><?php
                                        if ($row->status == 1) {
                                            echo '<font color="red">'.$row->statusvalue.'</font>';
                                        }
                                        else if ($row->status == 2 || $row->status == 3) {
                                            echo '<font color="blue">'.$row->statusvalue.'</font>';
                                        }
                                        else if ($row->status == 4) {
                                            echo '<font color="green">'.$row->statusvalue.'</font>';
                                        }
                                        else {
                                            echo $row->statusvalue;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($row->status == 1 || $row->financestatus == 0): ?>
                                            <a href="<?php echo site_url("apply_check/check?id=" . $row->id.'&type='.$_GET['type'].'&p='.$p) ?>"
                                               class="btn btn-small btn-primary">审核</a>
                                        <?php else: ?>
                                            <a href="<?php echo site_url("apply_check/check?id=" . $row->id.'&type='.$_GET['type'].'&p='.$p) ?>"
                                               class="btn btn-small btn-primary">详细</a>
                                            <!--                                            <button class="btn ">审核</button>-->
                                        <?php endif;?>
                                    </td>
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
                            <?php
                                echo (isset($page))?$page:'';
                            ?>
                        </div>
                    </div>
                </div> <!-- /widget -->
            </div> <!-- /span9 -->
        </div> <!-- /row -->
    </div> <!-- /container -->
</div> <!-- /content -->


<?php $this->load->view("common/footer"); ?>

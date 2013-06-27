<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>
<!-- Modal -->
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
                    <a href="<?php echo site_url('saleorder/orderList') ?>" class="path-menu-a"> 销售单管理</a> >销售单退单详情
                </h1>

                <div class="row">
                    <div class="span9">
                        <div class="widget">
                            <div class="widget-header">
                                <h3>销售单退单详情</h3>
                            </div>
                            <!-- /widget-header -->
                            <div class="widget-content">
                                <div class="tabbable">
                                    <br>
                                    <div class="row">
                                        <div class="span8">
                                            <label class="pull-right">
                                                <ul class="nav nav-pills">
<!--                                                    <a href="javascript:;" onclick="onPrint()" class="btn btn-primary">打印销售单</a>-->
                                                    <a href="<?php echo site_url('saleorder/returnCreatOrder?id=').$sell->id ?>" class="btn btn-primary">创建换货销售单</a>
                                                    <a href="<?php echo site_url('saleorder/reBillList') ?>" class="btn btn-primary">返回</a>
                                                </ul>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="1">
                                            <table class="table table-bordered" width="100%">
                                                <tr>
                                                    <td>销单退订编号</td>
                                                    <td colspan="3">
                                                        <?php echo $rebill->returnnumber;?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>退单人</td>
                                                    <td>
                                                        <?php echo $rebill->createby;?>
                                                    </td>
                                                    <td>退单日期</td>
                                                    <td>
                                                        <?php echo $rebill->returntime;?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>退单描述</td>
                                                    <td>
                                                        <?php echo $rebill->returnmemo;?>
                                                    </td>
                                                    <td>退单备注</td>
                                                    <td>
                                                        <?php echo $rebill->remark;?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>销单编号</td>
                                                    <td colspan="3">
                                                        <?php echo $sell->sellnumber;?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>创建人</td>
                                                    <td>
                                                        <?php echo $sell->createby;?>
                                                    </td>
                                                    <td>销售日期</td>
                                                    <td>
                                                        <?php echo $sell->selldate;?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>销售店</td>
                                                    <td><?php echo $sell->storehousecode;?> </td>
                                                    <td>客户名称</td>
                                                    <td><?php echo $sell->clientname;?> </td>
                                                </tr>
                                                <tr>
                                                    <td>客户电话</td>
                                                    <td><?php echo $sell->clientphone;?></td>
                                                    <td>客户地址</td>
                                                    <td><?php echo $sell->clientadd;?></td>
                                                </tr>
                                                <tr>
                                                    <td>总价(RMB)</td>
                                                    <td><?php echo $sell->totalmoney;?>  </td>
                                                    <td>折扣价(RMB)</td>
                                                    <td><?php echo $sell->discount;?></td>
                                                </tr>
                                                <tr>
                                                    <td>已付金额(RMB)</td>
                                                    <td><?php echo $sell->paymoney;?>  </td>
                                                    <td>未付金额(RMB)</td>
                                                    <td><?php echo $sell->lastmoney;?></td>
                                                </tr>

                                                <tr>
                                                    <td>备注</td>
                                                    <td><?php echo $sell->remark;?>  </td>
                                                    <td>操作人</td>
                                                    <td><?php echo $sell->checkby;?></td>
                                                </tr>
                                            </table>

                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>序号</th>
                                                    <th>名称</th>
                                                    <th>代码</th>
                                                    <th>描述</th>
                                                    <th>厂家</th>
                                                    <th>类别</th>
                                                    <th>颜色</th>
                                                    <th>条形码</th>
                                                    <th>库房</th>
                                                    <th>状态</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $num=1; if (isset($list)) foreach ($list as $row): ?>
                                                    <tr>
                                                        <td><?php echo $num ?></td>
                                                        <td><?php echo $row->title ?></td>
                                                        <td><?php echo $row->code ?></td>
                                                        <td><?php echo $row->memo ?></td>
                                                        <td><?php echo $row->factoryname ?></td>
                                                        <td><?php echo $row->typename ?></td>
                                                        <td><?php echo $row->color ?></td>
                                                        <td><?php echo $row->barcode ?></td>
                                                        <td><?php echo saleorder::getStorehouse($row->storehouseid) ?></td>
                                                        <td><?php echo $row->statusvalue;$num++ ?></td>
                                                    </tr>
                                                <?php endforeach;?>
                                                </tbody>
                                            </table>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /widget-content -->
                        </div>
                        <!-- /widget -->
                    </div>
                    <!-- /span9 -->
                </div>
                <!-- /row -->
            </div>
            <!-- /span9 -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div> <!-- /content -->

<?php $this->load->view("common/footer"); ?>


<!--------------------------------- -->
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"> 产品添加</h3>
    </div>
    <div class="modal-body">
        <!-- ============================================================== -->
        <div class="minbox">
            <div class="part_search">
                <div class="navbar">
                    <div class="navbar-inner">
                        <form class="navbar-form" method="post" id="searchfrom" style="margin-bottom:5px">
                            <font class="myfont">产品名称：</font>
                            <input type="text" class=" span3" name="title" value="">
                            &nbsp; <font class="myfont">备注：</font>
                            <input type="text" class="span3" name="remark" value=""><br/>
                            <font class="myfont">产品代码：</font>
                            <input type="text" class=" span3" name="code" value="">

                            <button style="margin-left:45px" id="search" type="button" class="btn">高级搜索</button>
                            <button style="margin-left:20px" type="button" class="btn btn-primary">&nbsp;&nbsp;搜&nbsp;&nbsp;索&nbsp;&nbsp;</button>
                        </form>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped    table-hover">
                <thead>
                <tr class="info">
                    <th class="table-textcenter"> 选择</th>
                    <th class="table-textcenter">产品名称</th>
                    <th class="table-textcenter">代码</th>
                    <th class="table-textcenter">备注</th>
                    <th class="table-textcenter">颜色</th>
                    <th class="table-textcenter">规格</th>
                    <th class="table-textcenter">所在仓库</th>
                </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
            </table>
        </div>

        <!-- ============================================================== -->

    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" id="additem">添加</button>
        <button class="btn btn-primary" id="closebox" data-dismiss="modal" aria-hidden="true">关闭</button>
    </div>
</div>

<!-- ----------------------------------- -->
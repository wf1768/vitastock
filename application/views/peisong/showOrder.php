<style>

</style>
<!-- Modal -->
<div id="content3">
    <div class="container">
        <div class="row">

            <!-- /span3 -->
            <div class="span9">

                <div class="row">
                    <div class="span9">
                        <div class="widget">

                            <!-- /widget-header -->
                            <div class="widget-content">
                                <div class="tabbable" style="margin-top: 0px">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="1">
                                            <table class="table table-bordered" width="100%">
                                                <tr>
                                                    <td>销售单编号</td>
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
                                                    <td>备注</td>
                                                    <td><?php echo $sell->remark;?>  </td>
                                                    <td>操作人</td>
                                                    <td><?php echo $sell->checkby;?></td>
                                                </tr>
                                                <tr>
                                                    <td>配送人</td>
                                                    <td><?php echo isset($outinfo->getman) ? $outinfo->getman : "-"?>  </td>
                                                    <td>配送时间</td>
                                                    <td><?php echo isset($outinfo->ptime) ? $outinfo->ptime : "-"?></td>
                                                </tr>
                                            </table>

                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                <tr>

                                                    <th>名称</th>
                                                    <th>代码</th>
                                                    <th>描述</th>
                                                    <th>厂家</th>
                                                    <th>品牌</th>
                                                    <th>类别</th>
                                                    <th>颜色</th>
                                                    <th>条形码</th>
                                                    <th>库房</th>
                                                    <th>状态</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if (isset($list)) foreach ($list as $row): ?>
                                                    <tr id="s<?php echo $row->id ?>">

                                                        <td><?php echo $row->title ?></td>
                                                        <td><?php echo $row->code ?></td>
                                                        <td><?php echo $row->memo ?></td>
                                                        <td><?php echo $row->factoryname ?></td>
                                                        <td><?php echo $row->brandname ?></td>
                                                        <td><?php echo $row->typename ?></td>
                                                        <td><?php echo $row->color ?></td>
                                                        <td><?php echo $row->barcode ?></td>
                                                        <td><?php echo peisong::getStorehouse($row->storehouseid); ?></td>
                                                        <td><?php echo $row->statusvalue ?></td>
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


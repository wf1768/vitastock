<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<script>
    $(function() {

    })
</script>

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
                    <a href="<?php echo site_url('buy') ?>" class="path-menu-a"> 采购管理</a> > <a href="<?php echo site_url('buy/buy_list') ?>" class="path-menu-a"> 采购单管理</a> > 采购商品浏览
                </h1>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>采购商品信息</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                        <?php if ($row) : ?>
                            <table class="table table-bordered" width="100%">
                                <tr>
                                    <td>名称</td>
                                    <td><?php echo $row[0]->title ?></td>
                                    <td>代码</td>
                                    <td><?php echo $row[0]->code ?></td>
                                    <td rowspan="6" width="260px">
                                        <ul class="thumbnails">
                                            <li class="span3">
                                                <a class="thumbnail" href="javascript:;">
                                                    <img style="width: 260px; height: 180px;" src="<?php echo base_url().$row[0]->picpath ?>">
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>描述</td>
                                    <td colspan="3"><?php echo $row[0]->memo ?></td>
                                </tr>
                                <tr>
                                    <td>厂家</td>
                                    <td><?php echo $row[0]->factoryname ?></td>
                                    <td>品牌</td>
                                    <td><?php echo $row[0]->brandname ?></td>
                                </tr>
                                <tr>
                                    <td>类别</td>
                                    <td><?php echo $row[0]->typename ?></td>
                                    <td>数量</td>
                                    <td><?php echo $row[0]->number ?></td>
                                </tr>
                                <tr>
                                    <td>单价(€)</td>
                                    <td><?php echo $row[0]->cost ?></td>
                                    <td>标准单价(€)</td>
                                    <td><?php echo $row[0]->standardcost ?></td>
                                </tr>
                                <tr>
                                    <td>总价(€)</td>
                                    <td><?php echo $row[0]->totalprice ?></td>
                                    <td>标准总价(€)</td>
                                    <td><?php echo $row[0]->standardtotalprice ?></td>
                                </tr>
                                <tr>
                                    <td>售价(￥)</td>
                                    <td><?php echo $row[0]->salesprice ?></td>
                                    <td>颜色</td>
                                    <td colspan="2"><?php echo $row[0]->color ?></td>
                                </tr>
                                <tr>
                                    <td>材质等级</td>
                                    <td><?php echo $row[0]->format ?></td>
                                    <td>箱号</td>
                                    <td colspan="2"><?php echo $row[0]->boxno ?></td>
                                </tr>
                                <tr>
                                    <td>件数</td>
                                    <td><?php echo $row[0]->itemnumber ?></td>
                                    <td>状态</td>
                                    <td colspan="2"><?php echo ($row[0]->status == 0)? '<font color="red">'.$row[0]->statusvalue.'</font>':$row[0]->statusvalue ?></td>
                                </tr>
                                <tr>
                                    <td>备注</td>
                                    <td colspan="4"><?php echo $row[0]->remark ?></td>
                                </tr>
                            </table>
                        <?php endif ?>
                    </div> <!-- /widget-content -->
                </div>
                <div class="row">
                    <div class="span9">
                        <label class="pull-left">
                            <a href="<?php echo site_url('buy/show?id=' . $row[0]->buyid) ?>"
                               class="btn">返回</a>
                        </label>
                    </div>
                </div>
            </div>
            <!-- /span9 -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div> <!-- /content -->

<?php $this->load->view("common/footer"); ?>

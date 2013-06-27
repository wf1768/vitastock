<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<script>

    $(function() {
        $('#barcode').focus();

        $("input[name='barcode']").keyup(function(event){
            var e = event || window.event; //浏览器兼容
            if (e.keyCode == 13) {
                $('#find_form').submit();
            }
        });
        //控制文本框回车不提交
//        document.getElementsByTagName('form')[0].onkeydown = function(e){
//            var e = e || event;
//            var keyNum = e.which || e.keyCode;
//            return keyNum==13 ? false : true;
//        };


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
                    <a href="<?php echo site_url('stock') ?>" class="path-menu-a"> 库存管理</a> > 条形码查询
                </h1>
                <span id="barcode_handle" >
                <div class="row">
                    <form method="post" id="find_form" class="form-inline">
                        <div class="span6">
                            <label >条形码: <input id="barcode" name="barcode" type="text" class="span3 input-medium" placeholder="扫描条形码" value=""></label>
                        </div>
                        <div class="span3">
                            <label class="pull-right">
                            <a href="javascript:;" id="hand_in_btn" name="hand_in_btn" class="btn btn-primary" onclick="no_auto_handle_in()">
                                <i class="icon-barcode"> 查询</i>
                            </a>
                            </label>
                        </div>
                    </form>
                </div>
                <?php if (isset($row)) : ?>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>商品信息</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                        <table class="table table-bordered" width="100%">
                            <tr>
                                <td>名称</td>
                                <td colspan="3"><?php echo $row->title ?></td>
                                <td rowspan="6" width="260px">
                                    <ul class="thumbnails">
                                        <li class="span3">
                                            <a class="thumbnail" href="javascript:;"">
                                                <img style="width: 260px; height: 180px;" src="<?php echo base_url().$row->picpath ?>">
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>代码</td>
                                <td><?php echo $row->code ?></td>
                                <td>所属库房</td>
                                <td><?php echo $housecode ?></td>
                            </tr>
                            <tr>
                                <td>描述</td>
                                <td colspan="3"><?php echo $row->memo ?></td>
                            </tr>
                            <tr>
                                <td>厂家</td>
                                <td><?php echo $row->factoryname ?></td>
                                <td>品牌</td>
                                <td><?php echo $row->brandname ?></td>
                            </tr>
                            <tr>
                                <td>类别</td>
                                <td><?php echo $row->typename ?></td>
                                <td>数量</td>
                                <td><?php echo $row->number ?></td>
                            </tr>
                            <tr>
                                <td>售价(￥)</td>
                                <td><?php echo $row->salesprice ?></td>
                                <td>颜色</td>
                                <td><?php echo $row->color ?></td>
                            </tr>
                            <?php if ($this->account_info_lib->power == 2) : ?>
                                <tr>
                                    <td>单价(€)</td>
                                    <td><?php echo $row->cost ?></td>
                                    <td>标准单价(€)</td>
                                    <td colspan="2"><?php echo $row->standardcost ?></td>
                                </tr>
                            <?php endif ?>
                            <tr>
                                <td>材质等级</td>
                                <td><?php echo $row->format ?></td>
                                <td>箱号</td>
                                <td colspan="2"><?php echo $row->boxno ?></td>
                            </tr>
                            <tr>
                                <td>件数</td>
                                <td><?php echo $row->itemnumber ?></td>
                                <td>状态</td>
                                <td colspan="2"><?php echo ($row->statuskey == 0)? '<font color="red">'.$row->statusvalue.'</font>':$row->statusvalue ?></td>
                            </tr>
                            <tr>
                                <td>条形码</td>
                                <td colspan="4"><?php echo $row->barcode ?></td>
                            </tr>
                            <tr>
                                <td>条形码样式</td>
                                <td colspan="4"><img id="barcode-image" src="<?php echo (!empty($row->barcode)) ? site_url('barcode/buildcode/BCGcode128/'.$row->barcode):'' ?>" alt="" /></td>
                            </tr>
                            <tr>
                                <td>备注</td>
                                <td colspan="4"><?php echo $row->remark ?></td>
                            </tr>
                        </table>

                    </div> <!-- /widget-content -->
                </div>
                <?php endif ?>
                <?php if(isset($apply)):?>
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
                                    <th>合同编号</th>
                                    <th>销售日期</th>
                                    <th>客户名称</th>
                                    <th>申请人</th>
                                    <th>申请日期</th>
                                    <th>处理人</th>
                                    <th>承诺到货日期</th>
                                    <th>备注</th>
                                    <th>状态</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?php echo $apply->applynumber ?></td>
                                    <td><?php echo $apply->contractnumber ?></td>
                                    <td><?php echo strtotime($apply->selldate)?$apply->selldate:'';  ?></td>
                                    <td><?php echo $apply->clientname ?></td>
                                    <td><?php echo $apply->applyby ?></td>
                                    <td><?php echo strtotime($apply->applydate)?$apply->applydate:'';  ?></td>
                                    <td><?php echo $apply->checkby ?></td>
                                    <td><?php echo strtotime($apply->commitgetdate)?$apply->commitgetdate:'';  ?></td>
                                    <td><?php echo $apply->remark ?></td>
                                    <td><?php
                                        if ($apply->status == 1) {
                                            echo '<font color="red">'.$apply->statusvalue.'</font>';
                                        }
                                        else if ($apply->status == 2 || $apply->status == 3) {
                                            echo '<font color="blue">'.$apply->statusvalue.'</font>';
                                        }
                                        else if ($apply->status == 4) {
                                            echo '<font color="green">'.$apply->statusvalue.'</font>';
                                        }
                                        else {
                                            echo $apply->statusvalue;
                                        }
                                        ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div> <!-- /widget-content -->
                    </div>
                <?php endif; ?>
                <?php if(isset($buy)):?>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>采购单列表</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>采购单编号</th>
                                <th>创建时间</th>
                                <th>创建人</th>
                                <th>采购负责人</th>
                                <th>采购日期</th>
                                <th>备注</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $buy->buynumber ?></td>
                                    <td><?php echo strtotime($buy->createtime)?$buy->createtime:'';  ?></td>
                                    <td><?php echo $buy->createby ?></td>
                                    <td><?php echo $buy->buyman ?></td>
                                    <td><?php echo strtotime($buy->buydate)?$buy->buydate:''; ?></td>
                                    <td><?php echo $buy->remark ?></td>
                                    <td><?php echo ($buy->status == 0)? '<font color="red">未结束</font>':'已结束' ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div> <!-- /widget-content -->
                </div>
                <?php endif; ?>
                <?php if(isset($storehouse_in)):?>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>商品所属入库单</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                         <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
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
                                <tr>
                                    <td><?php echo $storehouse_in->innumber ?></td>
                                    <td><?php echo $storehouse_in->createtime ?></td>
                                    <td><?php echo $storehouse_in->createby ?></td>
                                    <td><?php echo $storehouse_in->checkby ?></td>
                                    <td><?php echo strtotime($storehouse_in->overtime)?$storehouse_in->overtime:'';  ?></td>
                                    <td><?php echo $storehouse_in->fromcode ?></td>
                                    <td><?php echo ($storehouse_in->instatus == 0)? '<font color="red">未结束</font>':'已结束' ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div> <!-- /widget-content -->
                </div> <!-- /widget -->
                <?php endif;?>
                <?php if(isset($sell)):?>
                    <div class="widget widget-table">
                        <div class="widget-header">
                            <i class="icon-th-list"></i>
                            <h3>销售单列表</h3>
                        </div> <!-- /widget-header -->
                        <div class="widget-content">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th> 销售单编号</th>
                                    <th> 创建者</th>
                                    <th> 合同编号</th>
                                    <th> 销售日期</th>
                                    <th> 客户名称</th>
                                    <th> 客户电话</th>
                                    <th> 操作人</th>
                                    <th> 状态</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($sells as $sell):?>
                                    <tr>
                                        <td><?php echo $sell->sellnumber ?></td>
                                        <td><?php echo $sell->createby ?></td>
                                        <td><?php echo $sell->contractnumber ?></td>
                                        <td><?php echo $sell->selldate ?></td>
                                        <td><?php echo $sell->clientname ?></td>
                                        <td><?php echo $sell->clientphone ?></td>
                                        <td><?php echo $sell->checkby ?></td>
                                        <td><?php $status=array('0'=>'待审核','2'=>'已审核');echo $status[$sell->status] ?></td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div> <!-- /widget-content -->
                    </div>
                <?php endif; ?>
            </div> <!-- /span9 -->
        </div> <!-- /row -->
    </div> <!-- /container -->
</div> <!-- /content -->


<?php $this->load->view("common/footer"); ?>

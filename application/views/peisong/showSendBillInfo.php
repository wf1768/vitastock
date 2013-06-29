<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>
<script>
    function onPrint() {
        $(".my_show").jqprint({
            importCSS: false,
            debug: false
        });
        return false;
    }
    $(document).ready(function () {
        $("#doback").click(function () {
            history.back();
            return false;
        });
        $('#datepic').datepicker().on('changeDate', function (ev) {
            $(this).datepicker('hide')
        });
        $("#dosend").click(function () {
            var msg = '确定配配送吗？确定后不能修改!';
            bootbox.confirm(msg, function (result) {
                if (result) {
                    $("#dosends").submit();
                }
            });
            return false;
        });
    });
</script>
<!-- Modal -->
<div id="content">
    <form id="dosends" action="<?php echo site_url("peisong/DoSendBill"); ?>"
          method="post">
        <div class="container">
            <div class="row">
                <div class="span3"><?php $this->load->view('common/leftmenu'); ?></div>
                <!-- /span3 -->
                <div class="span9">
                    <h1 class="page-title"><i class="icon-th-list"></i> <a
                            href="<?php echo site_url('peisong/sDataList') ?>"
                            class="path-menu-a"> 销售单管理</a> >配送单详情</h1>

                    <div class="row">
                        <div class="span9 doconfirm"><label class="pull-right">

                                <a class="btn btn-primary" name="moreinfo" href="javascript:void(0)"
                                   onclick="onPrint()"> 打印 </a>
                                <a class="btn btn-primary" name="moreinfo"
                                   href="<?php echo site_url('peisong/sDataList') ?>"> 返回 </a> </label></div>
                    </div>
                    <div class="row">
                        <div class="span9">
                            <div class="widget">
                                <div class="widget-header">
                                    <h3>配送单详情</h3>
                                </div>
                                <!-- /widget-header -->
                                <div class="widget-content">

                                    <div class="tabbable">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="1">
                                                <table class="table table-bordered" width="100%">
                                                    <tr>
                                                        <td>销售单编号</td>
                                                        <td colspan="3"><?php echo $sell->sellnumber;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>创建人</td>
                                                        <td><?php echo $sell->createby;?></td>
                                                        <td>销售日期</td>
                                                        <td><?php echo $sell->selldate;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>销售店</td>
                                                        <td><?php echo $sell->storehousecode;?></td>
                                                        <td>备注</td>
                                                        <td><?php echo $sell->remark;?></td>
                                                    </tr>

                                                    <tr>
                                                        <td>总价(RMB)</td>
                                                        <td><?php echo $sell->totalmoney;?></td>
                                                        <td>折扣价(RMB)</td>
                                                        <td><?php echo $sell->discount;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>已付金额(RMB)</td>
                                                        <td><?php echo $sell->paymoney;?></td>
                                                        <td>未付金额(RMB)</td>
                                                        <td><?php echo $sell->lastmoney;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>折扣率</td>
                                                        <td><?php
                                                            if ($sell->discount > 0 && $sell->totalmoney > 0) {
                                                                echo ((round($sell->discount / $sell->totalmoney, 2)) * 100) . '%';
                                                            } else {
                                                                echo '0%';
                                                            }
                                                            ?></td>
                                                        <td>销售者</td>
                                                        <td><?php echo $sell->checkby;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>客户名称</td>
                                                        <td><?php echo $sendinfo->clientname;?></td>
                                                        <td>客户电话</td>
                                                        <td><?php echo $sendinfo->clientphone;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>配送人</td>
                                                        <td><?php echo $sendinfo->sendman;?></td>

                                                        <td>配送日期</td>
                                                        <td><?php echo $sendinfo->senddate;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>客户地址</td>
                                                        <td colspan="3"><?php echo $sendinfo->clientadd;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>备注</td>
                                                        <td colspan="3"><?php echo $sendinfo->remark;?></td>
                                                    </tr>
                                                </table>
                                                <div class="row">
                                                    <div class="span8"><label class="pull-left"> 配送商品： </label></div>
                                                </div>
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>序号</th>
                                                        <th>名称</th>
                                                        <th>代码</th>
                                                        <th>描述</th>
                                                        <th>厂家</th>
                                                        <th>颜色</th>
                                                        <th>条形码</th>
                                                        <th>售价</th>
                                                        <th>库房</th>
                                                        <th>状态</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $num = 1; if (isset($list)) foreach ($list as $row): ?>
                                                        <input type="hidden" value="<?php echo $row->id ?>"
                                                               name="chkitem[]">
                                                        <tr id="s<?php echo $row->id ?>">

                                                            <td><?php echo $num ?></td>
                                                            <td><?php echo $row->title ?></td>
                                                            <td><?php echo $row->code ?></td>
                                                            <td><?php echo $row->memo ?></td>
                                                            <td><?php echo $row->factoryname ?></td>
                                                            <td><?php echo $row->color ?></td>
                                                            <td><?php echo $row->barcode ?></td>
                                                            <td><?php echo $row->salesprice ?></td>
                                                            <td><?php echo peisong::getStorehouse($row->storehouseid); ?></td>
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
                                <!-- /widget-content --></div>
                            <!-- /widget --></div>
                        <!-- /span9 --></div>
                    <!-- /row --></div>
                <!-- /span9 --></div>
            <!-- /row --></div>
        <!-- /container --></form>
</div>
<!-- /content -->

<?php $this->load->view("common/footer");  $page = 21; ?>


<div style="width: 800px; height: 0px; overflow: hidden">
    <?php $allcount = 0;for ($i = 0; $i < ceil(count($list) / $page); $i++) : ?>
        <div class="my_show" style="page-break-after: always;">
            <style>
                .print_font {
                    font-size: 12px;
                    margin: 0px 0px 10px 0px;
                }

                .tr_height {
                    height: 20px;
                }

                .foorer_font {
                    font-size: 12px;
                    font-weight: blod;
                }
            </style>
            <table cellspacing="0"
                   style="border-collapse: collapse; border-spacing: 0; background-color: transparent; max-width: 100%"
                   cellpadding="0" width="100%" class="print_font">
                <thead>
                <tr>
                    <th colspan="7">
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                            <tr align="center">
                                <td style="text-align: right; width: 200px;"><img
                                        src='<?php echo base_url('public/img/logo.jpg') ?>'
                                        style="width: 60px;"></td>
                                <td
                                    style="text-align: left; font-size: 20px; padding-bottom: 5px; padding-top: 10px">
                                    &nbsp;&nbsp;北京丰意德工贸有限公司配送单
                                </td>
                            </tr>
                            <tr align="center">
                                <td colspan="2"
                                    style="font-size: 10px; padding-bottom: 30px; padding-top: 5px">(配送单)
                                </td>
                            </tr>
                        </table>
                        <table border="0" cellspacing="0" cellpadding="0" width="100%"
                               class="print_font">
                            <tr>
                                <td align="left" style="padding-left: 10px">
                                    配送单号：&nbsp;<?php echo $sendinfo->sendnumber?></td>
                                <td align="left"></td>
                                <td align="right" style="padding-right: 10px">
                                    合同单号:&nbsp;&nbsp;&nbsp;<?php echo $sell->sellnumber;?>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" style="padding-left: 10px">
                                    客户名称：&nbsp;<?php echo $sendinfo->clientname?></td>
                                <td>客户电话: &nbsp;<?php echo $sendinfo->clientphone?></td>
                                <td align="right" style="padding-right: 10px">
                                    配送日期:&nbsp;<?php echo $sendinfo->senddate?></td>
                            </tr>
                            <tr>
                                <td colspan="3" align="left" style="padding-left: 10px;border:black 0px solid">客户地址：&nbsp;<?php echo $sendinfo->clientadd?></td>
                            </tr>
                        </table>
                    </th>
                </tr>
                <tr style="border: 1px #000 solid; text-align: center; height: 40px">
                    <th style="border: 1px #000 solid;">序号</th>
                    <th style="border: 1px #000 solid;">图片</th>
                    <th style="border: 1px #000 solid;">商品名称</th>
                    <th style="border: 1px #000 solid;">商品描述</th>
                    <th style="border: 1px #000 solid;">厂家</th>
                    <th style="border: 1px #000 solid;">条形码</th>
                    <th style="border: 1px #000 solid;">数量</th>
                    <th style="border: 1px #000 solid;">库房</th>
                    <th style="border: 1px #000 solid;">备注</th>
                </tr>
                </thead>
                <tbody>

                <?php for ($j = $i * $page; $j < $i * $page + $page; $j++) : ?>
                    <?php if ($j < count($list)) : ?>
                        <tr class="content_tr"
                            style="height: 30px; border: 1px #000 solid; text-align: center">
                            <td style="border: 1px #000 solid;"><?php echo $j + 1; ?></td>
                            <td><img class="thumbnail smallImg" src="<?php echo base_url('').$list[$j]->barcode ?>"></td>
                            <td style="border: 1px #000 solid;"><?php echo $list[$j]->title ?></td>
                            <td style="border: 1px #000 solid;"><?php echo $list[$j]->memo ?></td>
                            <td style="border: 1px #000 solid;"><?php echo $list[$j]->factoryname ?></td>
                            <td style="border: 1px #000 solid;"><?php echo $list[$j]->barcode; ?></td>
                            <td style="border: 1px #000 solid;"><?php echo $list[$j]->number ?></td>
                            <td style="border: 1px #000 solid;"><?php echo peisong::getStorehouse($list[$j]->storehouseid); ?></td>
                            <td style="border: 1px #000 solid;"><?php $allcount += $list[$j]->number;?></td>
                        </tr>
                    <?php else : ?>
                        <tr class="content_tr"
                            style="height: 30px; border: 1px #000 solid; text-align: center">
                            <td style="border: 1px #000 solid;"></td>
                            <td style="border: 1px #000 solid;"></td>
                            <td style="border: 1px #000 solid;"></td>
                            <td style="border: 1px #000 solid;"></td>
                            <td style="border: 1px #000 solid;"></td>
                            <td style="border: 1px #000 solid;"></td>
                            <td style="border: 1px #000 solid;"></td>
                            <td style="border: 1px #000 solid;"></td>
                        </tr>
                    <?php endif ?>
                <?php endfor ?>
                <tr style="height: 30px; border: 1px #000 solid; text-align: center">
                    <td style="border: 1px #000 solid;">总计</td>
                    <td style="border: 1px #000 solid;"></td>
                    <td style="border: 1px #000 solid;"></td>
                    <td style="border: 1px #000 solid;"><?php
                        if ($i + 1 == ceil(count($list) / $page)) {
                            echo $allcount;
                        } else {
                            echo '见尾页';
                        }
                        ?></td>
                </tr>
                </tbody>
            </table>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" style="margin-top:20px" class="print_font">
                <tr>
                    <td align="left" style="padding-left: 10px">客户签字:&nbsp;&nbsp;&nbsp;_____________</td>
                    <td align="left"></td>
                    <td align="right" style="padding-right: 10px">
                        配送人:&nbsp;&nbsp;&nbsp;<?php echo $sendinfo->sendman;?></td>
                    <td align="left"></td>
                </tr>
                <tr>
                    <td align="left" style="padding-left: 10px"></td>
                    <td align="left">&nbsp;</td>
                    <td align="right" style="padding-right: 10px"></td>
                    <td align="left"></td>
                </tr>
                <tr>
                    <td align="left" style="padding-left: 10px"></td>
                    <td align="left"></td>
                    <td align="right" style="padding-right: 10px">北京丰意德工贸有限公司</td>
                    <td align="left"></td>
                </tr>
            </table>
        </div>
    <?php endfor;?></div>

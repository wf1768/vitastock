<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>
<style>
    <!--
    .modal {
        background-clip: padding-box;
        background-color: #FFFFFF;
        border: 1px solid rgba(0, 0, 0, 0.3);
        border-radius: 6px 6px 6px 6px;
        box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
        left: 50%;
        margin-left: -375px;
        outline: 0 none;
        position: fixed;
        top: 10%;
        width: 746px;
        z-index: 1050;
    }
    .modal-body {
        max-height: 380px;
        overflow-y: auto;
        padding: 15px;
        position: relative;
    }
    -->
</style>

<script>

    $(function () {
        $("#doback").click(function(){
            history.back();
            return false;
        });
    })

    function onPrint() {
        $(".my_show").jqprint({
            importCSS:false,
            debug:false
        });
    }

    function save_finance() {
        var form_data = $('#add_finance_form').serialize();

        $.ajax({
            type:"post",
            data: form_data,
            url:"<?php echo site_url('finance_check/add_finance_check')?>",
            success: function(data){
                if (data) {
                    window.location.reload();
                }
                else {
                    openalert('处理审核意见出错，请重新尝试或与管理员联系。');
                }
            },
            error: function() {
                openalert('执行操作出错，请重新尝试或与管理员联系。');
            }
        });

    }

    function open_finance() {
        $('#add-finance-dialog').modal('show');
    }
</script>

<!-- Modal -->
<div id="add-finance-dialog" class="modal hide fade" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        添加审核意见
    </div>
    <div class="modal-body">
        <form id="add_finance_form" method="post" action="">
            <input type="hidden" id="sellid" name="sellid" value="<?php echo $sell->id ?>">
            <input type="hidden" id="sellnumber" name="sellnumber" value="<?php echo $sell->sellnumber ?>">
            <table class="table table-striped table-bordered">
                <tbody>
                <tr>
                    <td style="vertical-align:middle">审核时间</td>
                    <td><input style="margin-bottom: 0px;" id="financetime" name="financetime" class="span2" size="16" type="text" value="<?php echo date("Y-m-d")?>" readonly>
                    </td>
                    <td style="vertical-align:middle">审核人</td>
                    <td><input style="margin-bottom: 0px;" id="financeman" name="financeman" class="span2" size="16" type="text" value="<?php echo $this->account_info_lib->accountname ?>" readonly>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:middle">收款金额</td>
                    <td>
                        <div class="input-prepend input-append" style="margin-bottom: 0px;">
                            <span class="add-on">￥</span><input type="text" style="width: 100px;" class="span2" value="0" onkeypress="return isfloat(event)" id="paymoney" name="paymoney"><span class="add-on">.00</span>
                        </div>
                    </td>
                    <td style="vertical-align:middle">余款</td>
                    <td>
                        <div class="input-prepend input-append" style="margin-bottom: 0px;">
                            <span class="add-on">￥</span><input type="text" style="width: 100px;" class="span2" value="0" onkeypress="return isfloat(event)" id="lastmoney" name="lastmoney"><span class="add-on">.00</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:middle">审核意见</td>
                    <td colspan="3">
                        <input type="text" class="span5" id="remark" style="margin-bottom: 0px; width: 536px;" name="remark" >
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
    <div class="modal-footer">
        <a href="javascript:;" onclick="save_finance()" class="btn btn-primary">添加</a>
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    </div>
</div>

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
                    <a href="javascript:;" class="path-menu-a"> 销售审核</a> >销售单详情
                </h1>

                <div class="row">
                    <div class="span9">

                        <div class="widget">

                            <div class="widget-header">
                                <h3>销售单详情</h3>
                            </div>
                            <!-- /widget-header -->
                            <div class="widget-content">
                                <div class="row">
                                    <div class="span8">
                                        <label class="pull-right">
                                            <ul class="nav nav-pills">
                                                <?php if ($sell->financestatus == 0) : ?>
                                                <a href="javascript:;" onclick="open_finance()" class="btn btn-primary">审核意见</a>
                                                <?php endif ?>
                                                <?php if ($sell->status == 0) : ?>
                                                <a href="javascript:;" onclick="return docheck(this)" class="btn btn-primary">通过审核</a>
                                                <?php endif ?>
                                                <a href="javascript:;" onclick="onPrint()" class="btn btn-primary">打印销售单</a>
                                                <a href="<?php echo site_url('saleorder/cwcheck?type=').$type.'&p='.$_GET['p'] ?>" class="btn btn-primary">返回</a>
<!--                                                <a href="javascript:;" id='doback' class="btn btn-primary">返回</a>-->
                                            </ul>
                                        </label>
                                    </div>
                                </div>
                                <div class="tabbable">

                                    销售单信息：
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
                                                    <td>状态</td>
                                                    <td>
                                                        <?php
                                                            if ($sell->status == 0) {
                                                                echo '<font color="red">待审核';
                                                            }
                                                            else {
                                                                echo '<font color="green">已审核';
                                                            }
                                                        ;?>
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
                                                    <td style="color: red"><?php echo $sell->totalmoney;?>  </td>
                                                    <td>折扣价(RMB)</td>
                                                    <td style="color: red"><?php echo $sell->discount;?></td>
                                                </tr>
                                                <tr>
                                                    <td>已付金额(RMB)</td>
                                                    <td style="color: red"><?php echo $sell->paymoney;?>  </td>
                                                    <td>未付金额(RMB)</td>
                                                    <td style="color: red"><?php echo $sell->lastmoney;?></td>
                                                </tr>
                                                <tr>
                                                    <td>折扣率</td>
                                                    <td><?php
                                                        if ($sell->discount >0 && $sell->totalmoney >0) {
                                                            echo ((round($sell->discount/$sell->totalmoney, 2))*100).'%';
                                                        }
                                                        else {
                                                            echo '0%';
                                                        }
                                                        ?>  </td>
                                                    <td>销售者</td>
                                                    <td><?php echo $sell->checkby;?></td>
                                                </tr>
                                                <tr>
                                                    <td>备注</td>
                                                    <td colspan="3"><?php echo $sell->remark;?> </td>
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
                                                    <th>品牌</th>
                                                    <th>类别</th>
                                                    <th>颜色</th>
                                                    <th>售价</th>
                                                    <th>状态</th>
                                                    <th>配送</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $num = 0; if (isset($list)) foreach ($list as $row): ?>
                                                    <tr id="s<?php echo $row->id ?>">
                                                        <td><?php echo $num+1 ?></td>
                                                        <td><?php echo $row->title ?></td>
                                                        <td><?php echo $row->code ?></td>
                                                        <td><?php echo $row->memo ?></td>
                                                        <td><?php echo $row->factoryname ?></td>
                                                        <td><?php echo $row->brandname ?></td>
                                                        <td><?php echo $row->typename ?></td>
                                                        <td><?php echo $row->color ?></td>
                                                        <td><?php echo $row->salesprice?></td>
                                                        <td><?php echo $row->statusvalue ?></td>
                                                        <td><?php echo $row->sendtype ? '自提' : '配送'; $num++?></td>
                                                    </tr>
                                                <?php endforeach;?>
                                                </tbody>
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
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3> 审核意见列表</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th> 审核时间</th>
                                <th> 收款金额</th>
                                <th> 余款</th>
                                <th> 审核人</th>
                                <th> 审核意见</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($finance_check as $row):?>
                                <tr>
                                    <td><?php echo $row->financetime ?></td>
                                    <td><?php echo $row->paymoney ?></td>
                                    <td><?php echo $row->lastmoney ?></td>
                                    <td><?php echo $row->financeman ?></td>
                                    <td><?php echo $row->remark ?></td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div> <!-- /widget-content -->
                </div> <!-- /widget -->
            </div>
            <!-- /span9 -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div> <!-- /content -->



<?php $this->load->view("common/footer"); ?>


<!--------------------------------- -->

<script>

    function docheck(ob) {
        var msg = '确定通过审核?';
        bootbox.confirm(msg, function (result) {
            if (result) {
                $.ajax({
                    type:"get",
                    data: 'id=<?php echo $_GET["id"] ?>&type=<?php echo $type ?>',
                    url:"<?php echo site_url('saleorder/doCwCheck')?>",
                    success: function(data){
                        if (data) {
                            window.location.reload();
                        }
                        else {
                            openalert('审核出错，请重新尝试或与管理员联系。');
                        }
                    },
                    error: function() {
                        openalert('执行操作出错，请重新尝试或与管理员联系。');
                    }
                });
<!--                location.href = "--><?php //echo site_url("saleorder/doCwCheck?id=".$_GET['id'].'&type='.$type)?><!--";-->
            }
        });
        return false;
    }
</script>
<!-- ----------------------------------- -->

<div style="height:0px;width:0px;overflow:hidden">
<?php for ($i=0;$i<ceil(count($list)/10);$i++) : ?>
    <div class="my_show" style="page-break-after: always;">
    <style>
        .print_font {
            font-size: 10px;
            margin:0px 0px 10px 0px;
        }
        .tr_height {
            height: 20px;
        }
        .foorer_font {
            font-size: 12px;
            font-weight:blod;
        }

        .content_tr {}

        .content_tr td {
            height: 10px;
            overflow: hidden;
            font-size: 12px;
        }
    </style>
    <table  cellspacing="0" style="border-collapse: collapse; border-spacing: 0;background-color: transparent;max-width: 100%" cellpadding="0" width="100%" class="print_font">
        <thead>
        <tr>
            <th colspan="7">
                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr align="center">
                        <td style="text-align: right; width: 150px;"><img src='<?php echo base_url('public/img/logo.jpg') ?>' style="width: 90px;"></td>
                        <td style="text-align:left; font-size: 26px;padding-bottom: 5px;padding-top: 0px">&nbsp;&nbsp;北京丰意德工贸有限公司销售合同</td>
                    </tr>
                    <tr align="center">
                        <td colspan="2" style="font-size: 14px;padding-bottom: 10px;padding-top: 0px">(现&nbsp;&nbsp;&nbsp;&nbsp;货)</td>
                    </tr>
                </table>
                <table border="0" cellspacing="0" cellpadding="0" width="100%" class="print_font">
                    <tr>
                        <!--                            <td style="width: 73%"></td>-->
                        <td colspan="2" style="font-size:14px;text-align: right">合同编号：<span style="font-size: 20px;color: red;"><?php echo $sell->sellnumber;?></span></td>
                    </tr>
                    <tr>
                        <td style="font-size: 14px;">一.&nbsp;&nbsp;买方决定购买卖方提供的如下</td>
                        <td style="font-size:14px;text-align: right">销售店：<?php echo $sell->storehousecode ?> 签订日期：<?php echo $sell->selldate; ?></td>
                    </tr>
                </table>
            </th>
        </tr>
        <tr style="border:1px #000 solid;text-align: center;height: 40px">
            <th style="border:1px #000 solid;">序号</th>
            <th style="border:1px #000 solid;">生产厂家</th>
            <th style="border:1px #000 solid;">产品描述</th>
            <th style="border:1px #000 solid;">数量</th>
            <th style="border:1px #000 solid;">条形码</th>
            <th style="border:1px #000 solid;">单价(RMB)</th>
            <th style="border:1px #000 solid;">配送<br/>方式</th>
        </tr>
        </thead>
        <tbody >
        <?php for ($j=$i*10;$j<$i*10+10;$j++) :?>
            <?php if ($j<count($list)) :?>
                <tr class="content_tr" style="height:30px;border:1px #000 solid;text-align: center">
                    <td style="border:1px #000 solid;"><?php echo $j+1; ?></td>
                    <td style="border:1px #000 solid;"><?php echo $list[$j]->factoryname ?></td>
                    <td style="border:1px #000 solid;"><?php echo $list[$j]->title ?></td>
                    <td style="border:1px #000 solid;"><?php echo $list[$j]->number ?></td>
                    <td style="border:1px #000 solid;"><?php echo $list[$j]->barcode; ?></td>
                    <td style="border:1px #000 solid;"><?php echo $list[$j]->salesprice; ?></td>
                    <td style="border:1px #000 solid;"><?php echo $list[$j]->sendtype ? '自提' : '配送'; ?></td>
                </tr>
            <?php else :?>
                <tr class="content_tr" style="height:30px;border:1px #000 solid;text-align: center">
                    <td style="border:1px #000 solid;"></td>
                    <td style="border:1px #000 solid;"></td>
                    <td style="border:1px #000 solid;"></td>
                    <td style="border:1px #000 solid;"></td>
                    <td style="border:1px #000 solid;"></td>
                    <td style="border:1px #000 solid;"></td>
                </tr>
            <?php endif ?>
        <?php endfor ?>
        <tr class="content_tr" style="height:30px;border:1px #000 solid;text-align: center">
            <td style="border:1px #000 solid;"></td>
            <td style="border:1px #000 solid;">总价(RMB)</td>
            <td style="border:1px #000 solid;"></td>
            <td style="border:1px #000 solid;"></td>
            <td style="border:1px #000 solid;"></td>
            <td style="border:1px #000 solid;">
                <?php
                if ($i+1 == ceil(count($list)/10)) {
                    echo $sell->totalmoney;
                }
                else {
                    echo '';
                }
                ?>
            </td>
        </tr>
        <tr class="content_tr" style="height:30px;border:1px #000 solid;text-align: center">
            <td style="border:1px #000 solid;"></td>
            <td style="border:1px #000 solid;">折扣价(RMB)</td>
            <td style="border:1px #000 solid;"></td>
            <td style="border:1px #000 solid;"></td>
            <td style="border:1px #000 solid;"></td>
            <td style="border:1px #000 solid;">
                <?php
                if ($i+1 == ceil(count($list)/10)) {
                    echo $sell->discount .'(折扣率:'.((round($sell->discount/$sell->totalmoney, 2))*100).'%'.')';
                }
                else {
                    echo '见尾页';
                }
                ?>
            </td>
        </tr>
        </tbody>
    </table>
    <table border="0" cellspacing="0" cellpadding="0" width="100%" class="print_font">
        <tr class="tr_height">
            <td valign="top" style="width: 25px;line-height:1.5em;" >二.</td>
            <td valign="top" style="line-height:1.5em;">付款方式：买方需于签订合同当日交纳合同总额的50%作为订金，在送货之前付清余款，如付支票卖方在银行兑现之后送货。买方订购期货时，只有买方按合同规定交付足额定金后卖方才开始执行本合同。
            </td>
        </tr>
        <tr class="tr_height">
            <td style="width: 15px;line-height:1.5em;"></td>
            <td style="font-size: 12px;">首付(RMB)：<span style="text-decoration:underline;"><?php echo $sell->paymoney?></span> 余款(RMB)： <span style="text-decoration:underline;"><?php echo $sell->lastmoney?></span> 订金收据号：______________
            </td>
        </tr>
        <tr class="tr_height">
            <td valign="top" style="width: 15px;line-height:1.5em;">三.</td>
            <td valign="top" style="line-height:1.5em;">商品一年内出现质量问题，由卖方负责免费维修，因买方使用不当，或超过交货时间一年的，卖方维修时，收取成本费和服务费。
            </td>
        </tr>
        <tr class="tr_height">
            <td valign="top" style="width: 15px;line-height:1.5em;">四.</td>
            <td valign="top" style="line-height:1.5em;">买方需要退货，应在订金交付后三日内办理，特殊要求订购的期货，不予退货。如买方坚持退货，卖方不退还订金。
            </td>
        </tr>
        <tr class="tr_height">
            <td valign="top" style="width: 15px;line-height:1.5em;">五.</td>
            <td valign="top" style="line-height:1.5em;">买方应在签订的交货期十五天内提货，超过此期限，由于仓库存储造成的破损卖方不承担责任：超过三个月不提货，卖方收取存货总值的1%/月作为仓库保管费。
            </td>
        </tr>
        <tr class="tr_height">
            <td style="width: 15px">六.</td>
            <td>由于卖方其自身原因未能按期交货，每天按未到货货款的1‰，作为违约金。
            </td>
        </tr>
        <tr class="tr_height">
            <td style="width: 15px;line-height:1.5em;">七.</td>
            <td style="line-height:1.5em;">买方如需空运卖方加收需空运货物总值的15%作为加急服务费。
            </td>
        </tr>
        <tr class="tr_height">
            <td valign="top" style="width: 15px;line-height:1.5em;">八.</td>
            <td valign="top" style="line-height:1.5em;">因不可抗力因素造成合同不能正常履行时，发生方应立即通知对方合同中止或终止执行，另一方不得追究其违约责任。不能执行一方必须在发生不可抗力事件
                两周内就不可抗力提出公证证明，不可抗力结束后，双方可就合同能否继续执行提出书面协议。
            </td>
        </tr>
        <tr class="tr_height">
            <td style="width: 15px">九.</td>
            <td>交货时间：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日
            </td>
        </tr>
        <tr class="tr_height">
            <td style="width: 15px">十.</td>
            <td>其他约定事项：
            </td>
        </tr>
        <tr class="tr_height">
            <td style="width: 30px">十一.</td>
            <td>本合同一式贰份，买方执壹份，卖方执壹份。
            </td>
        </tr>
    </table>
    <div >
        <table border="0" cellspacing="0" cellpadding="0" width="100%" class="foorer_font" >
            <tr class="tr_height">
                <td style="width: 60px;line-height:1.5em;padding-top: 30px" >买&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;方：</td>
                <td style="text-align: left;width: 300px;padding-top: 30px">____________________________</td>
                <td style="width: 60px;line-height:1.5em;padding-top: 30px" >卖&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;方：</td>
                <td style="line-height:1.5em;padding-top: 30px">北京丰意德工贸有限公司</td>
            </tr>
            <tr class="tr_height">
                <td></td>
                <td></td>
                <td style="line-height:1.5em;">经办人：</td>
                <td style="line-height:1.5em;">__________________________</td>
            </tr>
            <tr class="tr_height">
                <td>联系电话：</td>
                <td>____________________________</td>
                <td style="line-height:1.5em;">审核人：</td>
                <td style="line-height:1.5em;">__________________________</td>
            </tr>
            <tr class="tr_height">
                <td></td>
                <td></td>
                <td colspan="2" style="line-height:1.5em;">旗舰店：85370666&nbsp;&nbsp;&nbsp;中粮店：85111616</td>
            </tr>
            <tr class="tr_height">
                <td>送货地址：</td>
                <td>____________________________</td>
                <td colspan="2" style="line-height:1.5em;padding-bottom: 0px;">天津店：022-88259818&nbsp;&nbsp;居然店：84636618</td>
            </tr>
        </table>
        <div style="margin-top: -190px;margin-right: 0px;float: right;"><img src="<?php echo base_url('public/img/vita_seal.png') ?>" width="180px" height="180px" /></div>
    </div>
    </div>
<?php endfor ?>
</div>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>
<script>
    function onPrint() {
        $(".my_show").jqprint({
            importCSS:false,
            debug:false
        });
    }
    $(document).ready(function(){
      $("#doback").click(function(){ 
          history.back();
          return false;
      });
      $('#datepic').datepicker().on('changeDate', function(ev) {
  		$(this).datepicker('hide')
      });
      $("#dosend").click(function(){
         var msg='确定配配送吗？确定后不能修改!';
    	 bootbox.confirm(msg, function(result) {
    	     if(result){
    	    	 $("#dosends").submit();
    	   	 } 
    	 }); 
         return false;
      });
    });
</script>
<!-- Modal -->
<div id="content">
  <form id="dosends" action="<?php echo site_url("peisong/DoSendBill");?>" method="post">
    <div class="container">
        <div class="row">
            <div class="span3">
                <?php $this->load->view('common/leftmenu'); ?>
            </div>
            <!-- /span3 -->
            <div class="span9">
                <h1 class="page-title">
                    <i class="icon-th-list"></i>
                     <a href="<?php echo site_url('saleorder/orderList') ?>" class="path-menu-a"> 销售单管理</a> >配送单详情
                </h1>
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
                                        <input type="hidden" name="sellid" value="<?php echo trim($_POST['billid'])?>">	
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
                                                    <td>备注</td>
                                                    <td><?php echo $sell->remark;?> </td>
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
                                                    <td>客户名称</td>
                                                    <td><input type="text" value="<?php echo $sell->clientname;?>" name="clientname"></td>
                                                    <td>客户电话</td>
                                                    <td><input type="text" value="<?php echo $sell->clientphone;?>"  name="clientphone"></td>
                                                </tr>
                                                <tr>
                                                    <td>配送日期</td>
                                                    <td>
                                                        <div class="input-append date" id="datepic" data-date="<?php echo date("Y-m-d")?>" data-date-format="yyyy-mm-dd">
                                                            <input name="senddate" class="span2" style="width:176px" size="16" type="text" value="<?php echo date("Y-m-d")?>" readonly>
                                                            <span class="add-on"><i title="点击选择日期" class="icon-calendar"></i></span>
                                                        </div>
                                                    </td>
                                                    <td>配送人</td>
                                                    <td><input type="text" value="" placeholder="请输入配送人员姓名"  name="sendman" required></td>
                                                </tr>
                                                <tr>
                                                    <td>客户地址</td>
                                                    <td colspan="3"><input type="text" value="<?php echo $sell->clientadd;?>" class="span5" name="clientadd"></td>
                                                </tr>
                                                <tr>
                                                    <td>备注</td>
                                                    <td colspan="3"><input type="text" value="" class="span5"   name="remark" ></td>
                                                </tr>
                                            </table>
                                            <div class="row">
                                                <div class="span8">
                                                    <label class="pull-left">
                                                        <ul class="nav nav-pills">
                                                            <li class="active">
                                                            <input class="btn btn-primary"  type="submit" value="配送选中产品">
                                                            
                                                            </li>
                                                        </ul>
                                                    </label>
                                                   
                                                </div>
                                            </div>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>序号</th>
                                                    <th>名称</th>
                                                    <th>代码</th>
                                                    <th>描述</th>
                                                    <th>厂家</th>
<!--                                                    <th>品牌</th>-->
<!--                                                    <th>类别</th>-->
                                                    <th>颜色</th>
                                                    <th>条形码</th>
                                                    <th>售价</th>
                                                    <th>状态</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $num=1; if(isset($list)) foreach($list as $row):?>
                                                    <input  type="hidden"  value="<?php echo $row->id ?>" name="chkitem[]">
                                                    <tr id="s<?php echo $row->id ?>">
                                                         
                                                        <td>
                                                       
                                                          <?php echo $num ?></td>
                                                        <td><?php echo $row->title ?></td>
                                                        <td><?php echo $row->code ?></td>
                                                        <td><?php echo $row->memo ?></td>
                                                        <td><?php echo $row->factoryname ?></td>
<!--                                                        <td>--><?php //echo $row->brandname ?><!--</td>-->
<!--                                                        <td>--><?php //echo $row->typename ?><!--</td>-->
                                                        <td><?php echo $row->color ?></td>
                                                        <td><?php echo $row->barcode ?></td>
                                                        <td><?php echo $row->salesprice ?></td>
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
  </form>
</div> <!-- /content -->

<?php $this->load->view("common/footer"); ?>


<div style="height:0px;width:0px;overflow:hidden">
    <div class="my_show">
        <style>
            .print_font {
                font-size: 12px;
                margin:0px 0px 10px 0px;
            }
            .tr_height {
                height: 20px;
            }
            .foorer_font {
                font-size: 12px;
                font-weight:blod;
            }
        </style>
        <table  cellspacing="0" style="border-collapse: collapse; border-spacing: 0;background-color: transparent;max-width: 100%" cellpadding="0" width="100%" class="print_font">
            <thead>
            <tr>
                <th colspan="7">
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tr align="center">
                            <td style="text-align: right; width: 200px;"><img src='<?php echo base_url('public/img/logo.jpg') ?>' style="width: 60px;"></td>
                            <td style="text-align:left; font-size: 20px;padding-bottom: 5px;padding-top: 10px">&nbsp;&nbsp;北京丰意德工贸有限公司销售合同</td>
                        </tr>
                        <tr align="center">
                            <td colspan="2" style="font-size: 10px;padding-bottom: 30px;padding-top: 5px">(现&nbsp;&nbsp;&nbsp;&nbsp;货)</td>
                        </tr>
                    </table>
                    <table border="0" cellspacing="0" cellpadding="0" width="100%" class="print_font">
                        <tr>
                            <td style="width: 80%"></td>
                            <td style="text-align: left">合同编号：<?php echo $sell->contractnumber;?></td>
                        </tr>
                        <tr>
                            <td>一.&nbsp;&nbsp;买方决定购买卖方提供的如下</td>
                            <td style="text-align: left">签订日期：<?php echo date("Y-m-d"); ?></td>
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
            </tr>
            </thead>
            <tbody >
            <?php $num=1; if(isset($list)) foreach($list as $row): ?>
                    <tr style="height:30px;border:1px #000 solid;text-align: center">
                        <td style="border:1px #000 solid;"><?php echo $num ?></td>
                        <td style="border:1px #000 solid;"><?php echo $row->factoryname ?></td>
                        <td style="border:1px #000 solid;"><?php echo $row->title ?></td>
                        <td style="border:1px #000 solid;">1</td>
                        <td style="border:1px #000 solid;"><?php echo $row->barcode ?></td>
                        <td style="border:1px #000 solid;"><?php echo $row->salesprice;$num++ ?></td>
                    </tr>
                <?php endforeach;?>
                <tr style="height:30px;border:1px #000 solid;text-align: center">
                    <td style="border:1px #000 solid;"></td>
                    <td style="border:1px #000 solid;">总价(RMB)</td>
                    <td style="border:1px #000 solid;"></td>
                    <td style="border:1px #000 solid;"></td>
                    <td style="border:1px #000 solid;"></td>
                    <td style="border:1px #000 solid;"><?php echo $sell->totalmoney?></td>
                </tr>
                <tr style="height:30px;border:1px #000 solid;text-align: center">
                    <td style="border:1px #000 solid;"></td>
                    <td style="border:1px #000 solid;">折扣价(RMB)</td>
                    <td style="border:1px #000 solid;"></td>
                    <td style="border:1px #000 solid;"></td>
                    <td style="border:1px #000 solid;"></td>
                    <td style="border:1px #000 solid;"><?php echo $sell->discount?>（折扣率：<?php echo ((round($sell->discount/$sell->totalmoney, 2))*100).'%';?>）</td>
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
                <td>首付：_______________ 余款：_____________ 订金收据号：______________
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
                <td colspan="2" style="line-height:1.5em;">天津店：022-88259818&nbsp;&nbsp;居然店：84636618</td>
            </tr>
        </table>
        <div style="margin-top: -150px;margin-right: 0px;float: right;"><img src="<?php echo base_url('public/img/vita_seal.png') ?>" width="180px" height="180px" /></div>
<!--            <div style="position:absolute; z-index:100;bottom: 20; right: 10;"><img src="--><?php //echo base_url('public/img/vita_seal.png') ?><!--" width="100px" height="100px" /></div>-->
        </div>
    </div>
</div>

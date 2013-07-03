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
      })
    });
    //结束销售单
    function isover(id) {
        if (id == '') {
            return;
        }

        bootbox.confirm('确定要［结束配送］吗？<br /> <span style="color: red">注意：销售商品如果有需要配送时，系统自动判断是否全部配送完毕。当销售商品全部为［自提］时，才用［结束配送］。结束配送后，将不能再对商品进行配送。</span> ', function(result) {
            if(result){
                $.ajax({
                    type: "get",
                    data: "id=" + id,
                    url: "<?php echo site_url('peisong/update_peisong_over')?>",
                    success: function (data) {
                        if (data) {
                            window.location.reload();
                        }
                        else {
                            openalert("结束配送出错，请重新尝试或与管理员联系。");
                        }
                    },
                    error: function () {
                        openalert("执行操作出错，请重新尝试或与管理员联系。");
                    }
                });
            }
        })
    }
    $(document).ready(function(){
        //全选删除
        $("#select-all").click(function(){
            if($(this).attr("checked")){
                $(".dochecksend").attr("checked",true)
            }else{
                $(".dochecksend").attr("checked",false)
            }
        });
        //删除商品
        $("#dopeisong").click(function(){
         if( $(".dochecksend").filter(":checked").length==0){
            openalert("当前没有产品被选中"); return false;
         }
         var msg='确定配送选中的产品吗?';
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
    <div class="container">
        <div class="row">
            <div class="span3">
                <?php $this->load->view('common/leftmenu'); ?>
            </div>
            <!-- /span3 -->
            <div class="span9">
                <h1 class="page-title">
                    <i class="icon-th-list"></i>
                     <a href="<?php echo site_url('peisong/sDataList') ?>" class="path-menu-a"> 商品配送</a> >销售单详情
                </h1>
                <div class="row">
                    <div class="span9">
                        <div class="widget">
                            <div class="widget-header">
                                <h3>销售单详情</h3>
                            </div>
                            <!-- /widget-header -->
                            <div class="widget-content">
                                <div class="tabbable">
                                   
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
                                                    <td>备注</td>
                                                    <td colspan="3"><?php echo $sell->remark;?> </td>
                                                </tr>
                                            </table>
                                            <div class="alert alert-info">
                                                <button data-dismiss="alert" class="close" type="button">×</button>
                                                商品配送说明：<br />
                                                1、销售商品可以多次配送，生成多个配送单。<br />
                                                2、自提的商品不能配送。如果仍有商品需要配送，销售单状态为［未配送］。当配送商品都配送完毕。销售单状态自动更改为［已配送］，销售单结束。<br />
                                                3、当全部商品都为［自提］时，需要手动点击［结束配送］按钮，将销售单的状态更改为［已配送］。
                                            </div>
                                           <div class="row">
                                                <div class="span8">
                                                    <label class="pull-left">
                                                        <ul class="nav nav-pills">
<!--                                                          --><?php //if(!isset($_GET['type'])):?>
                                                            <?php if($sell->status == 2):?>
                                                                <a href="javascript:;" id="dopeisong" class="btn btn-primary">配送选中商品</a>
                                                                <a href="javascript:;" onclick="isover('<?php echo $sell->id;?>')" class="btn btn-primary">结束配送</a>
<!--                                                            <li class="active"><a id="dopeisong" href="">配送选中商品</a></li>-->
                                                           <?php endif;?>
                                                        </ul>
                                                    </label>
                                                   
                                                </div>
                                            </div>
                                           <form id="dosends" action="<?php echo site_url("peisong/preDoSendBill");?>" method="post">
                                               <input type="hidden" value="<?php echo $sell->id;?>" name="billid">
                                               <table class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th><input id="select-all" type="checkbox" "=""></th>
                                                    <th>名称</th>
                                                    <th>代码</th>
                                                    <th>描述</th>
                                                    <th>厂家</th>
                                                    <th>颜色</th>
                                                    <th>条形码</th>
                                                    <th>售价</th>
                                                    <th>库房</th>
                                                    <th>状态</th>
                                                    <th>配送</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $num=1; if(isset($list)) foreach($list as $row):?>
                                                    <tr id="s<?php echo $row->id ?>">
                                                        <td>
                                                        <?php if($row->issend==0 && $row->sendtype == 0):?>
                                                           <input type="checkbox" class="dochecksend" value="<?php echo $row->id ?>" name="chkitem[]">
                                                        <?php endif;?>
                                                        </td>
                                                        <td><?php echo $row->title ?></td>
                                                        <td title="<?php echo $row->code ?>"><?php echo Common::subStr($row->code, 0, 10) ?></td>
                                                        <td title="<?php echo $row->memo ?>"><?php echo Common::subStr($row->memo, 0, 20) ?></td>
                                                        <td><?php echo $row->factoryname ?></td>
                                                        <td><?php echo $row->color ?></td>
                                                        <td><?php echo $row->barcode ?></td>
                                                        <td><?php echo $row->salesprice ?></td>
                                                        <td><?php echo peisong::getStorehouse($row->storehouseid); ?></td>
                                                        <td><?php echo $row->statusvalue;$num++ ?></td>
                                                        <td><?php echo $row->sendtype ? '自提' : '配送' ?></td>
                                                    </tr>
                                                    <?php endforeach;?>
                                                </tbody>
                                            </table>
                                           </form>
                                           
                                           <?php if($sendlist):?>
                                           
                                             <div class="row">
                                                <div class="span8">
                                                    <label class="pull-left">
                                                        产品配送单：
                                                    </label>
                                                   
                                                </div>
                                            </div>
                                            <br/>
                                             <table class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>配送单号</th>
                                                    <th>配送日期</th>
                                                    <th>配送人</th>
                                                    <th>备注</th>
                                                    <th>操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(isset($sendlist)) foreach($sendlist as $row):?>
                                                    <tr>
                                                        <td><?php echo $row->sendnumber ?></td>
                                                        <td><?php echo $row->senddate ?></td>
                                                        <td><?php echo $row->sendman ?></td>
                                                        <td><?php echo $row->remark ?></td>
                                                        <td>
                                                           <a href="<?php echo site_url("peisong/showSendBillInfo?id=".$row->id)?>"  class="btn btn-primary">查看详情</a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach;?>
                                                </tbody>
                                            </table>
                                            <?php endif;?>
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

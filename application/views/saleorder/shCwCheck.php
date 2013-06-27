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

    })

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
                    <td><input style="margin-bottom: 0px;" id="financetime" name="financetime" class="span2" size="16" type="text" value="<?php echo date("Y-m-d H:m:s")?>" readonly>
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
                    <a href="<?php echo site_url('saleorder/orderList') ?>" class="path-menu-a"> 销售单管理</a> >销售单详情
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
                                                <a href="<?php echo site_url('saleorder/cwcheck?type=').$type ?>" class="btn btn-primary">返回</a>
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
<!--                                                <tr>-->
<!--                                                    <td>总金额</td>-->
<!--                                                    <td colspan="3">--><?php //echo $sell->totalmoney;?><!--  </td>-->
<!--                                                </tr>-->
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
                                                    <th>材质等级</th>
                                                    <th>售价</th>
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
                                                        <td><?php echo $row->statusvalue ?></td>
                                                        <td><?php echo $row->salesprice?></td>
<!--                                                        <td>--><?php //echo $salprice[$row->id]?><!--</td>-->
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
<!--                                <th> 操作</th>-->
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
<!--                                    <td>-->
<!--                                        --><?php //if ($this->account_info_lib->id == $row->createbyid) :?>
<!--                                            <a href="" _var="--><?php //echo $row->id?><!--" name='returnbill' class="btn btn-primary">退单</a>-->
<!--                                        --><?php //endif;?>
<!--                                    </td>-->
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
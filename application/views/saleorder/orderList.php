<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<div id="content">
<div class="container">
<div class="row">
<div class="span3">
    <?php $this->load->view('common/leftmenu'); ?>




</div> <!-- /span3 -->
<script>
$(document).ready(function(){
	$("#supsearch").click(function(){
	    $("#search").toggle();
	    $("#dosupsearch").toggle();
		return false;
	});
})
</script>

<div class="span9">
    <h1 class="page-title">
        <i class="icon-th-list"></i>
        <a href="<?php echo site_url('saleorder/orderList') ?>" class="path-menu-a">销售单管理</a> > 销售单列表
    </h1>
    <div class="row">
        <div class="span9 doconfirm">
            <label class="pull-right">
                <input type="hidden" id="path" value="<?php echo site_url('') ?>">
                <a href="" id="supsearch" class="btn">
                    <i class="icon-search">高级检索</i>
                </a>
                <a href="<?php echo site_url("saleorder/addOrder")?>" id="add" class="btn">
                    <i class="icon-plus"> 添加销售单 </i>
                </a>


            </label>
        </div>
    </div>
    <div class="row" id="search" style="display: <?php if(isset($_GET['sup']))  echo "none";?>">
        <form id="stock_list_btn_form" name="stock_list_btn_form" action="<?php echo site_url('saleorder/orderList')?>" method="POST" class="form-inline">
            <div class="span5">
                <label>
                </label>
            </div>
            <div class="span4">
                <input type="text" style="display:none">
                <label class="pull-right">单号: 
                <input id="searchTxt" type="text" value="<?php echo isset($_REQUEST['sellnumber'])?$_REQUEST['sellnumber']:'';?>"  name="sellnumber" placeholder="请输销售单号">
                <input type="submit" value="查询" class="btn btn-primary" /> 
                </label>
            </div>
        </form>
    </div>
    <span style="display: <?php if(!isset($_GET['sup']))  echo "none";?>" id="dosupsearch">
                <div class="minbox">
                    <div class="part_search">
                        <div class="navbar">
                            <div class="navbar-inner">
                                <form style="margin-bottom:5px" id="searchfrom" action="<?php echo site_url('saleorder/orderList?sup=1')?>" method="POST"  class="navbar-form">
                                    <input type="hidden" value="1" name="search_type">
                                    <input type="hidden" value="1" name="houseid" id="houseid">
                                    <font class="myfont">&nbsp;&nbsp;销售单号：</font>
                                    <input type="text" placeholder="请输销售单号" value="<?php echo isset($_REQUEST['sellnumber'])?$_REQUEST['sellnumber']:'';?>" id="tiaoma" name="sellnumber">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font class="myfont">销售店：</font>
                                    <select id="storehouseid" name="storehouseid" class="span2" >
                                        <?php if($houses):?>
                                            <?php foreach($houses as $house):?>
                                                <option value="<?php echo $house->id ?>" <?php if ($house->id == $storehouseid) echo 'selected="true"' ?> ><?php echo $house->storehousecode ?></option>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                    </select><br>
                                    &nbsp;&nbsp;<font class="myfont">客户名称：</font>
                                    <input type="text" placeholder="请输入客户名称" value="<?php echo isset($_REQUEST['clientname'])?$_REQUEST['clientname']:'';?>" id="clientname" name="clientname">
                                   &nbsp;&nbsp; <font class="myfont">销&nbsp;售&nbsp;&nbsp;者：</font>
                                    <input type="text" placeholder="请输入销售者" value="<?php echo isset($_REQUEST['checkby'])?$_REQUEST['checkby']:'';?>" id="boxno" name="checkby">
                                    
                                    <button class="btn btn-primary" type="submit" id="search" style="margin-left:20px">&nbsp;&nbsp;搜&nbsp;&nbsp;索&nbsp;&nbsp;</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                </span>
    
    
    <div class="widget widget-table">
        <div class="widget-header">
            <i class="icon-th-list"></i>
            <h3> 销售单列表</h3>
        </div> <!-- /widget-header -->
        <div class="widget-content">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
<!--                    <th><input type="checkbox" id="select-all""></th>-->

                    <th> 销售单编号</th>
                    <th> 创建者</th>
                   <!-- <th> 创建时间</th> -->
<!--                    <th> 合同编号</th>-->
                    <th> 销售日期</th>
                    <th> 客户名称</th>
<!--                    <th> 客户电话</th>-->
                    <th> 销售者</th>
                    <th> 折扣总价</th>
                    <th> 已付金额</th>
                    <th> 销售库房</th>
                    <th> 状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($list as $row):?>
                    <tr>
<!--                        <td><input type="checkbox" name="checkbox" value="<?php echo $row->id ?>"/></td>-->
                        <td><?php echo $row->sellnumber ?></td>
                        <td><?php echo $row->createby ?></td>
                       <!-- <td><?php echo $row->createtime ?></td> -->
                        <!--<td><?php echo $row->contractnumber ?></td>
                        --><td><?php echo $row->selldate ?></td>
                        <td><?php echo $row->clientname ?></td><!--
                        <td><?php echo $row->clientphone ?></td>
                        --><td><?php echo $row->checkby ?></td>
                        <td><?php echo $row->discount ?></td>
                        <td><?php echo $row->paymoney ?></td>
                        <td><?php echo $row->storehousecode ?></td>
                        <td><?php $status=array('0'=>'待审核','2'=>'已审核','3'=>'已配送','6'=>'期货部分配送');echo $status[$row->status] ?></td>
                        <td>
                         <a href="<?php echo site_url("saleorder/showInfo?id=".$row->id)?>" class="btn btn-primary">详情</a>
<!--                       --><?php //if($row->status !=3 ):?>
                                <?php if ($this->account_info_lib->id == $row->createbyid) :?>
                            <a href="" _var="<?php echo $row->id?>" name='returnbill' class="btn btn-primary">退单</a>
                                <?php endif;?>
<!--                       --><?php //endif;?>
                         </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div> <!-- /widget-content -->
        <div class="row">
            <div class="span4" style="margin-top:20px ">
            <?php echo $info;?>
            </div>
            <div class=" pagination pagination-right">
            <?php
               echo $page;
            ?>
            </div>
        </div>
    </div> <!-- /widget -->
</div> <!-- /span9 -->
</div> <!-- /row -->
</div> <!-- /container -->
</div> <!-- /content -->


<?php $this->load->view("common/footer"); ?>
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" name="closeit" aria-hidden="true">×</button>
        <h3 id="myModalLabel">取消订单</h3>
  </div>
   <div class="modal-body">
        <form id="doreturns" class="form-horizontal" method="post" action="<?php echo site_url('saleorder/billReturn')?>">
                 <input type="hidden" value="" name="bid" id="sbid"> 
                  <div class="control-group">
                      <label class="control-label" for="inputEmail">退单描述</label>
                       <div class="controls">
                         <textarea rows="" name="returnmemo" cols="" class="span3"></textarea>
                       </div>
                  </div>
                  <div class="control-group">
                      <label class="control-label" for="inputEmail">退单备注</label>
                       <div class="controls">
                         <textarea rows="" name="remark" cols="" class="span3"></textarea>
                      </div>
                  </div>
        </form>	
   </div>
   <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true" name="closeit">关闭</button>
        <button class="btn btn-primary" id="dobillreturn"data-dismiss="modal" aria-hidden="true" name="closeit">退单</button>
   </div>
</div>
<script>
$("[name='returnbill']").click(function(){
	$("#sbid").val($(this).attr('_var'));
	$('#myModal').modal({ backdrop:false});
	$('<div class="modal-backdrop fade in">rtrt</div>').appendTo(document.body);
	return false;
 });
$('[name="closeit"]').click(function(){
	 $("body div:last").fadeOut(400,function(){
        $(this).remove();
        //清除产品搜到的产品列表  
       // $("#tbody").html('');
    });
});
$("#dobillreturn").click(function(){
	$("#doreturns").submit();
});
</script>



<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<div id="content">
<div class="container">
<div class="row">
<div class="span3">
    <?php $this->load->view('common/leftmenu'); ?>

<style>
.datepicker {
    border-radius: 4px 4px 4px 4px;
    left: 0;
    margin-top: 1px;
    padding: 4px;
    top: 0;
    z-index: 10000;
    
}
</style>


</div> <!-- /span3 -->


<div class="span9">
    <h1 class="page-title">
        <i class="icon-th-list"></i>
        <a href="javascript:;" class="path-menu-a">商品配送</a> > 销售单列表
    </h1>
   
    <div class="row">
    
        <form id="stock_list_btn_form" name="stock_list_btn_form" action="" method="POST" class="form-inline">
        
            <div class="span5">
                            <label class="radio inline">
                                <input type="radio" <?php if($type==2) echo 'checked'?> value="0" id="status1" name="status">未结束
                            </label>
                            <label class="radio inline">
                                <input type="radio" <?php if($type==10) echo 'checked'?> value="1" id="status2" name="status">已结束
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
    <div class="widget widget-table">
        <div class="widget-header">
            <i class="icon-th-list"></i>
            <h3>销售单列表</h3>
        </div> <!-- /widget-header -->
        <div class="widget-content">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
<!--                    <th><input type="checkbox" id="select-all""></th>-->
                    <th> 销售单编号</th>
                    <th> 创建者</th>
                    <th> 销售店</th>
                    <th> 销售日期</th>
                    <th> 客户名称</th>
                    <th> 客户电话</th>
                    <th> 操作人</th>
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
                        <td><?php echo $row->storehousecode ?></td>
                        <td><?php echo $row->selldate ?></td>
                        <td><?php echo $row->clientname ?></td>
                        <td><?php echo $row->clientphone ?></td>
                        <td><?php echo $row->checkby ?></td>
                        <td><?php $status=array('3'=>'已配送','2'=>'未配送','6'=>'期货部分配送');echo $status[$row->status] ?></td>
                        <td>
                        <?php if($type==2):?>
                          <a href="" _var="<?php echo $row->id?>" name='moreinfo' class="btn btn-primary">详情</a>
                          <?php if($row->status ==2):?>
                          <a href="<?php echo site_url("peisong/showBillInfo?id=".$row->id)?>"  name='returnbill' class="btn btn-primary">配送</a>
                          <?php else:?>
                          <button class="btn">配送</button>
                          <?php endif;?>
                          <?php else:?>
                           <a href="<?php echo site_url("peisong/showBillInfo?type=10&id=".$row->id)?>"  name='returnbill' class="btn btn-primary">详细</a>
                          <?php endif;?>
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
        <h3 id="myModalLabel">配送订单</h3>
  </div>
   <div class="modal-body">
        <form id="doreturns" class="form-horizontal" method="post" action="<?php echo site_url('peisong/doPeisong')?>">
                 <input type="hidden" value="" name="bid" id="sbid"> 
                  <div class="control-group">
                      <label class="control-label" for="inputEmail">配送人</label>
                       <div class="controls">
                         <input type="text" value=""  placeholder="请输入配送人姓名" id="getname" name="getname" required>
                       </div>
                  </div>
                  <div class="control-group">
                      <label class="control-label" for="inputEmail">配送日期</label>
                       <div class="controls">
                         <div class="input-append date" id="datepic" data-date="<?php echo date("Y-m-d")?>" data-date-format="yyyy-mm-dd">
				                                         <input name="selldate" class="span2" style="width:176px" size="16" type="text" value="<?php echo date("Y-m-d")?>" readonly>
				                                         <span class="add-on"><i title="点击选择日期" class="icon-calendar"></i></span>
			                                           </div>
                      </div>
                  </div>
        </form>	
   </div>
   <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true" name="closeit">关闭</button>
        <button class="btn btn-primary" id="dobillreturn"data-dismiss="modal" aria-hidden="true" name="closeit">配送</button>
   </div>
</div>
<div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 913px;margin-left: -470px">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" name="closeit" aria-hidden="true">×</button>
        <h3 id="myModalLabel">销售单详细信息</h3>
  </div>
   <div class="modal-body info " style="overflow-y:scroll;overflow-x:hidden; " ><!-- style="overflow-x:hidden" -->

   </div>
   <div class="modal-footer">
       <button class="btn" data-dismiss="modal" aria-hidden="true" name="closeit">关闭</button>
   </div>
</div>
<script>
$("#types").change(function(){
   var type=$("#types").val();
   var url="<?php echo site_url("peisong/sDataList?type=")?>";
   location.href=url+type;
});
//$("[name='returnbill']").click(function(){
//	$("#sbid").val($(this).attr('_var'));
//	$('#myModal').modal({ backdrop:false});
//	$('<div class="modal-backdrop fade in">rtrt</div>').appendTo(document.body);
//	return false;
// });
$("[name='moreinfo']").click(function(){
	$.get('<?php echo site_url("peisong/showInfo?id=")?>'+$(this).attr('_var'),function(data){
		 $(".info").html(data);
		 $('#myModal2').modal({ backdrop:false});
    });
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
	if($.trim($("#getname").val()) ==''){
		openalert("请输入配送人姓名");
    	return false;
	}
	$("#doreturns").submit();
});
/// $('#myModal').modal({ backdrop:false});
$('#datepic').datepicker().on('changeDate', function(ev) {
	$(this).datepicker('hide')
});
$("#status1").click(function(){
	location.href="<?php echo site_url('peisong/sDataList?type=2')?>"
});
$("#status2").click(function(){
	location.href="<?php echo site_url('peisong/sDataList?type=10')?>"
});
</script>



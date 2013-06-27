<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<div id="content">
<div class="container">
<div class="row">
<div class="span3">
    <?php $this->load->view('common/leftmenu'); ?>


</div> <!-- /span3 -->


<div class="span9">
    <h1 class="page-title">
        <i class="icon-th-list"></i>
        <a href="<?php echo site_url('users/sDataList') ?>" class="path-menu-a">销售单管理</a> > 商品列表
    </h1>
    <div class="row">
        <div class="span9 doconfirm">
            <label class="pull-right">
                <input type="hidden" id="path" value="<?php echo site_url('') ?>">
                <a href="<?php echo site_url("saleorder/addOrder")?>" id="addToCart" class="btn btn-primary">
                    <i class="icon-shopping-cart"> 添加到购物车 
                      <font id="carcont">
                       <?php if($carcount>0):?>
                      (<?php echo $carcount;?>)
                       <?php endif;?>
                      </font>
                    </i>
                </a>
                <a href="" id="clearCart" class="btn btn-primary">
                    <i class="icon-trash">清空购物车</i>
                </a>
                <a href="<?php echo site_url("product/creatOrder")?>" class="btn btn-primary">
                    <i class="icon-plus">生成销售单</i>
                </a>
            </label>
        </div>
    </div>
    <div class="row">
        <form  method="POST" class="form-inline" action="<?php echo site_url('product/proList')?>">
            <div class="span4">
                <label>
                    
                </label>
            </div>
            <div class="span5">
                <input type="text" style="display:none">
                <label class="pull-right">条形码: 
                <input id="searchTxt" type="text" value="<?php echo isset($_REQUEST['barcode'])?$_REQUEST['barcode']:'';?>"  name="barcode" placeholder="请输入条形码">
                <input type="submit" value="查询" class="btn btn-primary" /> 
                <input type="button" id="supsearch" value="高级查询" class="btn btn-primary" /> 
                </label>
            </div>
        </form>
    </div>
    <div class="widget widget-table">
        <div class="widget-header">
            <i class="icon-th-list"></i>
            <h3> 商品列表</h3>
        </div> <!-- /widget-header -->
        <div class="widget-content">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                <th><input type="checkbox" id="select-all""></th>
                   <th>名称</th>
                   <th>条形码</th>
                   <th>描述</th>
                   <th>厂家</th>
                   <th>品牌</th>
                   <th>类别</th>
                   <th>颜色</th>
                    <th>库房</th>
                   <th>状态</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($list as $row):?>
                    <tr id="s<?php echo $row->id ?>">
                        <td><input type="checkbox" name="products" value="<?php echo $row->id ?>"/></td>
                        <td><?php echo $row->title ?></td>
                        <td><?php echo $row->barcode ?></td>
                        <td><?php echo $row->memo ?></td>
                        <td><?php echo $row->factoryname ?></td>
                        <td><?php echo $row->brandname ?></td>
                        <td><?php echo $row->typename ?></td>
                        <td><?php echo $row->color ?></td>
                        <td><?php product::getStorehouse( $row->storehouseid)?></td>
                        <td><?php echo $row->statusvalue ?></td>
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
       <button type="button" class="close"  name="closeit" data-dismiss="modal" aria-hidden="true">×</button>
       <h3 id="myModalLabel">高级检索</h3>
   </div>
   <div class="modal-body">
       <form class="form-horizontal" method="POST" id="searchit"  action="<?php echo site_url('product/proList')?>">
                <div class="control-group">
                   <label class="control-label" for="inputEmail">条形码</label>
                     <div class="controls">
                        <input type="text" name="barcode" value="<?php echo isset($_REQUEST['barcode'])?$_REQUEST['barcode']:'';?>" placeholder="请输入条形码">
                     </div>
                </div>
                <div class="control-group">
                   <label class="control-label" for="inputEmail">产品名称</label>
                     <div class="controls">
                        <input type="text" id="" name="title" value="<?php echo isset($_REQUEST['title'])?$_REQUEST['title']:'';?>" placeholder="请输入条产品名称">
                     </div>
                </div>
               <div class="control-group">
                   <label class="control-label" for="inputEmail">厂家</label>
                     <div class="controls">
                        <select name="factorycode">
                         <option value="">请选择</option>
                           <?php foreach($factory as $val):?>
                             <option value="<?php echo $val->factorycode;?>"
                             <?php if(isset($_REQUEST['factorycode'])&&$_REQUEST['factorycode']==$val->factorycode){
                                      echo "selected";                         	
                             }?>
                             ><?php echo $val->factoryname;?></option>
                           <?php endforeach;?>
                        </select>
                     </div>
                </div>
                <div class="control-group">
                   <label class="control-label" for="inputEmail">颜色</label>
                     <div class="controls">
                         <select name="colorcode">
                           <option value="">请选择</option>
                           <?php foreach($color as $val):?>
                           <option value="<?php echo $val->colorcode;?>" 
                             <?php if(isset($_REQUEST['colorcode'])&&$_REQUEST['colorcode']==$val->colorcode){
                                      echo "selected";                         	
                             }?>><?php echo $val->colorname;?></option>
                           <?php endforeach;?>
                        </select>
                     </div>
                </div>
       </form>
     
     
   </div>
   <div class="modal-footer">
        <button class="btn btn-primary" name="closeit" data-dismiss="modal" aria-hidden="true">关闭</button>
        <button class="btn btn-primary" name="closeit" id="saveit" data-dismiss="modal" aria-hidden="true">查询</button>
   </div>
</div>
<script>
//全选删除
$("#select-all").click(function(){
    if($(this).attr("checked")){
   	 $("input[name='products']").attr("checked",true)
    }else{
   	 $("input[name='products']").attr("checked",false)
    }
})
var goods='';
$(document).ready(function(){
	//高级查询
	$("#supsearch").click(function(){
		$('#myModal').modal({ backdrop:false});
    	$('<div class="modal-backdrop fade in">rtrt</div>').appendTo(document.body);
    	return false;
    });
    $('[name="closeit"]').click(function(){
        $("body div:last").fadeOut(400,function(){
             $(this).remove();
        });
        if($(this).attr("id")=='saveit'){
           $("#searchit").submit();
        }
    });
	//添加到购物车
   $("#addToCart").click(function(){
      if($('input[name="products"]').filter(":checked").length==0){
    	  openalert("请先选择 产品");
      	  return false;
      }
      var carcount=$('#carcont').text();
      carcount=carcount.replace("(",'');
      carcount=carcount.replace(")",'');
      carcount=carcount*1;
   	  $('input[name="products"]').filter(":checked").each(function(i,item){
   		carcount=carcount+1;
   		goods+=item.value+'||';
      });
   	  $.get("<?php echo site_url('product/addProToCart');?>",{id:goods},function(data){
          if(data==1){
        	  alert("加入购物车成功");
        	  $('input[name="products"]').filter(":checked").each(function(i,item){
        	   		$("#s"+item.value).remove();
        	   		$('#carcont').text('('+carcount+')');
        	   		if($('input[name="products"]').filter(":checked").length==0){
        	      		 $("#select-all").attr("checked",false);
        	        }
        	  });
        	  window.location.reload();
          }
	  });
   	  goods='';
      return false;
   });
   //清空购物车
   $("#clearCart").click(function(){
	   $.get("<?php echo site_url('product/delProToCartAll');?>",function(data){
	          if(data==1){
	        	  alert("清空购物车成功");
	        	  $('#carcont').text('');
	        	  window.location.reload();
	          }
	  });
	 // 
      return false;
   });
});
</script>



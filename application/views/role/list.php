<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<div id="content">
<div class="container">
<div class="row">
<div class="span3">
    <?php $this->load->view('common/leftmenu'); ?>

<script>
$(document).ready(function(){
	$("#select-all").click(function(){
	    if ($(this).attr("checked") == 'checked') {
	        $("input[name='checkbox']").attr("checked",$(this).attr("checked"));
	    }
	    else {
	        $("input[name='checkbox']").attr("checked",false);
	    }
	});

});
    function remove() {
        var str="";
        $("input[name='checkbox']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
        	openalert("请选择 删除项");
        	return false;
        }
        var msg='确定删除选中数据吗?';
        bootbox.confirm(msg, function(result) {
            if(result){
            	str = str.substring(0,str.length-1);
                $.ajax({
                    type:"post",
                    data: "id=" + str,
                    url:"<?php echo site_url('role/doSdataDel')?>",
                    success: function(data){
                        if (data=='2') {
                        	$("#select-all").attr("checked",false);
                        	$("input[name='checkbox']").attr("checked",false);
                            window.location.reload();
                        }
                        else if(data=='1'){
                        	openalert("数据错误，请重新尝试 ");
                          	return false;
                        }else{
                        	openalert("删除商品出错，请重新尝试或与管理员联系。");
                        	return false;
                        }
                    },
                    error: function() {
                    	openalert("执行操作出错，请重新尝试或与管理员联系。");
                    	return false;
                    }
                });
            }
         }); 

       
    }

</script>


</div> <!-- /span3 -->


<div class="span9">
    <h1 class="page-title">
        <i class="icon-th-list"></i>
        <a href="<?php echo site_url('users/sDataList') ?>" class="path-menu-a"> 系统设置</a> >  角色管理
    </h1>
    <div class="row">
        <div class="span9 doconfirm">
            <label class="pull-right">
                <input type="hidden" id="path" value="<?php echo site_url('') ?>">
                <a href="<?php echo site_url("role/sDataAdd")?>" id="add" class="btn btn-small">
                    <i class="icon-plus"> 添加</i>
                </a>
                <a href="javascript:;" class="btn btn-small" onclick="remove()">
                    <i class="icon-minus"> 删除</i>
                </a>


            </label>
        </div>
    </div>
    <div class="row">
        <form id="stock_list_btn_form" name="stock_list_btn_form" method="POST" class="form-inline">
            <div class="span5">
                <label>
              
                    
                </label>
            </div>
            <div class="span4">
<!--                <input type="text" style="display:none">-->
<!--                <label class="pull-right">查询: <input id="searchTxt" type="text" value=""></label>-->
            </div>
        </form>
    </div>
    <div class="widget widget-table">
        <div class="widget-header">
            <i class="icon-th-list"></i>
            <h3> 角色列表</h3>
        </div> <!-- /widget-header -->
        <div class="widget-content">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th><input type="checkbox" id="select-all""></th>

                    <th> 角色名称</th>
                    <th> 角色备注</th>
                    <th> 操作权限</th>
                    <th> 是否财务</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                    <?php 
                    $power=array("0"=>"全站只读",'1'=>'本账户权限','2'=>'全部权限');
                    foreach($list as $row):?>
                    <tr>
                        <td><input type="checkbox" name="checkbox" value="<?php echo $row->id ?>"/></td>
                        <td><?php echo $row->rolecode ?></td>
                        <td><?php echo $row->rolememo ?></td>
                        <td><?php echo $power[$row->power]; ?></td>
                        <td><?php if(!$row->accounting) echo "<i class=' icon-remove'></i>"; else echo "<i class='icon-ok'></i>"; ?></td>
                        <td>
                            <a href="<?php echo site_url("role/sDataEdit?id=".$row->id)?>" class="btn btn-primary">修改数据</a>
                            <a href="<?php echo site_url("role/doRolePower?roleid=".$row->id)?>" class="btn btn-primary">设置权限</a>
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
<script>
var list='<li class="nav-header">List header</li>';
var slist='';
$(document).ready(function(){
	 $("#fuquan").click(function(){
	     list='';
	     $("#domenu").html('');
         $.getJSON("<?php echo site_url('role/getFunList')?>",function(result){
        	 $.each(result, function(i, field){
        		 list +='  <li ><a href="#" _id="'+field.id+'"><i class=" icon-list" ></i>'+field.name+'</a></li>';
        	 });
        	 $("#domenu").html(list);
         })
		 $('#myModal').modal();
	 });
	 //子菜单操作
	  $("#domenu  li  a").click(function(){

	  });
	 

	 
});

function doson(ob){
     //初始化数据 
     slist='';
	 //更换图标
	 
	 //加载子菜单
	 $.getJSON("<?php echo site_url('role/getFunList')?>",{pid:ob},function(result){
    	 $.each(result, function(i, field){
    		 slist +='<tr><td></td> <td><input type="checkbox" value="'+field.id+'"/></td><td>'+field.name+'</td></tr>';
    	 });
    	 $("#smend").html(slist);
     })
}
</script>

   
    <!-- Modal -->
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">权限设置</h3>
    </div>
    <div class="modal-body">
    <p>
       <div style="border:1px #ccc solid; width:525px;">
        <div style="padding:0px; width:135px;float:left" >
           <ul class="nav nav-list nav-tabs nav-stacked" style="padding-right:0px"  id="domenu">
           
               
           </ul>
        
        </div>
        <div style=" width:385px;float:right; border-left:1px #ccc solid;" >
        
            <table class="table table-hover" style="border-bottom:1px solid #CCC; margin-bottom:0px">
              <thead>
                <tr>
				  <th>&nbsp;</th>
				  <th><input type="checkbox" /></th>
            
                  <th>权限名称</th>
                </tr>
              </thead>
              <tbody id="smend">
                <tr><td>1</td><td>Mark</td></tr>
               
              </tbody>
            </table>
        
        
        </div>
       </div>
    </p>
    </div>
    <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    <button class="btn btn-primary">Save changes</button>
    </div>
    </div>


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
        <a href="<?php echo site_url('users/sDataList') ?>" class="path-menu-a"> 系统设置</a> >  授权管理
    </h1>
    <div class="row">
        <div class="span9 doconfirm">
            <label class="pull-right">
                <input type="hidden" id="path" value="<?php echo site_url('') ?>">
            </label>
        </div>
    </div>
    <div class="row">
        <form id="stock_list_btn_form" name="stock_list_btn_form" method="POST" class="form-inline">
            <div class="span5">
                <label>
                    切换授权组  <select class="span2" id="dogroups" >
<!--                                                 <option  value="0">全部</option>-->
                                                    <?php foreach($list as $val):?>
                                                      <option  value="<?php echo $val->id?>"
                                                      <?php if($val->id==$pid) echo "selected"?>
                                                      ><?php echo $val->funcode;?> </option>
                                                    <?php endforeach;?>
                                                </select>
                                               <button class="btn btn-primary" onclick="setpower()" type="button">保存权限</button>
                    
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
            <h3> 授权列表</h3>
        </div> <!-- /widget-header -->
        <div class="widget-content">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>功能名称</th>
                    <th>操作权限 <input type="checkbox" id="select-all""></th>
                    <th>模块内权限<input type="checkbox" id="select-all2""></th>
                </tr>
                </thead>
                <tbody>
                    <?php if(isset($slist)): foreach($slist as $row):?>
                    <tr>
                         <td><?php echo $row->funcode ?></td>
                        <td><input type="checkbox" name="checkbox" value="<?php echo $row->id ?>"
                        <?php if(in_array($row->id, $power)) echo "checked";?>
                        /></td>
                         <td><input type="checkbox" name="checkbox2" value="<?php echo $row->id ?>"
                        <?php if(in_array($row->id, $powerm)) echo "checked";?>
                        /></td>
                         </tr>
                    <?php endforeach; endif;?>
                </tbody>
            </table>
        </div> <!-- /widget-content -->
        <div class="row">
            <div class="span4" style="margin-top:20px ">
<?php echo isset($info)?$info:'';?>
            </div>
            <div class=" pagination pagination-right">
            <?php
               echo isset($page)?$page:'';
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
$(document).ready(function(){
	$("#select-all").click(function(){
	    if ($(this).attr("checked") == 'checked') {
	        $("input[name='checkbox']").attr("checked",$(this).attr("checked"));
	    }
	    else {
	        $("input[name='checkbox']").attr("checked",false);
	    }
	});
	$("#select-all2").click(function(){
	    if ($(this).attr("checked") == 'checked') {
	        $("input[name='checkbox2']").attr("checked",$(this).attr("checked"));
	    }
	    else {
	        $("input[name='checkbox2']").attr("checked",false);
	    }
	});
	var pid='';
	$("#dogroups").change(function(){
	   pid=$("#dogroups").val();
       var url='<?php echo site_url('role/doRolePower?roleid='.$roleid)?>';
       url =url + "&pid="+pid;
       location.href=url;
    });
});
function setpower() {
    var str="",str2='';
    $("input[name='checkbox']").each(function(){
        if($(this).attr("checked") == 'checked'){
            str+=$(this).val()+",";
        }
    });
    $("input[name='checkbox2']").each(function(){
        if($(this).attr("checked") == 'checked'){
            str2+=$(this).val()+",";
        }
    });
    
//    if (str == "") {
//    	openalert("请选择 权限操作项");
//    	return false;
//    }
    var msg='确定当前操作吗?';
    bootbox.confirm(msg, function(result) {
        if(result){
        	str = str.substring(0,str.length-1);
        	str2 = str2.substring(0,str2.length-1);
            $.ajax({
                type:"post",
                data: "id=" + str+"&id2="+str2+"&pid="+$("#dogroups").val(),
                url:"<?php echo site_url('role/setRolePower?roleid='.$roleid)?>",
                success: function(data){
                    if (data=='2') {
                    	openalert("数据更新成功");
                      	return false;
                    }
                    else if(data=='1'){
                    	openalert("数据错误，请重新尝试 ");
                      	return false;
                    }else{
                    	openalert("赋权限出错，请重新尝试或与管理员联系。");
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


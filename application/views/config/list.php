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
                    url:"<?php echo site_url('config/doSdataDel')?>",
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
        <a href="<?php echo site_url('users/sDataList') ?>" class="path-menu-a"> 系统设置</a> >  配置管理
    </h1>
    <div class="row">
        <div class="span9 doconfirm">
            <label class="pull-right">
                <input type="hidden" id="path" value="<?php echo site_url('') ?>">
                <a href="<?php echo site_url("config/sDataAdd")?>" id="add" class="btn btn-small">
                    <i class="icon-plus"> 添加</i>
                </a>
<!--                <a href="javascript:;" class="btn btn-small" onclick="remove()">-->
<!--                    <i class="icon-minus"> 删除</i>-->
<!--                </a>-->

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
            <h3> 配置列表</h3>
        </div> <!-- /widget-header -->
        <div class="widget-content">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
<!--                    <th><input type="checkbox" id="select-all""></th>-->

                    <th> 配置项目</th>
                    <th> 配置数据</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($list as $row):?>
                    <tr>
<!--                        <td><input type="checkbox" name="checkbox" value="<?php echo $row->id ?>"/></td>-->
                        <td><?php echo $row->memo ?></td>
                        <td><?php echo $row->value ?></td>
                        <td><a href="<?php echo site_url("config/sDataEdit?id=".$row->id)?>" class="btn btn-primary">修改数据</a> </td>
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





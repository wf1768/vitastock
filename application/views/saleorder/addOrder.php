<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>
<style>
<!--
.dotables table td{
  height:30px;
  vertical-align:middle;
}
 .dotables table td input , .dotables table td select {
    margin-bottom: 0px;
  }
-->
</style>
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
                    <a href="<?php echo site_url('buy/buy_list') ?>" class="path-menu-a"> 销售单管理</a> > 添加销售单
                </h1>
                <div class="row">
                    <div class="span9">
                        <div class="widget">
                            <div class="widget-header">
                                <h3>添加销售单</h3>
                            </div>
                            <!-- /widget-header -->
                            <div class="widget-content">
                                <div class="tabbable">
                                    <br>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="1">
                                         <form id="sell_form" class="dotables" onsubmit="return submitform();" action="<?php echo site_url('saleorder/doAddOrder')?>" method="post">
                                                 <input type="hidden" name="createby"
                                                        value="<?php echo $this->account_info_lib->accountname ?>"
                                                        readonly>
                                            <table class="table table-bordered" width="100%">
                                                <tr>
                                                    <td>销售单编号</td>
                                                    <td >
                                                          <input type="text"  id="remark" name="sellnumber"  value="" placeholder="自动生成" readonly>
                                                    </td>
                                                    <td>销售者</td>
                                                    <td><input type="text" value="<?php echo $this->account_info_lib->accountname ?>" name="checkby"  required></td>
                                                </tr>
                                                <tr>
                                                    <td>销售日期</td>
                                                    <td>
                                                        <div class="input-append date" id="datepic" data-date="<?php echo date("Y-m-d")?>" data-date-format="yyyy-mm-dd">
                                                            <input name="selldate" class="span2" style="width:176px" size="16" type="text" value="<?php echo date("Y-m-d")?>" readonly>
                                                            <span class="add-on"><i title="点击选择日期" class="icon-calendar"></i></span>
                                                        </div>
                                                    </td>
                                                    <td>销售店</td>
                                                    <td>
                                                    <select name="storehouseid">
                                                    <?php foreach($storehose as $key=>$val):?>
                                                      <option value="<?php echo $val->id?>"><?php echo $val->storehousecode?></option>
                                                    
                                                    <?php endforeach;?>
                                                    </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>总价</td>
                                                    <td><input type="text" value="0" id="totalmoney" name="totalmoney"  required  onkeypress="return isfloat(event)"></td>
                                                    <td>折扣总价(RMB)</td>
                                                    <td><input type="text" value="0" onblur="get_lastmoney()" onkeypress="return isfloat(event)" id="discount" name="discount" required></td>
                                                </tr>
                                                <tr>
                                                    <td>已付金额(RMB)</td>
                                                    <td><input type="text" value="0" onblur="get_lastmoney()" onkeypress="return isfloat(event)" id="paymoney" name="paymoney"  required></td>
                                                    <td>未付金额(RMB)</td>
                                                    <td><input type="text" value="0" id="lastmoney" name="lastmoney" onkeypress="return isfloat(event)" required></td>
                                                </tr>
                                                <tr>
                                                    <td>备注</td>
                                                    <td colspan="3"><input type="text" placeholder="请输入备注，也可不填"  class="span5" value="" name="remark" ></td>
                                                </tr>
                                                 <tr>
                                                    <td>客户名称</td>
                                                    <td><input type="text" value="" required  placeholder="请输入客户名称" name="clientname" ></td>
                                                    <td>客户电话</td>
                                                    <td><input type="text" value="" required  placeholder="请输入客户电话" name="clientphone" ></td>
                                                </tr>
                                                <tr>
                                                    <td>客户地址</td>
                                                    <td colspan="3"><input type="text" required value="" class="span5"  placeholder="请输入客户地址"  name="clientadd"></td>
                                                </tr>
                                            </table>
                                            <div class="row">
                                                <div class="span8">
                                                    <label class="pull-left">
                                                        <ul class="nav nav-pills">
                                                            <li class="active"><a href="" id="addbuy-product">添加销售商品</a></li>
                                                            <li class="active"><a href="javascript:;" id="delete_product">删除销售商品</a></li>
                                                           <li class="active">
                                                           <input style="margin-top:3px; margin-right:5px; margin-left:30px" type="text" name="barcode"  id="tiaomaadd" value="<?php echo isset($_REQUEST['barcode'])?$_REQUEST['barcode']:'';?>" placeholder="请输入条形码">
                                                            </li><li class="active"><a href=""  id="tiaoadditem">添加商品</a></li>
                                                        </ul>
                                                    </label>
                                                    <label class="pull-right">
                                                        <div class="btn-group">
                                                            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="c-666">修改配送方式：</span>
                                                                <strong>选择</strong>
                                                                <input type="hidden" name="category" value="娱乐">
                                                                <b class="caret"></b></a>
                                                            <ul class="dropdown-menu">
                                                                <li><a href="javascript:;" onclick="edit_sendtype('0')"> 配送</a></li>
                                                                <li><a href="javascript:;" onclick="edit_sendtype('1')"> 自提</a></li>
                                                            </ul>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="select-all""></th>
                                                    <th>序号</th>
                                                    <th>名称</th>
                                                    <th>代码</th>
                                                    <th>描述</th>
                                                    <th>厂家</th>
                                                    <th>条形码</th>
                                                    <th>销售价</th>
                                                    <th>所在店</th>
                                                    <th>配送</th>
                                                </tr>
                                                </thead>
                                                <tbody id="addcontent">
                                                </tbody>
                                            </table>
                                            <div class="row">
                                                <div class="span8">
                                                    <label class="pull-right">
                                                        <ul class="nav nav-pills">
                                                            <li class="active">
                                                            <input class="btn btn-primary"  type="submit" value="提交销售单">
                                                            </li>
                                                        </ul>
                                                    </label>

                                                </div>
                                            </div>
                                            
                                             </form>
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
</div> <!-- /content -->

<?php $this->load->view("common/footer"); ?>


<!--------------------------------- -->
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
         <button type="button" class="close" name="closeit" data-dismiss="modal" aria-hidden="true">×</button>
         
         <h3 id="myModalLabel"> 商品添加</h3>
    </div>
    <div class="modal-body">
       <!-- ============================================================== -->
           <div class="minbox">
          <div class="part_search">
           <div class="navbar">
              <div class="navbar-inner">
               <form class="navbar-form" method="post" id="searchfrom" style="margin-bottom:5px">
                    <font class="myfont" >商品形码：</font>
                       <input type="text" name="barcode"  id="tiaoma" value="<?php echo isset($_REQUEST['barcode'])?$_REQUEST['barcode']:'';?>" placeholder="请输入条形码">
               <span id="supserarch" style="display:none">
                       &nbsp;&nbsp;<font class="myfont" >商品名称：</font>
                           <input type="text" id="" name="title" value="<?php echo isset($_REQUEST['title'])?$_REQUEST['title']:'';?>" placeholder="请输入条商品名称"><br/>
                       <font class="myfont" >商品厂家：</font>
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
                         &nbsp;&nbsp;<font class="myfont" >商品颜色：</font>
                        <input type="text" id="" name="color" value="<?php echo isset($_REQUEST['color'])?$_REQUEST['color']:'';?>" placeholder="请输入商品颜色">
                        <br/>
                        <font class="myfont" >所在库房：</font>
                        <select name="storehouseid">
                            <option value="">请选择</option>
                            <?php foreach($storehose as $val):?>
                                <option value="<?php echo $val->id;?>"
                                    <?php if(isset($_REQUEST['storehouseid'])&&$_REQUEST['storehouseid']==$val->id){
                                    echo "selected";
                                }?>><?php echo $val->storehousecode;?></option>
                            <?php endforeach;?>
                        </select>
                        &nbsp;&nbsp;<font class="myfont" >商品代码：</font>
                        <input type="text" id="" name="code" value="<?php echo isset($_REQUEST['code'])?$_REQUEST['code']:'';?>" placeholder="请输入条商品代码"><br/>
                </span>
                    <button style="margin-left:70px" id="supsearchit"type="button" class="btn">高级搜索</button>
                    <button style="margin-left:20px" id="search"  type="button" class="btn btn-primary">&nbsp;&nbsp;搜&nbsp;&nbsp;索&nbsp;&nbsp;</button> 
               </form>
              </div>
            </div>
          </div>
            <div id="alert"></div>
        	<table class="table table-bordered table-striped    table-hover">
            	<thead>
                	<tr class="info">
                	    <th  class="table-textcenter"><input type="checkbox" id="select-alls""></th>
                        <th  class="table-textcenter">图片</th>
                    	<th  class="table-textcenter">名称</th>
                        <th  class="table-textcenter">代码</th>
                        <th  class="table-textcenter">条码</th>
                        <th  class="table-textcenter">厂家</th>
                        <th  class="table-textcenter">描述</th>
                        <th  class="table-textcenter">颜色</th>
                        <th  class="table-textcenter">售价</th>
                        <th  class="table-textcenter">店</th>
                        <th  class="table-textcenter">状态</th>
                  </tr>
                </thead>
                <tbody id="tbody">    
                </tbody>
            </table> 
          </div>
               
        <!-- ============================================================== -->      
           
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" id="additem">添加商品</button>
        <button class="btn btn-primary" name="closeit" id="closebox" data-dismiss="modal" aria-hidden="true">关闭</button>
    </div>
</div>
    
<!-- ----------------------------------- -->

<script> 

 var  josnData;
 var idAray=new Array();
 $(document).ready(function(){
     $('#totalmoney').val('0');
     $('#paymoney').val('0');
     $('#lastmoney').val('0');
     $('#discount').val('0');
     //================================================
      $("#tiaoma").keydown(function(e){
          if(e.keyCode==13){
        	  $("#search").trigger('click');
        	  return false;
          } 
      });
      $("#tiaomaadd").keydown(function(e){
          if(e.keyCode==13){
        	  autoAddToBill();
        	  return false;
          } 
      });
      $("#tiaoadditem").click(autoAddToBill);
	 ///===============================================
     $("#supsearchit").click(function(){
         $("#supserarch").toggle() 
     });
	 
	/// $('#myModal').modal({ backdrop:false});
	 $('#datepic').datepicker().on('changeDate', function(ev) {
		$(this).datepicker('hide')
     });
     $("#addbuy-product").click(function(){
    	$('#myModal').modal({ backdrop:false});
    	$('<div class="modal-backdrop fade in">rtrt</div>').appendTo(document.body);
    	return false;
     });
     $('[name="closeit"]').click(function(){
    	 $("body div:last").fadeOut(400,function(){
             $(this).remove();
             //清除商品搜到的商品列表  
            // $("#tbody").html('');
         });
     });
     //执行查询
     $("#search").click(function(){
         var data=$("#searchfrom").serialize();
         var list=new Array();
         var isHave=false;
         $.getJSON("<?php echo site_url('saleorder/dosearch')?>",data,function(data){
        	 josnData=data;
          	 $.each(data, function(i,item){
                 list.push('<tr id="'+item.id+'">');
                 list.push('<td class="table-textcenter"><input type="checkbox" name="chkitem" value="'+item.id+'"/></td>');
                 list.push('<td class="table-textcenter"><img src="<?php echo base_url() ?>'+item.picpath+'" alt="" class="thumbnail smallImg"/></td>');
                 list.push('<td class="table-textcenter">'+item.title+'</td>');
                 if (item.code.length > 10) {
                     list.push('<td class="table-textcenter" title="'+item.code+'">'+(item.code.substr(0,10)+'...')+'</td>');
                 }
                 else {
                     list.push('<td class="table-textcenter" title="'+item.code+'">'+item.code+'</td>');
                 }
                 if (item.barcode.length > 20) {
                     list.push('<td class="table-textcenter" title="'+item.barcode+'">'+(item.barcode.substr(0,10)+'...')+'</td>');
                 }
                 else {
                     list.push('<td class="table-textcenter" title="'+item.barcode+'">'+item.barcode+'</td>');
                 }
                 list.push('<td class="table-textcenter">'+item.factoryname+'</td>');
                 if (item.memo.length > 20) {
                     list.push('<td class="table-textcenter" title="'+item.memo+'">'+(item.memo.substr(0,30)+'...')+'</td>');
                 }
                 else {
                     list.push('<td class="table-textcenter" title="'+item.memo+'">'+item.memo+'</td>');
                 }

                 list.push('<td class="table-textcenter">'+item.color+'</td>');
                 list.push('<td class="table-textcenter">'+item.salesprice+'</td>');
                 list.push('<td class="table-textcenter">'+item.storehouse+'</td>');
                 list.push('<td class="table-textcenter">'+item.statusvalue+'</td>');
                 list.push('</tr>');
             });
         	 var string=list.join('');
         	// $("#tbody").html('');
         	 $("#tbody").html('');
         	 $("#select-alls").attr("checked",false);
         	 if(string){
         		$("#alert").html('');
         	    $(string).appendTo("#tbody");
         	 }else{
         		 var al ='<div class="alert" id="wrongs">';
         		     al+='<button type="button" class="close" data-dismiss="alert">&times;</button>';
         		     al+='<strong>数据提示!</strong> 没有符合条件的数据';
         		     al+='</div>';
                 $("#alert").html('').html(al);
             }

         	  
             
         });
      });
     
     //添加信息到销售单 additem
     $("#additem").click(function(){
         $('input[name="chkitem"]').filter(":checked").each(function(i,item){
             //添加到订单列表中
        	 addProToBill(item.value);
        	 //移除商品列表
             $("#"+item.value).remove();

             if($('input[name="chkitem"]').filter(":checked").length==0){
            	 $("#select-alls").attr("checked",false);
             }
         })
     });
     //全选删除
     $("#select-all").click(function(){
         if($(this).attr("checked")){
        	 $("input[name='chkitems']").attr("checked",true)
         }else{
        	 $("input[name='chkitems']").attr("checked",false)
          }
     });
     $("#select-alls").click(function(){
         if($(this).attr("checked")){
        	 $("input[name='chkitem']").attr("checked",true)
         }else{
        	 $("input[name='chkitem']").attr("checked",false)
          }
     });
     
     //删除商品  
     $("#delete_product").click(function(){
    	 if( $('input[name="chkitems"]').filter(":checked").length==0){
    		openalert("当前没有商品被选中");
         	return false;
         }
    	 var msg='确定删除选中数据吗?';
         bootbox.confirm(msg, function(result) {
        	 if(result){
        		 $('input[name="chkitems"]').filter(":checked").each(function(i,item){
                     //总价减去删除商品d价格
                     var price = parseFloat($("input[name='price["+item.value+"]']").val());
                     $('#totalmoney').val(parseFloat($('#totalmoney').val()) - price);
            		 //移除商品列表
                	 $("#s"+item.value).remove();
                	 _unset(item.value);
                 });
                 getNum();
                 if($('input[name="chkitems"]').length==0){
                     //取消全选
                	 $("#select-all").attr("checked",false);
                 }
             }
         });
     });
 });

 function submitform() {
     if (!confirm("确定要提交销售单吗？注意：因销售单总价，是通过销售商品的售价加减的，可能出现错误，请谨慎操作。")) {
         return false;
     }
 }
 //修改销售商品的配送方式
 function edit_sendtype(sendtype) {
     if (sendtype == '') {
         return;
     }

     if( $('input[name="chkitems"]').filter(":checked").length==0){
         openalert("请选择要修改配送方式的销售商品。");
         return false;
     }
     if (sendtype == '1') {
         var typecode = '自提';
         var msg='确定修改选中数据配送方式为［自提］吗?<br /> <span style="color: red">注意：销售商品为自提，表示商品客户自己取货，在配送环节，将不再配送。</span> ';
     }
     else {
         var typecode = '配送';
         var msg='确定修改选中数据配送方式为需要［配送］吗?';
     }
     bootbox.confirm(msg, function(result) {
         if(result){
             var str = "";
             $("input[name='chkitems']").each(function () {
                 if ($(this).attr("checked") == 'checked') {
                     str += $(this).val() + ",";
                 }
             })
             if (str == "") {
                 openalert('请选择要修改配送方式的销售商品。');
                 return;
             }
             str = str.substring(0, str.length - 1);

             $.ajax({
                 type: "post",
                 data: "ids=" + str + "&sendtype=" + sendtype,
                 url: "<?php echo site_url('stock/update_stock_sendtype')?>",
                 success: function (data) {
                     if (data) {
                         //如果保存通过，不刷新修改商品配送方式
                         $("input[name='chkitems']").each(function () {
                             if ($(this).attr("checked") == 'checked') {
                                $("td[name='sendtype_"+$(this).val()+"']").html(typecode);
                             }
                         })

                         $("#select-all").attr("checked",false);
                         $("input[name='chkitems']").each(function () {
                             $(this).attr("checked",false);
                         })
//                         $("td[name='sendtype']").html(typecode);
                     }
                     else {
                         openalert("修改商品配送方式出错，请重新尝试或与管理员联系。");
                     }
                 },
                 error: function () {
                     openalert("执行操作出错，请重新尝试或与管理员联系。");
                 }
             });
         }
     });

 }
//计算余额
 function get_lastmoney() {
     var paymoney = $('#paymoney').val();
     var lastmoney = $('#lastmoney').val();
     var discount = $('#discount').val();
     var totalmoney = $('#totalmoney').val();

     if (parseFloat(discount) > parseFloat(totalmoney)) {
         openalert('您输入的折扣总价，大于总价，这不符合逻辑');
         $('#discount').val('0');
         return;
     }

     if (paymoney == '') {
         $('#paymoney').val('0');
     }


     if (parseFloat(paymoney) > parseFloat(discount)) {
         openalert('您输入的已付金额，大于折扣总价，这不符合逻辑');
         $('#paymoney').val('0');
         var paymoney = $('#paymoney').val();
         $('#lastmoney').val(parseFloat(discount) - parseFloat(paymoney));
     }
     else {
         $('#lastmoney').val(parseFloat(discount) - parseFloat(paymoney));
     }

 }
 //计算添加商品的序号
 function getNum() {
     alert('dd');
     var num = 0;
     $('#addcontent tr').each(function () {
         $(this).children('td').eq(1).html(num+1);
         num++;
     });
 }


 function autoAddToBill(){
     var barcode=$("#tiaomaadd").val();
     var listCon=new Array();
     $.getJSON("<?php echo site_url('saleorder/dosearch')?>",{barcode:barcode},function(data){
    	 $.each(data, function(i,item){
    	  		if(true){
    	  			isHave=_search(item.id);
    	            if(isHave==false){
                        var sendtype = '';
                        if (item.sendtype == 1) {
                            sendtype = '自提';
                        }
                        else {
                            sendtype = '配送';
                        }
    	            	listCon.push('<tr id="s'+item.id+'">');
    	     	        listCon.push('<td class="table-textcenter">');
    	     	        listCon.push('<input type="checkbox" name="chkitems" value="'+item.id+'"/>');
    	     	        listCon.push('<input type="hidden" name="product[]"  value="'+item.id+'"/></td>');
                        listCon.push('<td class="table-textcenter">33</td>');
    	     	        listCon.push('<td class="table-textcenter">'+item.title+'</td>');
    	     	        listCon.push('<td class="table-textcenter">'+item.code+'</td>');
    	     	        listCon.push('<td class="table-textcenter">'+item.memo+'</td>');
    	     	        listCon.push('<td class="table-textcenter">'+item.factoryname+'</td>');
    	     	        listCon.push('<td class="table-textcenter">'+item.barcode+'</td>');
    	     	        listCon.push('<td class="table-textcenter">');
    	     	        listCon.push('<input readonly type="text" placeholder="留空则为原价格"  class="myaddclass span1" name="price['+item.id+']" value="'+item.salesprice+'" />');
    	     	        listCon.push('<input required type="hidden" class="myaddclass span1" name="price2['+item.id+']" value="'+item.salesprice+'" />');
    	     	        listCon.push( '</td>');
                        listCon.push('<td class="table-textcenter">'+item.storehouse+'</td>');
                        listCon.push('<td name="sendtype_'+item.id+'" class="table-textcenter">'+sendtype+'</td>');
    	     	        listCon.push('</tr>');
                        //添加到id 数组 记录
                        idAray.push(item.id);
                        $('#totalmoney').val(parseFloat($('#totalmoney').val()) + parseFloat(item.salesprice));
    	           }
    	  	  	}
    	      });
    	  	 var stringCon=listCon.join('');
    	  	// $("#addcontent").html('');
    	  	 $(stringCon).appendTo("#addcontent");
         $("#tiaomaadd").val('');
         getNum();
     });
  }
 //添加商品函数
 function addProToBill(id){
	 var listCon=new Array();
	 $.each(josnData, function(i,item){
  		if(item.id==id){
  			isHave=_search(item.id);
            if(isHave==false){
                var sendtype = '';
                if (item.sendtype == 1) {
                    sendtype = '自提';
                }
                else {
                    sendtype = '配送';
                }
            	listCon.push('<tr id="s'+item.id+'">');
     	        listCon.push('<td class="table-textcenter">');
     	        listCon.push('<input type="checkbox" name="chkitems" value="'+item.id+'"/>');
     	        listCon.push('<input type="hidden" name="product[]"  value="'+item.id+'"/></td>');
                listCon.push('<td class="table-textcenter">22</td>');
     	        listCon.push('<td class="table-textcenter">'+item.title+'</td>');
     	        listCon.push('<td class="table-textcenter">'+item.code+'</td>');
     	        listCon.push('<td class="table-textcenter">'+item.memo+'</td>');
     	        listCon.push('<td class="table-textcenter">'+item.factoryname+'</td>');
//     	        listCon.push('<td class="table-textcenter">'+item.brandname+'</td>');
//     	        listCon.push('<td class="table-textcenter">'+item.typename+'</td>');
//     	        listCon.push('<td class="table-textcenter">'+item.color+'</td>');
     	        listCon.push('<td class="table-textcenter">'+item.barcode+'</td>');
     	        listCon.push('<td class="table-textcenter">');
     	        listCon.push('<input readonly type="text" placeholder="留空则为原价格"  class="myaddclass span1" name="price['+item.id+']" value="'+item.salesprice+'" />');
    	        listCon.push('<input required type="hidden" class="myaddclass span1" name="price2['+item.id+']" value="'+item.salesprice+'" />');
    	        listCon.push( '</td>');
                listCon.push('<td class="table-textcenter">'+item.storehouse+'</td>');
                listCon.push('<td name="sendtype_'+item.id+'" class="table-textcenter">'+sendtype+'</td>');
    	        listCon.push('</tr>');
  	            //添加到id 数组 记录
                idAray.push(item.id);
                $('#totalmoney').val(parseFloat($('#totalmoney').val()) + parseFloat(item.salesprice));
           }
  	  	}

      });
  	 var stringCon=listCon.join('');
  	// $("#addcontent").html('');
  	 $(stringCon).appendTo("#addcontent");
     getNum();
 }
 function _search(key){
    var m=idAray.length
    for(i=0;i<m;i++){
      if(idAray[i]==key){
         return true;
      }
    }
    return false;
 }
 function _unset(key){
	    var m=idAray.length
	    for(i=0;i<m;i++){
	      if(idAray[i]==key){
	    	  idAray[i]='';
	      }
	    }
 }
  
</script>
<style>
<!--
.modal {
    background-clip: padding-box;
    background-color: #FFFFFF;
    border: 1px solid rgba(0, 0, 0, 0.3);
    border-radius: 6px 6px 6px 6px;
    box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
    left: 50%;
    margin-left: -490px;
    outline: 0 none;
    position: fixed;
    top: 10%;
    width: 946px;
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
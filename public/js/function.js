$(document).ready(function(){
	//消息确认
	$(".doconfirm a").click(function(){
		var url=$(this).attr('url');
		if(typeof(url)!='undefined'){
		   var msg=$(this).attr('msg');
		   msg=msg?msg:'确认执行当前操作操作';
		   bootbox.confirm(msg, function(result) {
               if(result){
               	   location.href=url;
               }
            }); 
            return false;
		}else{
			return true;
		}
	});
	//日历选择
	$('.datapic').datepicker({'format':'yyyy-mm-dd'}).on('changeDate', function(ev){
           $(this).datepicker('hide');
           $('.btn').focus();
    });
   
});
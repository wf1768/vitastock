<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <script type="text/javascript" src="<?php echo base_url("public/js/jquery-1.7.2.min.js");?>"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="en" />
	<meta name="GENERATOR" content="PHPEclipse 1.2.0" />
	<title>跳转提示</title>
	<link  href="<?php echo base_url("public/css/bootstrap/css/bootstrap.css");?>" rel="stylesheet" type="text/css" />

</head>

<body>
<div style="height:600px">

            <div class="modal">
              <div class="modal-header">
               
                <h3>提示信息</h3>
              </div>
              <div class="modal-body">
                <p>
   <div class="alert alert-block alert-success fade in">
       <h4>成功提示信息：</h4>
       <p class="text-success"  style="text-align:center"> <?php echo($msg); ?></p>
        
   </div>


  </p>
              </div>
              <div class="modal-footer">
  
<button class="btn btn-large btn-block" type="button" id="click">
      
页面自动 <a id="href" href="<?php if(isset($url)){ echo $url;}else{echo "javascript:history.back()";} ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
  
</button>
              </div>
            </div>
</div>
          
</body>
</html>
<script type="text/javascript">
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
(function(){
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time == 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
$("#click").click(function(){
    location.href = href;
});
</script>

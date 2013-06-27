<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>
  <link href="<?php echo base_url("public/css/uploadify.css");?>" rel="stylesheet">
 <script src="<?php echo base_url("public/js/jquery.uploadify.js");?>"></script>
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
        <a href="<?php echo site_url('notice/sDataList') ?>" class="path-menu-a">消息管理</a> > <a href="<?php echo site_url('color/sDataList') ?>" class="path-menu-a"> 消息管理</a> >查看消息 
    </h1>

    <div class="row">
        <div class="span9">
            <div class="widget">
                <div class="widget-header">
                    <h3>消息</h3>
                </div>
                <!-- /widget-header -->
                <div class="widget-content">
                    <div class="tabbable">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="account.html#1" data-toggle="tab">消息信息</a>
                            </li>
                        </ul>
                        <br>
                        <div class="tab-content">
                            <div class="tab-pane active" id="1">
                            <input type="hidden" id="path" value="<?php echo site_url('') ?>">
                                <form  method="post" class="form-horizontal" id="mfrom" action="<?php echo site_url("notice/doSdataAdd")?>">
                                    <fieldset>


                                        <!-- /control-group -->
                                        <div class="control-group" style="border-bottom:1px #ccc solid">
                                            <label class="control-label" for="remark">消息名称</label>

                                            <div class="controls">
                                                <?php echo  $info->title?>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                         <!-- /control-group -->
                                        <div class="control-group" style="border-bottom:1px #ccc solid">
                                            <label class="control-label" for="remark">发布人</label>

                                            <div class="controls">
                                                <?php echo  $info->sendman?>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                         <!-- /control-group -->
                                        <div class="control-group" style="border-bottom:1px #ccc solid">
                                            <label class="control-label" for="remark">发布时间</label>

                                            <div class="controls">
                                                <?php echo  $info->messagetime;?>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        
                                        <!-- /control-group -->
                                        <div class="control-group" style="border-bottom:1px #ccc solid">
                                            <label class="control-label" for="remark">消息内容</label>
                                            <div class="controls">
                                               <?php echo  $info->content?>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <?php if(isset($fujian)):?>
                                        <!-- /control-group -->
                                        <div class="control-group" style="border-bottom:1px #ccc solid">
                                            <label class="control-label" for="remark">附件</label>
                                            <div class="controls">
                                               <?php foreach($fujian as $val):?>
                                                <div class="alert alert-success"  style="width:320px">
                                                    文件：<?php echo $val->filename;?> - <a href="<?php echo site_url("notice/doDownload")."?id=".$val->id ?>">下载</a>
                                                </div>
                                               <?php endforeach;?>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <?php endif;?>
                                        <!-- /control-group -->
                                        
                                        <br/>
                                        <br/>
                                        <!-- /form-actions -->
                                    </fieldset>
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
<script type="text/javascript">
var errors=false;
function dosubmit(){
	 if($("#titles").val()==''){
	 	 openalert("请填写消息标题");
	 	 $("#titles").focus();
	 	 return false;
	 }
	 if($("#contents").val()==''){
	 	 openalert("请填写消息内容");
	 	  $("#contents").focus();
	 	 return false;
	 }
	 $('#file_upload').uploadify('upload','*');
}
		$(function() {
			$("#dosubmit").click(dosubmit);
			$("#contents").val($.trim($("#contents").val()));
			$('#file_upload').uploadify({
				'auto'     : false,
				buttonText      : '选择文件',
				//debug           : true,
				 'multi'    : true,   //多文件上传
				'buttonClass' : 'btn btn-primary',
				'swf'      : '<?php echo base_url("public/js/uploadify.swf");?>',
				'uploader' : '<?php echo site_url('Upload/do_upload')?>',
			    'onUploadError' : function(file, errorCode, errorMsg, errorString) {
                    alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
                },
                'onQueueComplete' : function(queueData) {
                	   if(errors==false){
                	   	 $("#mfrom").submit();
                	   }
                } ,
                'onUploadError' : function(file, errorCode, errorMsg, errorString) {
                   openalert('文件' + file.name + '上传失败 : ' + errorString);
                   $('#file_upload').uploadify('stop');
                   errors=true;
                   return false; 
                } ,
                'onUploadSuccess' : function(file, data, response) {
                	if(data==112){ //服务器错误
                		  openalert("服务器错误 ,上传失败");
                          $('#file_upload').uploadify('stop');
                          errors=true;
                          return false;              		
                	}else{
                		var su=new Array();
                	    su.push('<div class="alert alert-success"  style="width:320px">');
                	    su.push('<input type="hidden" value="'+data+'" name="fujian[]">');
                	    su.push('文件:'+file.name+'上传成功,');
                	    su.push('</div>');
                	    $("#dosu").append(su.join(''));
                	}
                }
			});
		});
	</script>
<?php $this->load->view("common/footer"); ?>


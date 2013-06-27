<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>



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
        <a href="<?php echo site_url('users/sDataList') ?>" class="path-menu-a"> 系统设置</a> > <a href="<?php echo site_url('account/sDataList') ?>" class="path-menu-a"> 用户管理</a> > 修改
    </h1>

    <div class="row">
        <div class="span9">
            <div class="widget">
                <div class="widget-header">
                    <h3>修改用户</h3>
                </div>
                <!-- /widget-header -->
                <div class="widget-content">
                    <div class="tabbable">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="account.html#1" data-toggle="tab">用户信息</a>
                            </li>
                        </ul>
                        <br>
                        <div class="tab-content">
                            <div class="tab-pane active" id="1">
                            <input type="hidden" id="path" value="<?php echo site_url('') ?>">
                                  <form id="forms"  method="post" class="form-horizontal" action="<?php echo site_url("account/doSdaatEdit")?>" >
                                    <fieldset>
                                        <input type="hidden" value="<?php echo $info->id;?>" name="id">

                                        <div class="control-group">
                                            <label class="control-label" for="remark">真实姓名</label>
                                            <div class="controls">
                                                <input type="text" class="span3" id="remark" name="accountname" placeholder="真实姓名" value="<?php echo $info->accountname?>"  check-type="required" required-message="登陆名不能为空！" >
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        
                                        
                                         <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">邮箱</label>
                                            <div class="controls">
                                                <input type="text"  class="span3"  required id="inputEmail" check-type="mail" name="email" value="<?php echo $info->email?>" placeholder="邮箱" mail-message="邮箱格式不正确！" >
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                         <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">原密码</label>
                                            <div class="controls">
                                                <input  type="password" class="span3"   id="inputEmail" name="ycode"  placeholder="原密码" >
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                          
                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">新密码</label>
                                            <div class="controls">
                                                <input type="password" id='m' class="span3" id="remark" name="password" placeholder="登陆密码"   >
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">重复密码</label>
                                            <div class="controls">
                                                <input type="password" id="m2" class="span3" id="remark" name="password" placeholder="重复密码" >
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                         <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">电话</label>
                                            <div class="controls">
                                                <input type="text" class="span3" id="remark" name="phone" placeholder="电话" value="<?php echo $info->phone?>" >
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                         <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">手机</label>
                                            <div class="controls">
                                                <input type="text" class="span3" id="remark" name="mobilephone" placeholder="手机" value="<?php echo $info->mobilephone?>" >
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <br/>
                                        <br/>
                                        <div class="form-actions">
                                           <input type="submit"   id="doadds" class="btn btn-primary " value="修改数据">
                                        </div>
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


<?php $this->load->view("common/footer"); ?>
<script>
   $(document).ready(function(){
	   $('#forms').validation();
	   $("#doadds").click(subCheck);
   });
   function subCheck(){
	   if($.trim($("#m").val()) != $.trim($("#m2").val())){
		  openalert("两次输入密码不一致 ");
       	  return false;
	   }else{
         return true;
	   }
  }
</script>
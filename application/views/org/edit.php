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
        <a href="<?php echo site_url('users/sDataList') ?>" class="path-menu-a"> 系统设置</a> > <a href="<?php echo site_url('color/sDataList') ?>" class="path-menu-a"> 分组管理</a> > 修改
    </h1>

    <div class="row">
        <div class="span9">
            <div class="widget">
                <div class="widget-header">
                    <h3>修改分组</h3>
                </div>
                <!-- /widget-header -->
                <div class="widget-content">
                    <div class="tabbable">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="account.html#1" data-toggle="tab">分组信息</a>
                            </li>
                        </ul>
                        <br>
                        <div class="tab-content">
                            <div class="tab-pane active" id="1">
                            <input type="hidden" id="path" value="<?php echo site_url('') ?>">
                                <form  method="post" class="form-horizontal" action="<?php echo site_url("org/doSdaatEdit")?>">
                                    <fieldset>
                                       <input type="hidden" name="id" value="<?php echo $info->id;?>">
                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">分组名称</label>

                                            <div class="controls">
                                                <input type="text" class="span3" id="remark" value="<?php echo $info->orgcode;?>" name="orgcode" placeholder="分组名称" required>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">用户角色</label>
                                            <div class="controls">
                                                <select name="roleid" required class="span3" >
                                                 <option  value="0">无角色</option>
                                                    <?php foreach($roles as $val):?>
                                                      <option  value="<?php echo $val->id?>"
                                                      <?php if($val->id==$info->roleid) echo "selected"?>
                                                      ><?php echo $val->rolecode;?> </option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        
                                        <div class="control-group">
                                            <label class="control-label" for="remark">分组备注</label>

                                            <div class="controls">
                                                <textarea id="beizhu" rows="3" style=" vertical-align:left" class="span3" cols="23" name="orgmemo"  placeholder="分组备注" required>
                                                <?php echo $info->orgcode;?>
                                                </textarea>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        
                                        <!-- /control-group -->
                                          <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">库房选择</label>
                                            <div class="controls">
                                                <select name="store[]"  class="span3"  size="6"  multiple="multiple">
                                                 <option  value="0">无库房</option>
                                                    <?php foreach($store as $val):?>
                                                      <option  value="<?php echo $val->id?>"
                                                      <?php if(@in_array($val->id, json_decode($info->store,true))) echo "selected"?>
                                                      ><?php echo $val->storehousecode;?> </option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <br/>
                                        <br/>
                                        <div class="form-actions">
                                           <input type="submit" class="btn btn-primary " value="保存数据">
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
<script type="text/javascript">
<!--
   $(document).ready(function(){
     $("#beizhu").val($.trim($("#beizhu").val()));
   });
//-->
</script>

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
        <a href="<?php echo site_url('users/sDataList') ?>" class="path-menu-a"> 系统设置</a> > <a href="<?php echo site_url('role/sDataList') ?>" class="path-menu-a"> 角色管理</a> > 修改
    </h1>

    <div class="row">
        <div class="span9">
            <div class="widget">
                <div class="widget-header">
                    <h3>修改角色</h3>
                </div>
                <!-- /widget-header -->
                <div class="widget-content">
                    <div class="tabbable">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="account.html#1" data-toggle="tab">角色信息</a>
                            </li>
                        </ul>
                        <br>
                        <div class="tab-content">
                            <div class="tab-pane active" id="1">
                            <input type="hidden" id="path" value="<?php echo site_url('') ?>">
                                <form  method="post" class="form-horizontal" action="<?php echo site_url("role/doSdaatEdit")?>">
                                    <fieldset>
                                        <input type="hidden" value="<?php echo $info->id;?>" name="id">

                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">角色名称</label>

                                            <div class="controls">
                                                <input type="text" class="span3" id="remark" name="rolecode" placeholder="角色名称" value="<?php echo $info->rolecode;?>" required>
                                            </div>
                                            <!-- /controls -->
                                        </div>

                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">角色备注</label>

                                            <div class="controls">
                                                <input type="text" class="span3" id="remark" name="rolememo" placeholder="角色备注" value="<?php echo $info->rolememo?>" required>
                                            </div>
                                            <!-- /controls -->
                                        </div>

                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">操作权限</label>

                                            <div class="controls">
                                                <select name="power" required class="span3" >
                                                 <option  value="0" <?php if($info->power==0) echo "selected"; ?>>全站只读</option>
                                                 <option  value="1" <?php if($info->power==1) echo "selected"; ?>>本账户权限</option>
                                                 <option  value="2" <?php if($info->power==2) echo "selected"; ?>>全部权限</option>
                                                </select>
                                            </div>
                                            
                                            <!-- /controls -->
                                        </div>

                                        <!-- /control-group -->
                                         <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">是否财务</label>

                                            <div class="controls">
                                                <select name="accounting" required class="span3" >
                                                 <option  value="0"  <?php if(0==$info->accounting) echo "selected"?>>否</option>
                                                 <option  value="1"  <?php if(1==$info->accounting) echo "selected"?>>是</option>
                                                </select>
                                            </div>
                                            
                                            <!-- /controls -->
                                        </div>

                                        <!-- /control-group -->
                                        <br/>
                                        <br/>
                                        <div class="form-actions">
                                           <input type="submit" class="btn btn-primary " value="修改数据">
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

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
        <a href="<?php echo site_url('users/sDataList') ?>" class="path-menu-a"> 系统设置</a> > <a href="<?php echo site_url('config/sDataList') ?>" class="path-menu-a"> 配置管理</a> > 添加
    </h1>

    <div class="row">
        <div class="span9">
            <div class="widget">
                <div class="widget-header">
                    <h3>添加配置</h3>
                </div>
                <!-- /widget-header -->
                <div class="widget-content">
                    <div class="tabbable">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="account.html#1" data-toggle="tab">配置信息</a>
                            </li>
                        </ul>
                        <br>
                        <div class="tab-content">
                            <div class="tab-pane active" id="1">
                            <input type="hidden" id="path" value="<?php echo site_url('') ?>">
                                <form  method="post" class="form-horizontal" action="<?php echo site_url("config/doSdataAdd")?>">
                                    <fieldset>
                                      <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">配置项目</label>

                                            <div class="controls">
                                                <input type="text" class="span3" id="remark" name="memo" placeholder="配置项目"  required>
                                            </div>
                                            <!-- /controls -->
                                        </div>

                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">配置数据</label>

                                            <div class="controls">
                                                <input type="text" class="span3" id="remark" name="value" placeholder="配置数据"  required>
                                            </div>
                                            <!-- /controls -->
                                        </div>

                                        <!-- /control-group -->
                                         <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">配置键</label>

                                            <div class="controls">
                                                <input type="text" class="span3" id="remark" name="key" placeholder="配置键"  required>
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

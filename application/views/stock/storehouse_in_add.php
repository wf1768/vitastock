<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<script>

    //获取入库单号
    function get_innumber() {
        $.ajax({
            type: "post",
            url: "<?php echo site_url('storehouse_in/get_innumber')?>",
            success: function (data) {
                $('#innumber').val(data);
            },

            error: function () {
                openalert("创建入库单号数据出错，请重新尝试或与管理员联系。");
            }
        });
    }

    $(function () {

    })

</script>


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
                    <a href="<?php echo site_url('stock') ?>" class="path-menu-a"> 库存管理</a> > <a
                        href="<?php echo site_url('storehouse_in') ?>" class="path-menu-a"> 入库单管理</a> > 添加入库单
                </h1>

                <div class="row">
                    <div class="span9">
                        <div class="widget">
                            <div class="widget-header">
                                <h3>添加入库单</h3>
                            </div>
                            <!-- /widget-header -->
                            <div class="widget-content">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="account.html#1" data-toggle="tab">入库单信息</a>
                                        </li>
                                    </ul>
                                    <br>

                                    <div class="tab-content">
                                        <div class="tab-pane active" id="1">
                                            <form id="edit-stock" method="post" class="form-horizontal" action="">
                                                <fieldset>
                                                    <div id="innumber-group" class="control-group">
                                                        <label class="control-label" for="innumber">入库单编号</label>

                                                        <div class="controls">
                                                            <input type="text" class="span3" id="innumber"
                                                                   name="innumber" required
                                                                   value="<?php echo set_value('innumber'); ?>"> *
                                                            <input type="button" class="btn  btn-small" value="生成入库单编号"
                                                                   onclick="get_innumber()">
                                                            <?php
                                                            if (form_error('innumber', '<span class="help-inline">', '</span>')) {
                                                                echo '<script>';
                                                                echo '$("#innumber-group").addClass("error");';
                                                                echo '</script>';
                                                            }
                                                            echo form_error('innumber', '<span class="help-inline">', '</span>');
                                                            ?>
                                                        </div>
                                                        <!-- /controls -->
                                                    </div>
                                                    <!-- /control-group -->
                                                    <div class="control-group">
                                                        <label class="control-label" for="createtime">备注</label>
                                                        <div class="controls">
                                                            <textarea rows="3" id="remark" name="remark" class="span3"
                                                                      style="width: 260px; height: 120px;"><?php echo set_value('remark'); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label" for="createtime">创建时间</label>

                                                        <div class="controls">
                                                            <input type="text" readonly="true" class="span2"
                                                                   id="createtime" name="createtime"
                                                                   value="<?php date_default_timezone_set('PRC'); echo date('Y-m-d H:i:s'); ?>">
                                                        </div>
                                                        <!-- /controls -->
                                                    </div>
                                                    <!-- /control-group -->
                                                    <div class="control-group">
                                                        <label class="control-label" for="createtime">创建人</label>

                                                        <div class="controls">
                                                            <input type="text" readonly="true" class="span2"
                                                                   id="createby" name="createby"
                                                                   value="<?php echo $this->account_info_lib->accountname ?>">
                                                        </div>
                                                        <!-- /controls -->
                                                    </div>

                                                    <br/>

                                                    <div class="form-actions">
                                                        <button id="btn-save" type="submit" class="btn btn-primary">《
                                                            保存
                                                        </button>
                                                        <a href="<?php echo site_url('storehouse_in') ?>"
                                                           class="btn">返回</a>
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

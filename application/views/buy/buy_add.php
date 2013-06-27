<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<script>

    //获取入库单号
    function get_buynumber() {
        $.ajax({
            type: "post",
            url: "<?php echo site_url('buy/get_number')?>",
            success: function (data) {
                $('#buynumber').val(data);
            },

            error: function () {
                alert("创建采购单号数据出错，请重新尝试或与管理员联系。");
            }
        });
    }

    $(function () {
        $('#buydate_div').datepicker().on('changeDate', function (ev) {
            $(this).datepicker('hide')
        });
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
                    <a href="<?php echo site_url('buy') ?>" class="path-menu-a"> 采购管理</a> > <a
                        href="<?php echo site_url('buy/buy_list') ?>" class="path-menu-a"> 采购单管理</a> > 添加采购单
                </h1>

                <div class="row">
                    <div class="span9">
                        <form id="" method="post" action="">
                            <div class="widget widget-table">
                                <div class="widget-header">
                                    <i class="icon-th-list"></i>

                                    <h3>添加采购单</h3>
                                </div>
                                <!-- /widget-header -->
                                <div class="widget-content">
                                    <table id="apply_table" class="table table-striped table-bordered">
                                        <tbody>
                                        <tr>
                                            <td style="vertical-align:middle">采购单编号</td>
                                            <td colspan="3"><input type="text" class="span3" id="buynumber"
                                                                   name="buynumber" required style="margin-bottom: 0px;"
                                                                   placeholder="必须填写"
                                                                   value="<?php echo set_value('buynumber'); ?>"> *
                                                <input type="button" class="btn  btn-small" value="生成采购单编号"
                                                       onclick="get_buynumber()">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle;width: 130px">采购日期</td>
                                            <td>
                                                <div style="margin-bottom: 0px;" class="input-append date"
                                                     id="buydate_div" data-date="<?php echo date("Y-m-d") ?>"
                                                     data-date-format="yyyy-mm-dd">
                                                    <input style="margin-bottom: 0px;" id="buydate" name="buydate"
                                                           class="span2" size="16" type="text"
                                                           value="<?php echo date("Y-m-d") ?>" readonly>
                                                    <span class="add-on"><i title="点击选择日期"
                                                                            class="icon-calendar"></i></span>
                                                </div>
                                            </td>
                                            <td style="vertical-align:middle;width: 130px">采购负责人</td>
                                            <td><input type="text" class="span2" id="buyman" name="buyman" required
                                                       value="<?php echo $this->account_info_lib->accountname ?>"> *
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">创建时间</td>
                                            <td><input type="text" readonly="true" class="span2"
                                                       id="createtime" name="createtime"
                                                       value="<?php date_default_timezone_set('PRC'); echo date('Y-m-d H:i:s'); ?>">
                                                *
                                            </td>
                                            <td style="vertical-align:middle">创建人</td>
                                            <td><input type="text" readonly="true" class="span2"
                                                       id="createby" name="createby"
                                                       value="<?php echo $this->account_info_lib->accountname ?>"> *
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">备注</td>
                                            <td colspan="3"><input type="text" class="span5" id="remark" name="remark"
                                                                   style="margin-bottom: 0px;"
                                                                   value="<?php echo set_value('remark'); ?>">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /widget-content -->
                            </div>
                            <!-- /widget -->

                            <div class="row">
                                <div class="span9">
                                    <label class="pull-left">
                                        <button id="btn-save" type="submit" class="btn btn-primary">《
                                            保存并返回列表
                                        </button>
                                        <a href="<?php echo site_url('buy/buy_list') ?>"
                                           class="btn">返回</a>
                                    </label>
                                </div>
                            </div>
                        </form>

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

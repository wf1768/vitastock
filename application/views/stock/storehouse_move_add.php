<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<script>

    //获取单号
    function get_movenumber() {
        $.ajax({
            type: "post",
            url: "<?php echo site_url('buy/get_number')?>",
            success: function (data) {
                $('#movenumber').val(data);
            },

            error: function () {
                alert("创建调拨单编号出错，请重新尝试或与管理员联系。");
            }
        });
    }

    $(function () {
        $('#movedate_div').datepicker().on('changeDate', function(ev) {
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
                    <a href="<?php echo site_url('storehouse_move') ?>" class="path-menu-a"> 调拨管理</a> > <a
                        href="<?php echo site_url('storehouse_move') ?>" class="path-menu-a"> 调拨单管理</a> > 添加调拨单
                </h1>
                <div class="row">
                    <div class="span9">
                        <form id="storehouse_move_form" method="post">
                        <div class="widget widget-table">
                            <div class="widget-header">
                                <i class="icon-th-list"></i>
                                <h3>调拨单</h3>
                            </div> <!-- /widget-header -->
                            <div class="widget-content">
                                <table id="apply_table" class="table table-striped table-bordered">
                                    <tbody>
                                        <tr>
                                            <td style="vertical-align:middle">调拨单编号</td>
                                            <td colspan="3"><input type="text" class="span3" id="movenumber"
                                                       name="movenumber" required style="margin-bottom: 0px;"
                                                       value="<?php echo set_value('movenumber'); ?>"> *
                                                <input type="button" class="btn btn-primary btn-small" value="生成调拨单编号"
                                                       onclick="get_movenumber()">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">运输负责人</td>
                                            <td><input type="text" class="span2" id="moveby"
                                                       name="moveby" required style="margin-bottom: 0px;"
                                                       value="<?php echo set_value('moveby'); ?>"> *
                                            </td>
                                            <td style="vertical-align:middle">调拨日期</td>
                                            <td>
                                                <div class="input-append date" id="movedate_div" data-date="<?php echo date("Y-m-d")?>" data-date-format="yyyy-mm-dd">
                                                    <input id="movedate" name="movedate" class="span2" size="16" type="text" value="<?php echo date("Y-m-d")?>" readonly>
                                                    <span class="add-on"><i title="点击选择日期" class="icon-calendar"></i></span> *
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">原库房</td>
                                            <td><select id="oldhouseid" name="oldhouseid" class="span2">
                                                    <?php if($houses):?>
                                                        <?php foreach($houses as $house):?>
                                                            <option value="<?php echo $house->id ?>" <?php if ($house->id == $oldhouseid): ?>selected="true"<?php endif; ?>><?php echo $house->storehousecode ?></option>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                </select> *
                                            </td>
                                            <td style="vertical-align:middle">目标库房</td>
                                            <td><select id="targethouseid" name="targethouseid" class="span2">
                                                    <?php if($houses):?>
                                                        <?php foreach($houses as $house):?>
                                                            <option value="<?php echo $house->id ?>" <?php if ($house->id == $targethouseid): ?>selected="true"<?php endif; ?>><?php echo $house->storehousecode ?></option>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                </select> *
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">备注</td>
                                            <td colspan="3"><input type="text" class="span5" id="remark" name="remark" style="margin-bottom: 0px;"
                                                       value="<?php echo set_value('remark'); ?>">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!-- /widget-content -->
                        </div> <!-- /widget -->

                        <div class="row">
                            <div class="span9">
                                <label class="pull-left">
                                    <input type="hidden" id="apply_content_json" name="apply_content_json" value="">
                                    <button class="btn btn-primary" type="submit" id="btn-save"><i class="icon-ok">保存</i></button>
                                    <a href="<?php echo site_url('storehouse_move/pages?status=0') ?>" class="btn">返回</a>
<!--                                    <a href="javascript:;" class="btn btn-primary" ><i class="icon-plus"> 保存</i></a>-->
<!--                                    <button class="btn btn-primary" type="submit" id="btn-save"><i class="icon-ok">返回</i></button>-->
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

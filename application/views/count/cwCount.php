<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<script src="<?php echo base_url("public/js/highcharts.js"); ?>"></script>
<script src="<?php echo base_url("public/js/exporting.js"); ?>"></script>
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
                    <a href="<?php echo site_url('count/xsCount') ?>" class="path-menu-a">系统统计</a> > <a
                        href="<?php echo site_url('count/cwCount') ?>" class="path-menu-a"> 财务统计</a>
                </h1>

                <div class="row">
                    <div class="span9">
                        <div style="margin-top:5px" class="alert alert-block alert-error fade in hide">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <p>日期区间选择错误</p>
                        </div>
                        <div class="part_search" style="margin-top:5px">
                            <div class="navbar" style="margin-bottom:8px">
                                <div class="navbar-inner">
                                    <form class="navbar-form" method="post" id="countform">
                                        <font class="myfont"> 日期选择：</font>
                                        <input name="start" type="text" id="dpd1" placeholder="点击选择日期"
                                               data-date-format="yyyy-mm" data-date-minviewmode="months"
                                               data-date-viewmode="months"
                                               value="<?php if (isset($start)) echo $start ?>" class="span3">
                                        -</font>
                                        <input name="end" type="text" id="dpd2" placeholder="点击选择日期"
                                               data-date-format="yyyy-mm" data-date-minviewmode="months"
                                               data-date-viewmode="months" value="<?php if (isset($end)) echo $end ?>"
                                               class="span3">
                                        <button type="button" class="btn" id="dosearch">&nbsp;&nbsp;统&nbsp;&nbsp;计&nbsp;&nbsp;</button>
                                    </form>
                                </div>
                            </div>
                            <div class="widget" style="margin-bottom:0">
                                <div class="widget-header">
                                    <i class="icon-th-list"></i>

                                    <h3>销售金额统计</h3>
                                </div>
                            </div>
                            <!-- /widget-header -->
                            <div class="widget-content" style="height:300px; border:none;padding:0px" id="container">

                            </div>
                            <!-- /widget-content -->
                        </div>
                        <!-- /widget -->
                        <?php if (isset($ydatas)): ?>
                            <div class="widget widget-table">
                                <div class="widget-header">
                                    <i class="icon-th-list"></i>

                                    <h3>销售总价金额统计(单位 元)</h3>
                                </div>
                                <!-- /widget-header -->

                                <div class="widget-content">

                                    <table class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <?php foreach ($xdatas as $val): ?>
                                                <th><?php echo $val?></th>
                                            <?php endforeach;?>
                                            <th>总计</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr>
                                            <?php foreach ($ydatas as $val): ?>
                                                <th><?php echo $val?></th>
                                            <?php endforeach;?>
                                            <td><?php echo $allcount;?></td>

                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /widget-content -->
                            </div>
                        <?php endif;?>
                        <?php if (isset($discountdata)): ?>
                            <div class="widget widget-table">
                                <div class="widget-header">
                                    <i class="icon-th-list"></i>

                                    <h3>销售折扣总价金额统计(单位 元)</h3>
                                </div>
                                <!-- /widget-header -->

                                <div class="widget-content">

                                    <table class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <?php foreach ($xdatas as $val): ?>
                                                <th><?php echo $val?></th>
                                            <?php endforeach;?>
                                            <th>总计</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr>
                                            <?php foreach ($discountdatas as $val): ?>
                                                <th><?php echo $val?></th>
                                            <?php endforeach;?>
                                            <td><?php echo $all_discount;?></td>

                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /widget-content -->
                            </div>
                        <?php endif;?>
                    </div>
                    <!-- /span9 -->

                </div>
                <!-- /row -->

                <div class="row">
                    <div class="span9">

                        <!-- /widget -->
                        <div class="widget widget-table">

                            <div class="widget-header">
                                <i class="icon-th-list"></i>

                                <h3>库房产品统计表</h3>
                            </div>
                            <!-- /widget-header -->

                            <div class="widget-content">

                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>产品</th>
                                        <?php  foreach ($store as $val): ?>
                                            <th><?php echo $val->storehousecode?></th>
                                        <?php endforeach;?>
                                        <th>总计</th>
                                        <th>总金额</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if ($source) foreach ($source as $key => $val): ?>
                                        <tr>
                                            <td><?php echo $key; $all = 0;$money = 0;?></td>
                                            <?php if ($val) foreach ($val as $sval): ?>
                                                <td><?php  echo $sval->count;$all += $sval->count;$money += $sval->al?></td>
                                            <?php endforeach;?>
                                            <td><?php  echo $all;?></td>
                                            <td><?php  echo  number_format($money, 2, '.', '')?></td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>

                                </table>
                            </div>
                            <!-- /widget-content -->
                        </div>
                        <!-- ================================================================================= -->
                        <div class="row">
                            <div class="span4" style="margin-top:20px ">
                                <?php echo $info;?>
                            </div>
                            <div class=" pagination pagination-right">
                                <?php
                                echo $page;
                                ?>
                            </div>
                        </div>
                    </div>

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
    $(function () {
        var chart;
        $(document).ready(function () {
            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container',
                    type: 'spline'
                },
                title: {
                    text: '销售金额统计'
                },
                subtitle: {
                    text: '一段时间内的平均值'
                },
                xAxis: {
                    categories:<?php echo $xdata;?>
                },
                yAxis: {
                    title: {
                        text: '销售金额(单位 元)'
                    },
                    labels: {
                        formatter: function () {
                            return this.value + ''
                        }
                    }
                },
                tooltip: {
                    crosshairs: true,
                    shared: true
                },
                plotOptions: {
                    spline: {
                        marker: {
                            radius: 4,
                            lineColor: '#666666',
                            lineWidth: 1
                        }
                    }
                },
                series: [
                    {
                        name: '销售总价',
                        data: <?php echo $ydata?>
                    },
                    {
                        name: '折扣总价',
                        marker: {
                            symbol: 'square'
                        },
                        data: <?php echo $discountdata?>
                    }
                ]
            });
        });

    });

    //====================================================================================
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    var checkin = $('#dpd1').datepicker({
        onRender: function (date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate',function (ev) {
            if (ev.date.valueOf() > checkout.date.valueOf()) {
                var newDate = new Date(ev.date)
                newDate.setDate(newDate.getDate() + 1);
                checkout.setValue(newDate);
            }
            checkin.hide();
            $('#dpd2')[0].focus();
        }).data('datepicker');
    var checkout = $('#dpd2').datepicker({
        onRender: function (date) {
            return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate',function (ev) {
            checkout.hide();
        }).data('datepicker');
</script>
<script>
    $("#dosearch").click(function () {
        if ($("#dpd1").val() == '' || $("#dpd2").val() == '') {
            openalert("请选择时间段");
            return false;
        }
        if (checkEndTime() == false) {
            $(".alert").fadeIn("normal");
            $('#dpd2')[0].focus();
            setTimeout(function () {
                $(".alert").fadeOut("normal")
            }, 1500)
            return false;
        }
        $("#countform").submit();
    });
    function checkEndTime() {
        var startTime = $("#dpd1").val();
        var start = new Date(startTime.replace("-", "/").replace("-", "/") + "/01");
        var endTime = $("#dpd2").val();
        var end = new Date(endTime.replace("-", "/").replace("-", "/") + "/01");
        var now = new Date("<?php echo  date('Y/m/d')?>");
        if (now < end) {
            return false;
        }
        if (end <= start) {
            return false;
        }
        return true;
    }
</script>
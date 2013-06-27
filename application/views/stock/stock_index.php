<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<script src="<?php echo base_url("public/js/charts/bar.js"); ?>"></script>


<div id="content">
    <div class="container">
        <div class="row">
            <div class="span3">
                <?php $this->load->view('common/leftmenu'); ?>
            </div>
            <div class="span9">
                <h1 class="page-title">
                    <i class="icon-th-list"></i>
                    库存管理
                </h1>
                <div class="stat-container">
                    <div class="stat-holder">
                        <div class="stat">
                            <span>564</span>
                            库存商品
                        </div>
                        <!-- /stat -->
                    </div>
                    <!-- /stat-holder -->
                    <div class="stat-holder">
                        <div class="stat">
                            <span>423</span>
                            待处理入库单
                        </div>
                        <!-- /stat -->
                    </div>
                    <!-- /stat-holder -->
                    <div class="stat-holder">
                        <div class="stat">
                            <span>96</span>
                            待处理出库单
                        </div>
                        <!-- /stat -->
                    </div>
                    <!-- /stat-holder -->
                    <div class="stat-holder">
                        <div class="stat">
                            <span>2</span>
                            未读信息
                        </div>
                        <!-- /stat -->
                    </div>
                    <!-- /stat-holder -->
                </div>
                <!-- /stat-container -->
                <div class="widget">
                    <div class="widget-header">
                        <i class="icon-signal"></i>

                        <h3>入库、出库</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                        <div id="bar-chart" class="chart-holder"></div>
                        <!-- /bar-chart -->
                    </div>
                    <!-- /widget-content -->
                </div>
                <!-- /widget -->
            </div>
            <!-- /span9 -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div> <!-- /content -->


<?php $this->load->view("common/footer"); ?>

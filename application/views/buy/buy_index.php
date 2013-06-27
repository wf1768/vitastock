<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<script src="<?php echo base_url("public/js/charts/bar.js");?>"></script>


<div id="content">
<div class="container">
<div class="row">
<div class="span3">

    <?php $this->load->view('common/leftmenu'); ?>


</div> <!-- /span3 -->
<div class="span9">
    <h1 class="page-title">
        <i class="icon-th-list"></i>
        采购、期货管理
    </h1>
    <div class="stat-container">
        <div class="stat-holder">
            <div class="stat">
                <span>564</span>
                Completed Sales
            </div> <!-- /stat -->
        </div> <!-- /stat-holder -->
        <div class="stat-holder">
            <div class="stat">
                <span>423</span>
                Pending Sales
            </div> <!-- /stat -->
        </div> <!-- /stat-holder -->
        <div class="stat-holder">
            <div class="stat">
                <span>96</span>
                Returned Sales
            </div> <!-- /stat -->
        </div> <!-- /stat-holder -->
        <div class="stat-holder">
            <div class="stat">
                <span>2</span>
                Chargebacks
            </div> <!-- /stat -->
        </div> <!-- /stat-holder -->
    </div> <!-- /stat-container -->
    <div class="widget">
        <div class="widget-header">
            <i class="icon-signal"></i>
            <h3>Area Chart</h3>
        </div> <!-- /widget-header -->
        <div class="widget-content">
            <div id="bar-chart" class="chart-holder"></div> <!-- /bar-chart -->
        </div> <!-- /widget-content -->
    </div> <!-- /widget -->
    <div class="widget widget-table">
        <div class="widget-header">
            <i class="icon-th-list"></i>
            <h3>Table</h3>
        </div> <!-- /widget-header -->
        <div class="widget-content">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Company</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>Michael</td>
                    <td>Jordan</td>
                    <td>@mjordan</td>
                    <td>Chicago Bulls</td>
                    <td class="action-td">
                        <a href="javascript:;" class="btn btn-small btn-warning">
                            <i class="icon-ok"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-small">
                            <i class="icon-remove"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Magic</td>
                    <td>Johnson</td>
                    <td>@mjohnson</td>
                    <td>Los Angeles Lakers</td>
                    <td class="action-td">
                        <a href="javascript:;" class="btn btn-small btn-warning">
                            <i class="icon-ok"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-small">
                            <i class="icon-remove"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Charles</td>
                    <td>Barkley</td>
                    <td>@cbarkley</td>
                    <td>Phoenix Suns</td>
                    <td class="action-td">
                        <a href="javascript:;" class="btn btn-small btn-warning">
                            <i class="icon-ok"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-small">
                            <i class="icon-remove"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Karl</td>
                    <td>Malone</td>
                    <td>@kmalone</td>
                    <td>Utah Jazz</td>
                    <td class="action-td">
                        <a href="javascript:;" class="btn btn-small btn-warning">
                            <i class="icon-ok"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-small">
                            <i class="icon-remove"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>David</td>
                    <td>Robinson</td>
                    <td>@drobinson</td>
                    <td>San Antonio Spurs</td>
                    <td class="action-td">
                        <a href="javascript:;" class="btn btn-small btn-warning">
                            <i class="icon-ok"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-small">
                            <i class="icon-remove"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Reggie</td>
                    <td>Miller</td>
                    <td>@rmiller</td>
                    <td>Indiana Pacers</td>
                    <td class="action-td">
                        <a href="javascript:;" class="btn btn-small btn-warning">
                            <i class="icon-ok"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-small">
                            <i class="icon-remove"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>Clyde</td>
                    <td>Drexler</td>
                    <td>@cdrexler</td>
                    <td>Portland Trail Blazers</td>
                    <td class="action-td">
                        <a href="javascript:;" class="btn btn-small btn-warning">
                            <i class="icon-ok"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-small">
                            <i class="icon-remove"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>Hakeem</td>
                    <td>Olajuwon</td>
                    <td>@holajuwon</td>
                    <td>Houston Rockets</td>
                    <td class="action-td">
                        <a href="javascript:;" class="btn btn-small btn-warning">
                            <i class="icon-ok"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-small">
                            <i class="icon-remove"></i>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div> <!-- /widget-content -->
    </div> <!-- /widget -->
</div> <!-- /span9 -->
</div> <!-- /row -->
</div> <!-- /container -->
</div> <!-- /content -->


<?php $this->load->view("common/footer"); ?>

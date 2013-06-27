<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

 <script src="<?php echo base_url("public/js/highcharts.js");?>"></script>
  <script src="<?php echo base_url("public/js/exporting.js");?>"></script>
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
        <a href="<?php echo site_url('users/sDataList') ?>" class="path-menu-a"> 系统设置</a> > <a href="<?php echo site_url('color/sDataList') ?>" class="path-menu-a"> 颜色管理</a> > 添加
    </h1>

    <div class="row">
        <div class="span9">
            <div class="widget">
                <div class="widget-header">
                <i class="icon-th-list"></i>
                    <h3>财务统计</h3>
                </div>
                    <div class="part_search" style="margin-top:5px">
           <div class="navbar" style="margin-bottom:8px">
              <div class="navbar-inner">
               <form class="navbar-form">
                 <font class="myfont" > 日期选择：</font>
                  <input type="text" id="dpd1" placeholder="点击选择日期"  data-date-format="yyyy-mm-dd"value="" class="span3">
                -</font> 
                <input type="text" id="dpd2"  placeholder="点击选择日期"  data-date-format="yyyy-mm-dd"value="" class="span3">
                    <button type="submit" class="btn">&nbsp;&nbsp;统&nbsp;&nbsp;计&nbsp;&nbsp;</button>  
               </form>
              </div>
            </div>
          </div>
                <!-- /widget-header -->
                <div class="widget-content" style="height:300px; border:none;padding:0px" id="container">
                    
                </div>
                <!-- /widget-content -->
            </div>
            <!-- /widget -->
          <!-- ================================================================================= -->
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
										<a class="btn btn-small btn-warning" href="javascript:;">
											<i class="icon-ok"></i>								
										</a>					
										<a class="btn btn-small" href="javascript:;">
											<i class="icon-remove"></i>						
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div> <!-- /widget-content -->
				</div>
				<!-- ================================================================================= -->
				
				
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
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'spline'
            },
            title: {
                text: '每个月的平均气温'
            },
            subtitle: {
                text: 'Source: 北京气象网站'
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: '气温'
                },
                labels: {
                    formatter: function() {
                        return this.value +'°'
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
            series: [{
                name: '北京',
                marker: {
                    symbol: 'square'
                },
                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2,46.5, 23.3, 18.3, 13.9, 9.6]
    
            }]
        });
    });
    
});

//====================================================================================
	    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
     
    var checkin = $('#dpd1').datepicker({
    onRender: function(date) {
    return date.valueOf() < now.valueOf() ? 'disabled' : '';
    }
    }).on('changeDate', function(ev) {
    if (ev.date.valueOf() > checkout.date.valueOf()) {
    var newDate = new Date(ev.date)
    newDate.setDate(newDate.getDate() + 1);
    checkout.setValue(newDate);
    }
    checkin.hide();
    $('#dpd2')[0].focus();
    }).data('datepicker');
    var checkout = $('#dpd2').datepicker({
    onRender: function(date) {
    return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
    }
    }).on('changeDate', function(ev) {
    checkout.hide();
    }).data('datepicker');
		</script>

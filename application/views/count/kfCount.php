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
        <a href="<?php echo site_url('count/xsCount') ?>" class="path-menu-a">系统统计</a> > <a href="<?php echo site_url('count/kfCount') ?>" class="path-menu-a"> 库房统计</a> 
    </h1>

    <div class="row">
        <div class="span9">
            
            <!-- /widget -->
          <div class="widget widget-table">
     				<div class="widget-content" style="margin-bottom:10px">
					
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
								    <th width="25%">数量总计</th>
									<th width="25%"><?php echo number_format($tcount,2);?></th>
									<th width="25%">价值总计(单位： 元)</th>
									<th width="25%">￥：<?php echo number_format($tmoney,2);?></th>
								</tr>
							</thead>
							
						</table>
					</div> <!-- /widget-content -->		
					<div class="widget-header">
						<i class="icon-th-list"></i>
						<h3>统计表</h3>
					</div> <!-- /widget-header -->
					
					
					
					
					<div class="widget-content">
					
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
								    <th>产品</th>
								<?php foreach($store as $val):?>
									<th><?php echo $val->storehousecode?></th>
							    <?php endforeach;?>
									<th>数量总计</th>
									<th>价值总计(单位： 元)</th>
								</tr>
							</thead>
							<tbody>
							<?php if($source) foreach($source as $key=> $val):?>
							<?php $allmoney=0; if($val['money']) foreach($val['money'] as $sval){$allmoney+=$sval;}?>
							 <tr>
							    <td><?php echo $key; $all=0;?></td>
							    <?php if($val['count']) foreach($val['count'] as $sval):?>
							         <td><?php  echo $sval;$all+=$sval;?></td>
							    <?php endforeach;?>
							     <td><?php  echo $all;?></td>
							     <td>￥：<?php  echo number_format($allmoney,2);?></td>
							 </tr>
							<?php endforeach;?>
							</tbody>
							
						</table>
					</div> <!-- /widget-content -->
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
                text: '销售统计'
            },
            subtitle: {
                text: '一段时间内的平均值'
            },
            xAxis: {
                categories:<?php echo $xdata;?>
            },
            yAxis: {
                title: {
                    text: '销售量(单位 件)'
                },
                labels: {
                    formatter: function() {
                        return this.value +''
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
                name: '销售量',
                marker: {
                    symbol: 'square'
                },
                data: <?php echo $ydata?>
    
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

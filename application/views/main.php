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
       <i class="icon-home"></i>
                      主页信息
   </h1>

    <div class="row">
        <div class="span9">
              <div class="stat-container">
    <div class="stat-holder">
        <div class="stat">
           <span><?php echo $xscount?$xscount:0?></span>
            本月商品销售数量
        </div>
        <!-- /stat -->
    </div>
    <!-- /stat-holder -->
    <div class="stat-holder">
        <div class="stat">
            <span><?php echo $cgcount?$cgcount:0?></span>
            本月商品采购数量
        </div>
        <!-- /stat -->
    </div>
    <!-- /stat-holder -->
    <div class="stat-holder">
        <div class="stat">
            <span><?php echo $qhcount?$qhcount:0?></span>
            本月期货订货数量
        </div>
        <!-- /stat -->
    </div>
    <!-- /stat-holder -->
    <div class="stat-holder">
        <div class="stat">
           <span><?php echo $thcount?$thcount:0?></span>
            本月 退货数量
        </div>
        <!-- /stat -->
    </div>
    <!-- /stat-holder -->
</div>
            <div class="widget" style="margin-bottom:0">
                <div class="widget-header">
                <i class="icon-th-list"></i>
                    <h3>销售统计</h3>
                </div>
          </div>
                <!-- /widget-header -->
                <div class="widget-content" style="height:300px; border:none;padding:0px" id="container">
                    
                </div>
                <!-- /widget-content -->
            </div>
            <!-- /widget -->
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
 <script>
             $("#dosearch").click(function(){
                 if($("#dpd1").val()==''||$("#dpd2").val()==''){
                	openalert("请选择时间段");
                 	return false;
                 }
                 if(checkEndTime()==false){
                	$(".alert").fadeIn("normal");
                	$('#dpd2')[0].focus();
                	setTimeout(function(){$(".alert").fadeOut("normal")},1500)
            	 	return false;
                 }
            	 $("#countform").submit();
             });
             function checkEndTime(){  
                 var startTime=$("#dpd1").val();  
                 var start=new Date(startTime.replace("-", "/").replace("-", "/")+"/01");  
                 var endTime=$("#dpd2").val();  
                 var end=new Date(endTime.replace("-", "/").replace("-", "/")+"/01");  
                 var now=new Date("<?php echo  date('Y/m/d')?>");
                 if(now<end){  
                     return false;  
                 }
                 if(end<=start){  
                     return false;  
                 }
                 return true;  
             }  
            </script>
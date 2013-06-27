<div class="row">

    <div class="span3">

        <div class="account-container">

            <div class="account-avatar">
                <img src="../../../upload/headshot/headshot.png" alt="" class="thumbnail" />
            </div> <!-- /account-avatar -->

            <div class="account-details">

                <span class="account-name">张三</span>

                <span class="account-role">库存管理员</span>

                            <span class="account-actions">
                                <a href="javascript:;">账户属性</a> |

                                <a href="javascript:;">编辑设置</a>
                            </span>

            </div> <!-- /account-details -->

        </div> <!-- /account-container -->

        <hr />

        <?php
        $mname = "system";
        include("../../common/leftmenu.php") ?>

        <hr />

        <div class="sidebar-extra">
            <p>账户活跃度可以根据登陆日志来显示。研究flot插件显示图。库房活跃度可以根据账户的操作日志来判断。</p>
        </div> <!-- .sidebar-extra -->

        <br />

    </div> <!-- /span3 -->



    <div class="span9">

        <h1 class="page-title">
            <i class="icon-home"></i>
            系统维护
        </h1>

        <div class="stat-container">

            <div class="stat-holder">
                <div class="stat">
                    <a href="#">
                    <span>5</span>
                    库房运营
                    </a>
                </div> <!-- /stat -->
            </div> <!-- /stat-holder -->

            <div class="stat-holder">
                <div class="stat">
                    <a href="#">
                    <span>20</span>
                    账户使用者
                    </a>
                </div> <!-- /stat -->

            </div> <!-- /stat-holder -->

            <div class="stat-holder">
                <div class="stat">
                    <a href="#">
                    <span>3</span>
                    类角色
                    </a>
                </div> <!-- /stat -->
            </div> <!-- /stat-holder -->

            <!-- /stat-holder -->

        </div> <!-- /stat-container -->

        <div class="widget">

            <div class="widget-header">
                <i class="icon-signal"></i>
                <h3>账户活跃度</h3>
            </div> <!-- /widget-header -->

            <div class="widget-content">
                <div id="bar-chart" class="chart-holder"></div> <!-- /bar-chart -->
            </div> <!-- /widget-content -->

        </div> <!-- /widget -->
        <script>
            $(function () {
                var data = new Array ();
                var ds = new Array();

                data.push ([[1,2]]);
                data.push ([[1,13]]);
                data.push ([[1,8]]);
                data.push ([[1,20]]);

                for (var i=0, j=data.length; i<j; i++) {

                    ds.push({
                        data:data[i],
                        grid:{
                            hoverable:true
                        },
                        bars: {
                            show: true,
                            barWidth: 1,
                            order: 1,
                            lineWidth: 0.5,
                            fillColor: { colors: [ { opacity: 0.65 }, { opacity: 1 } ] }
                        }
                    });
                }

                $.plot($("#bar-chart1"), ds, {
                    colors: ["#F90"]


                });
            });
        </script>
        <div class="widget">

            <div class="widget-header">
                <i class="icon-signal"></i>
                <h3>库房操作活跃度</h3>
            </div> <!-- /widget-header -->

            <div class="widget-content">
                <div id="bar-chart1" class="chart-holder"></div> <!-- /bar-chart -->
            </div> <!-- /widget-content -->

        </div> <!-- /widget -->
    </div> <!-- /span9 -->


</div> <!-- /row -->
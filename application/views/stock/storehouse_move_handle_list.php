<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<script>
    $(function() {
        $('#movenumber').keyup(function(event){
            var e = event || window.event; //浏览器兼容
            if (e.keyCode == 13) {
                $('#search-form').submit();
            }
        });
    })
</script>
<div id="content">
    <div class="container">
        <div class="row">
            <div class="span3">
                <?php $this->load->view('common/leftmenu'); ?>
            </div> <!-- /span3 -->
            <div class="span9">
                <h1 class="page-title">
                    <i class="icon-th-list"></i>
                    <a href="<?php echo site_url('storehouse_move/handle') ?>" class="path-menu-a"> 调拨商品</a> > 调拨单列表
                </h1>
                <div class="row">
                    <form method="GET" id="search-form" class="form-inline">
                        <div class="span9">
                            <label class="pull-right">调拨单编号: <input id="movenumber" name="movenumber" type="text" class="input-medium" placeholder="调拨单编号查询" value="<?php echo $search ?>"></label>
                        </div>
                    </form>
                </div>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>调拨单列表</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                         <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all""></th>
                                <th>调拨单编号</th>
                                <th>创建时间</th>
                                <th>创建人</th>
                                <th>调拨日期</th>
                                <th>运输负责人</th>
                                <th>原库房</th>
                                <th>目标库房</th>
                                <th>备注</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($list)):?>
                                <?php foreach($list as $row):?>
                                <tr>
                                    <td><input type="checkbox" name="checkbox" value="<?php echo $row->id ?>"/></td>
                                    <td><a href="<?php echo site_url('storehouse_move/handle_show?id='.$row->id) ?>"><?php echo $row->movenumber ?></a></td>
                                    <td><?php echo strtotime($row->createtime)?$row->createtime:'';  ?></td>
                                    <td><?php echo $row->createby ?></td>
                                    <td><?php echo strtotime($row->movedate)?$row->movedate:'';  ?></td>
                                    <td><?php echo $row->moveby ?></td>
                                    <td><?php echo $row->oldhouse ?></td>
                                    <td><?php echo $row->targethouse ?></td>
                                    <td><?php echo $row->remark ?></td>
                                    <td>
                                        <?php
                                        if ($row->status == 0) {
                                            echo '<font color="red">未开始</font>';
                                        }
                                        else if ($row->status == 1) {
                                            echo '<font color="green">调拨中</font>';
                                        }
                                        else if ($row->status == 2) {
                                            echo '已结束';
                                        }
                                        else {
                                            echo '未知状态，请与管理员联系。';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div> <!-- /widget-content -->
                    <div class="row">
                        <div class="span4" style="margin-top:20px ">
                            <?php echo (isset($info))?$info:'' ?>
                        </div>
                        <div class=" pagination pagination-right">
                            <?php
                                echo (isset($page))?$page:'';
                            ?>
                        </div>
                    </div>
                </div> <!-- /widget -->
            </div> <!-- /span9 -->
        </div> <!-- /row -->
    </div> <!-- /container -->
</div> <!-- /content -->


<?php $this->load->view("common/footer"); ?>

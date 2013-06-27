<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<script>

    function remove_move() {
        var str="";
        $("input[name='checkbox']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择要删除的调拨单。');
            return;
        }

        bootbox.confirm("确定要删除选择的调拨单吗？<br> <font color='red'>" +
                "注意：删除调拨单将同时移除调拨单包含的调拨商品，本操作不可恢复，请谨慎操作。</font> ", function(result) {
            if(result){
                str = str.substring(0,str.length-1);
                $.ajax({
                    type:"post",
                    data: "id=" + str,
                    url:"<?php echo site_url('storehouse_move/remove')?>",
                    success: function(data){
                        if (data) {
                            $("input[name='checkbox']").attr("checked",false);
                            window.location.reload();
                        }
                        else {
                            openalert("删除调拨单出错，请重新尝试或与管理员联系。");
                        }
                    },
                    error: function() {
                        openalert("执行操作出错，请重新尝试或与管理员联系。");
                    }
                });
            }
        })
    }

    $(function() {
        $("#select-all").click(function(){
            if ($(this).attr("checked") == 'checked') {
                $("input[name='checkbox']").attr("checked",$(this).attr("checked"));
            }
            else {
                $("input[name='checkbox']").attr("checked",false);
            }
        });


        $("input[name='status']").click(function() {
            window.location.href= '<?php echo site_url() ?>/storehouse_move/pages?status=' + this.value;
        });

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
                    <a href="<?php echo site_url('storehouse_move') ?>" class="path-menu-a"> 调拨管理</a> > 调拨单管理
                </h1>
                <?php if ($oper) : ?>
                <div class="row">
                    <div class="span9">
                        <label class="pull-right">
                            <a href="<?php echo site_url('storehouse_move/add') ?>" id="add" class="btn btn-small">
                                <i class="icon-plus"> 添加</i>
                            </a>
                            <?php if ($status == 0) : ?>
                            <a href="javascript:;" class="btn btn-small" onclick="remove_move()">
                                <i class="icon-minus"> 删除</i>
                            </a>
                            <?php endif ?>
                        </label>
                    </div>
                </div>
                <?php endif ?>

                <div class="minbox">
                    <div class="part_search">
                        <div class="navbar">
                            <div class="navbar-inner">
                                <form class="navbar-form" method="get" id="searchfrom" style="margin-bottom:5px">
                                    <input type="hidden" id='status' name="status" value="<?php echo $status ?>">
                                    <font class="myfont">调拨单号：</font>
                                    <input type="text" name="movenumber" id="movenumber"
                                           value="<?php echo isset($_REQUEST['movenumber']) ? $_REQUEST['movenumber'] : ''; ?>"
                                           placeholder="请输入调拨单编号">

                                    &nbsp;&nbsp;<font class="myfont">运输负责人：</font>
                                    <input type="text" id="moveby" name="moveby"
                                           value="<?php echo isset($_REQUEST['moveby']) ? $_REQUEST['moveby'] : ''; ?>"
                                           placeholder="请输入运输负责人"><br/>
                                    <font class="myfont">&nbsp;&nbsp;&nbsp;原库房：</font>
                                    <select name="oldhouseid">
                                        <option value="">请选择</option>
                                        <?php foreach ($storehouse as $val): ?>
                                            <option value="<?php echo $val->id; ?>"
                                                <?php if (isset($_REQUEST['oldhouseid']) && $_REQUEST['oldhouseid'] == $val->id) {
                                                echo "selected";
                                            }?>><?php echo $val->storehousecode;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font class="myfont">目标库房：</font>
                                    <select id="targethouseid" name="targethouseid" ">
                                        <option value="">请选择</option>
                                        <?php foreach ($storehouse as $val): ?>
                                            <option value="<?php echo $val->id; ?>"
                                                <?php if (isset($_REQUEST['targethouseid']) && $_REQUEST['targethouseid'] == $val->id) {
                                                echo "selected";
                                            }?>><?php echo $val->storehousecode;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <br/>
                                    <font class="myfont">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;备注：</font>
                                    <input type="text" name="remark" id="remark"
                                           value="<?php echo isset($_REQUEST['remark']) ? $_REQUEST['remark'] : ''; ?>"
                                           placeholder="请输入备注">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font class="myfont">状态：</font>
                                    <select name="status">
                                        <option value="0" <?php if ($status == 0) echo 'selected' ?>>未开始</option>
                                        <option value="1" <?php if ($status == 1) echo 'selected' ?>>调拨中</option>
                                        <option value="2" <?php if ($status == 2) echo 'selected' ?>>已结束</option>
                                        <option value="3" <?php if ($status == 3) echo 'selected' ?>>全部</option>
                                    </select>
                                    <button style="margin-left:20px" id="search" type="submit" class="btn btn-primary">&nbsp;&nbsp;搜&nbsp;&nbsp;索&nbsp;&nbsp;</button>
                                </form>

                            </div>
                        </div>
                    </div>
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
                                    <td><a href="<?php echo site_url('storehouse_move/show?id='.$row->id) ?>"><?php echo $row->movenumber ?></a></td>
                                    <td><?php echo strtotime($row->createtime)?$row->createtime:'';  ?></td>
                                    <td><?php echo $row->createby ?></td>
                                    <td><?php echo strtotime($row->movedate)?$row->movedate:'';  ?></td>
                                    <td><?php echo $row->moveby ?></td>
                                    <td><?php echo $row->oldhouse ?></td>
                                    <td><?php echo $row->targethouse ?></td>
                                    <td><?php echo $row->remark ?></td>
<!--                                    <td>--><?php //echo ($row->status == 0)? '<font color="red">未结束</font>':'已结束' ?><!--</td>-->
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

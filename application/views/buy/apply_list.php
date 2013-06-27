<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<script>

    function remove_apply() {
        var str="";
        $("input[name='checkbox']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择要删除的期货订单。');
            return;
        }

        bootbox.confirm("确定要删除选择的期货订单吗？<br> <font color='red'>" +
                "注意：删除期货订单将同时删除包含的商品和处理进度，本操作不可恢复，请谨慎操作。</font> ", function(result) {
            if(result){

                str = str.substring(0,str.length-1);

                $.ajax({
                    type:"post",
                    data: "id=" + str,
                    url:"<?php echo site_url('apply/remove')?>",
                    success: function(data){
                        if (data) {
                            $("input[name='checkbox']").attr("checked",false);
                            window.location.reload();
                        }
                        else {
                            openalert("删除期货订单出错，请重新尝试或与管理员联系。");
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
<!--            window.location.href= '--><?php //echo site_url() ?><!--/apply/pages?status=' + this.value+'--><?php //echo str_replace('?', '&',$stype)?><!--';-->
            window.location.href= '<?php echo site_url() ?>/apply/pages?status=' + this.value + '&stype=<?php echo $stype ?>';
        });

        $('#applynumber').keyup(function(event){
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
                    <?php if ($stype == 'apply') : ?>
                        <a href="<?php echo ($stype == 'apply')?site_url('buy'):'javascript:;' ?>" class="path-menu-a"> 采购管理</a> > 期货订单管理
                    <?php elseif ($stype == 'sale') : ?>
                        <a href="javascript:;" class="path-menu-a"> 销售管理</a> > 期货订单管理
                    <?php elseif ($stype == 'financial') : ?>
                        <a href="javascript:;" class="path-menu-a"> 财务管理</a> > 期货订单审核
                    <?php endif ?>
                </h1>
                <div class="row">
                    <div class="span9">
                        <label class="pull-right">
                            <?php if ($stype != 'financial') : ?>
                            <a href="<?php echo site_url('apply/add?stype=').$stype;?>" id="add" class="btn btn-small">
                                <i class="icon-plus"> 添加</i>
                            </a>
                            <?php endif; ?>
                            <?php if (($status == 2 || $status == 3) && $oper && $stype == 'apply') : ?>
                            <a href="javascript:;" class="btn btn-small" onclick="remove_apply()">
                                <i class="icon-minus"> 删除</i>
                            </a>
                            <?php endif ?>
                        </label>
                    </div>
                </div>
                <div class="minbox">
                    <div class="part_search">
                        <div class="navbar">
                            <div class="navbar-inner">
                                <form class="navbar-form" method="get" id="searchfrom" style="margin-bottom:5px">
                                    <input type="hidden" id='stype' name="stype" value="<?php echo $stype ?>">
                                    <font class="myfont">订单编号：</font>
                                    <input type="text" name="applynumber" id="applynumber"
                                           value="<?php echo isset($_REQUEST['applynumber']) ? $_REQUEST['applynumber'] : ''; ?>"
                                           placeholder="请输入订单编号">

                                    &nbsp;&nbsp;<font class="myfont">&nbsp;&nbsp;&nbsp;销售店：</font>
                                    <select name="storehouseid">
                                        <option value="">请选择</option>
                                        <?php foreach ($storehouse as $val): ?>
                                            <option value="<?php echo $val->id; ?>"
                                                <?php if (isset($_REQUEST['storehouseid']) && $_REQUEST['storehouseid'] == $val->id) {
                                                echo "selected";
                                            }?>><?php echo $val->storehousecode;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <br />
                                    <font class="myfont">客户名称：</font>
                                    <input type="text" name="clientname" id="clientname"
                                           value="<?php echo isset($_REQUEST['clientname']) ? $_REQUEST['clientname'] : ''; ?>"
                                           placeholder="请输入客户名称">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font class="myfont">申请人：</font>
                                    <input type="text" name="applyby" id="applyby"
                                           value="<?php echo isset($_REQUEST['applyby']) ? $_REQUEST['applyby'] : ''; ?>"
                                           placeholder="请输入申请人">
                                    <br/>
                                    <font class="myfont">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;备注：</font>
                                    <input type="text" name="remark" id="remark"
                                           value="<?php echo isset($_REQUEST['remark']) ? $_REQUEST['remark'] : ''; ?>"
                                           placeholder="请输入备注">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font class="myfont">状态：</font>
                                    <select name="status">
                                        <option value="1" <?php if ($status == 1) echo 'selected' ?>>未结束</option>
                                        <option value="2" <?php if ($status == 2) echo 'selected' ?>>已结束</option>
                                        <option value="3" <?php if ($status == 3) echo 'selected' ?>>全部</option>
<!--                                        <option value="1" --><?php //if ($status == 1) echo 'selected' ?><!-->待审核</option>-->
<!--                                        <option value="2" --><?php //if ($status == 2) echo 'selected' ?><!-->已审核</option>-->
<!--                                        <option value="3" --><?php //if ($status == 3) echo 'selected' ?><!-->处理中</option>-->
<!--                                        <option value="4" --><?php //if ($status == 4) echo 'selected' ?><!-->已结束</option>-->
<!--                                        <option value="5" --><?php //if ($status == 5) echo 'selected' ?><!-->全部</option>-->
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
                        <h3>期货订单列表</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                         <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all""></th>
                                <th>订单编号</th>
                                <th>销售店</th>
                                <th>销售日期</th>
                                <th>客户名称</th>
                                <th>申请人</th>
                                <th>申请日期</th>
                                <th>处理人</th>
                                <th>承诺到货日期</th>
                                <th>备注</th>
                                <th>状态</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($list)):?>
                                <?php foreach($list as $row):?>
                                <tr>
                                    <td><input type="checkbox" name="checkbox" value="<?php echo $row->id ?>"/></td>
                                    <td><a href="<?php echo site_url('apply/show?id='.$row->id.'&stype='.$stype) ?>"><?php echo $row->applynumber ?></a></td>
                                    <td><?php echo $row->storehousecode ?></td>
                                    <td><?php echo strtotime($row->selldate)?$row->selldate:'';  ?></td>
<!--                                    <td>--><?php //echo $row->createby ?><!--</td>-->
<!--                                    <td>--><?php //echo strtotime($row->createtime)?$row->createtime:'';  ?><!--</td>-->
<!--                                    <td>--><?php //echo $row->email ?><!--</td>-->
                                    <td><?php echo $row->clientname ?></td>
                                    <td><?php echo $row->applyby ?></td>
                                    <td><?php echo strtotime($row->applydate)?$row->applydate:'';  ?></td>
                                    <td><?php echo $row->checkby ?></td>
                                    <td><?php echo strtotime($row->commitgetdate)?$row->commitgetdate:'';  ?></td>
                                    <td><?php echo $row->remark ?></td>
                                    <td><?php
                                        if ($row->status == 1) {
                                            echo '<font color="red">'.$row->statusvalue.'</font>';
                                        }
                                        else if ($row->status == 2 || $row->status == 3) {
                                            echo '<font color="blue">'.$row->statusvalue.'</font>';
                                        }
                                        else if ($row->status == 4) {
                                            echo '<font color="green">'.$row->statusvalue.'</font>';
                                        }
                                        else {
                                            echo $row->statusvalue;
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

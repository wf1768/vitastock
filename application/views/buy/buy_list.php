<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<script>

    function remove_buy() {
        var str="";
        $("input[name='checkbox']").each(function(){
            if($(this).attr("checked") == 'checked'){
                str+=$(this).val()+",";
            }
        })
        if (str == "") {
            openalert('请选择要删除的采购单。');
            return;
        }

//        if(confirm("确定要删除选择的采购单吗？")) {
        bootbox.confirm("确定要删除选择的采购单吗？<br> <font color='red'>" +
                "注意：删除采购单将同时删除采购单包含的商品，本操作不可恢复，请谨慎操作。</font> ", function(result) {
            if(result){

                str = str.substring(0,str.length-1);

                $.ajax({
                    type:"post",
                    data: "id=" + str,
                    url:"<?php echo site_url('buy/remove_buy')?>",
                    success: function(data){
                        if (data) {
                            $("input[name='checkbox']").attr("checked",false);
                            window.location.reload();
                        }
                        else {
                            openalert("删除采购单出错，请重新尝试或与管理员联系。");
                        }
                    },
                    error: function() {
                        openalert("执行操作出错，请重新尝试或与管理员联系。");
                    }
                });
            }
        })
//        else {
//            return;
//        }
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
            window.location.href= '<?php echo site_url() ?>/buy/buy_pages?status=' + this.value;
        });

        $('#buynumber').keyup(function(event){
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
                    <a href="<?php echo site_url('buy') ?>" class="path-menu-a"> 采购管理</a> > 采购单管理
                </h1>
                <div class="row">
                    <div class="span9">
                        <label class="pull-right">
                            <input type="hidden" id="path" value="<?php echo site_url('') ?>">
                            <a href="<?php echo site_url('buy/add') ?>" id="add" class="btn btn-small">
                                <i class="icon-plus"> 添加</i>
                            </a>
                            <?php if ($status == 0) : ?>
                            <a href="javascript:;" class="btn btn-small" onclick="remove_buy()">
                                <i class="icon-minus"> 删除</i>
                            </a>
                            <?php endif ?>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <form method="GET" id="search-form" class="form-inline">
                        <div class="span5">
                            <label class="radio inline">
                                <input type="radio" name="status" id="status1" value="0" <?php if ($status == 0): ?>checked<?php endif; ?>>未结束
                            </label>
                            <label class="radio inline">
                                <input type="radio" name="status" id="status2" value="1" <?php if ($status == 1): ?>checked<?php endif; ?>>已结束
                            </label>
                            <label class="radio inline">
                                <input type="radio" name="status" id="status3" value="2" <?php if ($status == 2): ?>checked<?php endif; ?>>全部
                            </label>
                        </div>
                        <div class="span4">
                            <input type="hidden" id='status' name="status" value="<?php echo $status ?>">
<!--                            <input type="text" style="display:none">-->
                            <label class="pull-right">采购单编号: <input id="buynumber" name="buynumber" type="text" class="input-medium" placeholder="采购单编号查询" value="<?php echo $search ?>"></label>
                        </div>
                    </form>
                </div>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>采购单列表</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                         <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all""></th>
                                <th>采购单编号</th>
                                <th>创建时间</th>
                                <th>创建人</th>
                                <th>采购负责人</th>
                                <th>采购日期</th>
                                <th>备注</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($list)):?>
                                <?php foreach($list as $row):?>
                                <tr>
                                    <td><input type="checkbox" name="checkbox" value="<?php echo $row->id ?>"/></td>
                                    <td><a href="<?php echo site_url('buy/show?id='.$row->id) ?>"><?php echo $row->buynumber ?></a></td>
                                    <td><?php echo strtotime($row->createtime)?$row->createtime:'';  ?></td>
                                    <td><?php echo $row->createby ?></td>
                                    <td><?php echo $row->buyman ?></td>
                                    <td><?php echo strtotime($row->buydate)?$row->buydate:''; ?></td>
                                    <td><?php echo $row->remark ?></td>
                                    <td><?php echo ($row->status == 0)? '<font color="red">未结束</font>':'已结束' ?></td>
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
//                            $this->pagination->show();
                            ?>
                        </div>
                    </div>
                </div> <!-- /widget -->
            </div> <!-- /span9 -->
        </div> <!-- /row -->
    </div> <!-- /container -->
</div> <!-- /content -->


<?php $this->load->view("common/footer"); ?>

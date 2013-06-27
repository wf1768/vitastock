<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<script>

    $(function() {

    })

    function get_totalprice() {
        var totalprince = $('#cost').val()*$('#number').val();
        $('#totalprice').val(totalprince);
    }

    function get_standardtotalprice() {
        var standardtotalprice = $('#standardcost').val()*$('#number').val();
        $('#standardtotalprice').val(standardtotalprice);
    }

    function get_price() {
        var totalprince = $('#cost').val()*$('#number').val();
        $('#totalprice').val(totalprince);
        var standardtotalprice = $('#standardcost').val()*$('#number').val();
        $('#standardtotalprice').val(standardtotalprice);
    }



</script>


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
                    <a href="<?php echo site_url('buy') ?>" class="path-menu-a"> 采购管理</a> > <a href="<?php echo site_url('buy/buy_list') ?>" class="path-menu-a"> 采购单管理</a> > 添加采购商品
                </h1>

                <div class="row">
                    <div class="span9">
                        <form id="" method="post" action="<?php echo site_url('buy_product/doBuyProductAdd') ?>">
                            <input type="hidden" id="buyid" name="buyid" value="<?php echo $buyid ?>">
                            <div class="widget widget-table">
                                <div class="widget-header">
                                    <i class="icon-th-list"></i>

                                    <h3>添加采购商品</h3>
                                </div>
                                <!-- /widget-header -->
                                <div class="widget-content">
                                    <table id="apply_table" class="table table-striped table-bordered">
                                        <tbody>
                                        <tr>
                                            <td style="vertical-align:middle">商品名称</td>
                                            <td>
                                                <input type="text" class="span2" id="title" name="title" required style="margin-bottom: 0px;" placeholder="必须填写"
                                                       value="<?php echo set_value('title'); ?>"> *
                                            </td>
                                            <td style="vertical-align:middle">代码</td>
                                            <td><input type="text" class="span2" required
                                                       id="code" name="code" placeholder="必须填写" style="margin-bottom: 0px;"
                                                       value="<?php echo set_value('code') ?>"> *
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">商品描述</td>
                                            <td colspan="3">
                                                <input type="text" class="span5" id="memo" name="memo" required style="margin-bottom: 0px;" placeholder="必须填写"
                                                                   value="<?php echo set_value('memo'); ?>"> *
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle">单价</td>
                                            <td>
                                                <div class="input-prepend input-append" style="margin-bottom: 0px;">
                                                    <span class="add-on">€</span><input type="text" class="span2" onblur="get_totalprice()" onkeypress="return isfloat(event)" id="cost" name="cost" value="<?php echo set_value('cost',0); ?>"><span class="add-on">.00</span>
                                                </div>
                                            </td>
                                            <td style="vertical-align:middle">总价</td>
                                            <td>
                                                <div class="input-prepend input-append" style="margin-bottom: 0px;">
                                                    <span class="add-on">€</span><input type="text" class="span2" onkeypress="return isfloat(event)" id="totalprice" name="totalprice" value="<?php echo set_value('totalprice',0); ?>"><span class="add-on">.00</span>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="vertical-align:middle;width: 130px">标准单价</td>
                                            <td>
                                                <div class="input-prepend input-append" style="margin-bottom: 0px;">
                                                    <span class="add-on">€</span><input type="text" class="span2" onblur="get_standardtotalprice()" onkeypress="return isfloat(event)" id="standardcost" name="standardcost" value="<?php echo set_value('standardcost',0); ?>"><span class="add-on">.00</span>
                                                </div>
                                            </td>
                                            <td style="vertical-align:middle;width: 130px">标准总价</td>
                                            <td>
                                                <div class="input-prepend input-append" style="margin-bottom: 0px;">
                                                    <span class="add-on">€</span><input type="text" style="margin-bottom: 0px;" class="span2" onkeypress="return isfloat(event)" id="standardtotalprice" name="standardtotalprice" value="<?php echo set_value('standardtotalprice',0); ?>"><span class="add-on">.00</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle;width: 130px">售价</td>
                                            <td>
                                                <div class="input-prepend input-append" style="margin-bottom: 0px;">
                                                    <span class="add-on">￥</span><input type="text" class="span2" onkeypress="return isfloat(event)" value="<?php echo set_value('salesprice',0); ?>" id="salesprice" name="salesprice"><span class="add-on">.00</span>
                                                </div>
                                            </td>
                                            <td style="vertical-align:middle;width: 130px">厂家</td>
                                            <td>
                                                <select class="span2" id="factory" name="factory" style="margin-bottom: 0px;">
                                                    <?php if($factorys):?>
                                                        <?php foreach($factorys as $factory):?>
                                                            <option value="<?php echo $factory->id ?>" <?php echo set_select('factory', $factory->id ); ?> ><?php echo $factory->factoryname ?></option>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle;width: 130px">品牌</td>
                                            <td>
                                                <select class="span2" id="brand" name="brand" style="margin-bottom: 0px;">
                                                    <?php if($brands):?>
                                                        <?php foreach($brands as $brand):?>
                                                            <option value="<?php echo $brand->id ?>" <?php echo set_select('brand', $brand->id); ?> ><?php echo $brand->brandname ?></option>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                </select>
                                            </td>
                                            <td style="vertical-align:middle;width: 130px">类别</td>
                                            <td>
                                                <select class="span2" id="commoditytype" name="commoditytype" style="margin-bottom: 0px;">
                                                    <?php if($comtypes):?>
                                                        <?php foreach($comtypes as $type):?>
                                                            <option value="<?php echo $type->id ?>" <?php echo set_select('commoditytype', $type->id ); ?> ><?php echo $type->typename ?></option>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle;width: 130px">数量</td>
                                            <td>
                                                <input type="text" class="sp2" id="number" name="number" required onblur="get_price()" style="margin-bottom: 0px;" onkeypress="return isnumber(event)" value="<?php echo set_value('number',1); ?>">
                                            </td>
                                            <td style="vertical-align:middle;width: 130px">颜色</td>
                                            <td>
                                                <input type="text" class="sp3" id="color" name="color" style="margin-bottom: 0px;" value="<?php echo set_value('color'); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle;width: 130px">箱号</td>
                                            <td>
                                                <input type="text" class="sp3" id="boxno" name="boxno" style="margin-bottom: 0px;" value="<?php echo set_value('boxno'); ?>">
                                            </td>
                                            <td style="vertical-align:middle;width: 130px">件数</td>
                                            <td>
                                                <input type="text" class="sp3" id="itemnumber" name="itemnumber" style="margin-bottom: 0px;" value="<?php echo set_value('itemnumber'); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle;width: 130px">材质等级</td>
                                            <td>
                                                <input type="text" class="sp3" id="format" name="format" style="margin-bottom: 0px;" value="<?php echo set_value('format'); ?>">
                                            </td>
                                            <td style="vertical-align:middle;width: 130px">商品状态</td>
                                            <td>
                                                <select class="span2" id="status" name="status" style="margin-bottom: 0px;">
                                                    <option value="0" <?php echo set_select('status', '0', TRUE); ?>>未入库</option>
                                                </select>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="vertical-align:middle">备注</td>
                                            <td colspan="3">
                                                <input type="text" class="span5" id="remark" name="remark" style="margin-bottom: 0px;"
                                                       value="<?php echo set_value('remark'); ?>"> *
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <!-- /widget-content -->
                            </div>
                            <!-- /widget -->

                            <div class="row">
                                <div class="span9">
                                    <label class="pull-left">
                                        <button id="btnsave" type="submit" class="btn btn-primary">《 保存并返回列表</button>
                                        <a href="<?php echo site_url('buy/show?id='.$buyid) ?>" class="btn">返回</a>
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>  <!-- /span9 -->
                </div>  <!-- /row -->
            </div>  <!-- /span9 -->
        </div>  <!-- /row -->
    </div>  <!-- /container -->
</div> <!-- /content -->

<?php $this->load->view("common/footer"); ?>

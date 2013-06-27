<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<script>

    //获取条形码
    function get_barcode() {
        $.ajax({
            type:"post",
            data: "factoryid=" + $('#factory').val() + "&brandid=" + $('#brand').val() + '&commoditytypeid=' + $('#commoditytype').val(),
            url:"<?php echo site_url('barcode/get_uniqid')?>",
            success: function(data){
//                alert(data);
                $('#barcode').val(data);
                $('#barcode-image').attr('src',$('#path').val() + '/barcode/buildcode/BCGcode128/' + data);
            },

            error: function() {
                openalert("读取条形码数据出错，请重新尝试或与管理员联系。");
            }
        });
    }

    $(function() {

        $('#btn-submit').removeClass('disabled');

        $('#btn-submit').click(function(){
            $(this).addClass('disabled');
            $('#edit-stock').submit();
        })
    })

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
        <a href="<?php echo site_url('stock') ?>" class="path-menu-a"> 库存管理</a> > <a href="<?php echo site_url('stock/stock_pages?houseid='.$storehouseid.'&p='.$p.'&barcode='.$search) ?>" class="path-menu-a"> 商品管理</a> > 修改
    </h1>

    <div class="row">
        <div class="span9">
            <div class="widget">
                <div class="widget-header">
                    <h3>修改商品</h3>
                </div>
                <!-- /widget-header -->
                <div class="widget-content">
                    <div class="tabbable">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="account.html#1" data-toggle="tab">商品信息</a>
                            </li>
                        </ul>
                        <br>
                        <div class="tab-content">
                            <div class="tab-pane active" id="1">
                            <input type="hidden" id="path" value="<?php echo site_url('') ?>">
                                <form id="edit-stock" method="post" class="form-horizontal" action="">
                                    <fieldset>
                                        <div  class="control-group">
                                            <label class="control-label" for="storehouse">所属库房</label>
                                            <div class="controls">
                                                <select id="storehouse" name="storehouse" class="span2">
                                                    <?php if($houses):?>
                                                    <?php foreach($houses as $house):?>
                                                        <option value="<?php echo $house->id ?>" <?php echo set_select('storehouse',$house->id,($stock[0]->storehouseid == $house->id)) ?>><?php echo $house->storehousecode ?></option>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="title-group" class="control-group">
                                            <label class="control-label" for="title">商品名称</label>
                                            <div class="controls">
                                                <input type="text" class="span3" id="title" name="title" value="<?php echo set_value('title',(isset($stock[0]->title))? htmlspecialchars_decode($stock[0]->title):''); ?>"> *
                                                <?php
                                                    if (form_error('title', '<span class="help-inline">', '</span>')) {
                                                        echo '<script>';
                                                        echo '$("#title-group").addClass("error");';
                                                        echo '</script>';
                                                    }
                                                    echo form_error('title', '<span class="help-inline">', '</span>');
                                                ?>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <div id="code-group" class="control-group">
                                            <label class="control-label" for="code">代码</label>
                                            <div class="controls">
                                                <input type="text" class="span2" id="code" name="code" value="<?php echo set_value('code',(isset($stock[0]->code))?htmlspecialchars_decode($stock[0]->code):'' ); ?>"> *
                                                <?php
                                                    if (form_error('code', '<span class="help-inline">', '</span>')) {
                                                        echo '<script>';
                                                        echo '$("#code-group").addClass("error");';
                                                        echo '</script>';
                                                    }
                                                    echo form_error('code', '<span class="help-inline">', '</span>');
                                                ?>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="memo">商品描述</label>

                                            <div class="controls">
                                                <textarea rows="3" id="memo" name="memo" class="span3" style="width: 339px; height: 123px;"><?php echo set_value('memo',(isset($stock[0]->memo))?htmlspecialchars_decode($stock[0]->memo):''); ?></textarea>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <div id="cost-group" class="control-group">
                                            <label class="control-label" for="cost">单价</label>
                                            <div class="controls">
                                                <div class="input-prepend input-append">
                                                    <span class="add-on">€</span><input type="text" class="span2" id="cost" name="cost" onkeypress="return isfloat(event)" value="<?php echo set_value('cost',(isset($stock[0]->cost))?htmlspecialchars_decode($stock[0]->cost):0); ?>"><span class="add-on">.00</span>
                                                </div>
                                                <?php
                                                if (form_error('cost', '<span class="help-inline">', '</span>')) {
                                                    echo '<script>';
                                                    echo '$("#cost-group").addClass("error");';
                                                    echo '</script>';
                                                }
                                                echo form_error('cost', '<span class="help-inline">', '</span>');
                                                ?>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <div id="standardcost-group" class="control-group">
                                            <label class="control-label" for="standardcost">标准单价</label>
                                            <div class="controls">
                                                <div class="input-prepend input-append">
                                                    <span class="add-on">€</span><input type="text" class="span2" id="standardcost" name="standardcost" onkeypress="return isfloat(event)" value="<?php echo set_value('standardcost',(isset($stock[0]->standardcost))?htmlspecialchars_decode($stock[0]->standardcost):0); ?>"><span class="add-on">.00</span>
                                                </div>
                                                <?php
                                                if (form_error('standardcost', '<span class="help-inline">', '</span>')) {
                                                    echo '<script>';
                                                    echo '$("#standardcost-group").addClass("error");';
                                                    echo '</script>';
                                                }
                                                echo form_error('standardcost', '<span class="help-inline">', '</span>');
                                                ?>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <div id="salesprice-group" class="control-group">
                                            <label class="control-label" for="salesprice">售价</label>
                                            <div class="controls">
                                                <div class="input-prepend input-append">
                                                    <span class="add-on">￥</span><input type="text" class="span2" onkeypress="return isfloat(event)" value="<?php echo set_value('salesprice',(isset($stock[0]->salesprice))?htmlspecialchars_decode($stock[0]->salesprice):0); ?>" id="salesprice" name="salesprice"><span class="add-on">.00</span>
                                                </div>
                                                <?php
                                                if (form_error('salesprice', '<span class="help-inline">', '</span>')) {
                                                    echo '<script>';
                                                    echo '$("#salesprice-group").addClass("error");';
                                                    echo '</script>';
                                                }
                                                echo form_error('salesprice', '<span class="help-inline">', '</span>');
                                                ?>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="remark">备注</label>

                                            <div class="controls">
                                                <input type="text" class="span3" id="remark" name="remark" value="<?php echo set_value('remark',(isset($stock[0]->remark))?htmlspecialchars_decode($stock[0]->remark):''); ?>">
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="factory">厂家</label>
                                            <div class="controls">
                                                <select class="span2" id="factory" name="factory">
                                                <?php if($factorys):?>
                                                <?php foreach($factorys as $factory):?>
                                                    <option value="<?php echo $factory->id ?>" <?php echo set_select('factory', $factory->id,($stock[0]->factoryid == $factory->id)?true:false); ?> ><?php echo $factory->factoryname ?></option>
                                                    <?php endforeach;?>
                                                <?php endif;?>
                                                </select>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="brand">品牌</label>
                                            <div class="controls">
                                                <select class="span2" id="brand" name="brand">
                                                    <?php if($brands):?>
                                                    <?php foreach($brands as $brand):?>
                                                        <option value="<?php echo $brand->id ?>" <?php echo set_select('brand', $brand->id,($stock[0]->brandid == $brand->id)?true:false); ?> ><?php echo $brand->brandname ?></option>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                </select>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="commoditytype">商品类别</label>
                                            <div class="controls">
                                                <select class="span2" id="commoditytype" name="commoditytype">
                                                    <?php if($comtypes):?>
                                                    <?php foreach($comtypes as $type):?>
                                                        <option value="<?php echo $type->id ?>" <?php echo set_select('commoditytype', $type->id,($stock[0]->typeid == $type->id)?true:false); ?> ><?php echo $type->typename ?></option>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                </select>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <div id="barcode-group" class="control-group">
                                            <label class="control-label" for="barcode">条形码</label>
                                            <div class="controls">
                                                <input type="text" class="sp3"  id="barcode" name="barcode" value="<?php echo set_value('barcode',(isset($stock[0]->barcode))?htmlspecialchars_decode($stock[0]->barcode):''); ?>"> *
                                                <input type="button" class="btn  btn-small" value="生成条形码" onclick="get_barcode()">
                                                <?php
                                                if (form_error('barcode', '<span class="help-inline">', '</span>')) {
                                                    echo '<script>';
                                                    echo '$("#barcode-group").addClass("error");';
                                                    echo '</script>';
                                                }
                                                echo form_error('barcode', '<span class="help-inline">', '</span>');
                                                ?>
                                                <p class="help-block">
                                                    <img id="barcode-image" src="<?php echo site_url('barcode/buildcode/BCGcode128/'.$stock[0]->barcode) ?>" alt="Barcode Image" />
                                                </p>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <div id="number-group" class="control-group">
                                            <label class="control-label" for="number">数量</label>

                                            <div class="controls">
                                                <input type="text" class="sp2" id="number" name="number" value="<?php echo set_value('number',(isset($stock[0]->number))?$stock[0]->number:1); ?>">
                                                <?php
                                                if (form_error('number', '<span class="help-inline">', '</span>')) {
                                                    echo '<script>';
                                                    echo '$("#number-group").addClass("error");';
                                                    echo '</script>';
                                                }
                                                echo form_error('number', '<span class="help-inline">', '</span>');
                                                ?>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <div id="color-group" class="control-group">
                                            <label class="control-label" for="color">颜色</label>
                                            <div class="controls">
                                                <input type="text" class="sp3" id="color" name="color" value="<?php echo set_value('color',(isset($stock[0]->color)?htmlspecialchars_decode($stock[0]->color):'')); ?>">
<!--                                                <select class="span2" id="color" name="color">-->
<!--                                                    --><?php //if($colorlist):?>
<!--                                                    --><?php //foreach($colorlist as $color):?>
<!--                                                        <option value="--><?php //echo $color->id ?><!--" --><?php //echo set_select('color', $color->id,($stock[0]->color == $color->colorname)?true:false); ?><!-- >--><?php //echo $color->colorname ?><!--</option>-->
<!--                                                    --><?php //endforeach;?>
<!--                                                    --><?php //endif;?>
<!--                                                </select>-->
                                            </div>

                                        </div>
                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="format">规格</label>
                                            <div class="controls">
                                                <input type="text" class="sp3" id="format" name="format" value="<?php echo set_value('format',(isset($stock[0]->format)?htmlspecialchars_decode($stock[0]->format):'')); ?>">
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="boxno">箱号</label>
                                            <div class="controls">
                                                <input type="text" class="sp3" id="boxno" name="boxno" value="<?php echo set_value('boxno',(isset($stock[0]->boxno)?htmlspecialchars_decode($stock[0]->boxno):'')); ?>">
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="itemnumber">件数</label>
                                            <div class="controls">
                                                <input type="text" class="sp3" id="itemnumber" name="itemnumber" value="<?php echo set_value('itemnumber',(isset($stock[0]->itemnumber)?htmlspecialchars_decode($stock[0]->itemnumber):'')); ?>">
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="statuskey">商品状态</label>
                                            <div class="controls">
                                                <select class="span2" id="statuskey" name="statuskey">
                                                    <option value="1" <?php echo set_select('statuskey', '1', TRUE); ?>>在库</option>
                                                    <option value="2" <?php echo set_select('statuskey', '2'); ?>>已销售</option>
                                                </select>
                                            </div>
                                            <!-- /controls -->
                                        </div>
                                        <!-- /control-group -->
                                        <br/>
                                        <br/>
                                        <div class="form-actions">
                                            <input type="hidden" id="search" name="search" value="<?php echo $search ?>" />
                                            <button id="btn-submit" type="button" class="btn btn-primary">《 保存并返回列表</button>
                                            <?php if ($this->session->userdata('search_type') == '0') : ?>
                                            <a href="<?php echo site_url('stock/stock_pages?houseid='.$storehouseid.'&p='.$p.'&barcode='.$search) ?>" class="btn">返回</a>
                                            <?php endif ?>
                                            <?php if ($this->session->userdata('search_type') == '1') : ?>
                                                <a href="<?php echo site_url('stock/stock_pages?houseid='.$storehouseid.$this->session->userdata('super_search')) ?>" class="btn">返回</a>
                                            <?php endif ?>
                                        </div>
                                        <!-- /form-actions -->
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /widget-content -->
            </div>
            <!-- /widget -->

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

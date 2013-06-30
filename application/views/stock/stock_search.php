<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<script>

    $(function() {
        $("a[data-toggle=popover]").popover();
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
                    <a href="<?php echo site_url('stock') ?>" class="path-menu-a"> 库存管理</a> > 商品查询
                </h1>
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    查询提示：模糊查询，输入不完整的值，也可以查询。
                </div>
                <div class="minbox">
                    <div class="part_search">
                        <div class="navbar">
                            <div class="navbar-inner">
                                <form class="navbar-form" method="get" id="searchfrom" style="margin-bottom:5px">
                                    <font class="myfont">商品代码：</font>
                                    <input type="text" name="code"
                                           value="<?php echo isset($_REQUEST['code']) ? $_REQUEST['code'] : ''; ?>"
                                           placeholder="请输入代码">
                                    &nbsp;&nbsp;<font class="myfont">商品名称：</font>
                                    <input type="text" id="" name="title"
                                           value="<?php echo isset($_REQUEST['title']) ? $_REQUEST['title'] : ''; ?>"
                                           placeholder="请输入商品名称">
                                    <br/>
                                    <font class="myfont">商品厂家：</font>
                                    <select name="factorycode">
                                        <option value="">请选择</option>
                                        <?php foreach ($factory as $val): ?>
                                            <option value="<?php echo $val->factorycode; ?>"
                                                <?php if (isset($_REQUEST['factorycode']) && $_REQUEST['factorycode'] == $val->factorycode) {
                                                echo "selected";
                                            }?>><?php echo $val->factoryname;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    &nbsp;&nbsp;<font class="myfont">条形码：&nbsp;&nbsp;&nbsp;</font>
                                    <input type="text" name="barcode"
                                           value="<?php echo isset($_REQUEST['barcode']) ? $_REQUEST['barcode'] : ''; ?>"
                                           placeholder="请输入条形码">
                                    <br/>
                                    <font class="myfont">商品颜色：</font>
                                    <input type="text" name="color" id="color"
                                           value="<?php echo isset($_REQUEST['color']) ? $_REQUEST['color'] : ''; ?>"
                                           placeholder="请输入商品颜色">
                                    &nbsp;&nbsp;<font class="myfont">所属库房：</font>
                                    <select name="storehouseid">
                                        <option value="">请选择</option>
                                        <?php foreach ($storehouse as $house): ?>
                                            <option value="<?php echo $house->id; ?>"
                                                <?php if (isset($_REQUEST['storehouseid']) && $_REQUEST['storehouseid'] == $house->id) {
                                                echo "selected";
                                            }?>><?php echo $house->storehousecode;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <br/>
                                    <font class="myfont">商品类别：</font>
                                    <select name="typename">
                                        <option value="">请选择</option>
                                        <?php foreach ($type as $val): ?>
                                            <option value="<?php echo $val->typename; ?>"
                                                <?php if (isset($_REQUEST['typename']) && $_REQUEST['typename'] == $val->typename) {
                                                echo "selected";
                                            }?>><?php echo $val->typename;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    &nbsp;&nbsp;<font class="myfont">商品状态：</font>
                                    <select name="status" id="status">
                                        <option value="1000" <?php if ($status == '1000' ): ?>selected="true"<?php endif; ?>>全部</option>
                                        <option value="0" <?php if ($status == '0') { echo "selected"; }?> >未入库</option>
                                        <option value="1" <?php if ($status == '1') { echo "selected"; }?> >在库</option>
                                        <option value="3" <?php if ($status == '3') { echo "selected"; }?> >已销售</option>
                                        <option value="4" <?php if ($status == '4') { echo "selected"; }?> >已配送</option>
                                    </select>
                                    <br/>
                                    <font class="myfont">商品描述：</font>
                                    <input type="text" name="memo"
                                           value="<?php echo isset($_REQUEST['memo']) ? $_REQUEST['memo'] : ''; ?>"
                                           placeholder="请输入商品描述">
<!--                                    <br>-->
<!--                                    <font class="myfont">条形码：&nbsp;&nbsp;&nbsp;</font>-->
<!--                                    <input type="text" name="barcode"-->
<!--                                           value="--><?php //echo isset($_REQUEST['barcode']) ? $_REQUEST['barcode'] : ''; ?><!--"-->
<!--                                           placeholder="请输入条形码">-->
                                    <button style="margin-left:20px" id="search" type="submit" class="btn btn-primary">&nbsp;&nbsp;搜&nbsp;&nbsp;索&nbsp;&nbsp;</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget widget-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>商品列表</h3>
                    </div>
                    <!-- /widget-header -->
                    <div class="widget-content">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>缩略图</th>
                                <th>商品名称</th>
                                <th>代码</th>
                                <th>商品描述</th>
                                <th>厂家</th>
                                <th>品牌</th>
                                <th>类别</th>
                                <th>数量</th>
                                <th>颜色</th>
                                <th>条形码</th>
                                <th>库房</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($list)): ?>
                                <?php foreach ($list as $row): ?>
                                    <tr>
                                        <td><a href="javascript:;" data-html="true" data-trigger="hover"
                                               data-toggle="popover"
                                               data-content="<img src='<?php echo base_url($row->picpath) ?>' />">
                                               <img src="<?php echo base_url($row->picpath) ?>" alt="" class="thumbnail smallImg"/></a></td>
                                        <td>
                                            <a href="<?php echo site_url('stock_find/search_show?id='.$row->id) ?>"><?php echo $row->title ?></a>
                                        </td>
                                        <td title="<?php echo $row->code ?>"><?php echo Common::subStr($row->code, 0, 10) ?></td>
<!--                                        <td>--><?php //echo $row->code ?><!--</td>-->
                                        <td title="<?php echo $row->memo ?>"><?php echo Common::subStr($row->memo, 0, 10) ?></td>
                                        <td><?php echo $row->factoryname ?></td>
                                        <td><?php echo $row->brandname ?></td>
                                        <td><?php echo $row->typename ?></td>
                                        <td><?php echo $row->number ?></td>
                                        <td><?php echo $row->color ?></td>
                                        <td><?php echo $row->barcode ?></td>
                                        <td><?php
                                            foreach ($storehouse as $house) {
                                                if ($row->storehouseid == $house->id) {
                                                    echo $house->storehousecode;
                                                }
                                            }

                                            ?>
                                        </td>
                                        <td><?php echo $row->statusvalue ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /widget-content -->
                    <div class="row">
                        <div class="span4" style="margin-top:20px ">
                            <?php echo (isset($info)) ? $info : '' ?>
                        </div>
                        <div class=" pagination pagination-right">
                            <?php
                            echo (isset($page)) ? $page : '';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- /row -->
    </div> <!-- /container -->
</div> <!-- /content -->


<?php $this->load->view("common/footer"); ?>

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header");?>
<?php $this->load->view("common/topmenu");?>

<script>

    function print() {
        $(".my_show").jqprint({
            importCSS:true,
            debug:false
        });
    }

    $(function() {

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
                    <a href="<?php echo site_url('stock') ?>" class="path-menu-a"> 库存管理</a> > 批量打印条形码
                </h1>
                <span id="barcode_handle" >
                <div class="row">
                    <form method="post" id="" class="form-inline">
                        <div class="span6">
                        </div>
                        <div class="span3">
                            <label class="pull-right">
                            <a href="javascript:;" id="barcode_print_btn" name="barcode_print_btn" class="btn btn-primary" onclick="print()">
                                <i class="icon-barcode"> 打印</i>
                            </a>
                            <a href="<?php echo site_url($path) ?>" class="btn">返回</a>
                            </label>
                        </div>
                    </form>
                </div>
                <?php if (isset($list)) : ?>
                <div>
                <div class="my_show">
                    <?php foreach ($list as $row): ?>
                        <?php if ($row->barcode) :?>
                        <div style="page-break-after:always">
                            <table>
                                <tr>
                                    <td colspan="2"><img id="barcode-image" src="<?php echo (!empty($row->barcode)) ? site_url('barcode/buildcode/BCGcode128/'.$row->barcode):'' ?>"/></td>
                                </tr>
                                <tr>
                                    <td style="height: 5px"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="width: 2cm">条形码号:</td>
                                    <td><?php echo $row->barcode ?></td>
                                </tr>
                                <tr>
                                    <td>名称:</td>
                                    <td><?php echo $row->title ?></td>
                                </tr>
                                <tr>
                                    <td>代码:</td>
                                    <td><?php echo $row->code ?></td>
                                </tr>
                                <tr>
                                    <td>厂家:</td>
                                    <td><?php echo $row->factoryname ?></td>
                                </tr>
                                <tr>
                                    <td>件数:</td>
                                    <td><?php echo $row->itemnumber ?></td>
                                </tr>
                                <tr>
                                    <td>描述:</td>
                                    <td><?php echo $row->memo ?></td>
                                </tr>
                           </table>
                        </div>
                        <?php endif ?>
                    <?php endforeach; ?>
                    </div>
                </div>
                <?php endif ?>
            </div> <!-- /span9 -->
        </div> <!-- /row -->
    </div> <!-- /container -->
</div> <!-- /content -->


<?php $this->load->view("common/footer"); ?>

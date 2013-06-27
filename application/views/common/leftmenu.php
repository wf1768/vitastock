<div class="account-container">
    <div class="account-avatar">
        <img src="<?php echo base_url('upload/headshot/'.$this->account_info_lib->accountimage);?>" alt="" class="thumbnail" />
    </div> <!-- /account-avatar -->
    <div class="account-details">
        <span class="account-name"><?php echo $this->account_info_lib->accountname ?></span>
        <span class="account-role"><?php echo $this->account_info_lib->rolecode ?></span>
                            <span class="account-actions">
                                <a href="<?php echo site_url('account/sDataEdit') ?>">帐户设置</a> |
                                <a href="<?php echo site_url("login/logout") ?>">退出</a>
                            </span>
    </div> <!-- /account-details -->
</div> <!-- /account-container -->
<hr />
<ul id="main-nav" class="nav nav-tabs nav-stacked">

    <?php
        if ($fun_path == 'main') {
            echo '<li class="active"><a href="'.site_url("main").'"><i class="icon-home"></i>首页</a></li>';
        }
        else {
            echo '<li><a href="'.site_url("main").'"><i class="icon-home"></i>首页</a></li>';
        }

        //显示左侧菜单
        if ($leftmenu) {
            foreach ($leftmenu as $menu) {
                //显示左侧菜单有未处理的条目数量
                $active = '';
                if ($menu['funpath'] == 'storehouse_in') {
                    if ($num['storehouse_in'] > 0) {
                        $active = '<span class="label label-warning pull-right">'.$num['storehouse_in'].'</span>';
                    }
                }
                //财务的期货订单管理 待审核数量显示
                if ($menu['funpath'] == 'apply?stype=financial') {
                    if ($num['apply?stype=financial'] > 0) {
                        $active = '<span class="label label-warning pull-right">'.$num['apply?stype=financial'].'</span>';
                    }
                }
                //采购管理中期货订货  已审核数量显示
                if ($menu['funpath'] == 'apply?stype=apply') {
                    if ($num['apply?stype=apply'] > 0) {
                        $active = '<span class="label label-warning pull-right">'.$num['apply?stype=apply'].'</span>';
                    }
                }
                //采购管理中期货订货  已审核数量显示
                if ($menu['funpath'] == 'storehouse_move/handle') {
                    if ($num['storehouse_move/handle'] > 0) {
                        $active = '<span class="label label-warning pull-right">'.$num['storehouse_move/handle'].'</span>';
                    }
                }

                $result = '';
                if ($menu['funpath'] == $fun_path) {
                    $result = '<li class="active">';
                }
                else {
                    $result = '<li>';
                }
                $result = $result.'<a href="'.site_url($menu["funpath"]).'"><i class="'.$menu["funicon"].'"></i>'.$menu["funcode"];
                if (!empty($active)) {
                    $result = $result.$active.'</a></li>';
                }
                else {
                    $result = $result.'</a></li>';
                }
                echo $result;
            }

        }
    ?>

</ul>
<br />

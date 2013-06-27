<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="<?php echo site_url("main") ?>"><?php echo $sys_name ?></a>
            <div class="nav-collapse">
                <ul id="menu" class="nav pull-right">

                    <?php
                    if ($topmenu) {
                        echo '<li><a href="'.site_url("main").'">首页</a></li>';
                        foreach ($topmenu as $menu) {
                            echo '<li><a href="'.site_url($menu['funpath']).'">'.$menu['funcode'].'</a></li>';
                        }

                    }
                    else {

                    }

                    ?>
                </ul>
            </div> <!-- /nav-collapse -->
        </div> <!-- /container -->
    </div> <!-- /navbar-inner -->
</div> <!-- /navbar -->
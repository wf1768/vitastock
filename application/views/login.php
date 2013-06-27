<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("common/header"); ?>
<?php $this->load->view("common/topmenu"); ?>

<link href="<?php echo base_url("public/css/pages/login.css"); ?>" rel="stylesheet">

<div id="login-container">
    <div id="login-header">

        <h3 style="line-height: 27px;">登陆</h3>

    </div>
    <!-- /login-header -->

    <div id="login-content" class="clearfix">
        <form action="<?php echo base_url("index.php/login/accountLogin") ?>" method="post">

            <div class="control-group">
                <label class="control-label" for="accountcode">用户名:</label>

                <div class="controls">
                    <input type="text" id="accountcode" name="accountcode" value="admin" placeholder="登陆帐号" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="password">密码:</label>

                <div class="controls">
                    <input type="password" id="password" name="password" value="password" placeholder="登陆密码" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="verify">验证码:</label>

                <div class="controls input-append">
                    <input class="span2" style="width:249px" name="verify" placeholder="点击输入验证码" required id="verify"
                           type="text">
                    <img title="点击刷新" class="add-on" src="<?php echo site_url("login/getVerify"); ?>" alt="验证码"
                         id="verifyImg" style="height:35px;padding:0" class="img-polaroid">
                </div>

                <div class="pull-right">
                    <button type="submit" class="btn btn-warning btn-large">
                        登陆
                    </button>
                </div>

                <div id="remember-me" class="pull-left">
                    <p><?php echo (empty($login_error_msg)) ? '' : '<span style="color:red; ">' . $login_error_msg . '</span>'; ?></p>
                    <!--                <input type="checkbox" name="remember" id="remember" />-->
                    <!--                <label id="remember-label" for="remember">Remember Me</label>-->
                </div>


        </form>

    </div>
    <!-- /login-content -->


    <div id="login-extra">
        <p>如果您还没有帐户? <a href="javascript:;">点击申请.</a></p>
    </div>
    <!-- /login-extra -->

</div> <!-- /login-wrapper -->

<?php include("common/footer.php") ?>
<script type="text/javascript">
    function refreshCode() {
        var date = new Date();
        var ttime = date.getTime();
        var url = "<?php echo site_url("login/getVerify");?>";
        $('#verifyImg').show().attr('src', url + '/t/' + ttime);
    }
    $('#verifyImg').click(refreshCode);
//    $(document).ready(function () {
//        $(":input").filter(" [name='verify']").focus(refreshCode).click(refreshCode).blur(function () {
//            //	//$('#verifyImg').hide()
//        });
//    });
</script>


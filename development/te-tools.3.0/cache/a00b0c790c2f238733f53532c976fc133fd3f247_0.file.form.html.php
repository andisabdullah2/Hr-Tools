<?php
/* Smarty version 3.1.30, created on 2021-09-03 14:31:44
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\login\welcome\form.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6131cf60de2355_96195962',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a00b0c790c2f238733f53532c976fc133fd3f247' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\login\\welcome\\form.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_6131cf60de2355_96195962 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
    // jquery
    $(document).ready(function () {
        // ajax upload images
        $("#reload_captcha").click(function () {
            // ajax process
            $.ajax({
                url: '<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url("login/welcome/ajax_reload_captcha");?>
',
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $("#captcha_image").html();
                },
                success: function (data) {
                    $("#captcha_image").html(data.image);
                }
            });
        });
    });
<?php echo '</script'; ?>
>
<div class="py-5 text-center w-100">
    <div class="mx-auto w-xxl w-auto-xs">
        <div class="px-3">
            <!-- notification template -->
            <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            <!-- end of notification template-->
            <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('login/welcome/login_process');?>
" method="post" autocomplete="off">
                <div class="form-group">
                    <div class="input-group mb-2">
                        <div class="input-group-addon" style="width: 36px;">
                            <i class="fa fa-user"></i>
                        </div>
                        <input type="text" class="form-control" placeholder="Masukkan Username" name="username" maxlength="50" tabindex="1" required="required" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group mb-2">
                        <div class="input-group-addon" style="width: 36px;">
                            <i class="fa fa-key"></i>
                        </div>
                        <input type="password" class="form-control" placeholder="Masukkan Password" name="password" maxlength="50" tabindex="2" required="required" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-7">
                        <div class="form-group">
                            <div class="input-group mb-2">
                                <div class="input-group-addon" style="width: 36px;">
                                    <i class="fa fa-key"></i>
                                </div>
                                <input type="text" class="form-control" placeholder="Kode Captcha" name="captcha" maxlength="50" tabindex="2" required="required" />
                            </div>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="text-right" id="captcha_image">
                            <?php echo (($tmp = @$_smarty_tpl->tpl_vars['captcha']->value['image'])===null||$tmp==='' ? '' : $tmp);?>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-7"></div>
                    <div class="col-5">
                        <div class="text-right">
                            <a href="#" id="reload_captcha">
                                <i class="fa fa-refresh"></i> Reload Captcha
                            </a>
                        </div>
                    </div>
                </div>
                <br />
                <button type="submit" class="btn btn-lg btn-block primary">
                    <span class="fa fa-arrow-right" style="margin-right: 5px;"></span> Masuk
                </button>
            </form>
            <div class="my-4">
                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('login/welcome/lupa_password');?>
" class="text-danger"><i class="fa fa-lock m-r-5"></i> Lupa Password?</a>
            </div>
        </div>
    </div>
</div><?php }
}

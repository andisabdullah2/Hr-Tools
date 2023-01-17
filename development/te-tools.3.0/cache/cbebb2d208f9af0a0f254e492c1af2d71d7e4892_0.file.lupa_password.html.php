<?php
/* Smarty version 3.1.30, created on 2021-09-03 15:55:29
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\login\welcome\lupa_password.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6131e301c292b0_93171485',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cbebb2d208f9af0a0f254e492c1af2d71d7e4892' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\login\\welcome\\lupa_password.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_6131e301c292b0_93171485 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="py-5 text-center w-100">
    <div class="mx-auto w-xxl w-auto-xs">
        <div class="px-3">
            <div>
                <h5>Lupa password anda?</h5>
                <p class="text-muted my-3">
                    Masukkan email anda dibawah ini dan password anda akan direset lalu dikirimkan pada email tersebut.
                </p>
            </div>
            <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('login/welcome/lupa_password_process');?>
" method="post" autocomplete="off">
                <!-- notification template -->
                <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                <!-- end of notification template-->
                <div class="form-group">
                    <div class="input-group mb-2">
                        <div class="input-group-addon">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <input type="text" class="form-control" placeholder="Masukkan Email" name="user_mail" maxlength="50" tabindex="1" required="required" />
                    </div>   
                </div>
                <button type="submit" class="btn btn-lg btn-block warning p-x-md">Reset dan Kirim</button>
            </form>
            <div class="py-4">
                Kembali ke <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('login/welcome');?>
" class="text-danger _600">Halaman Masuk</a>
            </div> 
        </div>
    </div>
</div><?php }
}

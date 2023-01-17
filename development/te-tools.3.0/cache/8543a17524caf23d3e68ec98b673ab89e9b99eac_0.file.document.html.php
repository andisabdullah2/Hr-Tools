<?php
/* Smarty version 3.1.30, created on 2021-09-07 08:48:40
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\base\default\document.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6136c4f8f19bd8_45008188',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8543a17524caf23d3e68ec98b673ab89e9b99eac' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\base\\default\\document.html',
      1 => 1630978333,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/lazyload.config.html' => 1,
    'file:base/templates/theme.html' => 1,
  ),
),false)) {
function content_6136c4f8f19bd8_45008188 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
    <!-- head -->
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <title><?php echo (($tmp = @$_smarty_tpl->tpl_vars['page']->value['nav_title'])===null||$tmp==='' ? 'Selamat Datang' : $tmp);?>
 - <?php echo (($tmp = @$_smarty_tpl->tpl_vars['site']->value['site_title'])===null||$tmp==='' ? '' : $tmp);?>
</title>  
        <meta name='keywords' content="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['site']->value['meta_key'])===null||$tmp==='' ? '' : $tmp);?>
" />
        <meta name='description' content="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['site']->value['meta_desc'])===null||$tmp==='' ? '' : $tmp);?>
" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
        <meta name='robots' content='index,follow' />
        <meta name="mobile-web-app-capable" content="yes">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link href="<?php echo $_smarty_tpl->tpl_vars['BASEURL']->value;?>
resource/doc/images/icon/favicon.png" rel="SHORTCUT ICON" />
        <!-- themes style -->
        <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['THEMESPATH']->value;?>
" />
        <!-- other style -->
        <?php echo $_smarty_tpl->tpl_vars['LOAD_STYLE']->value;?>

        <!-- jQuery -->
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['BASEURL']->value;?>
resource/themes/default/plugins/jquery/dist/jquery.min.js"><?php echo '</script'; ?>
>
        <!-- Bootstrap -->
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['BASEURL']->value;?>
resource/themes/default/plugins/popper.js/dist/umd/popper.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['BASEURL']->value;?>
resource/themes/default/plugins/bootstrap/dist/js/bootstrap.min.js"><?php echo '</script'; ?>
>
        <!-- core -->
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['BASEURL']->value;?>
resource/themes/default/plugins/pace-progress/pace.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['BASEURL']->value;?>
resource/themes/default/plugins/scripts/nav.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['BASEURL']->value;?>
resource/themes/default/plugins/scripts/scrollto.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['BASEURL']->value;?>
resource/themes/default/plugins/scripts/toggleclass.js"><?php echo '</script'; ?>
>
        <!-- load javascript -->
        <?php echo $_smarty_tpl->tpl_vars['LOAD_JAVASCRIPT']->value;?>

        <!-- end of javascript  -->
    </head>
    <body>
        <header class="app-header primary theme box-shadow-y">
            <div class="navbar navbar-expand-lg">
                <a class="d-lg-none mx-2" data-toggle="modal" data-target="#aside">
                    <i class="fa fa-list fa-2x"></i>
                </a>
                <a href="#" class="navbar-brand">
                    <img src="<?php echo $_smarty_tpl->tpl_vars['BASEURL']->value;?>
resource/doc/images/icon/logote.png" alt="" />
                    <span class="hidden-folded d-inline">Time Excelindo</span>
                </a>
                <ul class="nav flex-row order-lg-2">
                    <li class="nav-item dropdown">
                        <a class="nav-link px-3" data-toggle="dropdown">
                            <span class="badge badge-pill up danger">6</span>
                            <i class="fa fa-car text-muted"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right w-md animate fadeIn mt-2 p-0">
                            <div class="scrollable hover" style="max-height: 250px">
                                <div class="list">
                                    <div class="list-item">
                                        <div class="list-body">
                                            <a href="#" class="item-title text-primary font-bold">DITANGUD</a>
                                            <div class="item-except text-sm text-muted h-1x">
                                                Jakarta
                                            </div>
                                        </div>
                                        <div>
                                            <span class="item-date text-xs text-muted">19/01/02</span>
                                        </div>
                                    </div>
                                    <div class="list-item">
                                        <div class="list-body">
                                            <a href="#" class="item-title text-primary font-bold">DITANGUD</a>
                                            <div class="item-except text-sm text-muted h-1x">
                                                Jakarta
                                            </div>
                                        </div>
                                        <div>
                                            <span class="item-date text-xs text-muted">19/01/02</span>
                                        </div>
                                    </div>
                                    <div class="list-item">
                                        <div class="list-body">
                                            <a href="#" class="item-title text-primary font-bold">DITANGUD</a>
                                            <div class="item-except text-sm text-muted h-1x">
                                                Jakarta
                                            </div>
                                        </div>
                                        <div>
                                            <span class="item-date text-xs text-muted">19/01/02</span>
                                        </div>
                                    </div>
                                    <div class="list-item">
                                        <div class="list-body">
                                            <a href="#" class="item-title text-primary font-bold">DITANGUD</a>
                                            <div class="item-except text-sm text-muted h-1x">
                                                Jakarta
                                            </div>
                                        </div>
                                        <div>
                                            <span class="item-date text-xs text-muted">19/01/02</span>
                                        </div>
                                    </div>
                                    <div class="list-item">
                                        <div class="list-body">
                                            <a href="#" class="item-title text-primary font-bold">DITANGUD</a>
                                            <div class="item-except text-sm text-muted h-1x">
                                                Jakarta
                                            </div>
                                        </div>
                                        <div>
                                            <span class="item-date text-xs text-muted">19/01/02</span>
                                        </div>
                                    </div>
                                    <div class="list-item">
                                        <div class="list-body">
                                            <a href="#" class="item-title text-primary font-bold">DITANGUD</a>
                                            <div class="item-except text-sm text-muted h-1x">
                                                Jakarta
                                            </div>
                                        </div>
                                        <div>
                                            <span class="item-date text-xs text-muted">19/01/02</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex px-3 py-2 b-t">
                                <div class="flex">
                                    <span>6 LPJ belum dilaporkan</span>
                                </div>
                                <a href="#" class="text-info">Lihat Semua&nbsp;&nbsp;<i class="fa fa-angle-right text-muted"></i></a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown d-flex align-items-center">
                        <a href="#" data-toggle="dropdown" class="d-flex align-items-center">
                            <span class="avatar w-32">
                                <img src="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['com_user']->value['user_img'])===null||$tmp==='' ? '' : $tmp);?>
" alt="" />
                            </span>
                            <span class="mx-2 d-none l-h-1x d-lg-block">
                                <span class="d-block"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['com_user']->value['nama_lengkap'])===null||$tmp==='' ? '-' : $tmp);?>
</span>
                                <span class="clearfix"></span>
                                <small class="text-muted d-block mt-1"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['com_user']->value['struktur_nama'])===null||$tmp==='' ? '-' : $tmp);?>
</small>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right w pt-0 mt-2 animate fadeIn">
                            <a class="dropdown-item" href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('home/welcome/profile');?>
">
                                <span>Profil Saya</span>
                            </a>
                            <a class="dropdown-item" href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('home/welcome/profile/account_settings');?>
">
                                <span>Ganti Password</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="alert('Halah, ndak perlu dibantu juga nanti bisa sendiri. Hehehee');">
                                Butuh bantuan?
                            </a>
                            <a class="dropdown-item" href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('login/welcome/logout_process');?>
">Sign out</a>
                        </div>
                    </li>
                </ul>
                <div class="collapse navbar-collapse  order-lg-1" id="navbarToggler"></div>
            </div>
        </header>
        <div class="app" id="app">
            <div id="aside" class="app-aside fade box-shadow-x nav-expand white" aria-hidden="true">
                <?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['template_sidebar']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

            </div>
            <div id="content" class="app-content box-shadow-0" role="main">
                <div class="content-main" id="content-main">
                    <div class="padding">
                        <?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['template_content']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
 
                    </div>
                </div>
                <!-- Footer -->
                <div class="content-footer white " id="content-footer">
                    <div class="d-flex p-3">
                        <span class="text-sm text-muted flex">&copy; Copyright 2018 Development Team. PT. Time Excelindo</span>
                        <div class="text-sm text-muted">Version 3.0</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Lazy load -->
        <?php $_smarty_tpl->_subTemplateRender("file:base/templates/lazyload.config.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
 
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['BASEURL']->value;?>
resource/themes/default/plugins/scripts/lazyload.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['BASEURL']->value;?>
resource/themes/default/plugins/scripts/plugin.js"><?php echo '</script'; ?>
>
        <?php $_smarty_tpl->_subTemplateRender("file:base/templates/theme.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['BASEURL']->value;?>
resource/themes/default/plugins/scripts/ajax.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['BASEURL']->value;?>
resource/themes/default/plugins/scripts/app.js"><?php echo '</script'; ?>
>
    </body>
</html> <?php }
}

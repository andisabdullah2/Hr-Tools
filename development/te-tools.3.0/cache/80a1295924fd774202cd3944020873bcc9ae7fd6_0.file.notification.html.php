<?php
<<<<<<< HEAD
/* Smarty version 3.1.30, created on 2021-09-03 14:31:44
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\base\templates\notification.html" */
=======
<<<<<<< HEAD:cache/a425e1049bd23bf487977a115edeb279ff8a993d_0.file.notification.html.php
/* Smarty version 3.1.30, created on 2021-09-08 10:53:51
  from "F:\BAYU\KODINGAN\Laravel\Project Saya\te-tools\application\views\base\templates\notification.html" */
=======
/* Smarty version 3.1.30, created on 2021-09-03 14:31:44
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\base\templates\notification.html" */
>>>>>>> amalia_dev:development/te-tools.3.0/cache/80a1295924fd774202cd3944020873bcc9ae7fd6_0.file.notification.html.php
>>>>>>> 8f8bde41a5bd67febd5a1be3b8e4906159eacf47

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
<<<<<<< HEAD
=======
<<<<<<< HEAD:cache/a425e1049bd23bf487977a115edeb279ff8a993d_0.file.notification.html.php
  'unifunc' => 'content_613833cf6b2716_93554068',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a425e1049bd23bf487977a115edeb279ff8a993d' => 
    array (
      0 => 'F:\\BAYU\\KODINGAN\\Laravel\\Project Saya\\te-tools\\application\\views\\base\\templates\\notification.html',
      1 => 1631072167,
=======
>>>>>>> 8f8bde41a5bd67febd5a1be3b8e4906159eacf47
  'unifunc' => 'content_6131cf60eee419_94082488',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '80a1295924fd774202cd3944020873bcc9ae7fd6' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\base\\templates\\notification.html',
      1 => 1630653434,
<<<<<<< HEAD
=======
>>>>>>> amalia_dev:development/te-tools.3.0/cache/80a1295924fd774202cd3944020873bcc9ae7fd6_0.file.notification.html.php
>>>>>>> 8f8bde41a5bd67febd5a1be3b8e4906159eacf47
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
<<<<<<< HEAD
function content_6131cf60eee419_94082488 (Smarty_Internal_Template $_smarty_tpl) {
=======
<<<<<<< HEAD:cache/a425e1049bd23bf487977a115edeb279ff8a993d_0.file.notification.html.php
function content_613833cf6b2716_93554068 (Smarty_Internal_Template $_smarty_tpl) {
=======
function content_6131cf60eee419_94082488 (Smarty_Internal_Template $_smarty_tpl) {
>>>>>>> amalia_dev:development/te-tools.3.0/cache/80a1295924fd774202cd3944020873bcc9ae7fd6_0.file.notification.html.php
>>>>>>> 8f8bde41a5bd67febd5a1be3b8e4906159eacf47
?>
<div class="row">
    <div class="col-md-12">
        <?php if ((($tmp = @$_smarty_tpl->tpl_vars['notification_header']->value)===null||$tmp==='' ? '' : $tmp) == "error") {?>
        <div class="alert alert-danger alert-dismissible text-left">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h6><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['notification_header']->value, 'UTF-8');?>
</h6>
            <?php echo $_smarty_tpl->tpl_vars['notification_message']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['notification_error']->value;?>

        </div>
        <?php } elseif ((($tmp = @$_smarty_tpl->tpl_vars['notification_header']->value)===null||$tmp==='' ? '' : $tmp) == "warning") {?> 
        <div class="alert alert-warning alert-dismissible text-left">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h6><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['notification_header']->value, 'UTF-8');?>
</h6>
            <?php echo $_smarty_tpl->tpl_vars['notification_message']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['notification_error']->value;?>

        </div>
        <?php } elseif ((($tmp = @$_smarty_tpl->tpl_vars['notification_header']->value)===null||$tmp==='' ? '' : $tmp) == "success") {?>
        <div class="alert alert-success alert-dismissible text-left">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h6><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['notification_header']->value, 'UTF-8');?>
</h6>
            <?php echo $_smarty_tpl->tpl_vars['notification_message']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['notification_error']->value;?>

        </div>
        <?php } elseif ((($tmp = @$_smarty_tpl->tpl_vars['notification_header']->value)===null||$tmp==='' ? '' : $tmp) == "info") {?>
        <div class="alert alert-info alert-dismissible text-left">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h6><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['notification_header']->value, 'UTF-8');?>
</h6>
            <?php echo $_smarty_tpl->tpl_vars['notification_message']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['notification_error']->value;?>

        </div>
        <?php } else { ?>
        <?php }?> 
    </div>
</div><?php }
}

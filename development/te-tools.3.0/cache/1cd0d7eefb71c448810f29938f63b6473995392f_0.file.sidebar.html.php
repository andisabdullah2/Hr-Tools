<?php
/* Smarty version 3.1.30, created on 2021-09-06 12:14:05
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\base\default\sidebar.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6135a39d0100c2_12555172',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1cd0d7eefb71c448810f29938f63b6473995392f' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\base\\default\\sidebar.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6135a39d0100c2_12555172 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="sidenav modal-dialog dk">
    <div class="navbar lt bg-white text-dark">
        <a href="#" class="navbar-brand">
            <span class="hidden-folded d-inline" style="font-size: 16px;">
                <?php echo $_smarty_tpl->tpl_vars['hari_ini']->value['hari'];?>
, <?php echo $_smarty_tpl->tpl_vars['hari_ini']->value['tanggal'];?>
 <?php echo $_smarty_tpl->tpl_vars['hari_ini']->value['bulan'];?>
 <?php echo $_smarty_tpl->tpl_vars['hari_ini']->value['tahun'];?>

            </span>
        </a>
    </div>
    <div class="nav-fold px-2 mt-1">
        <div class="hidden-folded flex p-2">
            <div class="d-flex nav nav-tabs">
                <?php echo (($tmp = @$_smarty_tpl->tpl_vars['list_top_nav']->value)===null||$tmp==='' ? '' : $tmp);?>

            </div>
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-content">
            <?php echo (($tmp = @$_smarty_tpl->tpl_vars['list_sidebar_nav']->value)===null||$tmp==='' ? '' : $tmp);?>

        </div>
    </div>
</div><?php }
}

<?php
<<<<<<< HEAD:cache/9afce2bc5dda210579d06a17b2271951c332dfad_0.file.index.html.php
/* Smarty version 3.1.30, created on 2021-09-09 14:16:18
  from "C:\xampp\htdocs\te-tools\application\views\settings\sistem\menu\index.html" */
=======
/* Smarty version 3.1.30, created on 2021-09-07 16:18:19
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\settings\sistem\menu\index.html" */
>>>>>>> amalia_dev:development/te-tools.3.0/cache/9529b00cbbffc2a3801197af717f85164913071c_0.file.index.html.php

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
<<<<<<< HEAD:cache/9afce2bc5dda210579d06a17b2271951c332dfad_0.file.index.html.php
  'unifunc' => 'content_6139b4c2463629_80136140',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9afce2bc5dda210579d06a17b2271951c332dfad' => 
    array (
      0 => 'C:\\xampp\\htdocs\\te-tools\\application\\views\\settings\\sistem\\menu\\index.html',
      1 => 1631071462,
=======
  'unifunc' => 'content_61372e5b1a2b91_70042581',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9529b00cbbffc2a3801197af717f85164913071c' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\settings\\sistem\\menu\\index.html',
      1 => 1630653434,
>>>>>>> amalia_dev:development/te-tools.3.0/cache/9529b00cbbffc2a3801197af717f85164913071c_0.file.index.html.php
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
<<<<<<< HEAD:cache/9afce2bc5dda210579d06a17b2271951c332dfad_0.file.index.html.php
function content_6139b4c2463629_80136140 (Smarty_Internal_Template $_smarty_tpl) {
=======
function content_61372e5b1a2b91_70042581 (Smarty_Internal_Template $_smarty_tpl) {
>>>>>>> amalia_dev:development/te-tools.3.0/cache/9529b00cbbffc2a3801197af717f85164913071c_0.file.index.html.php
echo '<script'; ?>
 type="text/javascript">
    $(document).ready(function () {
        $(".select-2").select2();
    });
<?php echo '</script'; ?>
>

<!-- Content Header (Page header) -->
<section class="content-header">
    <!-- <h1>Navigation</h1> -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
        <li class="breadcrumb-item active">Navigation</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Web Portal</h4>
                </div>
				<div class="box-divider m-0"></div>
                <div class="box-body">
                    <!-- notification template -->
                    <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                    <!-- end of notification template-->
					<table class="table table-bordered">
							<tr>
                                <th width='10%' class="text-center">ID</th>
                                <th width='30%' class="text-center">Nama Portal</th>
                                <th width='40%' class="text-center">Judul</th>
                                <th width='15%' class="text-center">Jumlah Menu</th>
                                <th width='5%' class="text-center"></th>
                            </tr>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_id']->value, 'result');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['result']->value) {
?>
                            <tr>
                                <td class="text-center"><?php echo $_smarty_tpl->tpl_vars['result']->value['portal_id'];?>
</td>
                                <td><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['portal_nm'], 'UTF-8');?>
</td>
                                <td><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['site_title'], 'UTF-8');?>
</td>
                                <td class="text-center"><?php echo $_smarty_tpl->tpl_vars['result']->value['total_menu'];?>
</td>
                                <td class="text-center">
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('settings/sistem/menu/navigation/').($_smarty_tpl->tpl_vars['result']->value['portal_id']));?>
" class="btn btn-xs white text-success"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                            <?php
}
} else {
?>

                            <tr>
                                <td colspan="5">Data tidak ditemukan!</td>
                            </tr>
                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                    </table>
                </div>
            </div>
        </div>
    </div>
</section>


<?php }
}

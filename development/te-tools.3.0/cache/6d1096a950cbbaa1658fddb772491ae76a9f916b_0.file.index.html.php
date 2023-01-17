<?php
<<<<<<< HEAD:cache/0eab9ad5dc1b65bb5391a88db3a720a75c6c60fc_0.file.index.html.php
/* Smarty version 3.1.30, created on 2021-09-09 13:53:20
  from "C:\xampp\htdocs\te-tools\application\views\settings\sistem\roles\index.html" */
=======
/* Smarty version 3.1.30, created on 2021-09-07 15:59:17
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\settings\sistem\roles\index.html" */
>>>>>>> amalia_dev:development/te-tools.3.0/cache/6d1096a950cbbaa1658fddb772491ae76a9f916b_0.file.index.html.php

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
<<<<<<< HEAD:cache/0eab9ad5dc1b65bb5391a88db3a720a75c6c60fc_0.file.index.html.php
  'unifunc' => 'content_6139af60a66e11_00005103',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0eab9ad5dc1b65bb5391a88db3a720a75c6c60fc' => 
    array (
      0 => 'C:\\xampp\\htdocs\\te-tools\\application\\views\\settings\\sistem\\roles\\index.html',
      1 => 1631071462,
=======
  'unifunc' => 'content_613729e51e3df5_82127438',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6d1096a950cbbaa1658fddb772491ae76a9f916b' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\settings\\sistem\\roles\\index.html',
      1 => 1630653434,
>>>>>>> amalia_dev:development/te-tools.3.0/cache/6d1096a950cbbaa1658fddb772491ae76a9f916b_0.file.index.html.php
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
<<<<<<< HEAD:cache/0eab9ad5dc1b65bb5391a88db3a720a75c6c60fc_0.file.index.html.php
function content_6139af60a66e11_00005103 (Smarty_Internal_Template $_smarty_tpl) {
=======
function content_613729e51e3df5_82127438 (Smarty_Internal_Template $_smarty_tpl) {
>>>>>>> amalia_dev:development/te-tools.3.0/cache/6d1096a950cbbaa1658fddb772491ae76a9f916b_0.file.index.html.php
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
        <li class="breadcrumb-item active">Roles</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Daftar Roles</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('settings/sistem/roles/add/');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-plus"></i> Tambah Data</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-search">
                    <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('settings/sistem/roles/search_process');?>
" method="post">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="role_nm" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['role_nm'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="Cari nama hak akses" maxlength="50">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="group_id" id="select2-single" data-plugin="select2" class="form-control" data-placeholder="Berdasarkan Groups Role">
                                        <option value=""></option>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_group']->value, 'group');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['group']->value) {
?>
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['group']->value['group_id'];?>
" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['search']->value['group_id'])===null||$tmp==='' ? '' : $tmp) == $_smarty_tpl->tpl_vars['group']->value['group_id']) {?>selected="selected"<?php }?>><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['group']->value['group_name'], 'UTF-8');?>
</option>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="submit" class="btn dark" name="save" value="Cari"><i class="fa fa-search"></i> Cari</button>
                                    <button type="submit" class="btn white" name="save" value="Reset"><i class="fa fa-refresh"></i> Reset</button>
                                </div>								
                            </div>
                        </div>
                    </form>
                </div>
                <div class="box-body">
                    <!-- notification template -->
                    <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                    <!-- end of notification template-->
                    <table class="table table-bordered">
                        <tr>
                            <th width='10%' class="text-center">ID</th>
                            <th width='20%' class="text-center">Group</th>
                            <th width='20%' class="text-center">Role Name</th>
                            <th width='25%' class="text-center">Role Description</th>
                            <th width='15%' class="text-center">Default Page</th>
                            <th width='10%' class="text-center"></th>
                        </tr>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_id']->value, 'result');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['result']->value) {
?>
                        <tr>
                            <td class="text-center"><?php echo $_smarty_tpl->tpl_vars['result']->value['role_id'];?>
</td>
                            <td><?php echo $_smarty_tpl->tpl_vars['result']->value['group_name'];?>
</td>
                            <td><?php echo $_smarty_tpl->tpl_vars['result']->value['role_nm'];?>
</td>
                            <td><?php echo $_smarty_tpl->tpl_vars['result']->value['role_desc'];?>
</td>
                            <td><?php echo $_smarty_tpl->tpl_vars['result']->value['default_page'];?>
</td>
                            <td class="text-center">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('settings/sistem/roles/edit/').($_smarty_tpl->tpl_vars['result']->value['role_id']));?>
" class="btn btn-xs white text-success"><i class="fa fa-edit"></i></a>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('settings/sistem/roles/delete/').($_smarty_tpl->tpl_vars['result']->value['role_id']));?>
" class="btn btn-xs white text-danger"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php
}
} else {
?>

                        <tr>
                            <td colspan="6">Data tidak ditemukan!</td>
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

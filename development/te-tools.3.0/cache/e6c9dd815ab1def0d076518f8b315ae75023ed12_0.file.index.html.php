<?php
/* Smarty version 3.1.30, created on 2021-09-07 16:33:23
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\settings\sistem\permissions\index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_613731e3084654_55491294',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e6c9dd815ab1def0d076518f8b315ae75023ed12' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\settings\\sistem\\permissions\\index.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_613731e3084654_55491294 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
$(document).ready(function() {
    $(".select-2").select2();
});
<?php echo '</script'; ?>
>
<!-- Content Header (Page header) -->
<section class="content-header">
    <!-- <h1>Permission </h1> -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
        <li class="breadcrumb-item active">Permissions</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">List Roles</h4>
                </div>
                <div class="box-search clearfix">
                    <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('settings/sistem/permissions/search_process');?>
" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <input type="text" name="role_nm" class="form-control" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['role_nm'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="Cari nama hak akses" maxlength="50" />
                                    </div>
                                    <div class="col-md-3">
                                        <!-- <select name="group_id" class="select-2 form-control" data-placeholder="Berdasarkan Groups Role" style="width: 100%"> -->
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
                                    <div class="col-md-4">
                                        <button class="btn  btn-dark" type="submit" name="save" value="Cari"><i class="fa fa-search "></i>&nbsp; Cari</button>
                                        <button class="btn  btn-default" type="submit" name="save" value="Reset"><i class="fa fa-refresh "></i>&nbsp; Reset</button>
                                    </div>
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
								<a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('settings/sistem/permissions/access_update/').($_smarty_tpl->tpl_vars['result']->value['role_id']));?>
"  class="btn btn-xs white text-success"><i class="fa fa-edit"></i></a>
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

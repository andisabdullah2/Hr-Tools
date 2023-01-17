<?php
/* Smarty version 3.1.30, created on 2021-09-07 16:16:37
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\settings\sistem\roles\add.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_61372df518cd90_46060257',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1657e0cc8b6c567379f03cca3606d2c7b18eca75' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\settings\\sistem\\roles\\add.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_61372df518cd90_46060257 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
    $(document).ready(function() {
        $(".select-2").select2();
    });
<?php echo '</script'; ?>
>
<!-- Content Header (Page header) -->
<section class="content-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('settings/sistem/roles/');?>
">Roles</a></li>
        <li class="breadcrumb-item active">Tambah Data</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <!-- Basic Forms -->
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Tambah Data Role</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('settings/sistem/roles/');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-divider m-0"></div>
                <!-- /.box-header -->
                <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('settings/sistem/roles/add_process');?>
"  method="post" class="form-horizontal">
                    <div class="box-body">
                        <!-- notification template -->
                        <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                        <!-- end of notification template-->
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Group</label>
                            <div class="col-md-7">
                                <select name="group_id" id="select2-single" data-plugin="select2" data-placeholder="Select Group" class="form-control">
                                    <option value=""></option>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_group']->value, 'group');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['group']->value) {
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['group']->value['group_id'];?>
" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['result']->value['group_id'])===null||$tmp==='' ? '' : $tmp) == $_smarty_tpl->tpl_vars['group']->value['group_id']) {?>selected="selected"<?php }?>><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['group']->value['group_name'], 'UTF-8');?>
</option>
                                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                                </select>
                                <small class="help-block text-danger">*wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Role Name</label>
                            <div class="col-md-7">
                                <input type="text" name="role_nm" maxlength="100" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['role_nm'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" />
                                <small class="help-block text-danger">*wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Role Description</label>
                            <div class="col-md-7">
                                <input type="text" name="role_desc" maxlength="100" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['role_desc'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" />
                                <small class="help-block text-danger">*wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Default Page</label>
                            <div class="col-md-7">
                                <input type="text" name="default_page" maxlength="50" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['default_page'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" />
                                <small class="help-block text-danger">*wajib diisi</small>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="form-group row">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-7">
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Simpan</button>
                                <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Reset</button>
                            </div>                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php }
}

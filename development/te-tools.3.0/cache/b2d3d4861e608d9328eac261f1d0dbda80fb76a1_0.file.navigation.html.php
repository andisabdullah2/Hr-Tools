<?php
/* Smarty version 3.1.30, created on 2021-09-07 16:18:34
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\settings\sistem\menu\navigation.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_61372e6a769b29_35888220',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b2d3d4861e608d9328eac261f1d0dbda80fb76a1' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\settings\\sistem\\menu\\navigation.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_61372e6a769b29_35888220 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <!-- <h1>Navigation</h1> -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('settings/sistem/menu/');?>
">Navigation</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('settings/sistem/menu/navigation/').($_smarty_tpl->tpl_vars['portal']->value['portal_id']));?>
"><?php echo (($tmp = @mb_strtoupper($_smarty_tpl->tpl_vars['portal']->value['portal_nm'], 'UTF-8'))===null||$tmp==='' ? '' : $tmp);?>
</a></li>
        <li class="breadcrumb-item active">Edit Data</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">List Navigation</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('settings/sistem/menu/add/').($_smarty_tpl->tpl_vars['portal']->value['portal_id']));?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-plus"></i> Tambah Data</a>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('settings/sistem/menu/');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </li>
                        </ul>
                    </div>
                </div>			
                <div class="box-search clearfix">
                    <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('settings/sistem/menu/search_process/').($_smarty_tpl->tpl_vars['portal']->value['portal_id']));?>
" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <!-- <select name="parent_id" class="form-control select-2" data-placeholder="Parent Menu"> -->
                                        <select name="parent_id" id="select2-single" data-plugin="select2" class="form-control" data-placeholder="Parent Menu">
                                            <option value=""></option>
                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_parent']->value, 'data');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
?>
                                            <option value="<?php echo $_smarty_tpl->tpl_vars['data']->value['nav_id'];?>
" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['search']->value['parent_id'])===null||$tmp==='' ? '' : $tmp) == $_smarty_tpl->tpl_vars['data']->value['nav_id']) {?>selected="selected"<?php }?>><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['data']->value['nav_title'], 'UTF-8');?>
</option>
                                            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn  btn-dark" type="submit" name="save" value="Cari"><i class="fa fa-search"></i>&nbsp; Cari</button>
                                        <button class="btn  btn-default" type="submit" name="save" value="Reset"><i class="fa fa-refresh"></i>&nbsp; Reset</button>
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
                            <th width='5%' class="text-center"></th>
                            <th width='35%' class="text-center">Judul Menu</th>
                            <th width='30%' class="text-center">Alamat</th>
                            <th width='10%' class="text-center">Digunakan</th>
                            <th width='10%' class="text-center">Ditampilkan</th>
                            <th width='10%' class="text-center"></th>
                        </tr>
                        <?php echo (($tmp = @$_smarty_tpl->tpl_vars['html']->value)===null||$tmp==='' ? '<tr><td colspan="6">Data tidak ditemukan!</td></tr>' : $tmp);?>

                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<?php }
}

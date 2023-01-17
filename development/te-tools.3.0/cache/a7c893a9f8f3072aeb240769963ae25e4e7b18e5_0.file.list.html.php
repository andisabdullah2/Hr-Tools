<?php
/* Smarty version 3.1.30, created on 2021-09-07 09:12:38
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\project\master\perusahaan\list.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6136ca96cd8489_42463400',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a7c893a9f8f3072aeb240769963ae25e4e7b18e5' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\project\\master\\perusahaan\\list.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_6136ca96cd8489_42463400 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <!-- </h1> -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-Projects</a></li>
        <li class="breadcrumb-item active"><a href="#">Bendera / Perusahaan</a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Data Perusahaan</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/master/perusahaan/add/');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-plus"></i> Tambah Data</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-divider m-0"></div>
                <div class="box-body">
                    <!-- notification template -->
                    <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                    <!-- end of notification template-->
                    <table class="table table-bordered">
                        <tr class="text-center">
                            <th width="4%">No</th>
                            <th width="25%">Nama Perusahaan</th>
                            <th width="25%">Alamat</th>
                            <th width="15%">Email</th>
                            <th width="10%">Telepon</th>
                            <th width="11%"></th>
                        </tr>
                        <?php $_smarty_tpl->_assignInScope('no', 1);
?>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_id']->value, 'result');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['result']->value) {
?>
                        <tr>
                            <td align="center"><?php echo $_smarty_tpl->tpl_vars['no']->value++;?>
.</td>
                            <td><?php echo (($tmp = @mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['perusahaan_nama'], 'UTF-8'))===null||$tmp==='' ? '' : $tmp);?>
</td>
                            <td><?php echo (($tmp = @mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['perusahaan_alamat'], 'UTF-8'))===null||$tmp==='' ? '' : $tmp);?>
 <?php echo (($tmp = @mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['perusahaan_kota'], 'UTF-8'))===null||$tmp==='' ? '' : $tmp);?>
 <?php echo (($tmp = @mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['perusahaan_propinsi'], 'UTF-8'))===null||$tmp==='' ? '' : $tmp);?>
</td>
                            <td><?php echo (($tmp = @mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['perusahaan_email'], 'UTF-8'))===null||$tmp==='' ? '' : $tmp);?>
</td>
                            <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['perusahaan_telepon'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                            <td align="center">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url((($tmp = @('project/master/perusahaan/edit/').($_smarty_tpl->tpl_vars['result']->value['perusahaan_id']))===null||$tmp==='' ? '' : $tmp));?>
" class="btn btn-xs white text-success"><i class="fa fa-edit"></i></a>                            
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url((($tmp = @('project/master/perusahaan/delete/').($_smarty_tpl->tpl_vars['result']->value['perusahaan_id']))===null||$tmp==='' ? '' : $tmp));?>
" class="btn btn-xs white text-danger"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php
}
} else {
?>

                        <tr>
                            <td colspan="6">Data not found!</td>
                        </tr>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                    </table>
                </div>
                <div class="box-body pt-0">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="pagination">
                                <li>Menampilkan <?php echo (($tmp = @$_smarty_tpl->tpl_vars['total']->value)===null||$tmp==='' ? 0 : $tmp);?>
 data</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php }
}

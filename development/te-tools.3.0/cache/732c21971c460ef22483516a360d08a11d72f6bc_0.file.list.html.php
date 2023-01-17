<?php
/* Smarty version 3.1.30, created on 2021-09-07 16:38:53
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\kepegawaian\master\jadwal_kerja\list.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6137332d449f11_30021462',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '732c21971c460ef22483516a360d08a11d72f6bc' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\kepegawaian\\master\\jadwal_kerja\\list.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_6137332d449f11_30021462 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-HR</a></li>
        <li class="breadcrumb-item"><a href="#">Data Pendukung</a></li>
        <li class="breadcrumb-item active">Jadwal Kerja</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Data Jadwal Kerja</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/jadwal_kerja/add/');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-plus"></i> Tambah Data</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-body">
                    <!-- notification template -->
                    <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                    <!-- end of notification template-->
                    <table class="table table-bordered">
                        <tr class="text-center">
                            <th width="4%">No</th>
                            <th>Tahun</th>
                            <th>Nama Jadwal</th>
                            <th>Status</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th width="8%"></th>
                        </tr>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_id']->value, 'result');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['result']->value) {
?>
                        <?php $_smarty_tpl->_assignInScope('total_data_presented', $_smarty_tpl->tpl_vars['total_data_presented']->value+1);
?>
                        <tr <?php if (($_smarty_tpl->tpl_vars['no']->value%2) <> 1) {?>class="blink-row"<?php }?>>
                            <td align="center"><?php echo $_smarty_tpl->tpl_vars['no']->value++;?>
.</td>
                            <td align="center"><?php echo $_smarty_tpl->tpl_vars['result']->value['jadwal_tahun'];?>
</td>
                            <td><?php echo $_smarty_tpl->tpl_vars['result']->value['jadwal_nama'];?>
</td>                                
                            <td align="center"><?php echo $_smarty_tpl->tpl_vars['result']->value['jadwal_status'];?>
</td>
                            <td align="center"><?php echo $_smarty_tpl->tpl_vars['dtm']->value->get_full_date((($tmp = @$_smarty_tpl->tpl_vars['result']->value['jadwal_mulai'])===null||$tmp==='' ? '' : $tmp));?>
</td>
                            <td align="center"><?php echo $_smarty_tpl->tpl_vars['dtm']->value->get_full_date((($tmp = @$_smarty_tpl->tpl_vars['result']->value['jadwal_selesai'])===null||$tmp==='' ? '' : $tmp));?>
</td>
                            <td align="center"jadwal_selesai>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('kepegawaian/master/jadwal_kerja/edit/').($_smarty_tpl->tpl_vars['result']->value['jadwal_id']));?>
" class="btn btn-xs white text-success"><i class="fa fa-edit"></i></a>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('kepegawaian/master/jadwal_kerja/delete/').($_smarty_tpl->tpl_vars['result']->value['jadwal_id']));?>
" class="btn btn-xs white text-danger"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php
}
} else {
?>

                        <tr>
                            <td colspan="7">Data not found!</td>
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
                                <li>Menampilkan <?php echo (($tmp = @$_smarty_tpl->tpl_vars['pagination']->value['start'])===null||$tmp==='' ? 0 : $tmp);?>
 - <?php echo (($tmp = @$_smarty_tpl->tpl_vars['pagination']->value['end'])===null||$tmp==='' ? 0 : $tmp);?>
 dari total <?php echo (($tmp = @$_smarty_tpl->tpl_vars['pagination']->value['total'])===null||$tmp==='' ? 0 : $tmp);?>
 data</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <nav class="pull-right">
                                <ul class="pagination">
                                    <li class="page-item"></li><?php echo (($tmp = @$_smarty_tpl->tpl_vars['pagination']->value['data'])===null||$tmp==='' ? '' : $tmp);?>
 </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php }
}

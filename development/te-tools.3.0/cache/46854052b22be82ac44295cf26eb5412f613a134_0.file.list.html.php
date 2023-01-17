<?php
/* Smarty version 3.1.30, created on 2021-09-07 17:09:44
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\home\task\lpj\list.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_61373a684ea472_68634290',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '46854052b22be82ac44295cf26eb5412f613a134' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\home\\task\\lpj\\list.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_61373a684ea472_68634290 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Tugas & Fasilitas Saya</a></li>
        <li class="breadcrumb-item"><a href="#">Jobs & Project</a></li>
        <li class="breadcrumb-item active">Laporan Perjalanan Dinas</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header">
                    <h4>Laporan Perjalanan Dinas Karyawan</h4>
                </div>
                <!-- notification template -->
                <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                <!-- end of notification template-->
                <div class="box-divider m-0"></div>
                <div class="box-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="20%" class="text-center">Project Name</th>
                                    <th width="15%" class="text-center">Location</th>
                                    <th width="15%" class="text-center">Perihal</th>
                                    <th width="15%" class="text-center">Karyawan</th>
                                    <th width="10%" class="text-center">Mulai</th>
                                    <th width="10%" class="text-center">Selesai</th>
                                    <th width="10%" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
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
                                    <td><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['project_alias'], 'UTF-8');?>
</td>
                                    <td><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['lokasi_tujuan'], 'UTF-8');?>
</td>
                                    <td><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['uraian_tugas'], 'UTF-8');?>
</td>
                                    <td><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['nama_lengkap'], 'UTF-8');?>
</td>
                                    <td align="center"><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['dtm']->value->get_full_date($_smarty_tpl->tpl_vars['result']->value['tanggal_berangkat']), 'UTF-8');?>
</td>
                                    <td align="center"><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['dtm']->value->get_full_date($_smarty_tpl->tpl_vars['result']->value['tanggal_pulang']), 'UTF-8');?>
</td>
                                    <td align="center">
                                        <a href="<?php ob_start();
echo (($tmp = @((('home/task/lpj/approval/').($_smarty_tpl->tpl_vars['result']->value['spt_id'])).('/')).($_smarty_tpl->tpl_vars['result']->value['process_id']))===null||$tmp==='' ? '' : $tmp);
$_prefixVariable1=ob_get_clean();
echo $_smarty_tpl->tpl_vars['config']->value->site_url($_prefixVariable1);?>
" class="btn btn-xs white text-success"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                                <?php
}
} else {
?>

                                <tr>
                                    <td colspan="9">Data Tidak Dtemukan</td>
                                </tr>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php }
}

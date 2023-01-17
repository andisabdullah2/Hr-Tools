<?php
/* Smarty version 3.1.30, created on 2021-09-07 10:14:30
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\project\tools\penagihan\list.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6136d916a751d9_24803296',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c470da5677ee945007ff1e78c46f6376195a9796' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\project\\tools\\penagihan\\list.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_6136d916a751d9_24803296 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-Projects</a></li>
        <li class="breadcrumb-item"><a href="#">Penagihan & Pembayaran</a></li>
        <li class="breadcrumb-item active"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/tools/penagihan/');?>
">Daftar Penagihan</a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                  <h4 class="box-title">Daftar Penagihan</h4>
                  <div class="box-tool">
                      <ul class="nav nav-xs">
                          <li class="nav-item">
                          </li>
                      </ul>
                  </div>
                </div>
                <div class="box-search">
                    <!-- notification template -->
                    <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                    <!-- end of notification template-->
                    <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/tools/penagihan/search_process');?>
" method="post">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="penagihan" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['nama_kontrak'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" placeholder="Nama Kontrak"/>
                            </div>                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="bulan" id="select2-single" data-plugin="select2" class="form-control" data-placeholder="Pilih Bulan">
                                        <option value=""></option>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_bulan']->value, 'bulan', false, 'i');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['i']->value => $_smarty_tpl->tpl_vars['bulan']->value) {
?>
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['search']->value['bulan'])===null||$tmp==='' ? '' : $tmp) == $_smarty_tpl->tpl_vars['i']->value) {?>selected="selected"<?php }?>><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['bulan']->value, 'UTF-8');?>
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
                                    <select name="tahun" class="form-control" data-plugin="select2" data-option="{}" data-placeholder="Pilih Tahun">
                                        <option value=""></option>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_tahun']->value, 'tahun');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['tahun']->value) {
?>
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['tahun']->value['tahun'];?>
" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['search']->value['tahun'])===null||$tmp==='' ? '' : $tmp) == $_smarty_tpl->tpl_vars['tahun']->value['tahun']) {?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['tahun']->value['tahun'];?>
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
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="10%">Tanggal</th>
                                    <th width="20%">Kontrak</th>
                                    <th width="20%">Uraian</th>
                                    <th width="15%">Nilai</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Status Penagihan</th>
                                    <th width="5%" class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_id']->value, 'result');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['result']->value) {
?>
                                <tr <?php if (($_smarty_tpl->tpl_vars['no']->value%2) <> 1) {?>class="blink-row"<?php }?>>
                                    <td class="text-center"><?php echo $_smarty_tpl->tpl_vars['no']->value++;?>
.</td>
                                    <td class="text-center"><?php echo $_smarty_tpl->tpl_vars['dtm']->value->get_full_date((($tmp = @$_smarty_tpl->tpl_vars['result']->value['termin_tanggal'])===null||$tmp==='' ? '' : $tmp));?>
</td>                                    
                                    <td><?php echo $_smarty_tpl->tpl_vars['result']->value['nomor_kontrak'];?>
 | <?php echo $_smarty_tpl->tpl_vars['result']->value['judul_kontrak'];?>
</td>
                                    <td class="text-left"><?php echo (($tmp = @mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['termin_uraian'], 'UTF-8'))===null||$tmp==='' ? '-' : $tmp);?>
</td>
                                    <td class="text-left">Rp. <?php echo number_format($_smarty_tpl->tpl_vars['result']->value['termin_nilai'],0,',','.');?>
</td>
                                    <td align="center"> 
                                        <?php if ($_smarty_tpl->tpl_vars['result']->value['termin_status'] == 'waiting') {?>
                                            <span class="badge badge-warning"><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['termin_status'], 'UTF-8');?>
</span>
                                        <?php } elseif ($_smarty_tpl->tpl_vars['result']->value['termin_status'] == 'lunas') {?>
                                            <span class="badge badge-success"><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['termin_status'], 'UTF-8');?>
</span>
                                        <?php } elseif ($_smarty_tpl->tpl_vars['result']->value['termin_status'] == 'cancel') {?>
                                            <span class="badge badge-danger"><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['termin_status'], 'UTF-8');?>
</span>
                                        <?php }?>
                                    </td>
                                    <td align="center"> 
                                        <?php if ($_smarty_tpl->tpl_vars['result']->value['invoices_status'] == 'process') {?>
                                            <span class="badge badge-warning"><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['invoices_status'], 'UTF-8');?>
</span>
                                        <?php } elseif ($_smarty_tpl->tpl_vars['result']->value['invoices_status'] == 'paid') {?>
                                            <span class="badge badge-success"><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['invoices_status'], 'UTF-8');?>
</span>
                                        <?php } elseif ($_smarty_tpl->tpl_vars['result']->value['invoices_status'] == 'draft') {?>
                                            <span class="badge badge-primary"><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['invoices_status'], 'UTF-8');?>
</span>
                                        <?php } elseif ($_smarty_tpl->tpl_vars['result']->value['invoices_status'] == 'cancel') {?>
                                            <span class="badge badge-danger"><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['invoices_status'], 'UTF-8');?>
</span>
                                        <?php } else { ?>
                                            <span class="badge badge-secondary">TIDAK ADA DATA INVOICE</span>
                                        <?php }?>
                                    </td>       
                                    <td class="text-center">
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('project/tools/penagihan/detail/').($_smarty_tpl->tpl_vars['result']->value['termin_id']));?>
" class="btn btn-xs white text-success"><i class="fa fa-bars"></i></a>
                                    </td>
                                </tr>    
                                <?php
}
} else {
?>

                                <tr>
                                    <td colspan="8">Data not found!</td>
                                </tr>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                            </tbody>
                       </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="pagination pagination-sm">
                                <li>Menampilkan <?php echo (($tmp = @$_smarty_tpl->tpl_vars['pagination']->value['start'])===null||$tmp==='' ? 0 : $tmp);?>
 - <?php echo (($tmp = @$_smarty_tpl->tpl_vars['pagination']->value['end'])===null||$tmp==='' ? 0 : $tmp);?>
 dari total <?php echo (($tmp = @$_smarty_tpl->tpl_vars['pagination']->value['total'])===null||$tmp==='' ? 0 : $tmp);?>
 data</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="pagination pagination-sm pull-right">
                                <?php echo (($tmp = @$_smarty_tpl->tpl_vars['pagination']->value['data'])===null||$tmp==='' ? '' : $tmp);?>

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

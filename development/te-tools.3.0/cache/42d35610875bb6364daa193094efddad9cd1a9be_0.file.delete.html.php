<?php
/* Smarty version 3.1.30, created on 2021-09-17 13:44:35
  from "/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/presensi/delete.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_61443953aee346_24758922',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '42d35610875bb6364daa193094efddad9cd1a9be' => 
    array (
      0 => '/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/presensi/delete.html',
      1 => 1631861072,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_61443953aee346_24758922 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-HR</a></li>
        <li class="breadcrumb-item"><a href="#">Data Pendukung</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/presensi/');?>
">Mesin Presensi</a></li>
        <li class="breadcrumb-item active">Hapus Data</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <!-- Basic Forms -->
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Hapus Data Mesin Presensi</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/presensi');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-divider m-0"></div>
                <!-- /.box-header -->
                <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/presensi/delete_process');?>
" method="post" class="form-horizontal" onsubmit="return confirm('Apakah anda yakin akan menghapus data berikut ini?');">
                <input type="hidden" name="mesin_id" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['mesin_id'])===null||$tmp==='' ? '' : $tmp);?>
">
                    <div class="box-body">
                        <!-- notification template -->
                        <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                        <!-- end of notification template-->
                        <p class="text-danger">Apakah anda yakin akan menghapus data berikut ini?</p>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">IP Mesin</label>
                            <div class="col-md-9">
                                <label class="control-label"><b><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['mesin_ip'])===null||$tmp==='' ? '' : $tmp);?>
</b></label>
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Lokasi Mesin</label>
                            <div class="col-md-9">
                                <label class="control-label"><b><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['mesin_lokasi'])===null||$tmp==='' ? '' : $tmp);?>
</b></label>
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Waktu Diubah</label>
                            <div class="col-md-9">
                                <label class="control-label"><b><?php echo $_smarty_tpl->tpl_vars['dtm']->value->get_full_date((($tmp = @$_smarty_tpl->tpl_vars['result']->value['mdd'])===null||$tmp==='' ? '' : $tmp));?>
</b></label>
                            </div>
                        </div>         
                    </div>
                    <div class="box-footer clearfix">
                        <button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i>&nbsp;  HAPUS</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section><?php }
}

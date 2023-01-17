<?php
/* Smarty version 3.1.30, created on 2021-09-17 10:45:35
  from "/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/presensi/edit.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_61440f5fcf4bb3_10863825',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4bc2897f8c5a042bb6fc816f634fceca9078d51b' => 
    array (
      0 => '/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/presensi/edit.html',
      1 => 1631850326,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_61440f5fcf4bb3_10863825 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-HR</a></li>
        <li class="breadcrumb-item"><a href="#">Data Pendukung</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/presensi/');?>
">Mesin Presensi</a></li>
        <li class="breadcrumb-item active">Edit Data</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <!-- Basic Forms -->
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Edit Data Mesin Presensi</h4>
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
                <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/presensi/edit_process');?>
" method="post" class="form-horizontal">
                <input type="hidden" name="mesin_id" value="<?php echo $_smarty_tpl->tpl_vars['result']->value['mesin_id'];?>
">
                    <div class="box-body">
                        <!-- notification template -->
                        <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                        <!-- end of notification template-->
                        <div class="form-group row">
                            <label class="col-md-3 control-label">IP Mesin</label>
                            <div class="col-md-4">
                                <input class="form-control" name="mesin_ip" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['mesin_ip'])===null||$tmp==='' ? '' : $tmp);?>
"  size="100" maxlength="100" />
                                <small class="help-block text-danger">* wajib diisi.</small>
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Lokasi Mesin</label>
                            <div class="col-md-4">
                                <input class="form-control" name="mesin_lokasi" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['mesin_lokasi'])===null||$tmp==='' ? '' : $tmp);?>
"  size="100" maxlength="100" />
                                <small class="help-block text-danger">* wajib diisi.</small>
                            </div>
                        </div>                        
                    </div>
                    <div class="box-footer clearfix">
                        <div class="form-group row">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-4">
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

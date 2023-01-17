<?php
/* Smarty version 3.1.30, created on 2021-09-07 16:39:01
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\kepegawaian\master\jadwal_kerja\add.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_613733353d4768_84315676',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1945fd3099c355f21c4b1b5b179accfd2cca46e4' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\kepegawaian\\master\\jadwal_kerja\\add.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_613733353d4768_84315676 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-HR</a></li>
        <li class="breadcrumb-item"><a href="#">Data Pendukung</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/jadwal_kerja/');?>
">Jadwal Kerja</a></li>
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
                    <h4 class="box-title">Tambah Data Jadwal Kerja</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/jadwal_kerja');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-divider m-0"></div>
                <!-- /.box-header -->
                <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/jadwal_kerja/add_process');?>
" method="post" class="form-horizontal">
                    <div class="box-body">
                        <!-- notification template -->
                        <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                        <!-- end of notification template-->
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Tahun</label>
                            <div class="col-md-2">
                                <div class="input-group">
                                        <input type="text" name="jadwal_tahun" maxlength="4" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['jadwal_tahun'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" />
                                </div>
                                <small class="help-block text-danger">wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Nama</label>
                            <div class="col-md-4">
                                <input type="text" name="jadwal_nama" maxlength="50" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['jadwal_nama'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" />
                                <small class="help-block text-danger">wajib diisi</small>
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Status</label>
                            <div class="col-md-2">
                                <select name="jadwal_status" class="form-control" data-placeholder="Status">
                                    <option value="" disabled="disabled" selected="selected"></option>
                                    <option value="normal" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['result']->value['jadwal_status'])===null||$tmp==='' ? '' : $tmp) == 'normal') {?>selected="selected"<?php }?>>NORMAL</option>
                                    <option value="khusus" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['result']->value['jadwal_status'])===null||$tmp==='' ? '' : $tmp) == 'khusus') {?>selected="selected"<?php }?>>KHUSUS</option>
                                </select> 
                                <small class="help-block text-danger">wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Tanggal</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                        <input tabindex="1" type="text" class="form-control" data-plugin="datepicker" data-option="{format: 'yyyy-mm-dd',autoclose:true}" placeholder="Tanggal" name="jadwal_mulai" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['jadwal_mulai'])===null||$tmp==='' ? '' : $tmp);?>
" style="height:33px;font-weight: bold; text-align: center;"/>
                                        <span class="input-group-addon-custom" style="height:33px"><span class="fa fa-calendar"></span></span>
                                        <span class="input-group-addon"><b>s/d</b></span>
                                        <input tabindex="1" type="text" class="form-control" data-plugin="datepicker" data-option="{format: 'yyyy-mm-dd',autoclose:true}" placeholder="Tanggal" name="jadwal_selesai" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['jadwal_selesai'])===null||$tmp==='' ? '' : $tmp);?>
" style="height:33px;font-weight: bold; text-align: center;"/>
                                        <span class="input-group-addon-custom" style="height:33px"><span class="fa fa-calendar"></span></span>                                        
                                </div>      
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

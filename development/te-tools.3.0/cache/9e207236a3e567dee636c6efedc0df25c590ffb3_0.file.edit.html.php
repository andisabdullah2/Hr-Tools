<?php
/* Smarty version 3.1.30, created on 2021-09-07 10:04:14
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\kepegawaian\master\struktur_organisasi\edit.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6136d6aeaa1280_17398079',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9e207236a3e567dee636c6efedc0df25c590ffb3' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\kepegawaian\\master\\struktur_organisasi\\edit.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_6136d6aeaa1280_17398079 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-HR</a></li>
        <li class="breadcrumb-item"><a href="#">Data Kepegawaian</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/struktur_organisasi/');?>
">Struktur Organisasi</a></li>
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
                    <h4 class="box-title">Edit Data Struktur Organisasi</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/struktur_organisasi');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-divider m-0"></div>
                <!-- /.box-header -->
                <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/struktur_organisasi/edit_process');?>
" method="post" class="form-horizontal">
                    <div class="box-body">
                        <!-- notification template -->
                        <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                        <!-- end of notification template-->
                        <input type="hidden" name="struktur_cd" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['rs_detail_struktur']->value['struktur_cd'])===null||$tmp==='' ? '' : $tmp);?>
" />
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Level Struktur</label>
                            <div class="col-md-4">
                              <select name="struktur_level_label" data-plugin="select2" data-option="{}" data-placeholder="Pilih Level Struktur" class="form-control">
                                <option value=""></option>
                                <option value="PERUSAHAAN" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['rs_detail_struktur']->value['struktur_level_label'])===null||$tmp==='' ? '' : $tmp) == "PERUSAHAAN") {?> selected="selected"<?php }?>>PERUSAHAAN</option>
                                <option value="DEPARTEMEN" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['rs_detail_struktur']->value['struktur_level_label'])===null||$tmp==='' ? '' : $tmp) == "DEPARTEMEN") {?> selected="selected"<?php }?>>DEPARTEMEN</option>
                                <option value="KANTOR REPRESENTATIF" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['rs_detail_struktur']->value['struktur_level_label'])===null||$tmp==='' ? '' : $tmp) == "KANTOR REPRESENTATIF") {?> selected="selected"<?php }?>>KANTOR REPRESENTATIF</option>
                                <option value="KANTOR CABANG" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['rs_detail_struktur']->value['struktur_level_label'])===null||$tmp==='' ? '' : $tmp) == "KANTOR CABANG") {?> selected="selected"<?php }?>>KANTOR CABANG</option>
                                <option value="DIVISI" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['rs_detail_struktur']->value['struktur_level_label'])===null||$tmp==='' ? '' : $tmp) == "DIVISI") {?> selected="selected"<?php }?>>DIVISI</option>
                                <option value="SEKSI" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['rs_detail_struktur']->value['struktur_level_label'])===null||$tmp==='' ? '' : $tmp) == "SEKSI") {?> selected="selected"<?php }?>>SEKSI</option>
                              </select>
                            </div>
                            <small class="help-block text-danger">wajib diisi</small>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Induk Struktur</label>
                            <div class="col-md-4">
                              <select name="struktur_induk" data-plugin="select2" data-option="{}" data-placeholder="Pilih Nama Department" class="form-control">
                                <option value=""></option>
                                  <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_all_struktur']->value, 'data');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
?>
                                  <option value="<?php echo $_smarty_tpl->tpl_vars['data']->value['struktur_cd'];?>
" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['rs_detail_struktur']->value['struktur_induk'])===null||$tmp==='' ? '' : $tmp) == $_smarty_tpl->tpl_vars['data']->value['struktur_cd']) {?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value['struktur_nama'];?>
</option>
                                  <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                                  <option value="NULL"> - </option>
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Nama Struktur</label>
                            <div class="col-md-4">
                              <input class="form-control" name="struktur_nama" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['rs_detail_struktur']->value['struktur_nama'])===null||$tmp==='' ? '' : $tmp);?>
"  size="35" maxlength="100" />
                            </div>
                            <small class="help-block text-danger">wajib diisi</small>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Singkatan Struktur</label>
                            <div class="col-md-4">
                                <input class="form-control" name="struktur_singkatan" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['rs_detail_struktur']->value['struktur_singkatan'])===null||$tmp==='' ? '' : $tmp);?>
"  size="35" maxlength="50" />
                            </div>
                            <small class="help-block text-danger">wajib diisi</small>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Keterangan Struktur</label>
                            <div class="col-md-4">
                                <input class="form-control" name="struktur_keterangan" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['rs_detail_struktur']->value['struktur_keterangan'])===null||$tmp==='' ? '' : $tmp);?>
"  size="35" maxlength="255" />
                            </div>
                            <small class="help-block text-danger">wajib diisi</small>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Kode Struktur</label>
                            <div class="col-md-4">
                                <input class="form-control" name="struktur_kode" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['rs_detail_struktur']->value['struktur_kode'])===null||$tmp==='' ? '' : $tmp);?>
"  size="35" maxlength=3 />
                            </div>
                            <small class="help-block text-danger">wajib diisi</small>
                        </div>
                    </div>
                    <div class="box-footer clearfix">
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

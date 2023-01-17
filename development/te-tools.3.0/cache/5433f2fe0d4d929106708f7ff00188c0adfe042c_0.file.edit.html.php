<?php
/* Smarty version 3.1.30, created on 2021-09-14 16:37:21
  from "/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/hari_libur/edit.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_61406d514020a6_05463539',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5433f2fe0d4d929106708f7ff00188c0adfe042c' => 
    array (
      0 => '/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/hari_libur/edit.html',
      1 => 1631071316,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_61406d514020a6_05463539 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-HR</a></li>
        <li class="breadcrumb-item"><a href="#">Data Pendukung</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/hari_libur/');?>
">Hari Libur</a></li>
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
                    <h4 class="box-title">Edit Data Hari Libur</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/hari_libur');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-divider m-0"></div>
                <!-- /.box-header -->
                <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/hari_libur/edit_process');?>
" method="post" class="form-horizontal">
                <input type="hidden" name="libur_id" value="<?php echo $_smarty_tpl->tpl_vars['result']->value['libur_id'];?>
">
                    <div class="box-body">
                        <!-- notification template -->
                        <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                        <!-- end of notification template-->
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Tanggal</label>
                            <div class="col-md-2">
                                <div class="input-group">
                                        <input tabindex="1" type="text" class="form-control" data-plugin="datepicker" data-option="{format: 'yyyy-mm-dd'}" placeholder="Tanggal" name="libur_tanggal" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['libur_tanggal'])===null||$tmp==='' ? '' : $tmp);?>
" style="height:33px;font-weight: bold; text-align: center;"/>
                                        <span class="input-group-addon-custom" style="height:33px">
                                        <span class="fa fa-calendar"></span>
                                        </span>
                                </div>
                                <small class="help-block text-danger">wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Jenis</label>
                            <div class="col-md-2">
                                <select name="libur_jenis" class="form-control">
                                        <option value="" selected disabled>Jenis Libur</option>
                                        <option value="NASIONAL" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['libur_jenis'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable1=ob_get_clean();
if ($_prefixVariable1 == "NASIONAL") {?>selected="selected"<?php }?>>Nasional</option>
                                        <option value="KANTOR" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['libur_jenis'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable2=ob_get_clean();
if ($_prefixVariable2 == "KANTOR") {?>selected="selected"<?php }?>>Kantor</option>
                                </select>
                                <small class="help-block text-danger">wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Judul</label>
                            <div class="col-md-4">
                                <input class="form-control" name="libur_judul" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['libur_judul'])===null||$tmp==='' ? '' : $tmp);?>
"  size="100" maxlength="100" />
                                <small class="help-block text-danger">wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Keterangan</label>
                            <div class="col-md-4">
                                <textarea class="form-control" name="libur_keterangan" rows="3"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['libur_keterangan'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
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

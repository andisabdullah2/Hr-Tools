<?php
/* Smarty version 3.1.30, created on 2021-09-07 16:37:49
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\kepegawaian\master\jabatan_struktural\add.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_613732ed023e76_36779302',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '67a8448f255b76395c660d81a8da3f759fa4819f' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\kepegawaian\\master\\jabatan_struktural\\add.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_613732ed023e76_36779302 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-HR</a></li>
        <li class="breadcrumb-item"><a href="#">Data Pendukung</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/jabatan_struktural/');?>
">Jabatan Struktural</a></li>
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
                    <h4 class="box-title">Tambah Data Jabatan Struktural</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/jabatan_struktural');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-divider m-0"></div>
                <!-- /.box-header -->
                <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/jabatan_struktural/add_process');?>
" method="post" class="form-horizontal">
                    <div class="box-body">
                        <!-- notification template -->
                        <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                        <!-- end of notification template-->
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Nama Jabatan</label>
                            <div class="col-md-4">
                                <input class="form-control" name="jabatan_nama" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['jabatan_nama'])===null||$tmp==='' ? '' : $tmp);?>
"  size="100" maxlength="100" />
                                <small class="help-block text-danger">wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Alias</label>
                            <div class="col-md-4">
                                <input class="form-control" name="jabatan_alias" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['jabatan_alias'])===null||$tmp==='' ? '' : $tmp);?>
"  size="50" maxlength="50" />
                                <small class="help-block text-danger">wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Level</label>
                            <div class="col-md-2">
                                <input class="form-control" name="jabatan_level" type="number" min="0" max="999" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['jabatan_level'])===null||$tmp==='' ? '' : $tmp);?>
" />
                                <small class="help-block text-danger">wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Unit Kerja</label>
                            <div class="col-md-4">
                                <select name="struktur_cd" id="select2-single" data-plugin="select2" class="form-control" data-placeholder="Struktur Induk">
                                <option value=""></option>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_struktur']->value, 'data');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
?>
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['data']->value['struktur_cd'];?>
" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['rs_struktur']->value['department_id'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable1=ob_get_clean();
if ($_prefixVariable1 == $_smarty_tpl->tpl_vars['data']->value['struktur_cd']) {?>selected="selected"<?php }?>><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['data']->value['struktur_nama'], 'UTF-8');?>
</option>
                                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                              </select>       
                               <small class="help-block text-danger">wajib diisi</small>                              
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Keterangan</label>
                            <div class="col-md-4">
                                <textarea class="form-control" name="jabatan_keterangan" rows="3"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['jabatan_keterangan'])===null||$tmp==='' ? '' : $tmp);?>
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

<?php
/* Smarty version 3.1.30, created on 2021-09-07 16:48:26
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\kepegawaian\master\struktur_organisasi\delete.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6137356a635be5_56712167',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd0bd4a7383fc571e05a33d7eb17da2d4e4acb7e8' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\kepegawaian\\master\\struktur_organisasi\\delete.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_6137356a635be5_56712167 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-HR</a></li>
        <li class="breadcrumb-item"><a href="#">Data Kepegawaian</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/struktur_organisasi/');?>
">Struktur Organisasi</a></li>
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
                    <h4 class="box-title">Hapus Data Struktur Organisasi</h4>
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
                <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/struktur_organisasi/delete_process');?>
" method="post" class="form-horizontal" onsubmit="return confirm('Apakah anda yakin akan menghapus data berikut ini?');">
                    <div class="box-body">
                        <!-- notification template -->
                        <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                        <!-- end of notification template-->
                        <p class="text-danger">Apakah anda yakin akan menghapus data berikut ini?</p>

                        <input type="hidden" name="struktur_cd" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['struktur_cd'])===null||$tmp==='' ? '' : $tmp);?>
" />
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Struktur CD</label>
                            <div class="col-md-9">
                                <label class="control-label"><b><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['struktur_cd'])===null||$tmp==='' ? '' : $tmp);?>
</b></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Induk Struktur</label>
                            <div class="col-md-9">
                                <label class="control-label"><b><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['struktur_induk'])===null||$tmp==='' ? '' : $tmp);?>
</b></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Nama Struktur</label>
                            <div class="col-md-9">
                                <label class="control-label"><b><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['struktur_nama'])===null||$tmp==='' ? '' : $tmp);?>
</b></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Level Struktur</label>
                            <div class="col-md-9">
                                <label class="control-label"><b><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['struktur_level_label'])===null||$tmp==='' ? '' : $tmp);?>
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
</section>
<?php }
}

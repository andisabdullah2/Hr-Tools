<?php
/* Smarty version 3.1.30, created on 2021-09-07 17:02:19
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\project\master\client\edit.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_613738abc05978_66456056',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '58233088a2aadeb42246a3be4c39a18c33da5325' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\project\\master\\client\\edit.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_613738abc05978_66456056 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <!-- </h1> -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-Projects</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/master/client/');?>
">Client Data</a></li>
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
                    <h4 class="box-title">Edit Data Client</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/master/client/');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--<div class="box-divider m-0"></div>-->
                <div class="b-b nav-active-bg">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Data Client</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url((($tmp = @('project/master/client/alamat/').($_smarty_tpl->tpl_vars['result']->value['client_id']))===null||$tmp==='' ? '' : $tmp));?>
">Data Alamat</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url((($tmp = @('project/master/client/pic/').($_smarty_tpl->tpl_vars['result']->value['client_id']))===null||$tmp==='' ? '' : $tmp));?>
">Contact Person</a>
                        </li>
                    </ul>
                </div>
                <!-- /.box-header -->
                <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/master/client/edit_process');?>
" method="post" class="form-horizontal">
                    <div class="box-body">
                        <!-- notification template -->
                        <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                        <!-- end of notification template-->
                        <input type="hidden" name="client_id" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['client_id'])===null||$tmp==='' ? '' : $tmp);?>
" />
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Nama Alias</label>
                            <div class="col-md-7">
                                <input type="text" name="client_nm" maxlength="100" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['client_nm'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" />
                                <small class="help-block text-danger">*wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Nama Client</label>
                            <div class="col-md-7">
                                <input type="text" name="client_desc" maxlength="100" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['client_desc'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" />
                                <small class="help-block text-danger">*wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Kota</label>
                            <div class="col-md-7">
                                <input type="text" name="client_city" maxlength="100" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['client_city'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" />
                                <small class="help-block text-danger">*wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Alamat Client</label>
                            <div class="col-md-7">
                                <input type="text" name="client_address" maxlength="100" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['client_address'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" />
                                <small class="help-block text-danger">*wajib diisi</small>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Update terakhir</label>
                            <div class="col-md-3">
                                <label class="control-label"><b><?php echo (($tmp = @$_smarty_tpl->tpl_vars['dtm']->value->get_full_date($_smarty_tpl->tpl_vars['result']->value['mdd']))===null||$tmp==='' ? '' : $tmp);?>
</b></label>
                            </div>
                            <label class="col-md-2 control-label">Oleh</label>
                            <div class="col-md-4">
                                <label class="control-label"><b><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['mdb_name'])===null||$tmp==='' ? '' : $tmp);?>
</b></label>
                            </div>
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
</section><?php }
}

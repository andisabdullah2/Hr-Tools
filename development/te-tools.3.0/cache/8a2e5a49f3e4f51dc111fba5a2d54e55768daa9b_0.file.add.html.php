<?php
/* Smarty version 3.1.30, created on 2021-09-07 10:12:33
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\project\master\client\add.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6136d8a1915ee6_91442531',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8a2e5a49f3e4f51dc111fba5a2d54e55768daa9b' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\project\\master\\client\\add.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_6136d8a1915ee6_91442531 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <!-- </h1> -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-Projects</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/master/client/');?>
">Client Data</a></li>
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
                    <h4 class="box-title">Tambah Data Client</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/master/client');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-divider m-0"></div>
                <!-- /.box-header -->
                <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/master/client/add_process');?>
" method="post" class="form-horizontal">
                    <div class="box-body">
                        <!-- notification template -->
                        <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                        <!-- end of notification template-->
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Nama Client</label>
                            <div class="col-md-7">
                                <input class="form-control" name="client_desc" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['client_desc'])===null||$tmp==='' ? '' : $tmp);?>
" />
                                <small class="help-block text-danger">*wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Nama Alias</label>
                            <div class="col-md-7">
                                <input class="form-control" name="client_nm" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['client_nm'])===null||$tmp==='' ? '' : $tmp);?>
" />
                                <small class="help-block text-danger">*wajib diisi</small>
                            </div>
                        </div>                
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Alamat</label>
                            <div class="col-md-7">
                                <input class="form-control" name="client_address" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['client_address'])===null||$tmp==='' ? '' : $tmp);?>
"  size="35" maxlength="50" />
                                <small class="help-block text-danger">*wajib diisi</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 control-label">Kota</label>
                            <div class="col-md-7">
                                <input class="form-control" name="client_city" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['client_city'])===null||$tmp==='' ? '' : $tmp);?>
" />
                                <small class="help-block text-danger">*wajib diisi</small>
                            </div>
                        </div>                              
                        <div class="form-group row">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-7">
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Simpan</button>
                                <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Reset</button>
                            </div>
                        </div>                            
                    </div>
                    <div class="box-footer clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php }
}

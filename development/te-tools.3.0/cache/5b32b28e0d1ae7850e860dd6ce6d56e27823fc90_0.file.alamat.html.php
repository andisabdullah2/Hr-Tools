<?php
/* Smarty version 3.1.30, created on 2021-09-07 17:02:27
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\project\master\client\alamat.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_613738b39a3f58_16534252',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5b32b28e0d1ae7850e860dd6ce6d56e27823fc90' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\project\\master\\client\\alamat.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_613738b39a3f58_16534252 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
    $(document).ready(function () {
        //add
        $(".add").click(function () {
            $('#modal_add').modal('show');
        });

        //edit
        $(".edit").click(function () {
            $("#alamat_id").val($(this).data('alamat_id'));
            $("#alamat_kepada").val($(this).data('kepada'));
            $("#alamat_kantor").val($(this).data('kantor'));
            $("#alamat_default").val($(this).data('default')).change();
            $('#modal_edit').modal('show');
        });
    });
<?php echo '</script'; ?>
>
<!-- Content Header (Page header) -->
<section class="content-header">
    <!-- </h1> -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-Projects</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/master/client/');?>
">Client Data</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('project/master/client/edit/').($_smarty_tpl->tpl_vars['result']->value['client_id']));?>
"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['client_nm'])===null||$tmp==='' ? '' : $tmp);?>
</a></li>
        <li class="breadcrumb-item active">Alamat</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Daftar Alamat</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="#" class="btn btn-outline b-primary text-primary add" data-toogle="modal" data-toggle="tooltip" data-placement="top" ><i class="fa fa-plus"></i> Tambah Data</a>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('project/master/client/edit/').($_smarty_tpl->tpl_vars['result']->value['client_id']));?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="b-b nav-active-bg">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url((($tmp = @('project/master/client/edit/').($_smarty_tpl->tpl_vars['result']->value['client_id']))===null||$tmp==='' ? '' : $tmp));?>
">Data Client</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Data Alamat</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url((($tmp = @('project/master/client/pic/').($_smarty_tpl->tpl_vars['result']->value['client_id']))===null||$tmp==='' ? '' : $tmp));?>
">Contact Person</a>
                        </li>
                    </ul>
                </div>
                <div class="box-body">
                    <!-- notification template -->
                    <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                    <!-- end of notification template-->
                    <table class="table table-bordered">
                        <tr class="text-center">
                            <th width="4%">No</th>
                            <th width="25%">Kepada</th>
                            <th width="50%">Alamat Kantor</th>
                            <th width="10%">Default</th>
                            <th width="11%"></th>
                        </tr>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_id']->value, 'data', false, 'no');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['no']->value => $_smarty_tpl->tpl_vars['data']->value) {
?>
                        <tr>
                            <td class="text-center"><?php echo $_smarty_tpl->tpl_vars['no']->value+1;?>
.</td>
                            <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['data']->value['alamat_kepada'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                            <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['data']->value['alamat_kantor'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                            <td class="text-center">
                                <?php if ($_smarty_tpl->tpl_vars['data']->value['alamat_default'] == '0') {?>
                                <span class="text-danger"><i class="fa fa-times-circle-o"></i></span>
                                <?php } else { ?>
                                <span class="text-success"><i class="fa fa-check-circle-o"></i></span>
                                <?php }?>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-xs white text-success edit" data-toogle="modal" data-alamat_id="<?php echo $_smarty_tpl->tpl_vars['data']->value['alamat_id'];?>
" data-kepada="<?php echo $_smarty_tpl->tpl_vars['data']->value['alamat_kepada'];?>
" data-kantor="<?php echo $_smarty_tpl->tpl_vars['data']->value['alamat_kantor'];?>
" data-default="<?php echo $_smarty_tpl->tpl_vars['data']->value['alamat_default'];?>
" data-toggle="tooltip" data-placement="top" ><i class="fa fa-edit"></i></a>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(((('project/master/client/alamat_delete_process/').($_smarty_tpl->tpl_vars['result']->value['client_id'])).('/')).($_smarty_tpl->tpl_vars['data']->value['alamat_id']));?>
" class="btn btn-xs white text-danger"  onclick="return confirm('Apakah anda yakin akan menghapus data ini?')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>    
                        <?php
}
} else {
?>

                        <tr>
                            <td colspan="5">Data tidak ditemukan!</td>
                        </tr>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--MODAL ADD-->
<!--===================================================-->
<div class="modal fade" id="modal_add" role="dialog"  aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content box-shadow-md mb-3">
            <!--Modal header-->
            <div class="modal-header">
                <h5 class="modal-title">Tambah data alamat</h5>
            </div>
            <form class="form-horizontal" action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/master/client/alamat_add_process/');?>
" method="post" >
                <!--Modal body-->
                <div class="modal-body">
                    <input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['result']->value['client_id'];?>
" name="client_id" class="form-control" />
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Kepada</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="alamat_kepada" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['alamat_kepada'])===null||$tmp==='' ? '' : $tmp);?>
" />
                            <small class="form-text text-danger">wajib diisi</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Alamat Kantor</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="alamat_kantor" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['alamat_kantor'])===null||$tmp==='' ? '' : $tmp);?>
" />
                            <small class="form-text text-danger">wajib diisi</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Alamat Default</label>
                        <div class="col-sm-4">
                            <select name="alamat_default" class="form-control" data-plugin="select2" data-option="{}"  data-placeholder="Status">
                                <option value=""></option>
                                <option value="1" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['result']->value['alamat_default'])===null||$tmp==='' ? '' : $tmp) == '1') {?> selected="selected" <?php }?>>YA</option>
                                <option value="0" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['result']->value['alamat_default'])===null||$tmp==='' ? '' : $tmp) == '0') {?> selected="selected" <?php }?>>BUKAN</option>
                            </select>
                            <small class="form-text text-danger">wajib diisi</small>
                        </div>
                    </div>
                </div>
                <!--Modal footer-->
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn dark-white" type="button">Close</button>
                    <button class="btn primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--===================================================-->
<!--End MODAL ADD-->
<!--MODAL EDIT-->
<!--===================================================-->
<div class="modal fade" id="modal_edit" role="dialog"  aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content box-shadow-md mb-3">
            <!--Modal header-->
            <div class="modal-header">
                <h5 class="modal-title">Edit data alamat</h5>
            </div>
            <form class="form-horizontal" action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/master/client/alamat_edit_process/');?>
" method="post" >
                <!--Modal body-->
                <div class="modal-body">
                    <input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['result']->value['client_id'];?>
" name="client_id" class="form-control" />
                    <input type="hidden" id="alamat_id" name="alamat_id" class="form-control" />
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Kepada</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="alamat_kepada" type="text" value="" id="alamat_kepada" />
                            <small class="form-text text-danger">wajib diisi</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Alamat Kantor</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="alamat_kantor" type="text" value="" id="alamat_kantor" />
                            <small class="form-text text-danger">wajib diisi</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Alamat Default</label>
                        <div class="col-sm-4">
                            <select name="alamat_default" class="form-control" data-plugin="select2" data-option="{}"  data-placeholder="Status" id="alamat_default">
                                <option value=""></option>
                                <option value="1" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['result']->value['alamat_default'])===null||$tmp==='' ? '' : $tmp) == '1') {?> selected="selected" <?php }?>>YA</option>
                                <option value="0" <?php if ((($tmp = @$_smarty_tpl->tpl_vars['result']->value['alamat_default'])===null||$tmp==='' ? '' : $tmp) == '0') {?> selected="selected" <?php }?>>BUKAN</option>
                            </select>
                            <small class="form-text text-danger">wajib diisi</small>
                        </div>
                    </div>
                </div>
                <!--Modal footer-->
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn dark-white" type="button">Close</button>
                    <button class="btn primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--===================================================-->
<!--End MODAL EDIT--><?php }
}

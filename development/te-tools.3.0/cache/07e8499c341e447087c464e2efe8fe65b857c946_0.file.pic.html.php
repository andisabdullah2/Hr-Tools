<?php
/* Smarty version 3.1.30, created on 2021-09-07 17:02:33
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\project\master\client\pic.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_613738b990b523_36317453',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '07e8499c341e447087c464e2efe8fe65b857c946' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\project\\master\\client\\pic.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_613738b990b523_36317453 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
    $(document).ready(function () {
        //add
        $(".add").click(function () {
            $('#modal_add').modal('show');
        });

        //edit
        $(".edit").click(function () {
            $("#pic_id").val($(this).data('id'));
            $("#pic_name").val($(this).data('name'));
            $("#pic_position").val($(this).data('pos'));
            $("#pic_phone_number").val($(this).data('phone'));
            $("#pic_email").val($(this).data('email'));
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
        <li class="breadcrumb-item active">Contact Person</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Daftar Contact Person</h4>
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
                            <a class="nav-link" href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url((($tmp = @('project/master/client/alamat/').($_smarty_tpl->tpl_vars['result']->value['client_id']))===null||$tmp==='' ? '' : $tmp));?>
">Data Alamat</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="">Contact Person</a>
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
                            <th width="25%">Nama</th>
                            <th width="30%">Jabatan</th>
                            <th width="15%">Telepon</th>
                            <th width="15%">Email</th>
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
                            <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['data']->value['pic_name'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                            <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['data']->value['pic_position'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                            <td class="text-center"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['data']->value['pic_phone_number'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                            <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['data']->value['pic_email'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-xs white text-success edit" data-toogle="modal" data-id="<?php echo $_smarty_tpl->tpl_vars['data']->value['pic_id'];?>
" data-name="<?php echo $_smarty_tpl->tpl_vars['data']->value['pic_name'];?>
" data-pos="<?php echo $_smarty_tpl->tpl_vars['data']->value['pic_position'];?>
" data-phone="<?php echo $_smarty_tpl->tpl_vars['data']->value['pic_phone_number'];?>
" data-email="<?php echo $_smarty_tpl->tpl_vars['data']->value['pic_email'];?>
" data-toggle="tooltip" data-placement="top" ><i class="fa fa-edit"></i></a>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(((('project/master/client/pic_delete_process/').($_smarty_tpl->tpl_vars['result']->value['client_id'])).('/')).($_smarty_tpl->tpl_vars['data']->value['pic_id']));?>
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
                <h5 class="modal-title">Tambah data contact person</h5>
            </div>
            <form class="form-horizontal" action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/master/client/pic_add_process/');?>
" method="post" >
                <!--Modal body-->
                <div class="modal-body">
                    <input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['result']->value['client_id'];?>
" name="client_id" class="form-control" />
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="pic_name" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['pic_name'])===null||$tmp==='' ? '' : $tmp);?>
" />
                            <small class="form-text text-danger">wajib diisi</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jabatan</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="pic_position" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['pic_position'])===null||$tmp==='' ? '' : $tmp);?>
" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Telepon</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="pic_phone_number" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['pic_phone_number'])===null||$tmp==='' ? '' : $tmp);?>
" />
                            <small class="form-text text-danger">wajib diisi</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="pic_email" type="text" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['pic_email'])===null||$tmp==='' ? '' : $tmp);?>
" />
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
                <h5 class="modal-title">Edit data contact person</h5>
            </div>
            <form class="form-horizontal" action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/master/client/pic_edit_process/');?>
" method="post" >
                <!--Modal body-->
                <div class="modal-body">
                    <input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['result']->value['client_id'];?>
" name="client_id" class="form-control" />
                    <input type="hidden" id="pic_id" name="pic_id" class="form-control" />
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="pic_name" type="text" value="" id="pic_name" />
                            <small class="form-text text-danger">wajib diisi</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jabatan</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="pic_position" type="text" value="" id="pic_position" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Telepon</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="pic_phone_number" type="text" value="" id="pic_phone_number" />
                            <small class="form-text text-danger">wajib diisi</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="pic_email" type="text" value="" id="pic_email" />
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

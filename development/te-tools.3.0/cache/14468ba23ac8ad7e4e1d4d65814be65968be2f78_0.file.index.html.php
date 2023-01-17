<?php
/* Smarty version 3.1.30, created on 2021-09-07 10:12:21
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\project\master\client\index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6136d895e81046_06751600',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '14468ba23ac8ad7e4e1d4d65814be65968be2f78' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\project\\master\\client\\index.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_6136d895e81046_06751600 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <!-- </h1> -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-Projects</a></li>
        <li class="breadcrumb-item active">Client Data</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Data Client</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/master/client/add/');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-plus"></i> Tambah Data</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-search clearfix">
                    <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('project/master/client/proses_cari/');?>
" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <input type="text" name="client_city" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['client_city'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" placeholder="Kota"/>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="client_desc" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['client_desc'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" placeholder="Client"/>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn  btn-dark" type="submit" name="save" value="Cari"><i class="fa fa-search"></i>&nbsp; Cari</button>
                                        <button class="btn  btn-default" type="submit" name="save" value="Reset"><i class="fa fa-refresh"></i>&nbsp; Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="box-body">
                    <!-- notification template -->
                    <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                    <!-- end of notification template-->
                    <table class="table table-bordered">
                        <tr class="text-center">
                            <th width="4%">No</th>
                            <th width="15%">Kota</th>
                            <th width="25%">Nama Client</th>
                            <th width="15%">Nama Alias</th>
                            <th width="30%">Alamat </th>
                            <th width="11%"></th>
                        </tr>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_id']->value, 'result');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['result']->value) {
?>
                        <tr>
                            <td align='center'><?php echo $_smarty_tpl->tpl_vars['no']->value++;?>
.</td>
                            <td><?php echo (($tmp = @mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['client_city'], 'UTF-8'))===null||$tmp==='' ? '' : $tmp);?>
</td>
                            <td><?php echo (($tmp = @mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['client_desc'], 'UTF-8'))===null||$tmp==='' ? '' : $tmp);?>
</td>
                            <td><?php echo (($tmp = @mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['client_nm'], 'UTF-8'))===null||$tmp==='' ? '' : $tmp);?>
</td>
                            <td><?php echo (($tmp = @mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['client_address'], 'UTF-8'))===null||$tmp==='' ? '' : $tmp);?>
</td>
                            <td align="center">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('project/master/client/edit/').($_smarty_tpl->tpl_vars['result']->value['client_id']));?>
" class="btn btn-xs white text-success"><i class="fa fa-edit"></i></a>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('project/master/client/delete/').($_smarty_tpl->tpl_vars['result']->value['client_id']));?>
" class="btn btn-xs white text-danger"><i class="fa fa-trash"></i></a>                    
                            </td>
                        </tr>    
                        <?php
}
} else {
?>

                        <tr>
                            <td colspan="6">Data not found!</td>
                        </tr>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                    </table>
                </div>
                <div class="box-body pt-0">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="pagination">
                                <li>Menampilkan <?php echo (($tmp = @$_smarty_tpl->tpl_vars['pagination']->value['start'])===null||$tmp==='' ? 0 : $tmp);?>
 - <?php echo (($tmp = @$_smarty_tpl->tpl_vars['pagination']->value['end'])===null||$tmp==='' ? 0 : $tmp);?>
 dari total <?php echo (($tmp = @$_smarty_tpl->tpl_vars['pagination']->value['total'])===null||$tmp==='' ? 0 : $tmp);?>
 data</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <nav class="pull-right">
                                <ul class="pagination">
                                    <li class="page-item"></li><?php echo (($tmp = @$_smarty_tpl->tpl_vars['pagination']->value['data'])===null||$tmp==='' ? '' : $tmp);?>

                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php }
}

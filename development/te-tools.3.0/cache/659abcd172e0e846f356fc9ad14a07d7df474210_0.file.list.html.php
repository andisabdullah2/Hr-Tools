<?php
/* Smarty version 3.1.30, created on 2021-09-17 10:46:05
  from "/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/presensi/list.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_61440f7db25017_63927689',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '659abcd172e0e846f356fc9ad14a07d7df474210' => 
    array (
      0 => '/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/presensi/list.html',
      1 => 1631850363,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_61440f7db25017_63927689 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-HR</a></li>
        <li class="breadcrumb-item"><a href="#">Data Pendukung</a></li>
        <li class="breadcrumb-item active">Mesin Presensi</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Data Mesin Presensi</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/presensi/add/');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-plus"></i> Tambah Data</a>                          
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-search clearfix">
                    <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/presensi/proses_cari/');?>
" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-4">                                        
                                        <input type="text" name="mesin_ip" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['mesin_ip'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" placeholder="IP Mesin"/>                                          
                                    </div>                                                    
                                    <div class="col-md-4">
                                        <input type="text" name="mesin_lokasi" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['mesin_lokasi'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" placeholder="Lokasi Mesin"/>                                          
                                    </div>                                                                                   
                                    <div class="col-md-2">
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
                                <th width="">IP Mesin</th>
                                <th width="">Lokasi Mesin</th>                                
                                <th width="">Waktu Diubah</th>        
                                <th width="8%"></th>                        
                        </tr>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_mesin']->value, 'result');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['result']->value) {
?>
                        <?php $_smarty_tpl->_assignInScope('total_data_presented', $_smarty_tpl->tpl_vars['total_data_presented']->value+1);
?>
                        <tr <?php if (($_smarty_tpl->tpl_vars['no']->value%2) <> 1) {?>class="blink-row"<?php }?>>
                                <td align="center"><?php echo $_smarty_tpl->tpl_vars['no']->value++;?>
.</td>                                
                                <td align="center"><?php echo $_smarty_tpl->tpl_vars['result']->value['mesin_ip'];?>
</td>                                
                                <td align="center"><?php echo $_smarty_tpl->tpl_vars['result']->value['mesin_lokasi'];?>
</td>                                                                                                                             
                                <td align="center"><?php echo $_smarty_tpl->tpl_vars['dtm']->value->get_full_date((($tmp = @$_smarty_tpl->tpl_vars['result']->value['mdd'])===null||$tmp==='' ? '' : $tmp));?>
</td>                                
                                <td align="center">
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('kepegawaian/master/presensi/edit/').($_smarty_tpl->tpl_vars['result']->value['mesin_id']));?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['mesin_ip'];?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['mesin_lokasi'];?>
" class="btn btn-xs white text-success"><i class="fa fa-edit"></i></a>
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('kepegawaian/master/presensi/delete/').($_smarty_tpl->tpl_vars['result']->value['mesin_id']));?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['mesin_ip'];?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['mesin_lokasi'];?>
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
 </li>
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

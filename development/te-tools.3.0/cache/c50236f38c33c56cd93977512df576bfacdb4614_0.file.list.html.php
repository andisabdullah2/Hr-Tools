<?php
/* Smarty version 3.1.30, created on 2021-09-13 10:07:49
  from "/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/kuota_cuti/list.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_613ec08524f6c0_47058377',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c50236f38c33c56cd93977512df576bfacdb4614' => 
    array (
      0 => '/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/kuota_cuti/list.html',
      1 => 1631071316,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_613ec08524f6c0_47058377 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <!-- <h1> -->
        <!-- Human Resource Tools -->
    <!-- </h1> -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-HR</a></li>
        <li class="breadcrumb-item"><a href="#">Data Kepegawaian</a></li>
        <li class="breadcrumb-item active">Data Kuota Cuti Karyawan</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                  <h4 class="box-title">Data Kuota Cuti Karyawan</h4>
                  <div class="box-tool">
                      <ul class="nav nav-xs">
                          <li class="nav-item">
                              <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/kuota_cuti/add/');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-plus"></i> Tambah Data</a>                          
                          </li>
                      </ul>
                  </div>
                </div>
                <div class="box-search clearfix">
                    <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/kuota_cuti/proses_cari/');?>
" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-3">
                                      <input type="text" name="full_name" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['full_name'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" placeholder="Nama Karyawan"/>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input tabindex="1" maxlength="4" size="4" type="number" class="form-control" data-plugin="datepicker" data-option="{format: 'yyyy', clearBtn: true, viewMode:'years',minViewMode:'years'}" placeholder="Tahun" name="tahun" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['tahun'])===null||$tmp==='' ? '' : $tmp);?>
" style="font-weight: bold; text-align: center;"/>
                                            <span class="input-group-addon-custom">
                                                <span class="fa fa-calendar"></span>
                                            </span>
                                        </div>
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
                                <th width="13%">Nama</th>
                                <th width="6%">Jenis Cuti</th>
                                <th width="7%">Tahun</th>
                                <th width="8%">Kuota Cuti</th>
                                <th width="4%"></th>
                        </tr>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_id']->value, 'result');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['result']->value) {
?>
                        <?php $_smarty_tpl->_assignInScope('total_data_presented', $_smarty_tpl->tpl_vars['total_data_presented']->value+1);
?>
                        <tr <?php if (($_smarty_tpl->tpl_vars['no']->value%2) <> 1) {?>class="blink-row"<?php }?>>
                                <td align="center"><?php echo $_smarty_tpl->tpl_vars['no']->value++;?>
.</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['result']->value['nama_lengkap'];?>
</td>
                                <td align="center"> <?php echo $_smarty_tpl->tpl_vars['result']->value['jenis'];?>
 </td>
                                <td align="center"><?php echo $_smarty_tpl->tpl_vars['result']->value['tahun'];?>
</td>
                                <td align="center"><?php echo $_smarty_tpl->tpl_vars['result']->value['total'];?>
</td>
                                <td align="center">
                                  <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('kepegawaian/master/kuota_cuti/edit/').($_smarty_tpl->tpl_vars['result']->value['user_id']));?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['tahun'];?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['jenis_id'];?>
" class="btn btn-xs white text-success"><i class="fa fa-edit"></i></a>
                                  <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('kepegawaian/master/kuota_cuti/delete/').($_smarty_tpl->tpl_vars['result']->value['user_id']));?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['tahun'];?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['jenis_id'];?>
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

<?php
/* Smarty version 3.1.30, created on 2021-09-07 09:53:49
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\kepegawaian\master\struktur_organisasi\list.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6136d43df386d7_12138485',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0daab749c6c56a423686bf1dcf8829fba79f37cf' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\kepegawaian\\master\\struktur_organisasi\\list.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_6136d43df386d7_12138485 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-HR</a></li>
        <li class="breadcrumb-item"><a href="#">Data Kepegawaian</a></li>
        <li class="breadcrumb-item active">Struktur Organisasi</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Struktur Organisasi</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/struktur_organisasi/add/');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-plus"></i> Tambah Data</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-divider m-0"></div>
                <div class="box-search">
                  <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/struktur_organisasi/search_process');?>
" method="post">
                    <div class="row">
                      <div class="col-md-5">
                        <div class="form-group">
                          <select name="struktur_induk" data-plugin="select2" data-option="{}" data-placeholder="Pilih Induk Struktur" class="form-control">
                            <option value=""></option>
                              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_all']->value, 'data');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
?>
                              <option value="<?php echo $_smarty_tpl->tpl_vars['data']->value['struktur_cd'];?>
" <?php if ($_smarty_tpl->tpl_vars['search']->value['struktur_induk'] == $_smarty_tpl->tpl_vars['data']->value['struktur_cd']) {?>selected='selected'<?php }?>><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['data']->value['struktur_nama'], 'UTF-8');?>
</option>
                              <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group">
                            <button type="submit" class="btn dark" name="save" value="Cari"><i class="fa fa-search"></i> Cari</button>
                            <button type="submit" class="btn white" name="save" value="Reset"><i class="fa fa-refresh"></i> Reset</button>
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
                                <th width="6%">Kode</th>
                                <th width="40%">Nama Struktur</th>
                                <th width="20%">Keterangan</th>
                                <th width="20%">Level</th>
                                <th width="10%"></th>
                        </tr>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_id']->value, 'result');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['result']->value) {
?>
                        <tr>
                          <td align="center"><?php echo $_smarty_tpl->tpl_vars['no']->value++;?>
.</td>
                          <td align="center"><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['struktur_kode'], 'UTF-8');?>
</td>
                          <td <?php if (($_smarty_tpl->tpl_vars['result']->value['struktur_level'] == 1)) {?>style="padding-left:1rem"<?php } elseif (($_smarty_tpl->tpl_vars['result']->value['struktur_level'] == 2)) {?>style="padding-left:2rem"<?php }?>> <b><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['struktur_nama'], 'UTF-8');?>
 (<?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['struktur_singkatan'], 'UTF-8');?>
)</b></td>
                          <td align="center"><?php echo $_smarty_tpl->tpl_vars['result']->value['struktur_keterangan'];?>
</td>
                          <td align="center"><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['struktur_level_label'], 'UTF-8');?>
</td>
                          <td align="center">
                            <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('kepegawaian/master/struktur_organisasi/edit/').($_smarty_tpl->tpl_vars['result']->value['struktur_cd']));?>
" class="btn btn-xs white text-success"><i class="fa fa-edit"></i></a>
                            <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('kepegawaian/master/struktur_organisasi/delete/').($_smarty_tpl->tpl_vars['result']->value['struktur_cd']));?>
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
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="pagination pagination-sm">
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

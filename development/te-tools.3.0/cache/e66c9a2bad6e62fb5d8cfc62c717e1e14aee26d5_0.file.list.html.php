<?php
<<<<<<< HEAD
<<<<<<< HEAD:development/te-tools.3.0/cache/e66c9a2bad6e62fb5d8cfc62c717e1e14aee26d5_0.file.list.html.php
/* Smarty version 3.1.30, created on 2021-09-07 16:37:27
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\kepegawaian\master\jabatan_struktural\list.html" */
=======
/* Smarty version 3.1.30, created on 2021-09-14 14:52:58
  from "/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/pegawai_unit_kerja/list.html" */
>>>>>>> 8f8bde41a5bd67febd5a1be3b8e4906159eacf47:development/te-tools.3.0/cache/c5118ce8d66aa72b2d1600d6ed200aabd4c6db34_0.file.list.html.php
=======
/* Smarty version 3.1.30, created on 2021-09-07 16:37:27
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\kepegawaian\master\jabatan_struktural\list.html" */
>>>>>>> 8f8bde41a5bd67febd5a1be3b8e4906159eacf47

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
<<<<<<< HEAD
<<<<<<< HEAD:development/te-tools.3.0/cache/e66c9a2bad6e62fb5d8cfc62c717e1e14aee26d5_0.file.list.html.php
=======
>>>>>>> 8f8bde41a5bd67febd5a1be3b8e4906159eacf47
  'unifunc' => 'content_613732d7d351b4_29034637',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e66c9a2bad6e62fb5d8cfc62c717e1e14aee26d5' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\kepegawaian\\master\\jabatan_struktural\\list.html',
      1 => 1630653434,
<<<<<<< HEAD
=======
  'unifunc' => 'content_614054da5de3d5_01390902',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c5118ce8d66aa72b2d1600d6ed200aabd4c6db34' => 
    array (
      0 => '/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/pegawai_unit_kerja/list.html',
      1 => 1631071316,
>>>>>>> 8f8bde41a5bd67febd5a1be3b8e4906159eacf47:development/te-tools.3.0/cache/c5118ce8d66aa72b2d1600d6ed200aabd4c6db34_0.file.list.html.php
=======
>>>>>>> 8f8bde41a5bd67febd5a1be3b8e4906159eacf47
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
<<<<<<< HEAD
<<<<<<< HEAD:development/te-tools.3.0/cache/e66c9a2bad6e62fb5d8cfc62c717e1e14aee26d5_0.file.list.html.php
function content_613732d7d351b4_29034637 (Smarty_Internal_Template $_smarty_tpl) {
=======
function content_614054da5de3d5_01390902 (Smarty_Internal_Template $_smarty_tpl) {
>>>>>>> 8f8bde41a5bd67febd5a1be3b8e4906159eacf47:development/te-tools.3.0/cache/c5118ce8d66aa72b2d1600d6ed200aabd4c6db34_0.file.list.html.php
=======
function content_613732d7d351b4_29034637 (Smarty_Internal_Template $_smarty_tpl) {
>>>>>>> 8f8bde41a5bd67febd5a1be3b8e4906159eacf47
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <!-- <h1> -->
        <!-- Human Resource Tools -->
    <!-- </h1> -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-HR</a></li>
        <li class="breadcrumb-item"><a href="#">Data Pendukung</a></li>
        <li class="breadcrumb-item active">Jabatan Struktural</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Data Jabatan Struktural</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/jabatan_struktural/add/');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-plus"></i> Tambah Data</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-search clearfix">
                    <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/jabatan_struktural/proses_cari/');?>
" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <select name="struktur_cd" id="select2-single" data-plugin="select2" class="form-control" data-placeholder="Unit Kerja">
                                            <option value=""></option>
                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_department']->value, 'data');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
?>
                                            <option value="<?php echo $_smarty_tpl->tpl_vars['data']->value['struktur_cd'];?>
" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['struktur_cd'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable1=ob_get_clean();
if ($_prefixVariable1 == $_smarty_tpl->tpl_vars['data']->value['struktur_cd']) {?>selected="selected"<?php }?>><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['data']->value['struktur_nama'], 'UTF-8');?>
</option>
                                            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                                        </select>
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
                                <th width="">Jabatan</th>
                                <th width="">Alias</th>
                                <th width="">Unit Kerja</th>
                                <th width="">Level</th>
                                <th width="23%">Keterangan</th>
                                <th width="8%"></th>
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
                                <td><?php echo $_smarty_tpl->tpl_vars['result']->value['jabatan_nama'];?>
</td>
                                <td><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['jabatan_alias'], 'UTF-8');?>
</td>                                
                                <td><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['struktur_nama'], 'UTF-8');?>
</td>
                                <td align="center"><?php echo $_smarty_tpl->tpl_vars['result']->value['jabatan_level'];?>
</td>
                                <td align="center"><?php echo $_smarty_tpl->tpl_vars['result']->value['jabatan_keterangan'];?>
</td>
                                <td align="center">
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('kepegawaian/master/jabatan_struktural/edit/').($_smarty_tpl->tpl_vars['result']->value['jabatan_struktural_id']));?>
" class="btn btn-xs white text-success"><i class="fa fa-edit"></i></a>
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('kepegawaian/master/jabatan_struktural/delete/').($_smarty_tpl->tpl_vars['result']->value['jabatan_struktural_id']));?>
" class="btn btn-xs white text-danger"><i class="fa fa-trash"></i></a>
                                </td>
                        </tr>
                        <?php
}
} else {
?>

                        <tr>
                                <td colspan="7">Data not found!</td>
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

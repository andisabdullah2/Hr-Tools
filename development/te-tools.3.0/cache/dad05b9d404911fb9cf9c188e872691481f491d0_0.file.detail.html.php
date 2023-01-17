<?php
/* Smarty version 3.1.30, created on 2021-09-14 08:19:56
  from "/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/kehadiran/detail.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_613ff8bc82b235_11611469',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dad05b9d404911fb9cf9c188e872691481f491d0' => 
    array (
      0 => '/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/kehadiran/detail.html',
      1 => 1631071316,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_613ff8bc82b235_11611469 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-HR</a></li>
        <li class="breadcrumb-item"><a href="#">Data Kepegawaian</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/kehadiran/');?>
">Kehadiran Karyawan</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <!-- Basic Forms -->
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Data Presensi Karyawan</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/kehadiran');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-arrow-left"></i> Kembali</a>                                
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-divider m-0"></div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- notification template -->
                    <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                    <!-- end of notification template-->
                    <table class="table" width="100%">
                        <tbody>
                            <tr>
                                <td align="center" width='15%' rowspan="4">
                                    <img src="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['employee_img']->value)===null||$tmp==='' ? '' : $tmp);?>
" alt="" style="height: 100px; background-color: #fff; border: 1px solid #ccc; padding: 5px;" />
                                </td>
                                <td width="15%">NIK</td>
                                <td width="70%"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['pegawai_nip'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                            </tr>
                            <tr>
                                <td>Nama Lengkap</td>
                                <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['nama_lengkap'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                            </tr>
                            <tr>
                                <td>Nomor Telepon</td>
                                <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['nomor_telepon'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['user_mail'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                            </tr>
                            <tr>
                                <td>Tanggal Rekap</td>
                                <td colspan="3">
                                    <b><?php echo $_smarty_tpl->tpl_vars['dtm']->value->get_full_date((($tmp = @$_smarty_tpl->tpl_vars['search']->value['date_start'])===null||$tmp==='' ? '' : $tmp),'ins');?>
</b> s/d <b><?php echo $_smarty_tpl->tpl_vars['dtm']->value->get_full_date((($tmp = @$_smarty_tpl->tpl_vars['search']->value['date_end'])===null||$tmp==='' ? '' : $tmp),'ins');?>
</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered" width="100%">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th width="15%">Tanggal</th>
                            <th width="10%">Waktu Masuk</th>
                            <th width="10%">Keterlambatan</th>
                            <th width="10%">Waktu Pulang</th>
                            <th width="50%">Keterangan</th>
                        </tr>
                        <?php $_smarty_tpl->_assignInScope('total', 0);
?>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_id']->value, 'result');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['result']->value) {
?>
                        <tr <?php if (($_smarty_tpl->tpl_vars['no']->value%2) <> 1) {?>class="blink-row"<?php }?>>
                            <td align="center"><?php echo $_smarty_tpl->tpl_vars['no']->value++;?>
.</td>
                            <td align="center"><?php echo $_smarty_tpl->tpl_vars['dtm']->value->get_full_date($_smarty_tpl->tpl_vars['result']->value['kehadiran_tanggal']);?>
</td>
                            <td align="center"><?php echo $_smarty_tpl->tpl_vars['result']->value['kehadiran_jam_masuk'];?>
</td>
                            <td align="center">
                                <?php if ($_smarty_tpl->tpl_vars['result']->value['otp'] == 0) {?>
                                <b style="color: red;">
                                    <?php echo $_smarty_tpl->tpl_vars['result']->value['keterlambatan'];?>

                                    <?php echo $_smarty_tpl->tpl_vars['total']->value++;?>

                                </b>
                                <?php } else { ?>
                                -
                                <?php }?>
                            </td>
                            <td align="center"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['kehadiran_jam_pulang'])===null||$tmp==='' ? "-" : $tmp);?>
</td>
                            <td align="center"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['kehadiran_keterangan'])===null||$tmp==='' ? '' : $tmp);?>
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

                        <tr>                
                            <td colspan="3" align='center'><b>Prosentase</b></td>
                            <td colspan="1" align="center"><b><?php echo (($tmp = @number_format((((($_smarty_tpl->tpl_vars['no']->value-1)-$_smarty_tpl->tpl_vars['total']->value)/($_smarty_tpl->tpl_vars['no']->value-1))*100),2,',','.'))===null||$tmp==='' ? '0' : $tmp);?>
</b></td>
                            <td colspan="2"> </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section><?php }
}

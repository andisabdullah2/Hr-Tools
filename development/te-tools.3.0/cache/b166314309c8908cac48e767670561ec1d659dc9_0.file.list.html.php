<?php
/* Smarty version 3.1.30, created on 2021-09-14 08:19:48
  from "/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/kehadiran/list.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_613ff8b4d21412_33140668',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b166314309c8908cac48e767670561ec1d659dc9' => 
    array (
      0 => '/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/kehadiran/list.html',
      1 => 1631071316,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_613ff8b4d21412_33140668 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <!-- <h1> -->
        <!-- Human Resource Tools -->
    <!-- </h1> -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-HR</a></li>
        <li class="breadcrumb-item"><a href="#">Data Kepegawaian</a></li>
        <li class="breadcrumb-item active"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/kehadiran/');?>
">Kehadiran Karyawan</a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                  <h4 class="box-title">Data Kehadiran Karyawan</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/kehadiran/import/');?>
" class="btn btn-primary"><i class="fa fa-retweet"></i> Synchronize</a>
                            </li>
                        </ul>
                    </div>                  
                </div>
                <div class="box-search clearfix">
                    <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/kehadiran/proses_cari/');?>
" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <input type="text" name="full_name" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['full_name'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control" placeholder="Nama"/>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                                <select name="bulan" id="select2-single" data-plugin="select2" class="form-control" data-placeholder="Bulan">
                                                <option value="" selected="selected" disabled="disabled">Bulan</option>
                                                <option value="01" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['bulan'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable1=ob_get_clean();
if ($_prefixVariable1 == "01") {?>selected="selected"<?php }?>>Januari</option>
                                                <option value="02" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['bulan'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable2=ob_get_clean();
if ($_prefixVariable2 == "02") {?>selected="selected"<?php }?>>Februari</option>
                                                <option value="03" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['bulan'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable3=ob_get_clean();
if ($_prefixVariable3 == "03") {?>selected="selected"<?php }?>>Maret</option>
                                                <option value="04" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['bulan'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable4=ob_get_clean();
if ($_prefixVariable4 == "04") {?>selected="selected"<?php }?>>April</option>
                                                <option value="05" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['bulan'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable5=ob_get_clean();
if ($_prefixVariable5 == "05") {?>selected="selected"<?php }?>>Mei</option>
                                                <option value="06" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['bulan'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable6=ob_get_clean();
if ($_prefixVariable6 == "06") {?>selected="selected"<?php }?>>Juni</option>
                                                <option value="07" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['bulan'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable7=ob_get_clean();
if ($_prefixVariable7 == "07") {?>selected="selected"<?php }?>>Juli</option>
                                                <option value="08" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['bulan'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable8=ob_get_clean();
if ($_prefixVariable8 == "08") {?>selected="selected"<?php }?>>Agustus</option>
                                                <option value="09" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['bulan'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable9=ob_get_clean();
if ($_prefixVariable9 == "09") {?>selected="selected"<?php }?>>September</option>
                                                <option value="10" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['bulan'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable10=ob_get_clean();
if ($_prefixVariable10 == "10") {?>selected="selected"<?php }?>>Oktober</option>
                                                <option value="11" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['bulan'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable11=ob_get_clean();
if ($_prefixVariable11 == "11") {?>selected="selected"<?php }?>>November</option>
                                                <option value="12" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['bulan'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable12=ob_get_clean();
if ($_prefixVariable12 == "12") {?>selected="selected"<?php }?>>Desember</option>
                                                </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                                <input tabindex="1" maxlength="4" size="4" type="number" class="form-control" data-plugin="datepicker" data-option="{format: 'yyyy', clearBtn: true, viewMode:'years',minViewMode:'years'}" placeholder="Tahun" name="tahun" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['tahun'])===null||$tmp==='' ? '' : $tmp);?>
" style="height:33px;font-weight: bold; text-align: center;"/>
                                                <span class="input-group-addon-custom" style="height:33px">
                                                <span class="fa fa-calendar"></span>
                                                </span>
                                        </div>
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
                                <th width="21%">Nama Karyawan</th>
                                <th width="9%">NIK</th>
                                <th width="10%">Hari Kerja</th>
                                <th width="10%">Ijin / Cuti</th>
                                <th width="10%">Perjalanan Dinas</th>
                                <th width="10%">Kehadiran</th>
                                <th width="10%">Tepat Waktu</th>
                                <th width="8%">AP</th>
                                <th width="8%">OTP</th>
                                <th width="11%"></th>
                            </tr>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_kehadiran']->value, 'result');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['result']->value) {
?>
                            <tr <?php if (($_smarty_tpl->tpl_vars['no']->value%2) <> 1) {?>class="blink-row"<?php }?>>
                                <?php $_smarty_tpl->_assignInScope('total_data_presented', $_smarty_tpl->tpl_vars['total_data_presented']->value+1);
?>                            
                                <td align="center"><?php echo $_smarty_tpl->tpl_vars['no']->value++;?>
.</td>
                                <td><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['nama_lengkap'], 'UTF-8');?>
</td>
                                <td align="center"><b><?php echo $_smarty_tpl->tpl_vars['result']->value['nik'];?>
</b></td>
                                <td align="center"><?php echo $_smarty_tpl->tpl_vars['working_days']->value;?>
</td>
                                <td align="center"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['total_ijin'])===null||$tmp==='' ? '-' : $tmp);?>
</td>
                                <td align="center"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['total_jaldin'])===null||$tmp==='' ? '-' : $tmp);?>
</td>
                                <td align="center"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['total_presensi'])===null||$tmp==='' ? '-' : $tmp);?>
</td>
                                <td align="center"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['otp'])===null||$tmp==='' ? '-' : $tmp);?>
</td>
                                <td align="center">
                                    <?php if ($_smarty_tpl->tpl_vars['result']->value['total_presensi'] != '0') {?>
                                    <?php $_smarty_tpl->_assignInScope('ap', ($_smarty_tpl->tpl_vars['result']->value['total_presensi']/($_smarty_tpl->tpl_vars['working_days']->value-$_smarty_tpl->tpl_vars['result']->value['total_ijin']-$_smarty_tpl->tpl_vars['result']->value['total_jaldin']))*100);
?>
                                    <?php if ($_smarty_tpl->tpl_vars['ap']->value >= 90) {?>
                                    <b style="color: green;"><?php echo number_format($_smarty_tpl->tpl_vars['ap']->value,2,',','.');?>
</b>
                                    <?php } elseif ($_smarty_tpl->tpl_vars['ap']->value >= 80) {?>
                                    <b style="color: #2191c0;"><?php echo number_format($_smarty_tpl->tpl_vars['ap']->value,2,',','.');?>
</b>
                                    <?php } elseif ($_smarty_tpl->tpl_vars['ap']->value >= 60) {?>
                                    <b style="color: orangered;"><?php echo number_format($_smarty_tpl->tpl_vars['ap']->value,2,',','.');?>
</b>
                                    <?php } else { ?>
                                    <b style="color: red;"><?php echo number_format($_smarty_tpl->tpl_vars['ap']->value,2,',','.');?>
</b>
                                    <?php }?>
                                    <?php } else { ?>
                                    -
                                    <?php }?>
                                </td>
                                <td align="center">
                                    <?php if ($_smarty_tpl->tpl_vars['result']->value['total_presensi'] != '0') {?>
                                    <?php $_smarty_tpl->_assignInScope('otp', ($_smarty_tpl->tpl_vars['result']->value['otp']/$_smarty_tpl->tpl_vars['result']->value['total_presensi'])*100);
?>
                                    <?php if ($_smarty_tpl->tpl_vars['otp']->value >= 85) {?>
                                    <b style="color: green;"><?php echo number_format($_smarty_tpl->tpl_vars['otp']->value,2,',','.');?>
</b>
                                    <?php } elseif ($_smarty_tpl->tpl_vars['otp']->value >= 75) {?>
                                    <b style="color: #2191c0;"><?php echo number_format($_smarty_tpl->tpl_vars['otp']->value,2,',','.');?>
</b>
                                    <?php } elseif ($_smarty_tpl->tpl_vars['otp']->value >= 60) {?>
                                    <b style="color: orangered;"><?php echo number_format($_smarty_tpl->tpl_vars['otp']->value,2,',','.');?>
</b>
                                    <?php } else { ?>
                                    <b style="color: red;"><?php echo number_format($_smarty_tpl->tpl_vars['otp']->value,2,',','.');?>
</b>
                                    <?php }?>
                                    <?php } else { ?>
                                    -
                                    <?php }?>
                                </td>
                                <td align="center">
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url(('kepegawaian/master/kehadiran/detail/').($_smarty_tpl->tpl_vars['result']->value['user_id']));?>
" class="btn btn-xs white text-success"><i class="fa fa-bars"></i></a>
                                </td>                                
                            </tr>
                            <?php
}
} else {
?>

                            <tr>                
                                <td colspan="10">Data not found!</td>
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
                                                        <!-- <li class="page-item"><strong><?php echo (($tmp = @$_smarty_tpl->tpl_vars['pagination']->value['total'])===null||$tmp==='' ? 0 : $tmp);?>
 </strong> Record&nbsp;</li><?php echo (($tmp = @$_smarty_tpl->tpl_vars['pagination']->value['data'])===null||$tmp==='' ? '' : $tmp);?>
 -->
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

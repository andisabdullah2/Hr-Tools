<?php
/* Smarty version 3.1.30, created on 2021-09-06 12:14:05
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\home\welcome\dashboard\index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6135a39d1ac343_11334833',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '558f5a7dbb023c916ebc59f1d957d4c55f09927e' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\home\\welcome\\dashboard\\index.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6135a39d1ac343_11334833 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="lt d-flex mb-3">
    <div class="flex">
        <h1 class="text-md mb-1 _400">Selamat datang kembali, <span><?php echo $_smarty_tpl->tpl_vars['com_user']->value['nama_lengkap'];?>
</span></h1>
        <small class="text-muted">
            Anda login terakhir pada tanggal <?php echo (($tmp = @$_smarty_tpl->tpl_vars['last_login']->value[1])===null||$tmp==='' ? ((($tmp = @$_smarty_tpl->tpl_vars['last_login']->value[0])===null||$tmp==='' ? '-' : $tmp)) : $tmp);?>

        </small>
    </div>
    <div>
        <small class="text-md text-white d-block btn btn-dark"><?php echo $_smarty_tpl->tpl_vars['hari_ini']->value['tahun'];?>
</small>
    </div>
</div>
<div class="row">
    <div class="col-6 col-lg-3">
        <div class="box list-item">
            <span class="avatar w-40 text-center rounded primary">
                <span class="fa fa-car"></span>
            </span>
            <div class="list-body">
                <h4 class="m-0 text-sm"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('home/task/lpj');?>
" style="font-weight: bold;">Jaldin</a></h4>
                <small class="text-muted"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['total_lpj']->value)===null||$tmp==='' ? 0 : $tmp);?>
 Waiting</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="box list-item">
            <span class="avatar w-40 text-center rounded warning">
                <span class="fa fa-male"></span>
            </span>
            <div class="list-body">
                <h4 class="m-0 text-sm"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('home/task/permit');?>
" style="font-weight: bold;">Cuti</a></h4>
                <small class="text-muted"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['total_cuti_tahunan']->value['total_cuti'])===null||$tmp==='' ? 0 : $tmp);?>
 / <?php echo (($tmp = @$_smarty_tpl->tpl_vars['total_cuti_tahunan']->value['total_kuota'])===null||$tmp==='' ? 0 : $tmp);?>
</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="box list-item">
            <span class="avatar w-40 text-center rounded danger">
                <span class="fa fa-ambulance"></span>
            </span>
            <div class="list-body">
                <h4 class="m-0 text-sm"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('home/task/leave');?>
" style="font-weight: bold;">Ijin</a></h4>
                <small class="text-muted"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['total_ijin_tahunan']->value['total_sakit'])===null||$tmp==='' ? 0 : $tmp);?>
 S / <?php echo (($tmp = @$_smarty_tpl->tpl_vars['total_ijin_tahunan']->value['total_ijin'])===null||$tmp==='' ? 0 : $tmp);?>
 L</small>
            </div>
        </div>
    </div>
    <?php if ((($tmp = @$_smarty_tpl->tpl_vars['total_task']->value)===null||$tmp==='' ? '0' : $tmp) != '0') {?>
    <div class="col-6 col-lg-3">
        <div class="box list-item">
            <span class="avatar w-40 text-center rounded success">
                <span class="fa fa-check-circle fa-2x"></span>
            </span>
            <div class="list-body">
                <h4 class="m-0 text-sm"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('home/task/approval');?>
" style="font-weight: bold;">Approval</a></h4>
                <small class="text-muted"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['total_approval']->value)===null||$tmp==='' ? 0 : $tmp);?>
 Waiting</small>
            </div>

        </div>
    </div>
    <?php }?>
</div>
<div>
    <div class="row no-gutters box">
        <div class="col-sm-4">
            <div class="box">
                <div class="box-header">
                    <h3>Kedisiplinan Saya</h3>
                    <small>Bulan <?php echo $_smarty_tpl->tpl_vars['hari_ini']->value['bulan'];?>
 ( <?php echo (($tmp = @$_smarty_tpl->tpl_vars['total_hari_kerja']->value)===null||$tmp==='' ? 0 : $tmp);?>
 Hari Kerja )</small>
                </div>
                <div class="box-body">
                    <div class="list-group box">
                        <a href="#" class="list-group-item" onclick="return false;">
                            <span class="float-right badge warning text-sm"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['total_keterlambatan']->value)===null||$tmp==='' ? 0 : $tmp);?>
 Hari Kerja</span>
                            Keterlambatan
                        </a>
                        <a href="#" class="list-group-item" onclick="return false;">
                            <span class="float-right badge danger text-sm"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['total_ijin']->value)===null||$tmp==='' ? 0 : $tmp);?>
 Hari Kerja</span>
                            Ijin
                        </a>
                        <a href="#" class="list-group-item" onclick="return false;">
                            <span class="float-right badge info text-sm"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['total_cuti']->value)===null||$tmp==='' ? 0 : $tmp);?>
 Hari Kerja</span>
                            Cuti
                        </a>
                        <a href="#" class="list-group-item" onclick="return false;">
                            <span class="float-right badge success text-sm"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['total_lembur']->value)===null||$tmp==='' ? 0 : $tmp);?>
 Hari Kerja</span>
                            Lembur
                        </a>
                        <a href="#" class="list-group-item" onclick="return false;">
                            <span class="float-right badge primary text-sm"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['total_jaldin']->value)===null||$tmp==='' ? 0 : $tmp);?>
 Hari Kerja</span>
                            Perjalanan Dinas
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="padding light lt">
                <div id="chart_otp" style="height: 350px;"></div>
            </div>
        </div>
    </div>
</div>
<div>
    <div class="row">
        <div class="col-md-4">
            <div class="padding box">
                <h6 class="mb-3">Perjalanan Dinas Saya</h6>
                <hr />
                <div class="streamline streamline-xs">
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_jaldin']->value, 'jaldin');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['jaldin']->value) {
?>
                    <?php if ($_smarty_tpl->tpl_vars['jaldin']->value['spt_status'] == 'approved') {?>
                    <div class="sl-item b-success">
                        <div class="sl-content">
                            <span class="sl-date text-muted"><?php echo $_smarty_tpl->tpl_vars['dtm']->value->get_full_date($_smarty_tpl->tpl_vars['jaldin']->value['tanggal_berangkat']);?>
</span>
                            <div>
                                <a href="#" class="text-primary" onclick="return false;"><?php echo $_smarty_tpl->tpl_vars['jaldin']->value['client_nm'];?>
</a>
                                <?php echo $_smarty_tpl->tpl_vars['jaldin']->value['uraian_tugas'];?>

                                <br />
                                <span class="text-info"><?php echo $_smarty_tpl->tpl_vars['jaldin']->value['lokasi_tujuan'];?>
</span>                                
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="sl-item b-warning">
                        <div class="sl-content">
                            <span class="sl-date text-muted"><?php echo $_smarty_tpl->tpl_vars['dtm']->value->get_full_date($_smarty_tpl->tpl_vars['jaldin']->value['tanggal_berangkat']);?>
</span>
                            <div>
                                <a href="#" class="text-primary" onclick="return false;"><?php echo $_smarty_tpl->tpl_vars['jaldin']->value['client_nm'];?>
</a>
                                <?php echo $_smarty_tpl->tpl_vars['jaldin']->value['uraian_tugas'];?>

                                <br />
                                <span class="text-info"><?php echo $_smarty_tpl->tpl_vars['jaldin']->value['lokasi_tujuan'];?>
</span>                                
                            </div>
                        </div>
                    </div>
                    <?php }?>
                    <?php
}
} else {
?>

                    <div class="sl-item b-default">
                        <div class="sl-content">
                            <span class="sl-date text-muted">Belum ada data perjalanan dinas yang tercatat.</span>
                        </div>
                    </div>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="padding box">
                <h6 class="mb-3">Pengumuman / Agenda Kegiatan</h6>
                <hr />
                <div class="streamline">
                    <div class="sl-item  b-">
                        <div class="sl-content">
                            <span class="sl-date text-muted">08 Januari 2019 Jam 08:00</span>
                            <div>
                                <a href="#" class="text-primary">Kegiatan Rutin</a>
                                Pengajian Rutin Bulanan
                                <br />
                                <span class="text-info">Jumat, 12 Februari 2019</span>
                            </div>
                        </div>
                    </div>
                    <div class="sl-item  b-">
                        <div class="sl-content">
                            <span class="sl-date text-muted">08 Januari 2019 Jam 08:00</span>
                            <div>
                                <a href="#" class="text-primary">Info Sakit</a>
                                Menjenguk Pak Anto, diharapkan yang tidak berhalangan bisa ikut.
                                <br />
                                <span class="text-info">Jumat, 12 Februari 2019</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="padding box">
                <h6>
                    Personel Information, 
                    <small class="text-primary text-xs"><?php echo $_smarty_tpl->tpl_vars['hari_ini']->value['hari'];?>
, <?php echo $_smarty_tpl->tpl_vars['hari_ini']->value['tanggal'];?>
 <?php echo $_smarty_tpl->tpl_vars['hari_ini']->value['bulan'];?>
 <?php echo $_smarty_tpl->tpl_vars['hari_ini']->value['tahun'];?>
</small>
                </h6>
                <hr />
                <span class="text-muted">
                    <span class="badge badge-primary">J</span> Perjalanan Dinas
                    <span class="badge badge-warning">C</span> Cuti
                    <span class="badge badge-danger">S</span> Ijin Sakit
                    <span class="badge badge-danger">I</span> Ijin Lainnya
                </span>
                <hr />
                <div class="list inset">
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_personel_info']->value, 'personel_info');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['personel_info']->value) {
?>
                    <div class="list-item">
                        <span class="w-24 avatar circle <?php if ($_smarty_tpl->tpl_vars['personel_info']->value['jenis_kode'] == 'J') {?>primary<?php } elseif ($_smarty_tpl->tpl_vars['personel_info']->value['jenis_kode'] == 'C') {?>warning<?php } else { ?>danger<?php }?>">
                            <?php echo $_smarty_tpl->tpl_vars['personel_info']->value['jenis_kode'];?>

                        </span>
                        <div class="list-body">
                            <a href="#" class="item-title _500" onclick="return false;">
                                <?php echo $_smarty_tpl->tpl_vars['personel_info']->value['nama_lengkap'];?>

                                <br />
                                <small class="text-muted">
                                    <?php echo $_smarty_tpl->tpl_vars['personel_info']->value['uraian'];?>
,
                                    <i class="text-info" style="font-weight: bold;"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['personel_info']->value['tempat'])===null||$tmp==='' ? '-' : $tmp);?>
</i>
                                </small>
                            </a>
                            <div class="item-tag tag hide"></div>
                        </div>
                    </div>
                    <?php
}
} else {
?>

                    <div class="list-item">
                        <span class="w-24 avatar circle dark">
                            NA
                        </span>
                        <div class="list-body">
                            <a href="#" class="item-title text-muted" onclick="return false;">
                                Tidak ada personil yang tercatat sedang berhalangan.
                            </a>
                            <div class="item-tag tag hide"></div>
                        </div>
                    </div>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php echo '<script'; ?>
 type="text/javascript">
    $(document).ready(function () {
        // Single Chart OTP
        options_otp_chart = {
            chart: {
                renderTo: 'chart_otp',
                type: 'column',
                height: 350
            },
            legend: {
                enabled: false
            },
            title: {
                text: 'On Time Performance'
            },
            subtitle: {
                text: 'Tahun <?php echo $_smarty_tpl->tpl_vars['hari_ini']->value['tahun'];?>
'
            },
            xAxis: {
                categories: [],
                title: {
                    text: 'BULAN'
                }
            },
            yAxis: {
                min: 0,
                max: 100,
                title: {
                    text: 'PROSENTASE'
                }
            },
            exporting: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true
                    },
                    colorByPoint: true
                }
            },
            series: []
        };
        // Get Data Chart
        url = "<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('home/welcome/dashboard/get_data_chart_otp');?>
";
        $.getJSON(url, function (result) {
            options_otp_chart.xAxis.categories = result.categories;
            options_otp_chart.series = result.data_series;
            options_otp_chart.colors = result.colors;
            var chart_otp = new Highcharts.Chart(options_otp_chart);
        });
    });
<?php echo '</script'; ?>
>
<?php }
}

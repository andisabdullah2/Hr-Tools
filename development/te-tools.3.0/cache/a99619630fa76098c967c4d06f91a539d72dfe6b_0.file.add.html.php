<?php
/* Smarty version 3.1.30, created on 2021-09-13 10:09:18
  from "/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/kuota_cuti/add.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_613ec0de451633_23598786',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a99619630fa76098c967c4d06f91a539d72dfe6b' => 
    array (
      0 => '/opt/lampp/htdocs/te-tools/application/views/kepegawaian/master/kuota_cuti/add.html',
      1 => 1631071316,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:base/templates/notification.html' => 1,
  ),
),false)) {
function content_613ec0de451633_23598786 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
    jQuery(document).ready(function() {
        // Default file input style
        $(".file-styled").uniform({
            fileButtonClass: 'action btn btn-default'
        });
    });

<?php echo '</script'; ?>
>
<!-- Content Header (Page header) -->
<section class="content-header">
    <!-- <h1> -->
        <!-- Struktur Organisasi -->
    <!-- </h1> -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">E-HR</a></li>
        <li class="breadcrumb-item"><a href="#">Data Kepegawaian</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/kuota_cuti/');?>
">Kuota Cuti Karyawan</a></li>
        <li class="breadcrumb-item active">Tambah Data</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <!-- Basic Forms -->
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Tambah Data Kuota Cuti</h4>
                    <div class="box-tool">
                        <ul class="nav nav-xs">
                            <li class="nav-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/kuota_cuti/');?>
" class="btn btn-outline b-primary text-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box-divider m-0"></div>
                <!-- /.box-header -->
                <div class="box-body">  
                        <div class="tab-content" style="margin-top:0;">
                                <form action="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('kepegawaian/master/kuota_cuti/add_process');?>
" method="post" id="form" data-plugin="parsley" data-option="{}" novalidate="" class="form-horizontal adminex-form" enctype="multipart/form-data">
                                        <div class="tab-pane active" id="tab1">
                                                <!-- notification template -->
                                                <?php $_smarty_tpl->_subTemplateRender("file:base/templates/notification.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                                                <!-- end of notification template-->
                                                <input type="hidden" name="user_id" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['user_id'])===null||$tmp==='' ? '' : $tmp);?>
"/>
                                                <div class="form-group row">
                                                        <label class="col-md-2 col-form-label">Karyawan</label>
                                                        <div class="col-md-4">
                                                                <select name="user_id" data-plugin="select2" class="form-control select2-single" data-placeholder="Karyawan">
                                                                        <option value=""></option>
                                                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_pegawai']->value, 'data');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
?>
                                                                        <option value="<?php echo $_smarty_tpl->tpl_vars['data']->value['user_id'];?>
" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['user_id'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable1=ob_get_clean();
if ($_prefixVariable1 == $_smarty_tpl->tpl_vars['data']->value['user_id']) {?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value['nama_lengkap'];?>
</option>
                                                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                                                                </select>
                                                                <small class="form-text text-danger"><i>* wajib diisi.</i></small>
                                                        </div>
                                                </div>
                                                <div class="form-group row">
                                                        <label class="col-md-2 col-form-label">Tahun</label>
                                                        <div class="col-md-2">
                                                                <div class="input-group">
                                                                <input tabindex="1" maxlength="4" size="4" type="number" class="form-control" data-plugin="datepicker" data-option="{format: 'yyyy', clearBtn: true, viewMode:'years',minViewMode:'years'}" placeholder="Tahun" name="tahun" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['search']->value['tahun'])===null||$tmp==='' ? '' : $tmp);?>
" style="font-weight: bold; text-align: center;"/>
                                                                <span class="input-group-addon">
                                                                    <span class="fa fa-calendar"></span>
                                                                </span>
                                                                </div>
                                                                <small class="form-text text-danger"><i>* wajib diisi.</i></small>                                                                
                                                        </div>
                                                </div>
                                                <div class="form-group row">
                                                        <label class="col-md-2 col-form-label">Jenis Cuti</label>
                                                        <div class="col-md-2">
                                                                <select class="form-control" name="jenis_id">
                                                                        <option selected="selected" disabled="disabled">--Pilih--</option>
                                                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rs_cuti']->value, 'data');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
?>                                                                        
                                                                        <option value="<?php echo $_smarty_tpl->tpl_vars['data']->value['jenis_id'];?>
" <?php ob_start();
echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['jenis_id'])===null||$tmp==='' ? '' : $tmp);
$_prefixVariable2=ob_get_clean();
if ($_prefixVariable2 == "1") {?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value['jenis_cuti'];?>
</option>
                                                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                                                                </select>
                                                                <small class="form-text text-danger"><i>* wajib diisi.</i></small>
                                                        </div>
                                                </div>                                        
                                                <div class="form-group row">
                                                        <label class="col-md-2 col-form-label">Kuota Cuti</label>
                                                        <div class="col-md-2">
                                                                <input class="form-control" name="total" type="number" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['result']->value['total'])===null||$tmp==='' ? '' : $tmp);?>
" size="3" maxlength="3" />
                                                                <small class="form-text text-danger"><i>* wajib diisi.</i></small>
                                                        </div>
                                                </div>
                                        </div>
                                        <div class="panel-footer">
                                                <div class="form-group row">
                                                <label class="col-md-2 col-form-label"></label>                                                
                                                    <div class="col-md-6 control-label">
                                                        <button type="submit" class="btn btn-success mr5 "><i class="fa fa-check"></i> Simpan</button> &nbsp
                                                        <button type="reset" class="btn btn-default mr5 "><i class="fa fa-times"></i> Reset</button>
                                                    </div>
                                                </div>
                                        </div>
                                </form>                                 
                        </div>
                </div>                                
            </div>
        </div>
    </div>
</section><?php }
}

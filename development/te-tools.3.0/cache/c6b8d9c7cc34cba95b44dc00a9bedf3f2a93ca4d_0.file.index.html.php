<?php
/* Smarty version 3.1.30, created on 2021-09-07 09:49:23
  from "C:\xampp_php_7\htdocs\te-tools\development\te-tools.3.0\application\views\settings\sistem\template\index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6136d333804e99_70042264',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c6b8d9c7cc34cba95b44dc00a9bedf3f2a93ca4d' => 
    array (
      0 => 'C:\\xampp_php_7\\htdocs\\te-tools\\development\\te-tools.3.0\\application\\views\\settings\\sistem\\template\\index.html',
      1 => 1630653434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6136d333804e99_70042264 (Smarty_Internal_Template $_smarty_tpl) {
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Settings</a></li>
        <li class="breadcrumb-item active">CRUD Templates</li>
    </ol>
</nav>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h2>Daftar Data Contoh</h2>
                <small>Keterangan tambahan jika diperlukan disini.</small>
                <div class="box-tool">
                    <ul class="nav nav-xs">
                        <li class="nav-item">
                            <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value->site_url('settings/sistem/template/add');?>
" class="btn btn-sm btn-outline b-primary text-primary">
                                <i class="fa fa-plus mr-1"></i> Tambah Data
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="box-search">
                <form action="#" method="post">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <input type="text" class="form-control" placeholder="Keyword" />
                        </div>
                        <div class="col-md-3 mb-2">
                            <input type="text" class="form-control" placeholder="Enter email" />
                        </div>
                        <div class="col-md-3 mb-2">
                            <select name="pilihan" class="form-control" data-plugin="select2" data-option="{}" data-placeholder="Select an option" style="width: 100%;">
                                <option value=""></option>
                                <option value="AK">Alaska</option>
                                <option value="NV">Nevada</option>
                                <option value="OR">Oregon</option>
                                <option value="WA">Washington</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-dark mb-2"><i class="fa fa-search mr-2"></i>Cari</button>
                            <button type="submit" class="btn btn-default mb-2"><i class="fa fa-times mr-2"></i>Reset</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="box-body pt-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="grey text-uppercase">
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">Table heading</th>
                                <th width="25%">Table heading</th>
                                <th width="25%">Table heading</th>
                                <th width="25%">Table heading</th>
                                <th width="8%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td>Table cell</td>
                                <td>Table cell</td>
                                <td>Table cell</td>
                                <td>Table cell</td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs white text-success"><i class="fa fa-pencil"></i></a>
                                    <a href="#" class="btn btn-xs white text-danger"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td>Table cell</td>
                                <td>Table cell</td>
                                <td>Table cell</td>
                                <td>Table cell</td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs white text-success"><i class="fa fa-pencil"></i></a>
                                    <a href="#" class="btn btn-xs white text-danger"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td>Table cell</td>
                                <td>Table cell</td>
                                <td>Table cell</td>
                                <td>Table cell</td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs white text-success"><i class="fa fa-pencil"></i></a>
                                    <a href="#" class="btn btn-xs white text-danger"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box-body pt-0">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="pagination">
                            <li class="page-item"><span class="paging-label">Halaman 10 dari 10 Halaman</span>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <nav class="pull-right">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">4</a></li>
                                <li class="page-item"><a class="page-link" href="#">5</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php }
}

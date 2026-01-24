<?php
/* Smarty version 5.5.1, created on 2026-01-24 10:40:01
  from 'file:news/index.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69743f1140d393_06898615',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2d123c6e631197acf03991707ca622c5fed2f9a9' => 
    array (
      0 => 'news/index.tpl',
      1 => 1769225994,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69743f1140d393_06898615 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\news';
?><div class="row">
	<div class="col-12">
		<div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
			<div class="flex-grow-1">
				<h4 class="fs-18 text-uppercase fw-bold m-0"><?php echo $_smarty_tpl->getValue('pagename');?>
</h4>
			</div>
		</div><!-- end card header -->
	</div>
	<!--end col-->
</div> <!-- end row-->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <div class="d-flex flex-wrap justify-content-between gap-2">
                    <div class="position-relative">
                        <input type="text" id="searchInput" class="form-control ps-4" placeholder="Search Berita">
                        <i class="ti ti-search position-absolute top-50 translate-middle-y ms-2"></i>
                    </div>

                    <div>
                        <a href="<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')('news/create');?>
" class="btn btn-primary"><i class="ti ti-plus me-1"></i>Add Berita</a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover text-nowrap mb-0" id="dataTable">
                    <thead>
                        <tr>
                            <th width="80">ID</th>
                            <th>Pembuat<br/>Tanggal Post</th>
                            <th>Judul</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end">
                    <ul id="pagination" class="pagination pagination-rounded pagination-boxed mb-0">
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php echo '<script'; ?>
>
window.APP = {
    getUrl: '<?php echo $_smarty_tpl->getValue('BASE_URL');
echo $_smarty_tpl->getValue('ADMIN_URL');?>
'
};
<?php echo '</script'; ?>
>
<?php }
}

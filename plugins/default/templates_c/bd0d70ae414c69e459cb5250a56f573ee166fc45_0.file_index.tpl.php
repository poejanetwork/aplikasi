<?php
/* Smarty version 5.5.1, created on 2026-01-18 19:50:31
  from 'file:disposisi/index.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_696cd717a563e2_73062210',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bd0d70ae414c69e459cb5250a56f573ee166fc45' => 
    array (
      0 => 'disposisi/index.tpl',
      1 => 1768740589,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_696cd717a563e2_73062210 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\disposisi';
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
                        <input type="text" id="searchInput" class="form-control ps-4" placeholder="Search Data">
                        <i class="ti ti-search position-absolute top-50 translate-middle-y ms-2"></i>
                    </div>

                    <div>
                        <a href="javascript:void(0);" onclick="addData()" class="btn btn-primary"><i class="ti ti-plus me-1"></i>Add Data</a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover text-nowrap mb-0" id="dataTable">
                    <thead>
                        <tr>
                            <th width="80">ID</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end">
                    <ul id="pagination" class="pagination justify-content-center mb-0">
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

<?php
/* Smarty version 5.5.1, created on 2026-01-16 02:46:16
  from 'file:index.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69699868dac834_21883141',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7a0564bfaf31330b2dc0eb323c810b85d2078d0b' => 
    array (
      0 => 'index.tpl',
      1 => 1768527975,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
))) {
function content_69699868dac834_21883141 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates';
$_smarty_tpl->renderSubTemplate("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>

<div class="row">
	<div class="col-12">
		<div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
			<div class="flex-grow-1">
				<h4 class="fs-18 text-uppercase fw-bold m-0">Dashboard</h4>
			</div>
			<div class="mt-3 mt-sm-0">
				<form action="javascript:void(0);">
					<div class="row g-2 mb-0 align-items-center">
						<div class="col-auto">
							<a href="javascript: void(0);" class="btn btn-outline-primary">
								<i class="ti ti-sort-ascending me-1"></i> Sort By
							</a>
						</div>
						<!--end col-->
						<div class="col-sm-auto">
							<div class="input-group">
								<input type="text" class="form-control" data-provider="flatpickr" data-deafult-date="01 May to 31 May" data-date-format="d M" data-range-date="true">
								<span class="input-group-text bg-primary border-primary text-white">
									<i class="ti ti-calendar fs-15"></i>
								</span>
							</div>
						</div>
						<!--end col-->
					</div>
					<!--end row-->
				</form>
			</div>
		</div><!-- end card header -->
	</div>
	<!--end col-->
</div> <!-- end row-->

<?php $_smarty_tpl->renderSubTemplate("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}

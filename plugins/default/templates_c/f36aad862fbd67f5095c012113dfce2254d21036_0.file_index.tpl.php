<?php
/* Smarty version 5.5.1, created on 2026-01-16 10:00:31
  from 'file:dashboard/index.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_6969a9cfe1dca2_61400275',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f36aad862fbd67f5095c012113dfce2254d21036' => 
    array (
      0 => 'dashboard/index.tpl',
      1 => 1768532426,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6969a9cfe1dca2_61400275 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\dashboard';
?>
<div class="row">
	<div class="col-12">
		<div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
			<div class="flex-grow-1">
				<h4 class="fs-18 text-uppercase fw-bold m-0"><?php echo $_smarty_tpl->getValue('pagename');?>
</h4>
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
</div> <!-- end row--><?php }
}

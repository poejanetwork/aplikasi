<?php
/* Smarty version 5.5.1, created on 2026-01-16 14:49:09
  from 'file:404.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_6969ed75df4e13_60571665',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'feba582abb0eb4364016013ae5fe4375bbf1860f' => 
    array (
      0 => '404.tpl',
      1 => 1768549748,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6969ed75df4e13_60571665 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates';
?><div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                    <a href="dashboard" class="auth-brand mb-3">
                        <img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/images/logo.png" alt="logo light" height="24" class="logo-light">
                    </a>

                    <div class="mx-auto text-center">
                        <img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/images/error/error-404.png" alt="error 403 img" height="180" class="my-3">
                        <h2 class="fw-bold mt-3 text-primary lh-base text-danger">Page Not Found !</h2>
                        <h4 class="mt-2 text-dark lh-base">Something's missing...! This page is not available</h4>
                        <p class="text-muted fs-12 mb-3">sorry, we can't find the page you're looking for We suggest you to go homepage</p>
                        <a href="dashboard" class="btn btn-primary">Back to Home <i class="ti ti-home ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div><?php }
}

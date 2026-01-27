<?php
/* Smarty version 5.5.1, created on 2026-01-27 15:42:02
  from 'file:403.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69787a5ae2b3f0_27422427',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '03f3f981058fb37ab9d203d1eb20713f3a28f5ec' => 
    array (
      0 => '403.tpl',
      1 => 1768550788,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69787a5ae2b3f0_27422427 (\Smarty\Template $_smarty_tpl) {
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
/assets/images/error/error-403.png" alt="error 403 img" height="250" class="my-3">
                        <h2 class="fw-bold mt-3 text-primary lh-base text-danger">Access Denied !</h2>
                        <h4 class="mt-2 text-dark lh-base">You don't have permission to access on this server</h4>
                        <p class="text-muted fs-12 mb-3">You are not authorized to view this page. If you think this is a mistake, please contact support for assistance.</p>
                        <a href="dashboard" class="btn btn-primary">Back to Home <i class="ti ti-home ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div><?php }
}

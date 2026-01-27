<?php
/* Smarty version 5.5.1, created on 2026-01-27 18:54:39
  from 'file:supports/show.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_6978a77fa10a96_54706806',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '64785e98d3de9d0bf085ff932231e7f162a772bc' => 
    array (
      0 => 'supports/show.tpl',
      1 => 1769514662,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6978a77fa10a96_54706806 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\supports';
?><div class="row">
	<div class="col-12">
		<div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
			<div class="flex-grow-1">
				<h4 class="fs-18 text-uppercase fw-bold m-0"><?php echo $_smarty_tpl->getValue('pagetitle');?>
</h4>
			</div>
            <div class="text-end"><a href="<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')('supports');?>
" class="btn btn-sm btn-outline-dark"><i class="ti ti-arrow-left align-middle me-1"></i> kembali</a></div>
		</div><!-- end card header -->
	</div>
	<!--end col-->
</div> <!-- end row-->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                <div class="modal-header d-flex flex-wrap gap-2 align-items-start">
                    <i class="ti ti-alien-filled fs-42"></i>
                    <div class="flex-grow-1">
                        <h6 class="fs-16"><?php echo $_smarty_tpl->getValue('data')['name'];?>
</h6>
                        <p class="text-muted mb-0">From: <?php echo $_smarty_tpl->getValue('data')['email'];?>
</p>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <h5 class="fs-18"><?php echo $_smarty_tpl->getValue('data')['subject'];?>
</h5>
                <p class="mb-0"><?php echo nl2br((string) $_smarty_tpl->getValue('data')['message'], (bool) 1);?>
</p>
            </div> <!-- end card-body -->
        </div>

        <div class="card">
            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                <h4 class="header-title">Balas Pesan</h4>
            </div>

            <div class="card-body">
                <form id="formReplyData" data-id="<?php echo $_smarty_tpl->getValue('data')['id'];?>
">
                    
                    <div class="mb-3">
                        <label for="reply" class="form-label">Isi Pesan</label>
                        <textarea name="reply" class="form-control" id="reply" rows="5"><?php echo $_smarty_tpl->getValue('data')['reply'];?>
</textarea>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Balas
                        </button>
                    </div>
                </form>
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
><?php }
}

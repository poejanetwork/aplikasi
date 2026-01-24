<?php
/* Smarty version 5.5.1, created on 2026-01-24 08:20:28
  from 'file:surat_masuk/show.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69741e5cd7e918_58449951',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6882b7cf2b8c951ef62754240b4d1beb9cb8e12a' => 
    array (
      0 => 'surat_masuk/show.tpl',
      1 => 1769217609,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69741e5cd7e918_58449951 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\surat_masuk';
?><div class="row align-items-start justify-content-between">
    <div class="col-lg-12">
        <p class="text-dark fw-medium fs-15 d-flex align-items-center gap-1 mb-2">
            <iconify-icon icon="solar:box-bold-duotone" class="text-info"></iconify-icon>
            <?php echo $_smarty_tpl->getValue('data')['asal_surat'];?>

            <i class="ti ti-arrow-right"></i>
            <span class="badge bg-light-subtle rounded-pill text-dark border fs-14 py-1 px-2">
                <?php echo $_smarty_tpl->getValue('data')['nomor_surat'];?>

            </span>
        </p>

        <h4 class="mb-1 text-dark fw-semibold"><?php echo $_smarty_tpl->getValue('data')['perihal'];?>
</h4>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <p class="mb-0 fs-15">Tanggal : <?php echo $_smarty_tpl->getSmarty()->getModifierCallback('date_format_id')($_smarty_tpl->getValue('data')['tanggal_surat']);?>
</p>
            <div>|</div> Diterima : <?php echo $_smarty_tpl->getSmarty()->getModifierCallback('date_format_id')($_smarty_tpl->getValue('data')['tanggal_terima']);?>

            <div>
                <div class="mb-0 fs-15 text-success fw-medium  d-flex align-items-center gap-1"><i class="ti ti-plane-tilt"></i>Disposisi:</div>
                <div class="mb-0 fs-15 text-success fw-medium  d-flex align-items-center gap-1"><?php echo $_smarty_tpl->getValue('data')['disposisi_text'];?>
</div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 text-end">
        <div class="d-flex gap-2 flex-wrap justify-content-end my-2">
            <?php if ($_smarty_tpl->getValue('data')['file_path']) {?>
                <a href="sm/view/<?php echo $_smarty_tpl->getValue('data')['id'];?>
" class="btn btn-soft-primary" target="_blank">Lihat Surat</a>
                <a href="sm/download/<?php echo $_smarty_tpl->getValue('data')['id'];?>
" class="btn btn-primary">Download</a>
            <?php } else { ?>
                <button class="btn btn-secondary" disabled>Tidak ada file</button>
            <?php }?>
        </div>
    </div>
</div><?php }
}

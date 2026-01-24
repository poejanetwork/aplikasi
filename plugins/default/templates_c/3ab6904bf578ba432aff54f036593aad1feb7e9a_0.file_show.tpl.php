<?php
/* Smarty version 5.5.1, created on 2026-01-24 10:07:25
  from 'file:surat_keluar/show.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_6974376dab4013_65310686',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3ab6904bf578ba432aff54f036593aad1feb7e9a' => 
    array (
      0 => 'surat_keluar/show.tpl',
      1 => 1769224040,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6974376dab4013_65310686 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\surat_keluar';
?><div class="row align-items-start justify-content-between">
    <div class="col-lg-12">
        <p class="text-dark fw-medium fs-15 d-flex align-items-center gap-1 mb-2">
            <iconify-icon icon="solar:box-bold-duotone" class="text-warning"></iconify-icon>
            <?php echo $_smarty_tpl->getValue('data')['tujuan_surat'];?>

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
            <div>|</div> Dikirim : <?php echo $_smarty_tpl->getSmarty()->getModifierCallback('date_format_id')($_smarty_tpl->getValue('data')['tanggal_kirim']);?>

        </div>
    </div>

    <div class="col-lg-12 text-end">
        <div class="d-flex gap-2 flex-wrap justify-content-end my-2">
            <?php if ($_smarty_tpl->getValue('data')['file_path']) {?>
                <a href="sk/view/<?php echo $_smarty_tpl->getValue('data')['id'];?>
" class="btn btn-soft-primary" target="_blank">Lihat Surat</a>
                <a href="sk/download/<?php echo $_smarty_tpl->getValue('data')['id'];?>
" class="btn btn-primary">Download</a>
            <?php } else { ?>
                <button class="btn btn-secondary" disabled>Tidak ada file</button>
            <?php }?>
        </div>
    </div>
</div><?php }
}

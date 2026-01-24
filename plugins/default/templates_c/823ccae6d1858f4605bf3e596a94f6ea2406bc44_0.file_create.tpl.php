<?php
/* Smarty version 5.5.1, created on 2026-01-24 08:36:41
  from 'file:surat_keluar/create.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69742229ad0091_87867299',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '823ccae6d1858f4605bf3e596a94f6ea2406bc44' => 
    array (
      0 => 'surat_keluar/create.tpl',
      1 => 1769218226,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69742229ad0091_87867299 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\surat_keluar';
?><form id="formAddData" enctype="multipart/form-data">
    
    <div class="mb-3">
        <label for="tujuan_surat" class="form-label">Tujuan Surat</label>
        <input type="text" id="tujuan_surat" name="tujuan_surat" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label for="nomor_surat" class="form-label">Nomor Surat</label>
        <input type="text" id="nomor_surat" name="nomor_surat" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label for="perihal" class="form-label">Perihal</label>
        <input type="text" id="perihal" name="perihal" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
        <input type="date" id="tanggal_surat" name="tanggal_surat" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label for="tanggal_kirim" class="form-label">Tanggal Surat Dikirim</label>
        <input type="date" id="tanggal_kirim" name="tanggal_kirim" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label for="keterangan" class="form-label">Keterangan</label>
        <input type="text" id="keterangan" name="keterangan" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label class="form-label">Upload File</label>
        <input class="form-control" type="file"
            name="file_surat"
            accept=".pdf,.doc,.docx,image/*"
            capture="environment"
            required>
    </div>
    
    <div class="text-end">
        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">
            Batal
        </button>
        <button type="submit" class="btn btn-primary">
            Simpan Data
        </button>
    </div>
</form><?php }
}

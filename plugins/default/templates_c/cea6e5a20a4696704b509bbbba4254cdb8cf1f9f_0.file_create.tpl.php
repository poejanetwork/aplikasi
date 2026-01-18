<?php
/* Smarty version 5.5.1, created on 2026-01-16 15:42:14
  from 'file:surat_masuk/create.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_6969f9e69b4ed3_34812032',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cea6e5a20a4696704b509bbbba4254cdb8cf1f9f' => 
    array (
      0 => 'surat_masuk/create.tpl',
      1 => 1768552930,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6969f9e69b4ed3_34812032 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\surat_masuk';
?><form id="formAddData">
    
    <div class="mb-3">
        <label for="asal_surat" class="form-label">Asal Surat</label>
        <input type="text" id="asal_surat" name="asal_surat" class="form-control" value="">
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
        <label for="tanggal_terima" class="form-label">Tanggal Surat Diterima</label>
        <input type="date" id="tanggal_terima" name="tanggal_terima" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Lembar Disposisi</label>
        <div class="row g-2">
            <div class="col-sm-6">
                <div class="form-check form-checkbox-secondary mb-2">
                    <input class="form-check-input" type="checkbox" name="disposisi[tindak_lanjuti]" value="1" id="tindak_lanjuti">
                    <label class="form-check-label" for="tindak_lanjuti">Tindak lanjuti</label>
                </div>
                <div class="form-check form-checkbox-secondary mb-2">
                    <input class="form-check-input" type="checkbox" name="disposisi[arsipkan]" value="1" id="arsipkan">
                    <label class="form-check-label" for="arsipkan">Arsipkan</label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-check form-checkbox-secondary mb-2">
                    <input class="form-check-input" type="checkbox" name="disposisi[koordinasikan]" value="1" id="koordinasikan">
                    <label class="form-check-label" for="koordinasikan">Koordinasikan</label>
                </div>
                <div class="form-check form-checkbox-secondary mb-2">
                    <input class="form-check-input" type="checkbox" name="disposisi[laporkan]" value="1" id="laporkan">
                    <label class="form-check-label" for="laporkan">Laporkan</label>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="keterangan" class="form-label">Keterangan</label>
        <input type="text" id="keterangan" name="keterangan" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label class="form-label">Upload File</label>
        <input class="form-control" type="file" id="dataFile">
    </div>
    
    <div class="text-end">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            Batal
        </button>
        <button type="submit" class="btn btn-primary">
            Simpan Data
        </button>
    </div>
</form><?php }
}

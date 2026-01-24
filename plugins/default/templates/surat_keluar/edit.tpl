<form id="formEditData" data-id="{$data.id}" enctype="multipart/form-data">
    
    <div class="mb-3">
        <label for="tujuan_surat" class="form-label">Tujuan Surat</label>
        <input type="text" id="tujuan_surat" name="tujuan_surat" class="form-control" value="{$data.tujuan_surat}">
    </div>

    <div class="mb-3">
        <label for="nomor_surat" class="form-label">Nomor Surat</label>
        <input type="text" id="nomor_surat" name="nomor_surat" class="form-control" value="{$data.nomor_surat}">
    </div>

    <div class="mb-3">
        <label for="perihal" class="form-label">Perihal</label>
        <input type="text" id="perihal" name="perihal" class="form-control" value="{$data.perihal}">
    </div>

    <div class="mb-3">
        <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
        <input type="date" id="tanggal_surat" name="tanggal_surat" class="form-control" value="{if $data.tanggal_surat}{$data.tanggal_surat|date_format:"%Y-%m-%d"}{/if}">
    </div>

    <div class="mb-3">
        <label for="tanggal_kirim" class="form-label">Tanggal Surat Dikirim</label>
        <input type="date" id="tanggal_kirim" name="tanggal_kirim" class="form-control" value="{if $data.tanggal_kirim}{$data.tanggal_kirim|date_format:"%Y-%m-%d"}{/if}">
    </div>

    <div class="mb-3">
        <label for="keterangan" class="form-label">Keterangan</label>
        <input type="text" id="keterangan" name="keterangan" class="form-control" value="{$data.keterangan}">
    </div>

    <div class="mb-3">
        <label class="form-label">Upload File<br/><small>(jangan upload jika tidak mengganti)</small></label>
        <input class="form-control" type="file"
            name="file_surat"
            accept=".pdf,.doc,.docx,image/*"
            capture="environment">
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">
            Batal
        </button>
        <button type="submit" class="btn btn-primary">
            Simpan Data
        </button>
    </div>
</form>
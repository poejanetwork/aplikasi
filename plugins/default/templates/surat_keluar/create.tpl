<form id="formAddData" enctype="multipart/form-data">
    
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
</form>
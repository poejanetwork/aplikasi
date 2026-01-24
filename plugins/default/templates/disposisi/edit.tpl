<form id="formEditData" data-id="{$data.id}">
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" id="nama" name="nama" class="form-control" value="{$data.nama}">
    </div>
    <div class="mb-3">
        <label for="urutan" class="form-label">Urutan</label>
        <input type="number" id="urutan" name="urutan" class="form-control" value="{$data.urutan}">
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
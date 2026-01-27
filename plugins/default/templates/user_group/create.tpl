<form id="formAddData">
    <div class="mb-3">
        <label for="group_name" class="form-label">Nama Group</label>
        <input type="text" id="group_name" name="group_name" class="form-control" placeholder="Jangan gunakan spasi atau karakter khusus" value="">
    </div>
    
    <div class="mb-3">
        <label for="status" class="form-label">Status Group</label>
        <select class="form-select" id="statuses" name="status">
            <option value="1">Aktif</option>
            <option value="0">Tidak Aktif</option>
        </select>
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
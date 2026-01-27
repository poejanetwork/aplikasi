<form id="formAddData">
    <div class="mb-3">
        <label for="setting_group" class="form-label">Group</label>
        <select class="form-select" id="setting_group" name="setting_group">
            <option value="news">Berita</option>
            <option value="social">Sosmed</option>
            <option value="umum">Umum</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="setting_key" class="form-label">Slug</label>
        <input type="text" id="setting_key" name="setting_key" class="form-control" placeholder="Jangan gunakan spasi atau karakter khusus" value="">
    </div>

    <div class="mb-3">
        <label for="setting_value" class="form-label">Value</label>
        <input type="text" id="setting_value" name="setting_value" class="form-control" value="">
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
<form id="formEditData" data-id="{$data.id}">
    <div class="mb-3">
        <label for="setting_group" class="form-label">Group</label>
        <select class="form-select" id="setting_group" name="setting_group">
            <option value="news" {if $data.setting_group == "news"}selected{/if}>Berita</option>
            <option value="social" {if $data.setting_group == "social"}selected{/if}>Sosmed</option>
            <option value="umum" {if $data.setting_group == "umum"}selected{/if}>Umum</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="setting_key" class="form-label">Slug</label>
        <input type="text" id="setting_key" name="setting_key" class="form-control" placeholder="Jangan gunakan spasi atau karakter khusus" value="{$data.key}">
    </div>

    <div class="mb-3">
        <label for="setting_value" class="form-label">Value</label>
        <input type="text" id="setting_value" name="setting_value" class="form-control" value="{$data.value}">
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
<form id="formEditData" data-id="{$data.id}">
    <div class="mb-3">
        <label for="group_name" class="form-label">Nama Group</label>
        <input type="text" id="group_name" name="group_name" class="form-control" placeholder="Jangan gunakan spasi atau karakter khusus" value="{$data.group_name}">
    </div>
    
    <div class="mb-3">
        <label for="status" class="form-label">Status Group</label>
        <select class="form-select" id="statuses" name="status">
            <option value="1" {if $data.status == 1}selected{/if}>Aktif</option>
            <option value="0" {if $data.status == 0}selected{/if}>Tidak Aktif</option>
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
<form id="formAddData">
    <div class="mb-3">
        <label for="user_group_id" class="form-label">Nama Group</label>
        <select class="form-select" id="user_group_id" name="user_group_id">
            {foreach $userGroupList as $key => $value}
            <option value="{$value.id}">{$value.group_name}</option>
            {/foreach}
        </select>
    </div>
    
    <div class="mb-3">
        <label for="module_name" class="form-label">Module</label>
        <select class="form-select" id="module_name" name="module_name">
            {foreach $moduleList as $key => $value}
            <option value="{$value}">{$value}</option>
            {/foreach}
        </select>
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
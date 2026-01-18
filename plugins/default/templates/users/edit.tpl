<form id="formEditUser" data-id="{$user.id}">
    <div class="mb-3">
        <label for="fullname" class="form-label">Nama Lengkap</label>
        <input type="text" id="fullname" name="fullname" class="form-control" value="{$user.fullname}">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" id="email" name="email" class="form-control" value="{$user.email}">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi</label>
        <input type="text" id="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti password">
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            Batal
        </button>
        <button type="submit" class="btn btn-primary">
            Simpan Data
        </button>
    </div>
</form>
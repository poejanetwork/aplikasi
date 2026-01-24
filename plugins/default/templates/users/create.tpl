<form id="formAddUser">
    <div class="mb-3">
        <label for="fullname" class="form-label">Nama Lengkap</label>
        <input type="text" id="fullname" name="fullname" class="form-control" value="" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" id="email" name="email" class="form-control" value="" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi</label>
        <input type="text" id="password" name="password" class="form-control" value="" required>
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
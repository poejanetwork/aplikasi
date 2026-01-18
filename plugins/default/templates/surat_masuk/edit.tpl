<form id="formEditData" data-id="{$data.id}">
    
    <div class="mb-3">
        <label for="asal_surat" class="form-label">Asal Surat</label>
        <input type="text" id="asal_surat" name="asal_surat" class="form-control" value="{$data.asal_surat}">
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
        <label for="tanggal_terima" class="form-label">Tanggal Surat Diterima</label>
        <input type="date" id="tanggal_terima" name="tanggal_terima" class="form-control" value="{if $data.tanggal_terima}{$data.tanggal_terima|date_format:"%Y-%m-%d"}{/if}">
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Lembar Disposisi</label>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="disposisi[tindak_lanjuti]" value="1">
            <label class="form-check-label">Tindak lanjuti</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="disposisi[arsipkan]" value="1">
            <label class="form-check-label">Arsipkan</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="disposisi[koordinasikan]" value="1">
            <label class="form-check-label">Koordinasikan</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="disposisi[laporkan]" value="1">
            <label class="form-check-label">Laporkan</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="disposisi[segera]" value="1">
            <label class="form-check-label">Segera</label>
        </div>
    </div>

    <div class="mb-3">
        <label for="keterangan" class="form-label">Keterangan</label>
        <input type="text" id="keterangan" name="keterangan" class="form-control" value="{$data.keterangan}">
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
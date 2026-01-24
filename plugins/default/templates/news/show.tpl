<div class="row align-items-start justify-content-between">
    <div class="col-lg-12">
        <p class="text-dark fw-medium fs-15 d-flex align-items-center gap-1 mb-2">
            <iconify-icon icon="solar:box-bold-duotone" class="text-info"></iconify-icon>
            {$data.asal_surat}
            <i class="ti ti-arrow-right"></i>
            <span class="badge bg-light-subtle rounded-pill text-dark border fs-14 py-1 px-2">
                {$data.nomor_surat}
            </span>
        </p>

        <h4 class="mb-1 text-dark fw-semibold">{$data.perihal}</h4>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <p class="mb-0 fs-15">Tanggal : {$data.tanggal_surat|@date_format_id}</p>
            <div>|</div> Diterima : {$data.tanggal_terima|@date_format_id}
            <div>
                <div class="mb-0 fs-15 text-success fw-medium  d-flex align-items-center gap-1"><i class="ti ti-plane-tilt"></i>Disposisi:</div>
                <div class="mb-0 fs-15 text-success fw-medium  d-flex align-items-center gap-1">{$data.disposisi_text}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 text-end">
        <div class="d-flex gap-2 flex-wrap justify-content-end my-2">
            {if $data.file_path}
                <a href="sm/view/{$data.id}" class="btn btn-soft-primary" target="_blank">Lihat Surat</a>
                <a href="sm/download/{$data.id}" class="btn btn-primary">Download</a>
            {else}
                <button class="btn btn-secondary" disabled>Tidak ada file</button>
            {/if}
        </div>
    </div>
</div>
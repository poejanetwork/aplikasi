<div class="row">
	<div class="col-12">
		<div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
			<div class="flex-grow-1">
				<h4 class="fs-18 text-uppercase fw-bold m-0">{$pagetitle}</h4>
			</div>
            <div class="text-end"><a href="{'supports'|surl}" class="btn btn-sm btn-outline-dark"><i class="ti ti-arrow-left align-middle me-1"></i> kembali</a></div>
		</div><!-- end card header -->
	</div>
	<!--end col-->
</div> <!-- end row-->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                <div class="modal-header d-flex flex-wrap gap-2 align-items-start">
                    <i class="ti ti-alien-filled fs-42"></i>
                    <div class="flex-grow-1">
                        <h6 class="fs-16">{$data.name}</h6>
                        <p class="text-muted mb-0">From: {$data.email}</p>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <h5 class="fs-18">{$data.subject}</h5>
                <p class="mb-0">{$data.message|nl2br}</p>
            </div> <!-- end card-body -->
        </div>

        <div class="card">
            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                <h4 class="header-title">Balas Pesan</h4>
            </div>

            <div class="card-body">
                <form id="formReplyData" data-id="{$data.id}">
                    
                    <div class="mb-3">
                        <label for="reply" class="form-label">Isi Pesan</label>
                        <textarea name="reply" class="form-control" id="reply" rows="5">{$data.reply}</textarea>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Balas
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
<script>
window.APP = {
    getUrl: '{$BASE_URL}{$ADMIN_URL}'
};
</script>
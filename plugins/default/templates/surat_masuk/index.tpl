<div class="row">
	<div class="col-12">
		<div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
			<div class="flex-grow-1">
				<h4 class="fs-18 text-uppercase fw-bold m-0">{$pagename}</h4>
			</div>
		</div><!-- end card header -->
	</div>
	<!--end col-->
</div> <!-- end row-->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <div class="d-flex flex-wrap justify-content-between gap-2">
                    <div class="position-relative">
                        <input type="text" class="form-control ps-4" placeholder="Search Surat Masuk">
                        <i class="ti ti-search position-absolute top-50 translate-middle-y ms-2"></i>
                    </div>

                    <div>
                        <a href="javascript:void(0);" onclick="addData()" class="btn btn-primary"><i class="ti ti-plus me-1"></i>Add Surat</a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover text-nowrap mb-0" id="dataTable">
                    <thead>
                        <tr>
                            <th width="80">ID</th>
                            <th>Nomor Surat<br/>Asal Surat</th>
                            <th>Perihal</th>
                            <th>Tanggal Surat</th>
                            <th>Tanggal Terima</th>
                            <th>Disposisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end">
                    <ul id="pagination" class="pagination justify-content-center mb-0">
                        <li class="page-item disabled">
                            <a href="#" class="page-link"><i class="ti ti-chevrons-left"></i></a>
                        </li>
                        <li class="page-item">
                            <a href="#" class="page-link">1</a>
                        </li>
                        <li class="page-item active">
                            <a href="#" class="page-link">2</a>
                        </li>
                        <li class="page-item">
                            <a href="#" class="page-link">3</a>
                        </li>
                        <li class="page-item">
                            <a href="#" class="page-link"><i class="ti ti-chevrons-right"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
window.APP = {
    getUrl: '{$BASE_URL}{$ADMIN_URL}'
};
</script>

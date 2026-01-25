<div class="row">
	<div class="col-12">
		<div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
			<div class="flex-grow-1">
				<h4 class="fs-18 text-uppercase fw-bold m-0">{$pagetitle}</h4>
			</div>
            <div class="btn-group">
                <div class="input-group">
                    <label class="input-group-text" for="chooseFilter"><i class="ti ti-sort-ascending me-1"></i> Kategori</label>
                    <select class="form-select" id="chooseFilter">
                        <option selected="">Choose...</option>
                        {foreach $categories as $item}
                        <option value="{$item.id}" data-slug="{$item.slug}">{$item.name}</option>
                        {/foreach}
                    </select>
                </div>
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
                        <input type="text" id="searchInput" class="form-control ps-4" placeholder="Search Data">
                        <i class="ti ti-search position-absolute top-50 translate-middle-y ms-2"></i>
                    </div>

                    <div>
                        <a href="{'doktrin/create'|surl}" class="btn btn-primary"><i class="ti ti-plus me-1"></i>Add Data</a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover text-nowrap mb-0" id="dataTable">
                    <thead>
                        <tr>
                            <th width="80">ID</th>
                            <th>Jenis/Tahun</th>
                            <th>Judul</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end">
                    <ul id="pagination" class="pagination pagination-rounded pagination-boxed mb-0">
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

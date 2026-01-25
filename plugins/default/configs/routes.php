<?php
// ==============================
// ROUTES
// ==============================

// ==============================
// DASHBOARD
// ==============================
route('GET', '/', function () {
    requireAdmin();
    header('Location: ' . env('ADMIN_URL') . '/dashboard');
    exit;
});

route('GET', '/dashboard', function () {
    requireAdmin();

    view('index', [
        'content'   => 'dashboard/index.tpl',
        'pagetitle' => 'Dashboard',
        'pagename'  => 'dashboard'
    ]);
});


// ==============================
// USERS
// ==============================
routeGroup('/users', function () {

    route('GET', '/', function () {
        view('index', [
            'content'   => 'users/index.tpl',
            'pagetitle' => 'Users',
            'pagename'  => 'users',
            'page_js'   => 'users.js'
        ]);
    });

    route('GET', '/data', function () {
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 10;
        $offset = ($page - 1) * $limit;

        $searchText = trim($_GET['search'] ?? '');

        $search = [
            'keyword' => $searchText,
            'columns' => ['fullname', 'email']
        ];

        $total = pdo_count('users', [], $search);
        
        $data = pdo_paginate(
            'users',
            ['id','fullname','email','status'],
            $offset,
            $limit,
            [],
            [],
            'id DESC',
            $search
        );

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $data,
            'pagination' => [
                'total'        => $total,
                'per_page'     => $limit,
                'current_page' => $page,
                'last_page'    => ceil($total / $limit)
            ]
        ]);
    });

    route('GET', '/create', function () {
        view('users/create');
    });

    route('POST', '/store', function () {
        $rules = [
            'fullname' => 'required|max:250',
            'email'    => 'required|email',
            'password' => 'required'
        ];

        $data = [
            'fullname'  => $_POST['fullname'] ?? '',
            'email'     => $_POST['email'] ?? '',
            'password'  => password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT),
            'user_type' => $_POST['user_type'] ?? 'admin',
            'status'    => 1
        ];
        
        $id = pdo_insert('users', $data, $rules);

        if (is_array($id) && isset($id['errors'])) {
            response_json(
                $id['errors']['code'] ?? 400,
                false,
                $id['errors']['message']
            );
            return;
        }

        response_json(
            200,
            true,
            'Data berhasil dibuat'
        );
    });
    

    route('GET', '/edit/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $user = pdo_select_first('users', ['id' => (int)$id], '*', [], [], 1);

        view('users/edit', [
            'user' => $user
        ]);
    });

    route('PUT', '/update/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $rules = [
            'fullname' => 'required|max:250',
            'email'    => 'required|email'
        ];

        $data = [
            'fullname' => $_POST['fullname'] ?? '',
            'email'    => $_POST['email'] ?? ''
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        $ok = pdo_update('users', $data, ['id' => (int)$id], $rules);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil diperbarui' : 'Gagal memperbarui data'
        );
    });

    route('DELETE', '/delete/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $ok = pdo_softDelete('users', [
            'id'     => (int)$id,
            'status' => 1
        ]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil dihapus' : 'Gagal menghapus data'
        );
    });

    route('PATCH', '/restore/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $ok = pdo_restore('users', ['id' => (int)$id]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil dipulihkan' : 'Gagal restore data'
        );
    });

}, 'requireAdmin');


// ==============================
// SURAT MASUK DAN KELUAR
// ==============================
routeGroup('/sm', function () {

    route('GET', '/', function () {
        view('index', [
            'content'   => 'surat_masuk/index.tpl',
            'pagetitle' => 'Surat Masuk',
            'pagename'  => 'surat_masuk',
            'page_js'   => 'surat_masuk.js'
        ]);
    });

    route('GET', '/data', function () {
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 10;
        $offset = ($page - 1) * $limit;
        $searchText = trim($_GET['search'] ?? '');

        $search = [
            'keyword' => $searchText,
            'columns' => ['nomor_surat', 'asal_surat', 'perihal']
        ];

        $total = pdo_count('surat_masuk', [], $search);

        $data = pdo_paginate(
            'surat_masuk',
            ['id','nomor_surat','asal_surat','perihal','tanggal_surat','status'],
            $offset,
            $limit,
            [],
            [],
            'id DESC',
            $search
        );

        $disposisiMaster = pdo_select(
            'disposisi',
            ['status' => 1],
            ['id', 'nama'],
            [],
            [],
            null
        );

        // mapping id => nama
        $disposisiMap = [];
        foreach ($disposisiMaster as $d) {
            $disposisiMap[$d['id']] = $d['nama'];
        }

        foreach ($data as &$row) {
            $row['tanggal_surat'] = date_format_id($row['tanggal_surat']);
            $ids = array_filter(explode(',', $row['disposisi'] ?? ''));

            $labels = [];
            foreach ($ids as $id) {
                if (isset($disposisiMap[$id])) {
                    $labels[] = $disposisiMap[$id];
                }
            }

            $row['disposisi_text'] = implode(', ', $labels);
        }
        unset($row);

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $data,
            'pagination' => [
                'total'        => $total,
                'per_page'     => $limit,
                'current_page' => $page,
                'last_page'    => ceil($total / $limit)
            ]
        ]);
    });

    route('GET', '/create', function () {
        $disposisiList = pdo_select(
            'disposisi',
            ['status' => 1],
            ['id', 'nama'],
            [],
            [],
            null,
            'urutan ASC'
        );
        $count  = count($disposisiList);
        $half   = (int) ceil($count / 2); // kalau 5 -> 3 & 2, kalau 6 -> 3 & 3

        $disposisiLeft  = array_slice($disposisiList, 0, $half);
        $disposisiRight = array_slice($disposisiList, $half);

        view('surat_masuk/create', [
            'disposisiLeft'   => $disposisiLeft,
            'disposisiRight' => $disposisiRight
        ]);
    });

    route('POST', '/store', function () {
        $rules = [
            'asal_surat' => 'required',
            'nomor_surat'    => 'required',
            'perihal' => 'required',
            'tanggal_surat' => 'required',
            'tanggal_terima' => 'required',
            'disposisi' => 'required'
        ];

        $disposisi = $_POST['disposisi'] ?? [];
        $disposisiCsv = implode(',', array_map('intval', $disposisi));
        
        if (!isset($_FILES['file_surat'])) {
            response_json(400, false, 'File wajib diupload');
            return;
        }

        $upload = upload_file($_FILES['file_surat'], [
            'upload_path' => 'public/uploads/surat-masuk',
            'prefix' => 'sm',
            'max_size' => 5 * 1024 * 1024, // 5MB
            'allowed_mime' => [
                'application/pdf' => 'pdf',
                'application/msword' => 'doc',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                'image/jpeg' => 'jpg',
                'image/png' => 'png'
            ]
        ]);

        if (!$upload['status']) {
            response_json(400, false, $upload['message']);
            return;
        }

        $file = $upload['data'];

        $data = [
            'asal_surat'  => $_POST['asal_surat'] ?? '',
            'nomor_surat'     => $_POST['nomor_surat'] ?? '',
            'perihal'  => $_POST['perihal'] ?? '',
            'tanggal_surat' => $_POST['tanggal_surat'] ?? '',
            'tanggal_terima'  => $_POST['tanggal_terima'] ?? '',
            'disposisi'  => $disposisiCsv ?? '',
            'file_path' => $file['file_path'],
            'file_type' => $file['file_type'],
            'file_name' => $file['file_original_name'],
            'file_size' => $file['file_size'],
            'status'    => 1
        ];

        $id = pdo_insert('surat_masuk', $data, $rules);

        header('Content-Type: application/json');
        response_json(
            200,
            is_numeric($id) ? true : false,
            is_numeric($id)
                ? 'Data berhasil dibuat'
                : 'Gagal membuat data'
        );

    });
    

    route('GET', '/edit/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $data = pdo_select_first('surat_masuk', ['id' => (int)$id], '*', [], [], 1);
        $selected = array_filter(explode(',', $data['disposisi']));
        
        $disposisiList = pdo_select(
            'disposisi',
            ['status' => 1],
            ['id', 'nama'],
            [],
            [],
            null,
            'urutan ASC'
        );
        $count  = count($disposisiList);
        $half   = (int) ceil($count / 2); // kalau 5 -> 3 & 2, kalau 6 -> 3 & 3

        $disposisiLeft  = array_slice($disposisiList, 0, $half);
        $disposisiRight = array_slice($disposisiList, $half);

        view('surat_masuk/edit', [
            'data' => $data,
            'selected' => $selected,
            'disposisiLeft'   => $disposisiLeft,
            'disposisiRight' => $disposisiRight
        ]);
    });

    route('GET', '/show/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }
        
        $data = pdo_select_first('surat_masuk', ['id' => (int)$id], '*', [], [], 1);

        $disposisiMaster = pdo_select(
            'disposisi',
            ['status' => 1],
            ['id', 'nama'],
            [],
            [],
            null
        );

        // mapping id => nama
        $disposisiMap = [];
        foreach ($disposisiMaster as $d) {
            $disposisiMap[$d['id']] = $d['nama'];
        }

        // proses disposisi CSV
        $ids = array_filter(explode(',', $data['disposisi'] ?? ''));

        $labels = [];
        foreach ($ids as $did) {
            if (isset($disposisiMap[$did])) {
                $labels[] = $disposisiMap[$did];
            }
        }

        $data['disposisi_text'] = implode(', ', $labels);

        view('surat_masuk/show', [
            'data' => $data
        ]);
    });

    route('PUT', '/update/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(404, false, 'Invalid ID');
            return;
        }

        $rules = [
            'asal_surat' => 'required',
            'nomor_surat'    => 'required',
            'perihal' => 'required',
            'tanggal_surat' => 'required',
            'tanggal_terima' => 'required',
            'disposisi' => 'required'
        ];

        $old = pdo_select_first(
            'surat_masuk',
            ['id' => (int)$id, 'status' => 1],
            '*',
            [],
            [],
            1
        );

        if (!$old) {
            response_json(404, false, 'Data tidak ditemukan');
            return;
        }

        $disposisi = $_POST['disposisi'] ?? [];
        $disposisiCsv = implode(',', array_map('intval', (array)$disposisi));
        
        $data = [
            'asal_surat'     => $_POST['asal_surat'] ?? $old['asal_surat'],
            'nomor_surat'    => $_POST['nomor_surat'] ?? $old['nomor_surat'],
            'perihal'        => $_POST['perihal'] ?? $old['perihal'],
            'tanggal_surat'  => $_POST['tanggal_surat'] ?? $old['tanggal_surat'],
            'tanggal_terima' => $_POST['tanggal_terima'] ?? $old['tanggal_terima'],
            'disposisi'      => $disposisiCsv,
            'keterangan'     => $_POST['keterangan'] ?? $old['keterangan']
        ];

        if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] === UPLOAD_ERR_OK) {

            $upload = upload_file($_FILES['file_surat'], [
                'upload_path' => '/public/uploads/surat-masuk',
                'prefix'      => 'sm',
                'max_size'    => 10 * 1024 * 1024,
                'allowed_mime' => [
                    'application/pdf' => 'pdf',
                    'application/msword' => 'doc',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png'
                ]
            ]);

            if (!$upload['status']) {
                response_json(400, false, $upload['message']);
                return;
            }

            $file = $upload['data'];

            // ===== HAPUS FILE LAMA =====
            if (!empty($old['file_path'])) {
                $oldPath = ROOT_PATH . '/' . ltrim($old['file_path'], '/');
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // ===== UPDATE FIELD FILE =====
            $data['file_path'] = $file['file_path'];
            $data['file_name'] = $file['file_original_name'];
            $data['file_type'] = $file['file_type'];
            $data['file_size'] = $file['file_size'];
        }

        $ok = pdo_update(
            'surat_masuk',
            $data,
            ['id' => (int)$id],
            $rules
        );

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil diperbarui' : 'Gagal memperbarui data'
        );
    });

    route('DELETE', '/delete/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400,false,'Invalid ID');
            return;
        }

        $ok = pdo_softDelete('surat_masuk', ['id' => (int)$id]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data dihapus' : 'Gagal menghapus data'
        );
    });

    route('PATCH', '/restore/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400,false,'Invalid ID');
            return;
        }

        $ok = pdo_restore('surat_masuk', ['id' => (int)$id]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil dipulihkan' : 'Gagal restore data'
        );
    });

    route('GET', '/view/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400,false,'Invalid ID');
            return;
        }

        $data = pdo_select_first(
            'surat_masuk',
            ['id' => (int)$id, 'status' => 1],
            '*',
            [],
            [],
            1
        );

        if (!$data || empty($data['file_path'])) {
            response_json(400, false, 'File tidak ditemukan');
            return;
        }

        $filePath = ROOT_PATH . '/' . $data['file_path'];

        if (!file_exists($filePath)) {
            response_json(400, false, 'File tidak ada di server');
            return;
        }

        // Tentukan Content-Type
        $mimeMap = [
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png'
        ];

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mime = $mimeMap[$ext] ?? 'application/octet-stream';

        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . basename($data['file_name']) . '"');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
        exit;
    });

    route('GET', '/download/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $data = pdo_select_first(
            'surat_masuk',
            ['id' => (int)$id, 'status' => 1],
            '*',
            [],
            [],
            1
        );

        if (!$data || empty($data['file_path'])) {
            response_json(400, false, 'File tidak ditemukan');
            return;
        }

        $filePath = ROOT_PATH . '/' . $data['file_path'];

        if (!file_exists($filePath)) {
            response_json(400, false, 'File tidak ada di server');
            return;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($data['file_name']) . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Pragma: public');
        header('Cache-Control: must-revalidate');

        readfile($filePath);
        exit;
    });

}, 'requireAdmin');

// ==============================
// SURAT KELUAR
// ==============================
routeGroup('/sk', function () {

    route('GET', '/', function () {
        view('index', [
            'content'   => 'surat_keluar/index.tpl',
            'pagetitle' => 'Surat Keluar',
            'pagename'  => 'surat_keluar',
            'page_js'   => 'surat_keluar.js'
        ]);
    });

    route('GET', '/data', function () {
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 10;
        $offset = ($page - 1) * $limit;
        $searchText = trim($_GET['search'] ?? '');

        $search = [
            'keyword' => $searchText,
            'columns' => ['nomor_surat', 'tujuan_surat', 'perihal']
        ];

        $total = pdo_count('surat_keluar', [], $search);

        $data = pdo_paginate(
            'surat_keluar',
            ['id','nomor_surat','tujuan_surat','perihal','tanggal_surat','status'],
            $offset,
            $limit,
            [],
            [],
            'id DESC',
            $search
        );
        foreach ($data as &$row) {
            $row['tanggal_surat'] = date_format_id($row['tanggal_surat']);
        }
        unset($row);

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $data,
            'pagination' => [
                'total'        => $total,
                'per_page'     => $limit,
                'current_page' => $page,
                'last_page'    => ceil($total / $limit)
            ]
        ]);
    });

    route('GET', '/create', function () {

        view('surat_keluar/create', [
        ]);
    });

    route('POST', '/store', function () {
        $rules = [
            'tujuan_surat' => 'required',
            'nomor_surat'    => 'required',
            'perihal' => 'required',
            'tanggal_surat' => 'required',
            'tanggal_kirim' => 'required'
        ];

        if (!isset($_FILES['file_surat'])) {
            response_json(400, false, 'File wajib diupload');
            return;
        }

        $upload = upload_file($_FILES['file_surat'], [
            'upload_path' => 'public/uploads/surat-keluar',
            'prefix' => 'sm',
            'max_size' => 5 * 1024 * 1024, // 5MB
            'allowed_mime' => [
                'application/pdf' => 'pdf',
                'application/msword' => 'doc',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                'image/jpeg' => 'jpg',
                'image/png' => 'png'
            ]
        ]);

        if (!$upload['status']) {
            response_json(400, false, $upload['message']);
            return;
        }

        $file = $upload['data'];

        $data = [
            'tujuan_surat'  => $_POST['tujuan_surat'] ?? '',
            'nomor_surat'     => $_POST['nomor_surat'] ?? '',
            'perihal'  => $_POST['perihal'] ?? '',
            'tanggal_surat' => $_POST['tanggal_surat'] ?? '',
            'tanggal_kirim'  => $_POST['tanggal_kirim'] ?? '',
            'file_path' => $file['file_path'],
            'file_type' => $file['file_type'],
            'file_name' => $file['file_original_name'],
            'file_size' => $file['file_size'],
            'status'    => 1
        ];

        $id = pdo_insert('surat_keluar', $data, $rules);

        header('Content-Type: application/json');
        response_json(
            200,
            is_numeric($id) ? true : false,
            is_numeric($id)
                ? 'Data berhasil dibuat'
                : 'Gagal membuat data'
        );

    });
    

    route('GET', '/edit/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $data = pdo_select_first('surat_keluar', ['id' => (int)$id], '*', [], [], 1);

        view('surat_keluar/edit', [
            'data' => $data
        ]);
    });

    route('GET', '/show/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }
        
        $data = pdo_select_first('surat_keluar', ['id' => (int)$id], '*', [], [], 1);

        view('surat_keluar/show', [
            'data' => $data
        ]);
    });

    route('PUT', '/update/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(404, false, 'Invalid ID');
            return;
        }

        $rules = [
            'tujuan_surat' => 'required',
            'nomor_surat'    => 'required',
            'perihal' => 'required',
            'tanggal_surat' => 'required',
            'tanggal_kirim' => 'required'
        ];

        $old = pdo_select_first(
            'surat_keluar',
            ['id' => (int)$id, 'status' => 1],
            '*',
            [],
            [],
            1
        );

        if (!$old) {
            response_json(404, false, 'Data tidak ditemukan');
            return;
        }

        $data = [
            'tujuan_surat'     => $_POST['tujuan_surat'] ?? $old['tujuan_surat'],
            'nomor_surat'    => $_POST['nomor_surat'] ?? $old['nomor_surat'],
            'perihal'        => $_POST['perihal'] ?? $old['perihal'],
            'tanggal_surat'  => $_POST['tanggal_surat'] ?? $old['tanggal_surat'],
            'tanggal_kirim' => $_POST['tanggal_terima'] ?? $old['tanggal_kirim'],
            'keterangan'     => $_POST['keterangan'] ?? $old['keterangan']
        ];

        if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] === UPLOAD_ERR_OK) {

            $upload = upload_file($_FILES['file_surat'], [
                'upload_path' => '/public/uploads/surat-keluar',
                'prefix'      => 'sm',
                'max_size'    => 10 * 1024 * 1024,
                'allowed_mime' => [
                    'application/pdf' => 'pdf',
                    'application/msword' => 'doc',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png'
                ]
            ]);

            if (!$upload['status']) {
                response_json(400, false, $upload['message']);
                return;
            }

            $file = $upload['data'];

            // ===== HAPUS FILE LAMA =====
            if (!empty($old['file_path'])) {
                $oldPath = ROOT_PATH . '/' . ltrim($old['file_path'], '/');
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // ===== UPDATE FIELD FILE =====
            $data['file_path'] = $file['file_path'];
            $data['file_name'] = $file['file_original_name'];
            $data['file_type'] = $file['file_type'];
            $data['file_size'] = $file['file_size'];
        }

        $ok = pdo_update(
            'surat_keluar',
            $data,
            ['id' => (int)$id],
            $rules
        );

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil diperbarui' : 'Gagal memperbarui data'
        );
    });

    route('DELETE', '/delete/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400,false,'Invalid ID');
            return;
        }

        $ok = pdo_softDelete('surat_keluar', ['id' => (int)$id]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data dihapus' : 'Gagal menghapus data'
        );
    });

    route('PATCH', '/restore/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400,false,'Invalid ID');
            return;
        }

        $ok = pdo_restore('surat_keluar', ['id' => (int)$id]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil dipulihkan' : 'Gagal restore data'
        );
    });

    route('GET', '/view/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400,false,'Invalid ID');
            return;
        }

        $data = pdo_select_first(
            'surat_keluar',
            ['id' => (int)$id, 'status' => 1],
            '*',
            [],
            [],
            1
        );

        if (!$data || empty($data['file_path'])) {
            response_json(400, false, 'File tidak ditemukan');
            return;
        }

        $filePath = ROOT_PATH . '/' . $data['file_path'];

        if (!file_exists($filePath)) {
            response_json(400, false, 'File tidak ada di server');
            return;
        }

        // Tentukan Content-Type
        $mimeMap = [
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png'
        ];

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mime = $mimeMap[$ext] ?? 'application/octet-stream';

        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . basename($data['file_name']) . '"');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
        exit;
    });

    route('GET', '/download/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $data = pdo_select_first(
            'surat_keluar',
            ['id' => (int)$id, 'status' => 1],
            '*',
            [],
            [],
            1
        );

        if (!$data || empty($data['file_path'])) {
            response_json(400, false, 'File tidak ditemukan');
            return;
        }

        $filePath = ROOT_PATH . '/' . $data['file_path'];

        if (!file_exists($filePath)) {
            response_json(400, false, 'File tidak ada di server');
            return;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($data['file_name']) . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Pragma: public');
        header('Cache-Control: must-revalidate');

        readfile($filePath);
        exit;
    });

}, 'requireAdmin');

// ==============================
// DISPOSISI
// ==============================
routeGroup('/disposisi', function () {

    route('GET', '/', function () {
        view('index', [
            'content'   => 'disposisi/index.tpl',
            'pagetitle' => 'Disposisi',
            'pagename'  => 'disposisi',
            'page_js'   => 'disposisi.js'
        ]);
    });

    route('GET', '/data', function () {
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 10;
        $offset = ($page - 1) * $limit;
        $searchText = trim($_GET['search'] ?? '');

        $search = [
            'keyword' => $searchText,
            'columns' => ['nama']
        ];

        $total = pdo_count('disposisi', [], $search);

        $data = pdo_paginate(
            'disposisi',
            ['id','nama','urutan','status'],
            $offset,
            $limit,
            [],
            [],
            'urutan ASC',
            $search
        );

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $data,
            'pagination' => [
                'total'        => $total,
                'per_page'     => $limit,
                'current_page' => $page,
                'last_page'    => ceil($total / $limit)
            ]
        ]);
    });

    route('GET', '/create', function () {
        view('disposisi/create');
    });

    route('POST', '/store', function () {
        $rules = [
            'nama' => 'required|max:150',
            'urutan'    => 'required|numeric'
        ];
        
        $conn = getDBConnection();
        $conn->beginTransaction();

        try {

               // tentukan posisi
            if (empty($_POST['urutan'])) {
                $urutan = pdo_count('disposisi') + 1;
            } else {
                $urutan = (int)$_POST['urutan'];
            }
            // geser urutan existing (AMAN)
            pdo_reorder_insert(
                'disposisi',
                'urutan',
                $urutan
            );

            // Insert data baru
            $data = [
                'nama'   => $_POST['nama'] ?? '',
                'urutan' => $urutan,
                'status' => 1
            ];

            $id = pdo_insert('disposisi', $data, $rules);

            if (!is_numeric($id)) {
                response_json(400, false, 'Insert Failed');
                return;
            }

            $conn->commit();

            header('Content-Type: application/json');
            response_json(
                200,
                is_numeric($id) ? true : false,
                is_numeric($id)
                    ? 'Data berhasil dibuat'
                    : 'Gagal membuat data'
            );

        } catch (Exception $e) {
            $conn->rollBack();
            logDebug($e->getMessage());
            header('Content-Type: application/json');
            response_json(500, false, 'Gagal membuat data');
        }
    });
    

    route('GET', '/edit/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $data = pdo_select_first('disposisi', ['id' => (int)$id], '*', [], [], 1);

        view('disposisi/edit', [
            'data' => $data
        ]);
    });

    route('PUT', '/update/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $rules = [
            'nama' => 'required|max:150',
            'urutan'    => 'required|numeric'
        ];
        
        $conn = getDBConnection();
        $conn->beginTransaction();

        try {
            $oldData = pdo_select_first('disposisi', ['id' => (int)$id], ['urutan']);
            if (!$oldData) {
                throw new Exception('Data not found');
            }

            $oldUrutan = (int)$oldData['urutan'];
            $newUrutan = (int)$_POST['urutan'];

            // slot aman
            $conn->prepare(
                "UPDATE disposisi SET urutan = -1 WHERE id = ?"
            )->execute([(int)$id]);

            // reorder aman
            pdo_reorder_update(
                'disposisi',
                'urutan',
                $oldUrutan,
                $newUrutan,
                (int)$id
            );

            // update final
            pdo_update('disposisi', [
                'nama'   => $_POST['nama'],
                'urutan' => $newUrutan,
                'status' => 1
            ], ['id' => (int)$id], $rules);

            $conn->commit();

            response_json(200, true, 'Data berhasil diperbarui');
        } catch (Throwable $e) {
            $conn->rollBack();
            logDebug($e->getMessage());
            response_json(500, false, 'Gagal memperbarui data');
        }

    });

    route('DELETE', '/delete/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $ok = pdo_softDelete('disposisi', [
            'id'     => (int)$id,
            'status' => 1
        ]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil dihapus' : 'Gagal menghapus data'
        );
    });

    route('PATCH', '/restore/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $ok = pdo_restore('disposisi', ['id' => (int)$id]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil dipulihkan' : 'Gagal restore data'
        );
    });

}, 'requireAdmin');


// ==============================
// BERITA / NEWS
// ==============================
routeGroup('/news', function () {

    route('GET', '/', function () {
        view('index', [
            'content'   => 'news/index.tpl',
            'pagetitle' => 'Berita',
            'pagename'  => 'news',
            'page_js'   => 'news.js'
        ]);
    });

    route('GET', '/data', function () {
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 10;
        $offset = ($page - 1) * $limit;
        $searchText = trim($_GET['search'] ?? '');

        $search = [
            'keyword' => $searchText,
            'columns' => ['title']
        ];

        $total = pdo_count('news', [], $search);

        $data = pdo_paginate(
            'news',
            [
                'news.id',
                'news.user_id',
                'news.category_id',
                'news.title',
                'news.created_at',
                'news.status',
                'nc.name AS category_name',
                'u.fullname AS user_name'
            ],
            $offset,
            $limit,
            [
                [
                    'type'  => 'LEFT',
                    'table' => 'news_category nc',
                    'on'    => 'news.category_id = nc.id'
                ],
                [
                    'type'  => 'LEFT',
                    'table' => 'users u',
                    'on'    => 'news.user_id = u.id'
                ]
            ],
            [],
            'news.created_at DESC',
            $search
        );
        foreach ($data as &$row) {
            $row['created_at'] = date_format_id($row['created_at']);
        }
        unset($row);

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $data,
            'pagination' => [
                'total'        => $total,
                'per_page'     => $limit,
                'current_page' => $page,
                'last_page'    => ceil($total / $limit)
            ]
        ]);
    });

    route('GET', '/create', function () {
        $categoryList = pdo_select(
            'news_category',
            ['status' => 1],
            ['id', 'name'],
            [],
            [],
            null,
            'name ASC'
        );

        view('index', [
            'categories' => $categoryList,
            'pagetype' => 'can_upload',
            'content'   => 'news/create.tpl',
            'pagetitle' => 'Tambah Berita',
            'pagename'  => 'tambah_berita',
            'page_js'   => 'news.js'
        ]);
    });

    route('POST', '/store', function () {
        $rules = [
            'title' => 'required',
            'category_id'    => 'required',
            'created_at' => 'required'
        ];

        $full_text_bbcode = str_replace("\xc2\xa0", ' ', $_POST['full_text'] ?? '');
        require_once("plugins/jbbcode.php");
        $full_text_html   = parseBBCodeToHtml($full_text_bbcode);
        preg_match('/\[img\](.*?)\[\/img\]/i', $full_text_bbcode, $match);
        $thumbnail = isset($match[1]) ? trim($match[1]) : env('BASE_URL')."/theme/no_image.webp";

        $data = [
            'title'  => $_POST['title'] ?? '',
            'user_id'     => $_SESSION['admindetails']['id'] ?? '',
            'category_id'     => $_POST['category_id'] ?? '',
            "full_text_bbcode" => $full_text_bbcode ?? '',
            "full_text_html" => $full_text_html ?? '',
            'thumbnail' => $thumbnail,
            'created_at' => date('Y-m-d H:i:s', strtotime($_POST['created_at'])) ?? '',
            'status'    => 1
        ];

        preg_match_all('/\[img\](.*?)\[\/img\]/i', $full_text_bbcode, $matches);
        $imageUrls = $matches[1] ?? [];
        $imagePaths = [];
        foreach ($imageUrls as $url) {
            $parsed = parse_url($url);

            if (!empty($parsed['path'])) {
                // buang slash depan jika ada
                $path = ltrim($parsed['path'], '/');

                // pastikan hanya file upload lokal
                if (str_starts_with($path, 'public/uploads/')) {
                    $imagePaths[] = $path;
                }
            }
        }
        foreach ($imagePaths as $path) {
            pdo_update(
                'uploads',
                ['is_temp' => 0],
                ['file_path' => $path],
                []
            );
        }

        $id = pdo_insert('news', $data, $rules);

        header('Content-Type: application/json');
        response_json(
            200,
            is_numeric($id) ? true : false,
            is_numeric($id)
                ? 'Data berhasil dibuat'
                : 'Gagal membuat data'
        );

    });
    

    route('GET', '/edit/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }
        $categoryList = pdo_select(
            'news_category',
            ['status' => 1],
            ['id', 'name'],
            [],
            [],
            null,
            'name ASC'
        );

        $data = pdo_select_first('news', ['id' => (int)$id], '*', [], [], 1);

        view('index', [
            'data' => $data,
            'categories' => $categoryList,
            'pagetype' => 'can_upload',
            'content'   => 'news/edit.tpl',
            'pagetitle' => 'Edit Berita',
            'pagename'  => 'edit_berita',
            'page_js'   => 'news.js'
        ]);
    });

    route('PUT', '/update/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(404, false, 'Invalid ID');
            return;
        }

        $rules = [
            'title' => 'required',
            'category_id'    => 'required',
            'created_at' => 'required'
        ];

        $old = pdo_select_first(
            'news',
            ['id' => (int)$id, 'status' => 1],
            '*',
            [],
            [],
            1
        );

        if (!$old) {
            response_json(404, false, 'Data tidak ditemukan');
            return;
        }

        $full_text_bbcode = str_replace("\xc2\xa0", ' ', $_POST['full_text'] ?? '');
        require_once("plugins/jbbcode.php");
        $full_text_html   = parseBBCodeToHtml($full_text_bbcode);
        preg_match('/\[img\](.*?)\[\/img\]/i', $full_text_bbcode, $match);
        $thumbnail = isset($match[1]) ? trim($match[1]) : env('BASE_URL')."/theme/no_image.webp";

        $data = [
            'title'  => $_POST['title'] ?? '',
            'user_id'     => $_SESSION['admindetails']['id'] ?? '',
            'category_id'     => $_POST['category_id'] ?? '',
            "full_text_bbcode" => $full_text_bbcode ?? '',
            "full_text_html" => $full_text_html ?? '',
            'thumbnail' => $thumbnail,
            'created_at' => date('Y-m-d H:i:s', strtotime($_POST['created_at'])) ?? '',
            'status'    => 1
        ];

        preg_match_all('/\[img\](.*?)\[\/img\]/i', $full_text_bbcode, $matches);
        $imageUrls = $matches[1] ?? [];
        $imagePaths = [];
        foreach ($imageUrls as $url) {
            $parsed = parse_url($url);

            if (!empty($parsed['path'])) {
                // buang slash depan jika ada
                $path = ltrim($parsed['path'], '/');

                // pastikan hanya file upload lokal
                if (str_starts_with($path, 'public/uploads/')) {
                    $imagePaths[] = $path;
                }
            }
        }
        foreach ($imagePaths as $path) {
            pdo_update(
                'uploads',
                ['is_temp' => 0],
                ['file_path' => $path],
                []
            );
        }

        $ok = pdo_update(
            'news',
            $data,
            ['id' => (int)$id],
            $rules
        );

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil diperbarui' : 'Gagal memperbarui data'
        );
    });

    route('DELETE', '/delete/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400,false,'Invalid ID');
            return;
        }

        $ok = pdo_softDelete('news', ['id' => (int)$id]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data dihapus' : 'Gagal menghapus data'
        );
    });

    route('PATCH', '/restore/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400,false,'Invalid ID');
            return;
        }

        $ok = pdo_restore('news', ['id' => (int)$id]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil dipulihkan' : 'Gagal restore data'
        );
    });

}, 'requireAdmin');

// ==============================
// NEWS CATEGORY
// ==============================
routeGroup('/news_category', function () {

    route('GET', '/', function () {
        view('index', [
            'content'   => 'news_category/index.tpl',
            'pagetitle' => 'News Category',
            'pagename'  => 'news_category',
            'page_js'   => 'news_category.js'
        ]);
    });

    route('GET', '/data', function () {
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 10;
        $offset = ($page - 1) * $limit;
        $searchText = trim($_GET['search'] ?? '');

        $search = [
            'keyword' => $searchText,
            'columns' => ['name']
        ];

        $total = pdo_count('news_category', [], $search);

        $data = pdo_paginate(
            'news_category',
            ['id','name','status'],
            $offset,
            $limit,
            [],
            [],
            'name ASC',
            $search
        );

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $data,
            'pagination' => [
                'total'        => $total,
                'per_page'     => $limit,
                'current_page' => $page,
                'last_page'    => ceil($total / $limit)
            ]
        ]);
    });

    route('GET', '/create', function () {
        view('news_category/create');
    });

    route('POST', '/store', function () {
        $rules = [
            'name' => 'required'
        ];

        $data = [
            'name'  => $_POST['name'] ?? '',
            'slug' => $_POST['slug'] ?? slugify($_POST['name']),
            'status'    => 1
        ];
        
        $id = pdo_insert('news_category', $data, $rules);

        if (is_array($id) && isset($id['errors'])) {
            response_json(
                $id['errors']['code'] ?? 400,
                false,
                $id['errors']['message']
            );
            return;
        }

        response_json(
            200,
            true,
            'Data berhasil dibuat'
        );
    });
    

    route('GET', '/edit/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $data = pdo_select_first('news_category', ['id' => (int)$id], '*', [], [], 1);

        view('news_category/edit', [
            'news_category' => $data
        ]);
    });

    route('PUT', '/update/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $rules = [
            'name' => 'required'
        ];

        $data = [
            'name' => $_POST['name'] ?? '',
            'slug' => $_POST['slug'] ?? slugify($_POST['name'])
        ];

        $ok = pdo_update('news_category', $data, ['id' => (int)$id], $rules);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil diperbarui' : 'Gagal memperbarui data'
        );
    });

    route('DELETE', '/delete/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $ok = pdo_softDelete('news_category', [
            'id'     => (int)$id,
            'status' => 1
        ]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil dihapus' : 'Gagal menghapus data'
        );
    });

    route('PATCH', '/restore/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $ok = pdo_restore('news_category', ['id' => (int)$id]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil dipulihkan' : 'Gagal restore data'
        );
    });

}, 'requireAdmin');


// ==============================
// DATA PINAK/DOKTRIN
// ==============================
routeGroup('/doktrin', function () {

    route('GET', '/', function () {
        $categoryList = pdo_select(
            'doktrin_categories',
            ['status' => 1],
            ['id', 'name', 'slug'],
            [],
            [],
            null,
            'name ASC'
        );

        view('index', [
            'content'   => 'doktrin/index.tpl',
            'categories' => $categoryList,
            'pagetitle' => 'Doktrin',
            'pagename'  => 'doktrin',
            'page_js'   => 'doktrin.js'
        ]);
    });

    route('GET', '/data', function () {
        $doktrinId = $_GET['doktrin_id'] ?? '';
        
        if (!$doktrinId) {
            response_json(200, true, [
                'data' => [],
                'pagination' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 10
                ]
            ]);
            return;
        }

        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 10;
        $offset = ($page - 1) * $limit;
        $searchText = trim($_GET['search'] ?? '');

        $search = [
            'keyword' => $searchText,
            'columns' => ['nama','tahun']
        ];

        $total = pdo_count('doktrin', [], $search);

        $data = pdo_paginate(
            'doktrin',
            [
                'doktrin.id',
                'doktrin.doktrin_id',
                'doktrin.nama',
                'doktrin.tahun',
                'doktrin.file_path',
                'dc.name AS category_name'
            ],
            $offset,
            $limit,
            [
                [
                    'type'  => 'LEFT',
                    'table' => 'doktrin_categories dc',
                    'on'    => 'doktrin.doktrin_id = dc.id'
                ]
            ],
            ['doktrin_id' => $doktrinId],
            'doktrin.nama ASC',
            $search
        );

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $data,
            'pagination' => [
                'total'        => $total,
                'per_page'     => $limit,
                'current_page' => $page,
                'last_page'    => ceil($total / $limit)
            ]
        ]);
    });

    route('GET', '/create', function () {
        $categoryList = pdo_select(
            'doktrin_categories',
            ['status' => 1],
            ['id', 'name', 'slug'],
            [],
            [],
            null,
            'name ASC'
        );

        view('index', [
            'pagetype' => 'can_upload',
            'categories' => $categoryList,
            'content'   => 'doktrin/create.tpl',
            'pagetitle' => 'Tambah Data',
            'pagename'  => 'tambah_data',
            'page_js'   => 'doktrin.js'
        ]);
    });

    route('POST', '/store', function () {
        $rules = [
            'doktrin_id' => 'required',
            'nama'    => 'required',
            'tahun' => 'required'
        ];

        $data = [
            'doktrin_id'  => $_POST['doktrin_id'] ?? '',
            'nama'     => $_POST['nama'] ?? '',
            'tahun'     => $_POST['tahun'] ?? ''
        ];

        if (!empty($_POST['file_path'])) {
            $data['file_path'] = $_POST['file_path'] ?? '';
            // update file menjadi is_temp = 0
            $storageType  = $_POST['storage_type'] ?? 'local';
            if ($storageType === 'local' && !empty($data['file_path'])) {
                $updatedData = ['is_temp'  => 0];
                pdo_update(
                    'uploads',
                    $updatedData,
                    ['file_path' => $data['file_path']],
                    []
                );  
            }
        }

        $id = pdo_insert('doktrin', $data, $rules);

        header('Content-Type: application/json');
        response_json(
            200,
            is_numeric($id) ? true : false,
            is_numeric($id)
                ? 'Data berhasil dibuat'
                : 'Gagal membuat data'
        );

    });
    

    route('GET', '/edit/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }
        $categoryList = pdo_select(
            'doktrin_categories',
            ['status' => 1],
            ['id', 'name', 'slug'],
            [],
            [],
            null,
            'name ASC'
        );

        $data = pdo_select_first('doktrin', ['id' => (int)$id], '*', [], [], 1);

        view('index', [
            'data' => $data,
            'categories' => $categoryList,
            'pagetype' => 'can_upload',
            'content'   => 'doktrin/edit.tpl',
            'pagetitle' => 'Edit Data',
            'pagename'  => 'edit_data',
            'page_js'   => 'doktrin.js'
        ]);
    });

    route('PUT', '/update/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(404, false, 'Invalid ID');
            return;
        }

        $rules = [
            'doktrin_id' => 'required',
            'nama'    => 'required',
            'tahun' => 'required'
        ];

        $old = pdo_select_first(
            'doktrin',
            ['id' => (int)$id],
            '*',
            [],
            [],
            1
        );

        if (!$old) {
            response_json(404, false, 'Data tidak ditemukan');
            return;
        }

        $data = [
            'doktrin_id'     => $_POST['doktrin_id'] ?? $old['doktrin_id'],
            'nama'    => $_POST['nama'] ?? $old['nama'],
            'tahun'        => $_POST['tahun'] ?? $old['tahun']
        ];

        if (!empty($_POST['file_path'])) {
            $data['file_path'] = $_POST['file_path'] ?? '';
        }

        $ok = pdo_update(
            'doktrin',
            $data,
            ['id' => (int)$id],
            $rules
        );

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil diperbarui' : 'Gagal memperbarui data'
        );
    });

    route('DELETE', '/delete/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400,false,'Invalid ID');
            return;
        }

        $ok = pdo_softDelete('doktrin', ['id' => (int)$id]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data dihapus' : 'Gagal menghapus data'
        );
    });

    route('PATCH', '/restore/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400,false,'Invalid ID');
            return;
        }

        $ok = pdo_restore('doktrin', ['id' => (int)$id]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil dipulihkan' : 'Gagal restore data'
        );
    });
    
    route('GET', '/view/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400,false,'Invalid ID');
            return;
        }

        $data = pdo_select_first(
            'doktrin',
            ['id' => (int)$id],
            '*',
            [],
            [],
            1
        );

        if (!$data || empty($data['file_path'])) {
            response_json(400, false, 'File tidak ditemukan');
            return;
        }

        $filePath = ROOT_PATH . '/' . $data['file_path'];

        if (!file_exists($filePath)) {
            response_json(400, false, 'File tidak ada di server');
            return;
        }

        // Tentukan Content-Type
        $mimeMap = [
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png'
        ];

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mime = $mimeMap[$ext] ?? 'application/octet-stream';

        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . basename($data['file_name']) . '"');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
        exit;
    });

}, 'requireAdmin');

// ==============================
// DATA PINAK CATEGORIES
// ==============================
routeGroup('/doktrin_categories', function () {

    route('GET', '/', function () {
        view('index', [
            'content'   => 'doktrin_categories/index.tpl',
            'pagetitle' => 'Doktrin Categories',
            'pagename'  => 'doktrin_categories',
            'page_js'   => 'doktrin_categories.js'
        ]);
    });

    route('GET', '/data', function () {
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 10;
        $offset = ($page - 1) * $limit;
        $searchText = trim($_GET['search'] ?? '');

        $search = [
            'keyword' => $searchText,
            'columns' => ['name']
        ];

        $total = pdo_count('doktrin_categories', [], $search);

        $data = pdo_paginate(
            'doktrin_categories',
            ['id','name','slug','status'],
            $offset,
            $limit,
            [],
            [],
            'name ASC',
            $search
        );

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $data,
            'pagination' => [
                'total'        => $total,
                'per_page'     => $limit,
                'current_page' => $page,
                'last_page'    => ceil($total / $limit)
            ]
        ]);
    });

    route('GET', '/create', function () {
        view('doktrin_categories/create');
    });

    route('POST', '/store', function () {
        $rules = [
            'name' => 'required'
        ];

        $data = [
            'name'  => $_POST['name'] ?? '',
            'slug' => $_POST['slug'] ?? slugify($_POST['name']),
            'status'    => 1
        ];
        
        $id = pdo_insert('doktrin_categories', $data, $rules);

        if (is_array($id) && isset($id['errors'])) {
            response_json(
                $id['errors']['code'] ?? 400,
                false,
                $id['errors']['message']
            );
            return;
        }

        response_json(
            200,
            true,
            'Data berhasil dibuat'
        );
    });
    

    route('GET', '/edit/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $data = pdo_select_first('doktrin_categories', ['id' => (int)$id], '*', [], [], 1);

        view('doktrin_categories/edit', [
            'data' => $data
        ]);
    });

    route('PUT', '/update/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $rules = [
            'name' => 'required'
        ];

        $data = [
            'name' => $_POST['name'] ?? '',
            'slug' => $_POST['slug'] ?? slugify($_POST['name'])
        ];

        $ok = pdo_update('doktrin_categories', $data, ['id' => (int)$id], $rules);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil diperbarui' : 'Gagal memperbarui data'
        );
    });

    route('DELETE', '/delete/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $ok = pdo_softDelete('doktrin_categories', [
            'id'     => (int)$id,
            'status' => 1
        ]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil dihapus' : 'Gagal menghapus data'
        );
    });

    route('PATCH', '/restore/{id}', function ($id) {
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $ok = pdo_restore('doktrin_categories', ['id' => (int)$id]);

        header('Content-Type: application/json');
        response_json(
            200,
            $ok ? true : false,
            $ok ? 'Data berhasil dipulihkan' : 'Gagal restore data'
        );
    });

}, 'requireAdmin');

// ==============================
// upload image / file
// ==============================
routeGroup('/upload', function () {

    route('POST', '/img', function () {

        if (!isset($_FILES['file'])) {
            response_json(400, false, 'File wajib diupload');
            return;
        }

        $type = $_GET['type'] ?? 'default';

        // konfigurasi berdasarkan type
        switch ($type) {
            case 'avatar':
                $maxSize = 1 * 1024 * 1024; // 1MB
                $uploadPath = '/public/uploads/avatar';
                break;

            case 'news':
            default:
                $maxSize = 5 * 1024 * 1024; // 5MB
                $uploadPath = '/public/uploads/images';
                break;
        }

        $upload = upload_file($_FILES['file'], [
            'upload_path' => $uploadPath,
            'prefix' => $type,
            'max_size' => $maxSize,
            'allowed_mime' => [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
                'image/gif' => 'gif'
            ]
        ]);

        if (!$upload['status']) {
            response_json(400, false, $upload['message']);
            return;
        }

        $file = $upload['data'];
        $data = [
            'item_type' => 'image',
            'file_path' => $file['file_path'],
            'file_type' => $file['file_type'],
            'file_name' => $file['file_original_name'],
            'file_size' => $file['file_size']
        ];
        
        $id = pdo_insert('uploads', $data);

        if (is_array($id) && isset($id['errors'])) {
            response_json(
                $id['errors']['code'] ?? 400,
                false,
                $id['errors']['message']
            );
            return;
        }

        response_json(200, true, 'File Berhasil di upload', $data);
    });
    
    route('POST', '/file', function () {
        
        if (!isset($_FILES['file'])) {
            response_json(400, false, 'File wajib diupload');
            return;
        }

        $fileAttrRaw = $_POST['file_attr'] ?? '{}';
        $fileAttr = json_decode($fileAttrRaw, true);

        $upload = upload_file($_FILES['file'], [
            'upload_path' => '/public/uploads/'.$fileAttr['uploadFolder'].'/'.$fileAttr['jenis'],
            'prefix' => $fileAttr['jenis'],
            'max_size' => 10 * 1024 * 1024, // 10 mb
            'allowed_mime' => [
                'application/pdf' => 'pdf',
                'application/msword' => 'doc',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                'image/jpeg' => 'jpg',
                'image/png'  => 'png'
            ]
        ]);

        if (!$upload['status']) {
            response_json(400, false, $upload['message']);
            return;
        }

        $file = $upload['data'];
        $data = [
            'item_type' => 'file',
            'file_path' => $file['file_path'],
            'file_type' => $file['file_type'],
            'file_name' => $file['file_original_name'],
            'file_size' => $file['file_size']
        ];
        
        $id = pdo_insert('uploads', $data);

        if (is_array($id) && isset($id['errors'])) {
            response_json(
                $id['errors']['code'] ?? 400,
                false,
                $id['errors']['message']
            );
            return;
        }

        response_json(200, true, 'File Berhasil di upload', $data);
    });

    route('POST', '/rollback', function () {

        $path = $_POST['file_path'] ?? '';

        if (!$path) {
            response_json(400, false, 'File path kosong');
            return;
        }

        $fullPath = ROOT_PATH . '/' . ltrim($path, '/');

        if (file_exists($fullPath)) {
            unlink($fullPath);
            response_json(200, true, 'File berhasil dihapus');
        } else {
            response_json(404, false, 'File tidak ditemukan');
        }
    });

}, 'requireAdmin');


// ==============================
// AUTH
// ==============================
route('GET', '/logout', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
    header('Location: /');
    exit;
});

// ==============================
// CRON JOB
// pemakaian curl https://apli.kasi/admin/cron/checktocleanfile
// pemakaian curl https://apli.kasi/admin/cron/checktocleannews
// ==============================
function normalizePath(string $path): string
{
    $path = parse_url($path, PHP_URL_PATH);
    return '/' . ltrim($path, '/');
}
routeGroup('/cron', function () {

    route('GET', '/checktocleanfile', function () {
        $SAFE_MINUTES = 60;
        $checkUploads = pdo_select(
            'uploads',
            [
                'is_temp' => 1,
                'created_at <' => date('Y-m-d H:i:s', time() - ($SAFE_MINUTES * 60))
            ],
            ['id', 'file_path'],
            [],
            [],
            null,
            null
        );

        if (!$checkUploads) {
            exit;
        }

        foreach ($checkUploads as $row) {
            $path = ROOT_PATH . '/' . ltrim($row['file_path'], '/');
            if (is_file($path)) {
                unlink($path);
            }
            $conn = getDBConnection();
            $del = $conn->prepare("DELETE FROM uploads WHERE id = ?");
            $del->execute([$row['id']]);
        }
        exit;
    });

    route('GET', '/checktocleannews', function () {
        $checkUploads = pdo_select(
            'uploads',
            [
                'is_temp' => 1,
                'item_type' => 'image',
                'created_at >=' => date('Y-m-d H:i:s', time() - (2 * 3600))
            ],
            ['id', 'file_path'],
            [],
            [],
            null,
            null
        );

        if (!$checkUploads) {
            exit;
        }

        $usedFiles = [];
        $contents = pdo_select('news', ['created_at >=' => date('Y-m-d H:i:s', time() - (2 * 3600))], ['full_text_html'], [], [], null, null);
        foreach ($contents as $row) {
            preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $row['full_text_html'], $matches);
            foreach ($matches[1] as $src) {
                $usedFiles[] = normalizePath($src);
            }
        }
        $usedFiles = array_unique($usedFiles);   
        foreach ($checkUploads as $file) {
            $relative = normalizePath($file['file_path']);
            $fullPath = ROOT_PATH . '/' . ltrim($relative, '/');
            if (!in_array($relative, $usedFiles) && file_exists($fullPath)) {
                unlink($fullPath);
                $conn = getDBConnection();
                $del = $conn->prepare("DELETE FROM uploads WHERE id = ?");
                $del->execute([$file['id']]);
            }
        }   
        exit;
    });

});

// ==============================
// 404 Not Found
// ==============================
http_response_code(404);
view('index', [
    'content'   => '404.tpl',
    'pagetitle' => '404',
    'pagename'  => '404'
]);

exit;

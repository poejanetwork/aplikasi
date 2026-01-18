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

        $total = pdo_count('users');

        $users = pdo_paginate(
            'users',
            ['id', 'fullname', 'email', 'status'],
            $offset,
            $limit
        );

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $users,
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

        echo json_encode([
            'status'  => is_numeric($id),
            'message' => is_numeric($id)
                ? 'Data berhasil dibuat'
                : 'Gagal membuat data'
        ]);
    });
    

    route('GET', '/edit/{id}', function ($id) {
        if (!is_numeric($id)) {
            http_response_code(400);
            exit('Invalid ID');
        }

        $user = pdo_select('users', ['id' => (int)$id], '*', [], [], 1);

        view('users/edit', [
            'user' => $user
        ]);
    });

    route('PUT', '/update/{id}', function ($id) {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['status' => false, 'message' => 'Invalid ID']);
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

        echo json_encode([
            'status'  => $ok === true,
            'message' => $ok === true
                ? 'Data berhasil diperbarui'
                : 'Gagal memperbarui data'
        ]);
    });

    route('DELETE', '/delete/{id}', function ($id) {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['status' => false, 'message' => 'Invalid ID']);
            return;
        }

        $ok = pdo_softDelete('users', [
            'id'     => (int)$id,
            'status' => 1
        ]);

        echo json_encode([
            'status'  => (bool)$ok,
            'message' => $ok ? 'Data dihapus' : 'Gagal menghapus data'
        ]);
    });

    route('PATCH', '/restore/{id}', function ($id) {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['status' => false, 'message' => 'Invalid ID']);
            return;
        }

        $ok = pdo_restore('users', ['id' => (int)$id]);

        echo json_encode([
            'status'  => (bool)$ok,
            'message' => $ok ? 'Data berhasil dipulihkan' : 'Gagal restore data'
        ]);
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
            'pagename'  => 'Surat Masuk',
            'page_js'   => 'surat_masuk.js'
        ]);
    });

    route('GET', '/data', function () {
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 10;
        $offset = ($page - 1) * $limit;

        $total = pdo_count('surat_masuk');

        $users = pdo_paginate(
            'surat_masuk',
            ['id', 'nomor_surat', 'asal_surat',' perihal', 'tanggal_surat', 'keterangan', 'status'],
            $offset,
            $limit
        );

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $users,
            'pagination' => [
                'total'        => $total,
                'per_page'     => $limit,
                'current_page' => $page,
                'last_page'    => ceil($total / $limit)
            ]
        ]);
    });

    route('GET', '/create', function () {
        view('surat_masuk/create');
    });
// ini belum
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

        $id = pdo_insert('surat_masuk', $data, $rules);

        echo json_encode([
            'status'  => is_numeric($id),
            'message' => is_numeric($id)
                ? 'Data berhasil dibuat'
                : 'Gagal membuat data'
        ]);
    });
    

    route('GET', '/edit/{id}', function ($id) {
        if (!is_numeric($id)) {
            http_response_code(400);
            exit('Invalid ID');
        }

        $data = pdo_select('surat_masuk', ['id' => (int)$id], '*', [], [], 1);

        view('surat_masuk/edit', [
            'data' => $data
        ]);
    });

    route('PUT', '/update/{id}', function ($id) {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['status' => false, 'message' => 'Invalid ID']);
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

        echo json_encode([
            'status'  => $ok === true,
            'message' => $ok === true
                ? 'Data berhasil diperbarui'
                : 'Gagal memperbarui data'
        ]);
    });

    route('DELETE', '/delete/{id}', function ($id) {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['status' => false, 'message' => 'Invalid ID']);
            return;
        }

        $ok = pdo_softDelete('users', [
            'id'     => (int)$id,
            'status' => 1
        ]);

        echo json_encode([
            'status'  => (bool)$ok,
            'message' => $ok ? 'Data dihapus' : 'Gagal menghapus data'
        ]);
    });

    route('PATCH', '/restore/{id}', function ($id) {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['status' => false, 'message' => 'Invalid ID']);
            return;
        }

        $ok = pdo_restore('users', ['id' => (int)$id]);

        echo json_encode([
            'status'  => (bool)$ok,
            'message' => $ok ? 'Data berhasil dipulihkan' : 'Gagal restore data'
        ]);
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
// 404
// ==============================
http_response_code(404);

view('index', [
    'content'   => '404.tpl',
    'pagetitle' => '404',
    'pagename'  => '404'
]);

exit;

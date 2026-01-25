<?php

function env($key, $default = null)
{
    return $_ENV[$key]
        ?? $_SERVER[$key]
        ?? getenv($key)
        ?? $default;
}

// Connecting database
function getDBConnection() {
  static $conn = null;

  if ($conn === null) {
    $time = new DateTime();
    $minutes = $time->getOffset() / 60;
    $sign = ($minutes < 0 ? -1 : 1);
    $minutes = abs($minutes);
    $hours = floor($minutes / 60);
    $minutes -= $hours * 60;
    $offset = sprintf('%+d:%02d', $hours * $sign, $minutes);

    try {
      $conn = new PDO(
        'mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_NAME') . ';charset=utf8',
        env('DB_USER'),
        env('DB_PASS')
      );
      $conn->exec("SET time_zone='$offset';");
      $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die('Database Connection Failed: ' . $e->getMessage());
    }
  }

  return $conn;
}

function isAdmin(): bool
{
    if (empty($_SESSION['admindetails'])) {
        return false;
    }

    return in_array(
        $_SESSION['admindetails']['user_type'] ?? null,
        ['admin', 'superuser'],
        true
    );
}
function requireLogin(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['admindetails'])) {
        if (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'status'  => false,
                'message' => 'Silakan login terlebih dahulu'
            ]);
            exit;
        }
        header('Location: /login');
        exit;
    }
}

function requireAdmin(): void
{
    requireLogin();

    if (!in_array($_SESSION['admindetails']['user_type'] ?? '', ['admin', 'superuser'], true)) {

        if (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            http_response_code(403);
            echo json_encode([
                'status'  => false,
                'message' => 'Akses ditolak'
            ]);
            exit;
        }

        http_response_code(403);
        
        view('index', [
            'content'   => '403.tpl',
            'pagetitle' => '403',
            'pagename'  => '403'
        ]);
        exit;
    }
}
function surl($path = '') {
    return "/" . env('ADMIN_URL'). "/" . ltrim($path, '/');
}


function logDebug($msg = null){
$log = "response: " . print_r($msg, true) . PHP_EOL .
    "-------------------------" . PHP_EOL;
//-
file_put_contents('./log_' . date("j.n") . '.txt', $log, FILE_APPEND);
}

function readArr($msg){
    return print_r($msg, true) . PHP_EOL;
}

function getRealIpAddr(){
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
        foreach (explode(',', $_SERVER[$key]) as $ip) {
            $ip = trim($ip);

            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
            return $ip;
            } else {
            return $ip;
            }
        }
        }
    }
}
function response_json($number, $type = false, $message, $data = null){
    http_response_code($number);
    echo json_encode(['status' => $type, 'message' => $message, 'data' => $data]);
}

// detect user agent and IP
$getUserAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
$getUserIp = getRealIpAddr();

// secondary function
function date_format_id($date, $with_time = false)
{
    if (empty($date) || $date === '0000-00-00') {
        return '-';
    }

    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $timestamp = strtotime($date);
    if (!$timestamp) {
        return $date; // fallback aman
    }

    $day   = date('d', $timestamp);
    $month = (int) date('m', $timestamp);
    $year  = date('Y', $timestamp);

    $result = $day . ' ' . $bulan[$month] . ' ' . $year;

    if ($with_time) {
        $result .= ' ' . date('H:i', $timestamp);
    }

    return $result;
}


/**
 * Validator lengkap untuk form dan file upload.
 *
 * @param array $data    Data input (biasanya $_POST atau $_GET)
 * @param array $files   Data file (biasanya $_FILES)
 * @param array $rules   Aturan validasi
 * @param array $messages Pesan error custom (opsional)
 * 
 * @return array ['status' => bool, 'errors' => array]
 */
function validateFields(array $data, array $files, array $rules, array $messages = [])
{
    global $lang;

    $errors = [];
    $defaultMessages = $lang['validation'] ?? [];

    foreach ($rules as $field => $ruleString) {
        $rulesArray = explode('|', $ruleString);

        foreach ($rulesArray as $rule) {
            $param = null;

            if (strpos($rule, ':') !== false) {
                [$rule, $param] = explode(':', $rule, 2);
            }

            $value = trim($data[$field] ?? '');
            $isFile = isset($files[$field]) && is_array($files[$field]);

            // Ambil pesan error, prioritas: $messages → $lang['validation']
            $msgKey = "$field.$rule";
            $message = $messages[$msgKey]
                ?? ($defaultMessages[$rule] ?? "Field $field not valid.");

            $message = str_replace(':field', ucfirst(str_replace('_', ' ', $field)), $message);
            if ($param !== null) {
                $message = str_replace(':param', $param, $message);
            }
            switch ($rule) {
                case 'required':
                    if ($isFile) {
                        if ($files[$field]['error'] === UPLOAD_ERR_NO_FILE) {
                            $errors[$field][] = $message;
                        }
                    } else {
                        if ($value === '') {
                            $errors[$field][] = $message;
                        }
                    }
                    break;
                case 'numeric':
                    if ($value !== '' && !is_numeric($value)) {
                        $errors[$field][] = $message;
                    }
                    break;
                case 'email':
                    if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$field][] = $message;
                    }
                    break;
                case 'date':
                    if ($value !== '') {
                        $d = DateTime::createFromFormat('Y-m-d', $value);
                        if (!($d && $d->format('Y-m-d') === $value)) {
                            $errors[$field][] = $message;
                        }
                    }
                    break;
                case 'datetime':
                    if ($value !== '') {
                        $d = DateTime::createFromFormat('Y-m-d H:i:s', $value);
                        if (!($d && $d->format('Y-m-d H:i:s') === $value)) {
                            $errors[$field][] = $message;
                        }
                    }
                    break;
                case 'mimes':
                    if ($isFile && $files[$field]['error'] === UPLOAD_ERR_OK) {
                        $allowedTypes = explode(',', strtolower($param));
                        $ext = strtolower(pathinfo($files[$field]['name'], PATHINFO_EXTENSION));
                        if (!in_array($ext, $allowedTypes)) {
                            $errors[$field][] = str_replace(':param', implode(', ', $allowedTypes), $message);
                        }
                    }
                    break;
                case 'max':
                    if ($isFile) {
                        // ✅ Jika file adalah avatar, override max size jadi 100KB
                        $jenis = $data['jenis'] ?? '';
                        $maxSizeKB = $jenis === 'avatar' ? 100 : (int)$param;
                        $fileSizeKB = $files[$field]['size'] / 1024;

                        if ($files[$field]['error'] === UPLOAD_ERR_OK && $fileSizeKB > $maxSizeKB) {
                            $errors[$field][] = str_replace(':param', $maxSizeKB . 'KB', $message);
                        }
                    } else {
                        if ($value !== '' && strlen($value) > (int)$param) {
                            $errors[$field][] = $message;
                        }
                    }
                    break;

                default:
                    break;
            }
        }
    }

    if (!empty($errors)) {
        $errorMessages = [];
        foreach ($errors as $fieldErrors) {
            foreach ($fieldErrors as $error) {
                $errorMessages[] = $error;
            }
        }
        return ['status' => false, 'errors' => implode("; ", $errorMessages)];
    }
    // Jika validasi sukses
    return ['status' => true, 'errors' => null];
}

function pdo_select(
    $table,
    $where = [],
    $fields = '*',
    $joins = [],
    $rules = [],
    $limit = null,
    $order = null,
    $search = [] // NEW
) {
    $conn = getDBConnection();

    if ($rules) {
        $validation = validateFields($where, [], $rules);
        if (!$validation['status']) {
            return ['errors' => getErrorCode(25)];
        }
    }

    if (is_array($fields)) {
        $fields = implode(',', $fields);
    }

    $sql = "SELECT {$fields} FROM {$table}";
    $params = [];

    /* ======================
       JOIN
    ====================== */
    if (!empty($joins)) {
        foreach ($joins as $join) {
            $type  = strtoupper($join['type'] ?? 'LEFT');
            $tableJoin = $join['table'] ?? '';
            $on    = $join['on'] ?? '';
            if ($tableJoin && $on) {
                $sql .= " {$type} JOIN {$tableJoin} ON {$on}";
            }
        }
    }

    /* ======================
       WHERE (EQUAL)
    ====================== */
    $conditions = [];

    foreach ($where as $col => $val) {

        if (preg_match('/\s+(>=|<=|<>|!=|>|<|LIKE|IN)$/i', $col, $m)) {
            $operator = strtoupper($m[1]);
            $column = trim(str_replace($m[0], '', $col));
        } else {
            $operator = '=';
            $column = $col;
        }

        $param = str_replace('.', '_', $column) . count($params);

        if ($operator === 'IN' && is_array($val)) {
            $inParams = [];
            foreach ($val as $i => $v) {
                $p = "{$param}_{$i}";
                $inParams[] = ":{$p}";
                $params[$p] = $v;
            }
            $conditions[] = "{$column} IN (" . implode(',', $inParams) . ")";
        } else {
            $conditions[] = "{$column} {$operator} :{$param}";
            $params[$param] = $val;
        }
    }


    /* ======================
       SEARCH (LIKE)
    ====================== */
    if (!empty($search['keyword']) && !empty($search['columns'])) {
        $likeParts = [];
        foreach ($search['columns'] as $i => $col) {
            $p = "search_{$i}";
            $likeParts[] = "{$col} LIKE :{$p}";
            $params[$p] = '%' . $search['keyword'] . '%';
        }
        $conditions[] = '(' . implode(' OR ', $likeParts) . ')';
    }

    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    /* ======================
       ORDER
    ====================== */
    if ($order) {
        if (is_array($order)) {
            $orders = [];
            foreach ($order as $col => $dir) {
                $orders[] = "{$col} " . (strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC');
            }
            $sql .= ' ORDER BY ' . implode(', ', $orders);
        } else {
            $sql .= " ORDER BY {$order}";
        }
    }

    /* ======================
       LIMIT
    ====================== */
    if ($limit !== null) {
        $sql .= " LIMIT {$limit}";
    }

    $stmt = $conn->prepare($sql);
    foreach ($params as $k => $v) {
        $stmt->bindValue(":{$k}", $v);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function pdo_select_first(
    $table,
    $where = [],
    $fields = '*',
    $joins = [],
    $order = null,
    $search = []
) {
    $data = pdo_select(
        $table,
        $where,
        $fields,
        $joins,
        [],
        1,
        $order,
        $search
    );

    return $data[0] ?? [];
}
function pdo_paginate(
    $table,
    $fields,
    $offset,
    $limit,
    $joins = [],
    $where = [],
    $order = 'id DESC',
    $search = []
) {
    return pdo_select(
        $table,
        $where,
        $fields,
        $joins,
        [],
        "{$offset}, {$limit}",
        $order,
        $search
    );
}

    function pdo_insert($table, $data, $rules = [])
    {
        $conn = getDBConnection();

        // validasi input
        if ($rules) {
            $validation = validateFields($data, [], $rules);
            if (!$validation['status']) {
                return [
                    'errors' => [
                        'code'    => 422,
                        'type'    => 'validation',
                        'message' => 'Validasi gagal',
                        'detail'  => $validation['errors'] ?? []
                    ]
                ];
            }
        }

        unset($data['_method']);

        $cols = array_keys($data);
        $fields = implode(',', $cols);
        $placeholders = ':' . implode(',:', $cols);

        try {
            $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
            $stmt = $conn->prepare($sql);

            foreach ($data as $k => $v) {
                $stmt->bindValue(":{$k}", $v);
            }

            $stmt->execute();

            return (int)$conn->lastInsertId();

        } catch (PDOException $e) {
            // mapping error MySQL
            $errorInfo = $e->errorInfo; // [SQLSTATE, error_code, message]
            $errorCode = $errorInfo[1] ?? null;
            $message = getErrorCode($errorCode);

            logDebug([
                'errorCode' => $errorCode,
                'type' => 'PDO_INSERT_ERROR',
                'table' => $table,
                'error' => $e->getMessage()
            ]);

            return [
                'errors' => [
                    'code'    => 500,
                    'type'    => 'database',
                    'message' => $message
                ]
            ];
        }
    }


    function pdo_reorder_insert(
        $table,
        $column,
        $newPosition,
        $pk = 'id',
        $offset = 10000
    ) {
        $conn = getDBConnection();

        // 1. dorong ke zona aman
        $stmt1 = $conn->prepare("
            UPDATE {$table}
            SET {$column} = {$column} + :offset
            WHERE {$column} >= :pos
        ");
        $stmt1->execute([
            ':offset' => $offset,
            ':pos'    => $newPosition
        ]);

        // 2. tarik balik ke posisi final
        $stmt2 = $conn->prepare("
            UPDATE {$table}
            SET {$column} = {$column} - :offset + 1
            WHERE {$column} >= :pos + :offset1
        ");
        $stmt2->execute([
            ':offset' => $offset,
            ':offset1' => $offset,
            ':pos'    => $newPosition
        ]);

        return true;
    }


    function pdo_reorder_update(
        $table,
        $column,
        $old,
        $new,
        $excludeId,
        $pk = 'id',
        $offset = 10000
    ) {
        if ($old === $new) {
            return true;
        }

        $conn = getDBConnection();

        if ($new < $old) {
            // naik
            // 1. push ke zona aman
            $conn->prepare("
                UPDATE {$table}
                SET {$column} = {$column} + :offset
                WHERE {$column} >= :new
                AND {$column} < :old
                AND {$pk} != :id
            ")->execute([
                ':offset' => $offset,
                ':new'    => $new,
                ':old'    => $old,
                ':id'     => $excludeId
            ]);

            // 2. tarik balik
            $conn->prepare("
                UPDATE {$table}
                SET {$column} = {$column} - :offset + 1
                WHERE {$column} >= :new + :offset1
                AND {$column} < :old + :offset2
            ")->execute([
                ':offset' => $offset,
                ':offset1' => $offset,
                ':offset2' => $offset,
                ':new'    => $new,
                ':old'    => $old
            ]);
        } else {
            // turun
            $conn->prepare("
                UPDATE {$table}
                SET {$column} = {$column} + :offset
                WHERE {$column} <= :new
                AND {$column} > :old
                AND {$pk} != :id
            ")->execute([
                ':offset' => $offset,
                ':new'    => $new,
                ':old'    => $old,
                ':id'     => $excludeId
            ]);

            $conn->prepare("
                UPDATE {$table}
                SET {$column} = {$column} - :offset - 1
                WHERE {$column} <= :new + :offset1
                AND {$column} > :old + :offset2
            ")->execute([
                ':offset' => $offset,
                ':offset1' => $offset,
                ':offset2' => $offset,
                ':new'    => $new,
                ':old'    => $old
            ]);
        }

        return true;
    }

    function pdo_update($table, $data, $where, $rules = [])
    {
        $conn = getDBConnection();
        // Validasi data dan where jika ada aturan
        if ($rules) {
            $validation = validateFields(array_merge($data, $where), [], $rules);
            if (!$validation['status']) {
                return ['errors' => getErrorCode(25)];
            }
        }

        // Hapus field tidak relevan dari data (jika ada)
        unset($data['act'], $data['form_id'], $data['form_token'], $data['form_sig']);

        // Pastikan ada data dan where
        if (empty($data)) {
            return ['errors' => 'No data to update'];
        }
        if (empty($where)) {
            return ['errors' => 'Where condition is required to update'];
        }

        // Siapkan bagian SET
        $setParts = [];
        foreach ($data as $key => $value) {
            $setParts[] = "$key = :set_$key";
        }
        $setClause = implode(", ", $setParts);

        // Siapkan bagian WHERE
        $whereParts = [];
        foreach ($where as $key => $value) {
            $whereParts[] = "$key = :where_$key";
        }
        $whereClause = implode(" AND ", $whereParts);

        // Siapkan SQL
        $sql = "UPDATE $table SET $setClause WHERE $whereClause";

        $stmt = $conn->prepare($sql);

        // Bind parameter data untuk SET dengan prefix :set_
        foreach ($data as $key => $value) {
            $stmt->bindValue(":set_$key", $value);
        }

        // Bind parameter untuk WHERE dengan prefix :where_
        foreach ($where as $key => $value) {
            $stmt->bindValue(":where_$key", $value);
        }

        // Eksekusi dan cek hasil
        $success = $stmt->execute();

        return $success ? true : ['errors' => 'Update failed'];
    }

    function pdo_softDelete(string $table, array $where): bool
    {
        return pdo_setStatus($table, $where, 0);
    }

    function pdo_restore(string $table, array $where): bool
    {
        return pdo_setStatus($table, $where, 1);
    }

    function pdo_setStatus(string $table, array $where, int $status): bool
    {
        $conn = getDBConnection();

        $allowedTables = ['users', 'surat_masuk', 'disposisi'];
        if (!in_array($table, $allowedTables, true)) {
            return false;
        }

        if (empty($where)) {
            return false;
        }

        if (!in_array($status, [0, 1], true)) {
            return false;
        }

        $conditions = [];
        $params = [];

        foreach ($where as $key => $val) {
            $conditions[] = "$key = :$key";
            $params[":$key"] = $val;
        }

        // gunakan placeholder BERBEDA
        $conditions[] = "status != :current_status";
        $params[':current_status'] = $status;

        $sql = "UPDATE {$table} 
                SET status = :new_status 
                WHERE " . implode(' AND ', $conditions);

        $params[':new_status'] = $status;

        $stmt = $conn->prepare($sql);
        return $stmt->execute($params);
    }

    function pdo_count($table, $where = [], $search = [])
    {
        $conn = getDBConnection();
        $sql = "SELECT COUNT(*) FROM {$table}";
        $conditions = [];
        $params = [];

        foreach ($where as $col => $val) {
            $conditions[] = "{$col} = ?";
            $params[] = $val;
        }

        if (!empty($search['keyword']) && !empty($search['columns'])) {
            $likes = [];
            foreach ($search['columns'] as $col) {
                $likes[] = "{$col} LIKE ?";
                $params[] = '%' . $search['keyword'] . '%';
            }
            $conditions[] = '(' . implode(' OR ', $likes) . ')';
        }

        if ($conditions) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    function upload_file(array $file, array $config = [])
    {
        $defaults = [
            'upload_path' => 'public/uploads',
            'allowed_mime' => [],
            'max_size' => 5 * 1024 * 1024,
            'prefix' => 'file',
            'use_date_folder' => true
        ];

        $config = array_merge($defaults, $config);

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $messages = [
                UPLOAD_ERR_INI_SIZE  => 'Ukuran file melebihi batas server',
                UPLOAD_ERR_FORM_SIZE => 'Ukuran file melebihi batas form',
                UPLOAD_ERR_PARTIAL  => 'File terupload sebagian',
                UPLOAD_ERR_NO_FILE  => 'File tidak ditemukan'
            ];

            return [
                'status' => false,
                'message' => $messages[$file['error']] ?? 'Upload error'
            ];
        }

        if (!empty($config['allowed_mime']) &&
            !isset($config['allowed_mime'][$file['type']])) {
            return [
                'status' => false,
                'message' => 'Format file tidak diizinkan'
            ];
        }

        if ($file['size'] > $config['max_size']) {
            return [
                'status' => false,
                'message' => 'Ukuran file terlalu besar'
            ];
        }

        $ext = $config['allowed_mime'][$file['type']]
            ?? pathinfo($file['name'], PATHINFO_EXTENSION);

        $year  = date('Y');
        $month = date('m');

        $basePath = ROOT_PATH . '/' . trim($config['upload_path'], '/');
        $urlPath  = trim($config['upload_path'], '/');

        if ($config['use_date_folder']) {
            $basePath .= "/$year/$month";
            $urlPath  .= "/$year/$month";
        }

        if (!is_dir($basePath)) {
            mkdir($basePath, 0755, true);
        }

        $filename = $config['prefix'] . '_' . time() . '_' . rand(100,999) . '.' . $ext;
        $target   = $basePath . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            return [
                'status' => false,
                'message' => 'Gagal menyimpan file'
            ];
        }

        return [
            'status' => true,
            'message' => 'File berhasil diupload',
            'data' => [
                'file_path' => $urlPath . '/' . $filename,
                'file_name' => $filename,
                'file_original_name' => $file['name'],
                'file_type' => $ext,
                'file_size' => $file['size']
            ]
        ];
    }

    // use {$row.file_size|@format_file_size}
    function format_file_size($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }

    function logHistory(array $user, string $table, string $action, int $rowId, $oldData=null, $newData=null, $getUserIp, $getUserAgent){
        $conn = getDBConnection();
        $oldJson = $oldData ? json_encode($oldData, JSON_UNESCAPED_UNICODE) : null;
        $newJson = $newData ? json_encode($newData, JSON_UNESCAPED_UNICODE) : null;

        $stmt = $conn->prepare("INSERT into log_history (uid,user_type,fullname,action,table_name,old_value,new_value,data_date,ip_addr,browser_agent) VALUES (:uid,:utype,:fname,:action,:tbl,:oldval,:newval,:data_date,:dip,:dag)");
        $stmt->execute([
            ':uid'    => $user['id'],
            ':utype'  => $user['user_type'],
            ':fname'  => $user['fullname'],
            ':action' => $action,
            ':tbl'    => $table,
            ':oldval' => $oldJson,
            ':newval' => $newJson,
            ':data_date' => date('Y-m-d H:i'),
            ':dip' => $getUserIp,
            ':dag' => $getUserAgent
        ]);
        $logId = $conn->lastInsertId();
        if ($table === 'students') {
            $up = $conn->prepare("UPDATE $table SET log_history_id = :lid WHERE id = :sid");
            $up->execute([':lid'=>$logId, ':sid'=>$rowId]);
        }
        return $logId;
    }
    
    function slugify($text){
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtoupper($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

// ==============================
// REQUEST INFO
// ==============================

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Hilangkan /admin.php dari URL
$uri = preg_replace('#^/admin(\.php)?#', '', $uri);
$uri = rtrim($uri, '/') ?: '/';

// NORMALISASI ROOT
if ($uri === '') {
    $uri = '/';
}
// ==============================
// METHOD SPOOFING
// ==============================
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}

// ==============================
// ROUTER CORE
// ==============================
function route($httpMethod, $path, $callback)
{
    global $method, $uri, $routeGroupPrefix, $routeGroupMiddleware;

    if ($method !== $httpMethod) return;

    $fullPath = rtrim($routeGroupPrefix . '/' . ltrim($path, '/'), '/');
    $fullPath = $fullPath === '' ? '/' : $fullPath;

    // ROOT special case
    if ($fullPath === '/' && $uri === '/') {
        foreach ($routeGroupMiddleware as $mw) {
            $mw();
        }
        call_user_func($callback);
        exit;
    }

    $pattern = preg_replace('#\{([\w]+)\}#', '(?P<$1>[^/]+)', $fullPath);
    $pattern = '#^' . $pattern . '$#';

    if (preg_match($pattern, $uri, $matches)) {

        foreach ($routeGroupMiddleware as $mw) {
            $mw();
        }

        $params = array_filter(
            $matches,
            fn ($k) => !is_int($k),
            ARRAY_FILTER_USE_KEY
        );

        call_user_func_array($callback, $params);
        exit;
    }
}

// ==============================
// ROUTE GROUP
// ==============================
$routeGroupPrefix = '';
$routeGroupMiddleware = [];

function routeGroup(string $prefix, callable $callback, callable $middleware = null)
{
    global $routeGroupPrefix, $routeGroupMiddleware;

    $previousPrefix = $routeGroupPrefix;
    $previousMiddleware = $routeGroupMiddleware;

    $prefix = '/' . trim($prefix, '/');
    $routeGroupPrefix .= $prefix;

    if ($middleware) {
        $routeGroupMiddleware[] = $middleware;
    }

    $callback();

    $routeGroupPrefix = $previousPrefix;
    $routeGroupMiddleware = $previousMiddleware;
}

// ==============================
// VIEW HELPER
// ==============================
function view($tpl, $data = [])
{
    global $smarty;

    foreach ($data as $k => $v) {
        $smarty->assign($k, $v);
    }

    $smarty->display($tpl . '.tpl');
}

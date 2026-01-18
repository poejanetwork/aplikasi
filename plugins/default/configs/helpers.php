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
// detect user agent and IP
$getUserAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
$getUserIp = getRealIpAddr();

// secondary function

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
    $where = array(),
    $fields = '*',
    $joins = array(),
    $rules = array(),
    $limit = null,
    $order = null
) {
    $conn = getDBConnection();

    if ($rules) {
        $validation = validateFields($where, array(), $rules);
        if (!$validation['status']) {
            return ['errors' => getErrorCode(25)];
        }
    }

    if (is_array($fields)) {
        $fields = implode(',', $fields);
    }

    $sql = "SELECT $fields FROM $table";

    // JOIN
    if (!empty($joins) && is_array($joins)) {
        foreach ($joins as $join) {
            $joinType = strtoupper($join['type'] ?? 'LEFT');
            $joinTable = $join['table'] ?? '';
            $joinOn = $join['on'] ?? '';
            if ($joinTable && $joinOn) {
                $sql .= " $joinType JOIN $joinTable ON $joinOn";
            }
        }
    }

    // WHERE
    if (!empty($where)) {
        $conditions = [];
        foreach ($where as $k => $v) {
            $param = str_replace('.', '_', $k);
            $conditions[] = "$k = :$param";
        }
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    // ORDER
    if (!is_null($order)) {
        if (is_array($order)) {
            $orderClauses = [];
            foreach ($order as $col => $dir) {
                $dir = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';
                $orderClauses[] = "$col $dir";
            }
            $sql .= " ORDER BY " . implode(', ', $orderClauses);
        } elseif (is_string($order)) {
            $sql .= " ORDER BY $order";
        }
    }

    // LIMIT (support int dan "offset,limit")
    if (!is_null($limit)) {
        if (is_int($limit)) {
            $sql .= " LIMIT $limit";
        } elseif (is_string($limit)) {
            $sql .= " LIMIT $limit";
        }
    }

    $stmt = $conn->prepare($sql);

    foreach ($where as $k => $v) {
        $param = str_replace('.', '_', $k);
        $stmt->bindValue(":$param", $v);
    }

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($result)<=1){
        return $result[0] ?? [];
    }
    if ($limit !== null) {
        return $result ?: [];
    }
}
function pdo_paginate($table, $fields, $offset, $limit)
{
    return pdo_select(
        $table,
        [],
        $fields,
        [],
        [],
        "$offset, $limit"
    );
}

function pdo_insert($table, $data, $rules)
{
    $conn = getDBConnection();
    // Validasi input
    if ($rules) {
        $validation = validateFields($data, array(), $rules);
        if (!$validation['status']) {
            return ['errors' => getErrorCode(25)];
        }
    }
    // Hapus field tidak relevan
    // unset($data['act'], $data['form_id'], $data['form_token'], $data['form_sig']);
    unset($data['_method']);

    $cols = array_keys($data);
    $fields = implode(',', $cols);
    $placeholders = ':' . implode(',:', $cols);

    $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
    $stmt = $conn->prepare($sql);
    foreach ($data as $k => $v) {
        $stmt->bindValue(":$k", $v);
    }
    $stmt->execute();
    return $conn->lastInsertId();
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

        $allowedTables = ['users', 'roles', 'satuan'];
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

    function pdo_count($table, $where = [])
    {
        $conn = getDBConnection();

        $sql = "SELECT COUNT(*) FROM {$table}";
        $params = [];

        if (!empty($where)) {
            $conditions = [];
            foreach ($where as $key => $value) {
                $conditions[] = "{$key} ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
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

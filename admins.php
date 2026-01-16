<?php
ini_set('display_errors', 'on');
$urldetails       = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$urldetail        = parse_url($urldetails);
$urldetail['dir'] = dirname($_SERVER['PHP_SELF']);
if (session_status() === PHP_SESSION_NONE) {
  session_name("PHPSESSID");
  session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/', 
    'domain'   => '.' . $_SERVER['SERVER_NAME'],
    'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'httponly' => true,
    'samesite' => 'Lax'
  ]);
  session_start();
}
ob_start();

if (file_exists('install.php')) {
  die('Please Install Script First.');
}

// Load config terenkripsi
$iv = substr("LinkAbuPujas3cr3tk3y", 0, 16);
$enkey = openssl_digest("LinkAbuPujas3cr3tk3y", 'MD5', true);
function loadSettings() {
  global $enkey;
  global $iv;
    $content = file_get_contents("settings.php");
    $lines = explode("\n", $content);
    $config = unserialize(openssl_decrypt(json_decode(base64_decode($lines[1]), true), "aes-256-cbc", $enkey, 0, $iv));
  return $config;
}

// Load timezone
$config = loadSettings();
$settings = $config['settings'];
// SELECT TIMEZONE
date_default_timezone_set($settings['sitetimezone']);
// Connecting database
function getDBConnection() {
  static $conn = null;

  if ($conn === null) {
    $getConfig = loadSettings();
    $configdb = $getConfig['database'];

    $time = new DateTime();
    $minutes = $time->getOffset() / 60;
    $sign = ($minutes < 0 ? -1 : 1);
    $minutes = abs($minutes);
    $hours = floor($minutes / 60);
    $minutes -= $hours * 60;
    $offset = sprintf('%+d:%02d', $hours * $sign, $minutes);

    try {
      $conn = new PDO(
        'mysql:host=' . $configdb['DBHOST'] . ';dbname=' . $configdb['DBNAME'] . ';charset=utf8',
        $configdb['DBUSER'],
        $configdb['DBPASS']
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

$admin = false;
$continue = true;
if (!empty($_SESSION['adminapi'])) {
  $admin = true;
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

$getUserIp = getRealIpAddr();
$getUserAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
// if (isset($_SESSION['adminapitime'])) {
//   if ((time() - $_SESSION['adminapitime']) > 300) {
//     header('Location: ?p=logout');
//   } elseif ((time() - $_SESSION['adminapitime']) > 10) {
//     $_SESSION['adminapitime'] = time();
//   }
// }
// if (isset($_SESSION['adminapiip'])) {
//   if ($getUserIp != $_SESSION['adminapiip']) {
//     header('Location: ?p=logout');
//   }
// }
// if (isset($_COOKIE['password'])) {
//   if (md5($settings['username']) != $_COOKIE['password']) {
//     header('Location: ?p=logout');
//   }
// }


define('SC_COPYRIGHT', 'https://t.me/abu_puja');
define('BASE_URL', $settings['siteurl'] . '/' . $settings['admin_dir']);
define('PAGINATION', $settings['show_per_page']);

// start function
$empty = array();

$main_page = parse_url($_SERVER['REQUEST_URI']);
if (isset($main_page['query'])) {
  parse_str($main_page['query'], $url);
  $url_pages = array();
  foreach ($url as $key => $value) {
    $url_pages[] = $url[$key];
  }
  $url_page = array();
  foreach ($url as $key => $value) {
    $url_page[] = $key;
  }
  $page = substr(strtolower(preg_replace('([^a-zA-Z0-9-/])', '', $url_pages['0'])), 0, 20);
} else {
  $page = '';
}

$page_cat      = isset($url_pages[1]) ? $url_pages[1] : null;
$page_cat_name = isset($url_page[1]) ? $url_page[1] : null;
$page_val      = isset($url_pages[2]) ? $url_pages[2] : null;
$page_val_name = isset($url_page[2]) ? $url_page[2] : null;

$page  = isset($_GET['p']) ? $_GET['p'] : null;
$pages = isset($_GET['page']) ? $_GET['page'] : 1;
    // Paginate
    function getPagination($table, $start_from, $results_per_page, $order = 'ORDER BY id DESC', $where = null){
      $conn = getDBConnection();
      $stmt = $conn->prepare("SELECT * FROM $table $where $order LIMIT $start_from," . $results_per_page);
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetchAll();
    }
    // Generate pagination links
    function getPaginationLinks($table, $results_per_page, $url, $pages, $where = null){
      $pagination_links = '';
      $conn = getDBConnection();
      $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM " . $table . " " . $where);
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $res = $stmt->fetch();

      $total_pages        = ceil($res["total"] / $results_per_page); // calculate total pages with results
      $after_before_pages = 3;
      $start_pages        = ($pages > $after_before_pages) ? $pages - $after_before_pages : 1; // Start number pages
      $end_pages          = ($pages < ($total_pages - $after_before_pages)) ? $pages + $after_before_pages : $total_pages; // End number pages
      
      if (!empty($total_pages)) {
        $pagination_links .= ($pages >= 2) ? '<li class="page-item"><a class="page-link" href="' . $url . '&page=1">&laquo;First</a></li>' : '';

        for ($i = $start_pages; $i <= $end_pages; $i++) {
          $active_pages = ($pages == '' || $pages == $i) ? 'active' : '';
          $pagination_links .= '<li class="page-item"><a class="page-link ' . $active_pages . '" href="' . $url . '&page=' . $i . '">' . $i . '</a></li>';
        }
        $pagination_links .= ($pages == $total_pages) ? '' : '<li class="page-item"><a class="page-link" href="' . $url . '&page=' . $total_pages . '">Last&raquo;</a></li>';
      }
      return $pagination_links;
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
    // example pdo_count
    // Hitung semua user
    // $total_users = pdo_count($conn, 'users');
    // Hitung user aktif
    // $total_active = pdo_count($conn, 'users', ['status' => 1]);
    // Hitung user berdasarkan role dan status
    // $total_admins = pdo_count($conn, 'users', ['status' => 1, 'role' => 'admin']);
    function pdo_count($table, $where = array(), $custom = '')
    {
        $conn = getDBConnection();
        $sql = "SELECT COUNT(*) as total FROM $table";
        // Jika ada kondisi custom (misalnya INSTR atau lainnya)
        if ($custom) {
            $sql .= " WHERE " . $custom;
            if (!empty($where)) {
                $sql .= " AND " . implode(" AND ", array_map(fn($k) => "$k = :$k", array_keys($where)));
            }
        } elseif (!empty($where)) {
            $conditions = [];
            foreach ($where as $k => $v) {
                $conditions[] = "$k = :$k";
            }
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        $stmt = $conn->prepare($sql);
        foreach ($where as $k => $v) {
            $stmt->bindValue(":$k", $v);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['total'] : 0;
    }



    // example pdo_select
    // $data = pdo_select('users');
    // $data = pdo_select('users', ['id' => 5]);
    // $data = pdo_select('users', ['status' => 'active'], ['id', 'username', 'email']);
    // $rules = [
    //  'id' => 'required|integer'
    // ];
    // $data = pdo_select('users', ['id' => 1], '*', $rules);
    // $data = pdo_select(
    //     $conn,
    //     'users',
    //     ['users.status' => 'active'],
    //     ['users.id', 'users.username', 'profiles.avatar', 'profiles.bio'],
    //     $joins
    // );
    // $joins contoh
    //     [
    //     ['type' => 'LEFT', 'table' => 'categories c', 'on' => 'n.category = c.id'],
    //     ['type' => 'LEFT', 'table' => 'users u', 'on' => 'n.user_id = u.id']
    // ]
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

        // LIMIT
        if (!is_null($limit) && is_int($limit) && $limit > 0) {
            $sql .= " LIMIT $limit";
        }

        $stmt = $conn->prepare($sql);

        foreach ($where as $k => $v) {
            $param = str_replace('.', '_', $k);
            $stmt->bindValue(":$param", $v);
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($limit === null || $limit >= 2) {
            return $result ?: [];
        }
        return $result[0] ?? [];
    }



    // example pdo_insert
    // pdo_insert('savelog', $data, $empty);
    // pdo_insert('settings', ['update_id'=>$update_id], ['id'=>1], ['id'=>'required|numeric']);
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
        unset($data['act'], $data['form_id'], $data['form_token'], $data['form_sig']);

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

    // example pdo_update
    // $data = [
    // 'fullname' => 'John Doe',
    // 'email' => 'john@example.com'
    // ];

    // $where = [
    //     'id' => 5
    // ];

    // $result = pdo_update('users', $data, $where);

    // if ($result === true) {
    //     echo "Update berhasil";
    // } else {
    //     print_r($result['errors']);
    // }
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

    function pdo_delete($user, $table, $id, $getData, $getUserIp, $getUserAgent, ?string $where=null){
        $conn = getDBConnection();
        $del = $conn->prepare("UPDATE $table SET status = 0 WHERE id = :id $where");
        if($del->execute([':id'=>$id])){
            logHistory($user, $table, 'DELETE', $id, $getData, NULL, $getUserIp, $getUserAgent);
            return true;
        }
    }
    function pdo_restore($user, $table, $id, $getData, $getUserIp, $getUserAgent, ?string $where=null){
        $conn = getDBConnection();
        $del = $conn->prepare("UPDATE $table SET status = 1 WHERE id = :id $where");
        if($del->execute([':id'=>$id])){
            logHistory($user, $table, 'RESTORE', $id, $getData, NULL, $getUserIp, $getUserAgent);
            return true;
        }

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

    function getSuccessCode($successCode)
    {
        $success_array = array(
            1 => "Please wait, redirecting...",
            2 => "Successfully register.",
            3 => "Data updated successfully.",
            4 => "Merchant Registered successfully.",
            5 => "Merchant Withdraw successfully.",
            6 => "API Registered successfully.",
            10 => "Password reset recovery sent.",
        );
        $success_msg = $success_array[$successCode];
        return $success_msg;
    }
    function getErrorCode($errorCode)
    {
        $error_array = array(
            1 => "General Failure, please reload.", // global 1
            2 => "Captcha is not correct.",
            3 => "Wrong number input.",
            4 => "Payment system not found.",
            5 => "Not enough Merchant funds.",
            10 => "Merchant with domain already exist.", // merchant 10
            11 => "Merchant add funds error, please try again.",
            24 => "Wrong authenticator code.", // details 24
            25 => "All fields cannot be empty.",
            26 => "Password cannot contains any space(s).",
            27 => "Email validation is error.",
            28 => "Confirmation code is expired.",
            50 => "Password is wrong or cannot be empty.",
            51 => "Confirm Password is not same.",
        );
        $error_msg = $error_array[$errorCode];
        return $error_msg;
    }

    function cFormat($number, ?string $decimal = null)
    {
        global $settings;
        if ($decimal === null) {
            $decimal = $settings['cur_dec'];
        }
        // Gunakan sprintf agar hasil tidak dalam notasi E
        return rtrim(rtrim(sprintf("%." . $decimal . "f", (float)$number), '0'), '.');
    }
// end function
// start admin function

$bulanInggris = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$bulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
function DateToIndo($date) {
  global $bulanInggris;
  global $bulanIndo;
  $result = str_replace($bulanInggris, $bulanIndo, $date);
  return($result);
} 

function saveSettings($config,$save=true){
  global $enkey;
  global $iv;
  global $getUserIp;
  global $getUserAgent;
  global $empty;
  $dataconfig = base64_encode(json_encode(openssl_encrypt(serialize($config), "aes-256-cbc", $enkey, 0, $iv)));
  $success    = file_put_contents('settings.php', '<?' . PHP_EOL . $dataconfig . PHP_EOL . '?>');
  if (function_exists('opcache_invalidate')) {
    opcache_invalidate('settings.php');
  }
  if ($success) {
    $exists = is_file('settings.php');
    if (!$exists) {
      $msg = 'error';
    } else {
      if($save){
        $data = array(
            'data' => $dataconfig,
            'data_date' => date('Y-m-d H:i:s'),
            'dip' => $getUserIp,
            'dag' => $getUserAgent
        );
        $update_id = pdo_insert('savelog', $data, $empty);
        pdo_insert('settings', ['update_id'=>$update_id], $empty);
        pdo_update('settings', ['update_id'=>$update_id], ['id'=>1], ['id'=>'required|numeric']);
      }
      $msg = 'success';
    }
  }
  return $msg;
}

function checkAccess($page,$user_group_id,$user_type){
  $access = false;

  if($user_type=="superuser"){
    $access = true;
  }else{
    // check akses di user_privilege
    $count = pdo_count(
        'user_privilege',
        ['user_group_id' => $user_group_id, 'module_name' => $page],
        'INSTR(:module_name, module_name) > 0'
    );
    if ($count > 0) {
        $access = true;
    }
  }
  return $access;
}
// end admin function

// API FUNCTION
  function handleApiRequest($apiName, $method)
  {

    switch ($apiName) {
      case 'users':
        if ($method == 'GET') {
            // Contoh ambil semua user
            return pdo_select('users');
        }
        if ($method == 'POST') {
            // Tangani insert user
            $data = json_decode(file_get_contents('php://input'), true);
            return pdo_insert('users', $data, []);
        }
        break;

      case 'user_detail':
        if ($method == 'GET') {
            // Ambil detail user berdasarkan ID di parameter
            $id = $_GET['id'] ?? null;
            if ($id) {
                $result = pdo_select('users', ['id' => $id]);
                return isset($result[0]) ? $result[0] : ['error' => 'User not found'];
            }
            return ['error' => 'Missing id parameter'];
        }
        break;

      // Tambahkan API endpoint lain sesuai kebutuhan

      default:
          return ['error' => 'Unknown API endpoint'];
    }
  }


  if($page === "api" || isset($_GET['api'])){
    $admin = false;
    // Set response JSON header
    header('Content-Type: application/json');
    // Tangkap nama API endpoint
    $apiName = $_GET['api'];
    // Tangkap method HTTP (GET, POST, dll)
    $method = $_SERVER['REQUEST_METHOD'];
    // Jalankan fungsi API sesuai dengan nilai $apiName dan $method
    $response = handleApiRequest($apiName, $method);
    // Output JSON response
    echo json_encode($response);
    exit;
  }

    if ($admin && $page == 'submitAction' && isset($_GET['act'])) {
        $continue = false;
    
        $table = $_POST["page"];
        if ($_GET['act'] == "active" || $_GET['act'] == "disable") {
            if (isset($_POST["page"]) && isset($_POST["id"])) {
            foreach ($_POST["id"] as $id) {
                if (is_numeric($id)) {
                $table = $_POST["page"];
                if($_GET['act']=="active"){
                    $status = 1;
                }elseif($_GET['act']=="suspend"){
                    $status = 2;
                }else{
                    $status = 0;
                }
                pdo_update($table, ['status'=>$status], ['id'=>$id], ['id'=>'required|numeric']);
                }
            }
            $msg = array(
                "result" => 'success',
                "type"   => $_GET['act'] ,
                "msg"    => 'Item(s) '.$_GET['act'] .' successfully.',
            );
            echo json_encode($msg);
            }
        }elseif ($_GET['act'] == "delete") {
            if (isset($table) && isset($_POST["id"])) {
            foreach ($_POST["id"] as $id) {
                if (is_numeric($id)) {
                    $user = pdo_select($table, ['id'=>$admin_details['id']], ['id','user_type','fullname'],[],[['id'=>'required|numeric']],1);
                    $getData = pdo_select($table, ['id'=>$id], '*',[],[['id'=>'required|numeric']],1);
                    pdo_delete($user, $table, $id, $getData, $getUserIp, $getUserAgent);
                }
            }
            $msg = array(
                "result" => 'success',
                "type"   => 'delete',
                "msg"    => 'Item(s) deleted successfully.',
            );
            echo json_encode($msg);
            }
        } 
    }

  if ($admin && $page == 'getForm' && isset($_GET['table']) && isset($_GET['act'])) {
  $continue = false;
  // tampilkan form sesuai permintaan
  if($_GET['table']=="users"){
    // form option_groups
    if($_GET['act']=="add"){
      $dataCallback = array(
        "id" => "",
        "fullname" => "",
        "username" => "",
        "email" => "",
        "password" => "",
        "user_group_id" => "",
        "user_type" => "",
        "status" => 1,
        "created_at" => date("Y-m-d H:i:s"),
      );
      $datasubmit = "insert";
    }elseif($_GET['act']=="edit"){
      $dataCallback = pdo_select('users', ['id' => $_GET['id']], '*', [], ['id'=>'required|numeric'], 1);
      $datasubmit = "update";
    }

    $options_user_group = '<option value="">Tidak ada grup</option>';
    $alluser_group = pdo_select('user_group', ['status' => 1], ['id', 'group_name'], [], [], null, 'group_name ASC');
    foreach($alluser_group as $key => $value){
      $selected = ($dataCallback["id"] == $value['id']) ? 'selected' : '';
      $options_user_group .= '<option value="'.$value['id'].'" '.$selected.'>'.$value['group_name'].'</option>';
    }

    $result = 'success';
    $msg = '
      <form id="submitForm" data-submit="'.$datasubmit.'" method="post">
        <input type="hidden" name="act" value="do_'.$datasubmit.'">
        <input type="hidden" name="datatable" value="'.$_GET['table'].'">
        <input type="hidden" name="id" value="'.$dataCallback['id'].'">
        <input type="hidden" name="user_type" value="admin">
      <div class="mb-3">
        <div class="form-row">
          <div class="col-12 col-lg-12 mb-3">
          <label class="font-weight-bold" for="fullname">Nama Lengkap</label>
          <input type="text" name="fullname" class="form-control" id="fullname" placeholder="Masukkan nama lengkap" value="'.$dataCallback["fullname"].'">
          </div>
        </div>
        <div class="form-row">
          <div class="col-12 col-lg-6 mb-3">
          <label class="font-weight-bold" for="username">Username</label>
          <input type="text" name="username" class="form-control" id="username" placeholder="Masukkan username (tanpa spasi dan karakter unik)" value="'.$dataCallback["username"].'">
          </div>
          <div class="col-12 col-lg-6 mb-3">
          <label class="font-weight-bold" for="email">Email</label>
          <input type="email" name="email" class="form-control" id="email" placeholder="Masukkan email" value="'.$dataCallback["email"].'">
          </div>
        </div>
        <div class="form-row">
          <div class="col-12 col-lg-12 mb-3">
          <label class="font-weight-bold" for="password">Kata Sandi</label>
          <input type="text" name="password" class="form-control" id="password" placeholder="Kosongkan jika tidak mengganti" value="">
          </div>
        </div>
        <div class="form-row">
          <div class="col-12 col-lg-12 mb-3">
          <label class="font-weight-bold" for="user_group_id">User Group</label>
            <select name="user_group_id" class="form-control">
            '.$options_user_group.'
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="col-12 col-lg-6 mb-3">
            <label class="font-weight-bold" for="status">Status</label>
            <select name="status" class="form-control">
              <option value="1" '.($dataCallback["status"] == "1" ? "selected" : "").'>Aktif</option>
              <option value="0" '.($dataCallback["status"] == "0" ? "selected" : "").'>Nonaktif</option>
            </select>
          </div>
          <div class="col-12 col-lg-6 mb-3">
            <label class="font-weight-bold" for="created_at">Tanggal Data</label>
            <input type="datetime-local" name="created_at" class="form-control" id="created_at" value="'.date_format(new DateTime($dataCallback["created_at"]), "Y-m-d\TH:i:s").'" step="any">
          </div>
        </div>
      </div>
      <button id="submitFormBtn" type="submit" class="btn btn-primary btn-block mb-5">Save</button>
      </form>';
  }elseif($_GET['table']=="news_category"){
    // form option_groups
    if($_GET['act']=="add"){
      $dataCallback = array(
        "id" => "",
        "parent_id" => "",
        "name" => "",
        "slug" => "",
        "status" => 1,
      );
      $datasubmit = "insert";
    }elseif($_GET['act']=="edit"){
      $dataCallback = pdo_select('news_category', ['id' => $_GET['id']], '*', [], ['id'=>'required|numeric'], 1);
      $datasubmit = "update";
    }

    $options_parent_id = '<option value="">-- Kategori Utama --</option>';
    $allparent_id = pdo_select('news_category', ['status' => 1], ['id', 'name'], [], [], null, 'name ASC');
    foreach($allparent_id as $key => $value){
      $selected = ($dataCallback["id"] == $value['id']) ? 'selected' : '';
      $options_parent_id .= '<option value="'.$value['id'].'" '.$selected.'>'.$value['name'].'</option>';
    }

    $result = 'success';
    $msg = '
      <form id="submitForm" data-submit="'.$datasubmit.'" method="post">
        <input type="hidden" name="act" value="do_'.$datasubmit.'">
        <input type="hidden" name="datatable" value="'.$_GET['table'].'">
        <input type="hidden" name="id" value="'.$dataCallback['id'].'">
      <div class="mb-3">
        <div class="form-row">
          <div class="col-12 col-lg-12 mb-3">
          <label class="font-weight-bold" for="name">Nama Kategori</label>
          <input type="text" name="name" class="form-control" id="fullname" placeholder="Masukkan nama kategori" value="'.$dataCallback["name"].'">
          </div>
        </div>
        <div class="form-row">
          <div class="col-12 col-lg-12 mb-3">
          <label class="font-weight-bold" for="name">Slug Kategori</label>
          <input type="text" name="slug" class="form-control" id="slug" placeholder="Slug hanya kata dan nomor tanpa spasi" value="'.$dataCallback["slug"].'">
          </div>
        </div>
        <div class="form-row">
          <div class="col-12 col-lg-12 mb-3">
          <label class="font-weight-bold" for="parent_id">Parent ID</label>
            <select name="parent_id" class="form-control">
            '.$options_parent_id.'
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="col-12 col-lg-12 mb-3">
            <label class="font-weight-bold" for="status">Status</label>
            <select name="status" class="form-control">
              <option value="1" '.($dataCallback["status"] == "1" ? "selected" : "").'>Aktif</option>
              <option value="0" '.($dataCallback["status"] == "0" ? "selected" : "").'>Nonaktif</option>
            </select>
          </div>
        </div>
      </div>
      <button id="submitFormBtn" type="submit" class="btn btn-primary btn-block mb-5">Save</button>
      </form>';
  }
  $msg = array(
    "result" => $result,
    "type"   => $_GET['act'] ,
    "msg"    => $msg,
  );
  echo json_encode($msg);
}

if ($admin && $page == 'submitData' && isset($_POST['act'])) {
  $continue = false;
  $continuemsg = false;

  $getDataID = isset($_POST['id']) ? $_POST['id'] : "";
  $getDataAct = isset($_POST['act']) ? $_POST['act'] : "";
  $getDataTable = isset($_POST['datatable']) ? $_POST['datatable'] : "";
  $dataTable = $getDataTable;
  $where = "";
  
  unset($_POST['id']);
  unset($_POST['act']);
  unset($_POST['datatable']);
  $getData = $_POST;
  unset($_POST);
  $data = "";

  if($getDataAct=="do_insert" && !empty($getDataAct) && !empty($getDataTable)){
    if($getDataTable=="users"){
        $options = array(
            'cost' => 12,
        );
        $getData['password'] = password_hash($getData['password'], PASSWORD_BCRYPT, $options);
        $checkData = pdo_count($dataTable, ['email'=>$getData['email']]);
    }elseif($getDataTable=="news_category"){
        $checkData = pdo_count($dataTable, ['slug'=>$getData['slug']]);
    }
    if($checkData<=0){
        $doInsert = pdo_insert($dataTable, $getData, []);
        if ($doInsert>0) {
            $lastInsertId = $doInsert;
            $result = "success";
            $type = "insert";
            $msg = "Data berhasil disimpan.";
            $continuemsg = true;
        } else {
            $result = "error";
            $type = "insert";
            $msg = "Error insert data.";
        }
    } else {
        $result = "error";
        $type = "insert";
        $msg = "Error, Data sudah ada.";
    }
  }elseif($getDataAct=="do_update" && !empty($getDataID) && !empty($getDataAct) && !empty($getDataTable)){
    if($getDataTable=="settings"){
        // update settings.php
        $options  = array(
        'cost' => 12,
        );
        
        foreach ($getData as $k => $v) {
            if (!empty($getData['siteurl'])) {
                $config['settings']['siteurl'] = rtrim($getData['siteurl'], "/");
            }
            if (!empty($getData['password'])) {
                $config['settings']['password'] = password_hash($getData['password'], PASSWORD_BCRYPT, $options);
            } else {
                $config['settings']['password'] = $settings['password'];
            }
            if (isset($getData['reset_aag'])) {
                $config['settings']['aag'] = '';
            } else {
                if (!empty($getData['aag'])) {
                $config['settings']['aag'] = crypts(serialize($getData['aag']), 'e');
                } else {
                $config['settings']['aag'] = $settings['aag'];
                }
            }
            $config['settings'][$k] = $v;
        }
        $msg = saveSettings($config);
        
        $result = "success";
        $type = "update";
        $msg = "Settings berhasil diupdate.";
    }else{
        $where = ['id'=>$getDataID];
        $rules = ['id'=>'required|numeric'];
        if($getDataTable=="users"){
        $options = array(
            'cost' => 12,
        );
        // hanya berlaku ke table users
        if(isset($getData['password']) && !empty($getData['password'])){
            $getData['password'] = password_hash($getData['password'], PASSWORD_BCRYPT, $options);
        }
        $rules = ['email'=>'required|email'];
        }
        $doUpdate = pdo_update($dataTable, $getData, $where, $rules);
        if ($doUpdate) {
            $lastInsertId = $getDataID;
            $result = "success";
            $type = "update";
            $msg = "Data berhasil diupdate.";
            $continuemsg = true;
        } else {
            $result = "error";
            $type = "update";
            $msg = "Error update data.";
        }
    }
    }else{
        $result = "error";
        $type = "update";
        $msg = "Gagal data.";
    }
    if($continuemsg){
        // insert log history
        if($getDataTable=="users"){
        // return array users
        $data = array(
            "id"=>$lastInsertId,
            "status"=>$getData['status'],
            "username"=>$getData['username'],
            "email"=>$getData['email'],
            "user_group_id"=>$getData['user_group_id']
        );
        }
        if($getDataTable=="news_category"){
        // return array news_category
        $data = array(
            "id"=>$lastInsertId,
            "status"=>$getData['status'],
            "name"=>$getData['name'],
            "slug"=>$getData['slug'],
            "parent_id"=>$getData['parent_id']
        );
        }
    }
  $doresult = array(
    "result" => $result,
    "type"   => $type,
    "datatable" => $getDataTable,
    "data"    => $data,
    "msg"    => $msg,
  );
  echo json_encode($doresult);
}

if ($admin && $page == 'upload_img' && isset($_GET['act']) && isset($_GET['type'])) {
    header('Content-Type: application/json');
    $targetDir = __DIR__ . "/upload/";
    $baseUrl = "upload/";
    $url = "";
    $jenis = $_GET['type'];
    $maxFileSize = 2 * 1024 * 1024; // 2MB
    if($jenis=="avatar"){
        $maxFileSize = 1 * 1024 * 1024; // 1MB
    }

    if (!isset($_FILES["file"]) || $_FILES["file"]['error'] != UPLOAD_ERR_OK) {
        $result = 'fail';
        $pesan = 'File upload error';
    } else {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        $ext = strtolower(pathinfo($_FILES["file"]['name'], PATHINFO_EXTENSION));
        if ($_FILES["file"]['size'] > $maxFileSize) {
            $result = 'fail';
            $pesan = 'File tidak boleh lebih dari 2MB';
        } elseif (!in_array($ext, $allowed)) {
            $result = 'fail';
            $pesan = 'Jenis file tidak diizinkan';
        } else {
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0775, true);
            }

            $filename = uniqid($jenis . '_', true) . '.' . $ext;
            $targetFile = $targetDir . $filename;

            if (move_uploaded_file($_FILES["file"]['tmp_name'], $targetFile)) {
                $url = $settings['siteurl'] . '/' . $baseUrl . $filename;
                $result = 'success';
                $pesan = 'Sukses menyimpan file';
            } else {
                $result = 'fail';
                $pesan = 'Gagal menyimpan file';
            }
        }
    }

    $msg = array(
        "result" => $result,
        "url"    => $url,
        "msg"    => $pesan,
    );

    echo json_encode($msg);
    exit();
}

?>


<?php 
  $themePath = 'plugins/default';
  function isActivePage($currentPage, $pageToCheck) {
    if (is_array($pageToCheck)) {
      return in_array($currentPage, $pageToCheck) ? 'active' : '';
    }
    return ($currentPage === $pageToCheck) ? 'active' : '';
  }
  function isShowPage($currentPage, $pageToCheck) {
    if (is_array($pageToCheck)) {
      return in_array($currentPage, $pageToCheck) ? 'show' : '';
    }
    return ($currentPage === $pageToCheck) ? 'active' : '';
  }
  function hiddenPage($currentPage, $pageToCheck) {
    if (is_array($pageToCheck)) {
      return in_array($currentPage, $pageToCheck) ? '' : 'hidden';
    }
    return ($currentPage === $pageToCheck) ? '' : 'hidden';
  }
  function ariaExpanded($currentPage, $pageToCheck) {
    return ($currentPage === $pageToCheck) ? 'true' : 'false';
  }

if($continue==true && $admin){ 
    if(isset($_SESSION['admindetails'])){
        $admin_details = $_SESSION['admindetails'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin | Script By Abu Puja</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin script." name="description">
    <meta content="Abu_Puja" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= $themePath ?>/assets/images/favicon.ico">

    <?php if ($page == 'news') { ?>
        <!-- Dropzone File Upload js -->
        <link rel="stylesheet" href="<?= $themePath ?>/assets/vendor/dropzone/dropzone.min.css" type="text/css" />
    <?php } ?>

    <!-- Sweet Alert css-->
    <link href="<?= $themePath ?>/assets/vendor/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme Config Js -->
    <script src="<?= $themePath ?>/assets/js/config.js"></script>
    <!-- Vendor css -->
    <link href="<?= $themePath ?>/assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="<?= $themePath ?>/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <!-- Icons css -->
    <link href="<?= $themePath ?>/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        
        <!-- Sidenav Menu Start -->
        <div class="sidenav-menu">

            <!-- Brand Logo -->
            <a href="<?= BASE_URL ?>" class="logo">
                <span class="logo-light">
                    <span class="logo-lg"><img src="<?= $themePath ?>/assets/images/logo.png" alt="logo"></span>
                    <span class="logo-sm text-center"><img src="<?= $themePath ?>/assets/images/logo-sm.png" alt="small logo"></span>
                </span>

                <span class="logo-dark">
                    <span class="logo-lg"><img src="<?= $themePath ?>/assets/images/logo-dark.png" alt="dark logo"></span>
                    <span class="logo-sm text-center"><img src="<?= $themePath ?>/assets/images/logo-sm.png" alt="small logo"></span>
                </span>
            </a>

            <!-- Sidebar Hover Menu Toggle Button -->
            <button class="button-sm-hover">
                <i class="ti ti-circle align-middle"></i>
            </button>

            <!-- Full Sidebar Menu Close Button -->
            <button class="button-close-fullsidebar">
                <i class="ti ti-x align-middle"></i>
            </button>

            <div data-simplebar>

                <!--- Sidenav Menu -->
                <ul class="side-nav">

                    <li class="side-nav-item" data-pagename="dashboard">
                        <a href="<?= BASE_URL ?>" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                            <span class="menu-text"> Dashboard </span>
                        </a>
                    </li>

                    <li class="side-nav-title mt-2">Apps Menu</li>

                    <li class="side-nav-item <?php if(checkAccess("users",$admin_details['user_group_id'],$admin_details['user_type'])!=1){echo " d-none";}?>" data-pagename="users,user_group">
                        <a data-bs-toggle="collapse" href="#sidebarInvoice1" aria-expanded="false" aria-controls="sidebarInvoice1" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-file-invoice"></i></span>
                            <span class="menu-text"> Users</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarInvoice1">
                            <ul class="sub-menu">
                                <li class="side-nav-item">
                                    <a href="?p=users" class="side-nav-link">
                                        <span class="menu-text">Lihat User</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="?p=user_group" class="side-nav-link">
                                        <span class="menu-text">User Group</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="side-nav-item <?php if(checkAccess("news",$admin_details['user_group_id'],$admin_details['user_type'])!=1){echo " d-none";}?>" data-pagename="news,news_category">
                        <a data-bs-toggle="collapse" href="#sidebarInvoice2" aria-expanded="false" aria-controls="sidebarInvoice2" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-news"></i></span>
                            <span class="menu-text"> Berita</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarInvoice2">
                            <ul class="sub-menu">
                                <li class="side-nav-item">
                                    <a href="?p=news" class="side-nav-link">
                                        <span class="menu-text">Lihat Berita</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="?p=news_category" class="side-nav-link">
                                        <span class="menu-text">Kategori Berita</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="side-nav-item <?php if(checkAccess("contents",$admin_details['user_group_id'],$admin_details['user_type'])!=1){echo " d-none";}?>" data-pagename="contents">
                        <a href="?p=contents" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-folder-filled"></i></span>
                            <span class="menu-text"> Menu </span>
                        </a>
                    </li>

                    <li class="side-nav-item <?php if(checkAccess("settings",$admin_details['user_group_id'],$admin_details['user_type'])!=1){echo " d-none";}?>" data-pagename="settings_general,settings_seo,settings_security">
                        <a data-bs-toggle="collapse" href="#sidebarSettings2" aria-expanded="false" aria-controls="sidebarSettings2" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-settings"></i></span>
                            <span class="menu-text"> Pengaturan</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarSettings2">
                            <ul class="sub-menu">
                                <li class="side-nav-item">
                                    <a href="?p=settings_general" class="side-nav-link">
                                        <span class="menu-text">Umum</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="?p=settings_seo" class="side-nav-link">
                                        <span class="menu-text">URL SEO</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="?p=settings_security" class="side-nav-link">
                                        <span class="menu-text">Keamanan</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="side-nav-item <?php if(checkAccess("supports",$admin_details['user_group_id'],$admin_details['user_type'])!=1){echo " d-none";}?>" data-pagename="supports">
                        <a href="?p=supports" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-message-filled"></i></span>
                            <span class="menu-text"> Bantuan </span>
                        </a>
                    </li>

                    <li class="side-nav-item d-none">
                        <a href="javascript:void(0)" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-message-filled"></i></span>
                            <span class="menu-text"> Other </span>
                        </a>
                    </li>   

                </ul>

                <div class="clearfix"></div>
            </div>
        </div>
        <!-- Sidenav Menu End -->
        

        <!-- Topbar Start -->
        <header class="app-topbar">
            <div class="page-container topbar-menu">
                <div class="d-flex align-items-center gap-2">

                    <!-- Brand Logo -->
                    <a href="<?= BASE_URL ?>" class="logo">
                        <span class="logo-light">
                            <span class="logo-lg"><img src="<?= $themePath ?>/assets/images/logo.png" alt="logo"></span>
                            <span class="logo-sm"><img src="<?= $themePath ?>/assets/images/logo-sm.png" alt="small logo"></span>
                        </span>

                        <span class="logo-dark">
                            <span class="logo-lg"><img src="<?= $themePath ?>/assets/images/logo-dark.png" alt="dark logo"></span>
                            <span class="logo-sm"><img src="<?= $themePath ?>/assets/images/logo-sm.png" alt="small logo"></span>
                        </span>
                    </a>

                    <!-- Sidebar Menu Toggle Button -->
                    <button class="sidenav-toggle-button btn btn-secondary btn-icon">
                        <i class="ti ti-menu-deep fs-24"></i>
                    </button>

                    <!-- Horizontal Menu Toggle Button -->
                    <button class="topnav-toggle-button" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                        <i class="ti ti-menu-deep fs-22"></i>
                    </button>

                    <!-- Button Trigger Search Modal -->
                    <div class="topbar-search text-muted d-none d-xl-flex gap-2 align-items-center" data-bs-toggle="modal" data-bs-target="#searchModal" type="button">
                        <i class="ti ti-search fs-18"></i>
                        <span class="me-2">Search something..</span>
                        <button type="submit" class="ms-auto btn btn-sm btn-primary shadow-none">⌘K</span>
                    </div>

                </div>

                <div class="d-flex align-items-center gap-2">

                    <!-- Search for small devices -->
                    <div class="topbar-item d-flex d-xl-none">
                        <button class="topbar-link btn btn-outline-primary btn-icon" data-bs-toggle="modal" data-bs-target="#searchModal" type="button">
                            <i class="ti ti-search fs-22"></i>
                        </button>
                    </div>

                    <!-- Button Trigger Customizer Offcanvas -->
                    <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link btn btn-outline-primary btn-icon" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" type="button">
                            <i class="ti ti-settings fs-22"></i>
                        </button>
                    </div>

                    <!-- Light/Dark Mode Button -->
                    <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link btn btn-outline-primary btn-icon" id="light-dark-mode" type="button">
                            <i class="ti ti-moon fs-22"></i>
                        </button>
                    </div>

                    <!-- User Dropdown -->
                    <div class="topbar-item">
                        <div class="dropdown">
                            <a class="topbar-link btn btn-outline-primary dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown" data-bs-offset="0,22" type="button" aria-haspopup="false" aria-expanded="false">
                                <img src="<?= $themePath ?>/assets/images/users/avatar-1.png" width="24" class="rounded-circle me-lg-2 d-flex" alt="user-image">
                                <span class="d-lg-flex flex-column gap-1 d-none">
                                    <?= $admin_details['fullname'] ;?>.
                                </span>
                                <i class="ti ti-chevron-down d-none d-lg-block align-middle ms-2"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome !</h6>
                                </div>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <i class="ti ti-settings me-1 fs-17 align-middle"></i>
                                    <span class="align-middle">Settings</span>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <i class="ti ti-lifebuoy me-1 fs-17 align-middle"></i>
                                    <span class="align-middle">Support</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <!-- item-->
                                <a href="?p=logout" class="dropdown-item active fw-semibold text-danger">
                                    <i class="ti ti-logout me-1 fs-17 align-middle"></i>
                                    <span class="align-middle">Sign Out</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Topbar End -->

        <!-- Search Modal -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content bg-transparent">
                    <div class="card mb-0 shadow-none">
                        <div class="px-3 py-2 d-flex flex-row align-items-center" id="top-search">
                            <i class="ti ti-search fs-22"></i>
                            <input type="search" class="form-control border-0" id="search-modal-input" placeholder="Search for actions, people,">
                            <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close">[esc]</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="page-content">
            <div class="page-container">

        <?php 
        switch ($page) {
        case "users": 
            if ($admin && checkAccess("users",$admin_details['user_group_id'],$admin_details['user_type'])) {
        
          if($page_cat_name == 'status'){
            $bytype = 'WHERE status ='.$page_cat.'';
            $url = '&status='.$page_cat.'';
          }else{
            $bytype = '';
            $url = '';
          }
          $getData = getPagination($page, ($pages-1) * PAGINATION, PAGINATION, 'ORDER BY id DESC',$bytype);
        ?>
            <div class="row">
                <div class="col-12">
                    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-18 text-uppercase fw-bold m-0"><?=strtoupper($page);?></h4>
                        </div>
                        <div class="mt-3 mt-sm-0">
                            <form action="javascript:void(0);">
                                <div class="row g-2 mb-0 align-items-center">
                                    <div class="col-auto">
                                        <a href="javascript: void(0);" class="btn btn-outline-primary">
                                            <i class="ti ti-sort-ascending me-1"></i> Sort By
                                        </a>
                                    </div>
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Semua User</h4>
                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#dataModal" id="dataBtn" data-act="add" data-table="<?=$page;?>" data-judul="Data User" class="btn btn-sm btn-secondary">Tambah <i class="ti ti-plus ms-1"></i></a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-custom table-centered table-sm table-nowrap table-hover mb-0">
                                    <tbody>

                                    <?php if ($getData): $no = (($pages - 1) * PAGINATION) + 1;?>
                                        <?php foreach ($getData as $item): 
                                        $statusText = ($item['status'] == "0") ? "Nonaktif" : (($item['status'] == "2") ? "Unknown" : "Aktif");
                                        $iconText = ($item['status'] == "0") ? "text-danger" : (($item['status'] == "2") ? "text-muted" : "text-success");

                                        ?>
                                        <tr id="<?=$page.''.$item['id'];?>">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <span class="text-muted fs-12">Username</span> <br />
                                                        <h5 class="fs-14 mt-1" id="itemUsername<?=$item['id'];?>"><?=$item['username'];?></h5>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-12">Email</span>
                                                <h5 class="fs-14 mt-1 fw-normal" id="itemEmail<?=$item['id'];?>"><?=$item['email'];?></h5>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-12">User Type</span> <br />
                                                <h5 class="fs-14 mt-1 fw-normal" id="itemUsertype<?=$item['id'];?>"><?=ucfirst($item['user_type']);?></h5>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-12">Status</span>
                                                <h5 class="fs-14 mt-1 fw-normal">
                                                    <i id="itemStatus<?=$item['id'];?>" class="ti ti-circle-filled fs-12 <?=$iconText;?>"></i> <?=$statusText;?>
                                                </h5>
                                            </td>
                                            <td style="width: 30px;">
                                                <div class="dropdown">
                                                    <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a href="?p=<?=$page;?>&act=view&id=<?=$item['id'];?>" class="dropdown-item"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> Detail</a>
                                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#dataModal" id="dataBtn" data-act="edit" data-judul="Data User" data-table="<?=$page;?>" data-id="<?=$item['id'];?>" href="javascript:void(0)"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-info"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg> Edit</a>
                                                        <a class="dropdown-item" href="?p=<?=$page;?>&act=delete&id=<?=$item['id'];?>" onclick='return confirm("Apakah kamu yakin akan menghapus data?")'><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Hapus</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    <?php endif;?>

                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive-->
                        </div> <!-- end card-body-->

                        <div class="card-footer border-0">
                            <div class="align-items-center justify-content-between row text-center text-sm-start">
                                <div class="col-sm-auto mt-3 mt-sm-0">
                                    <ul class="pagination pagination-boxed pagination-sm mb-0 justify-content-center">
                                        <?php echo getPaginationLinks($page, PAGINATION, '?p='.$page.''.$url,$pages,$bytype); ?>
                                    </ul>
                                </div>
                            </div> <!-- -->
                        </div>

                    </div> <!-- end card-->
                </div> <!-- end col-->

            </div> <!-- end row-->

        <?php }
        break; // end "users" ?>
        
        <?php 
        case "user_group": 
            if ($admin && checkAccess("users",$admin_details['user_group_id'],$admin_details['user_type'])) {
        
          if($page_cat_name == 'status'){
            $bytype = 'WHERE status ='.$page_cat.'';
            $url = '&status='.$page_cat.'';
          }else{
            $bytype = '';
            $url = '';
          }
          $getData = getPagination($page, ($pages-1) * PAGINATION, PAGINATION, 'ORDER BY id DESC',$bytype);
        ?>
            <div class="row">
                <div class="col-12">
                    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-18 text-uppercase fw-bold m-0"><?=strtoupper($page);?></h4>
                        </div>
                        <div class="mt-3 mt-sm-0">
                            <form action="javascript:void(0);">
                                <div class="row g-2 mb-0 align-items-center">
                                    <div class="col-auto">
                                        <a href="javascript: void(0);" class="btn btn-outline-primary">
                                            <i class="ti ti-sort-ascending me-1"></i> Sort By
                                        </a>
                                    </div>
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Semua User Group</h4>
                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#dataModal" id="dataBtn" data-act="add" data-table="<?=$page;?>" data-judul="Data User Group" class="btn btn-sm btn-secondary">Tambah <i class="ti ti-plus ms-1"></i></a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-custom table-centered table-sm table-nowrap table-hover mb-0">
                                    <tbody>

                                    <?php if ($getData): $no = (($pages - 1) * PAGINATION) + 1;?>
                                        <?php foreach ($getData as $item): 
                                        $statusText = ($item['status'] == "0") ? "Nonaktif" : (($item['status'] == "2") ? "Unknown" : "Aktif");
                                        $iconText = ($item['status'] == "0") ? "text-danger" : (($item['status'] == "2") ? "text-muted" : "text-success");

                                        ?>
                                        <tr id="<?=$page.''.$item['id'];?>">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <span class="text-muted fs-12">Nama (ID)</span> <br />
                                                        <h5 class="fs-14 mt-1" id="itemName<?=$item['id'];?>"><?=$item['name'];?> (<?=$item['id'];?>)</h5>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-12">Slug</span>
                                                <h5 class="fs-14 mt-1 fw-normal" id="itemSlug<?=$item['id'];?>"><?=$item['slug'];?></h5>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-12">Kategori ID</span> <br />
                                                <h5 class="fs-14 mt-1 fw-normal" id="itemID<?=$item['id'];?>"><?=$item['id'];?></h5>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-12">Status</span>
                                                <h5 class="fs-14 mt-1 fw-normal">
                                                    <i id="itemStatus<?=$item['id'];?>" class="ti ti-circle-filled fs-12 <?=$iconText;?>"></i> <?=$statusText;?>
                                                </h5>
                                            </td>
                                            <td style="width: 30px;">
                                                <div class="dropdown">
                                                    <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#dataModal" id="dataBtn" data-act="edit" data-judul="Data Kategori" data-table="<?=$page;?>" data-id="<?=$item['id'];?>" href="javascript:void(0)"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-info"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg> Edit</a>
                                                        <a class="dropdown-item" href="?p=<?=$page;?>&act=delete&id=<?=$item['id'];?>" onclick='return confirm("Apakah kamu yakin akan menghapus data?")'><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Hapus</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    <?php endif;?>

                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive-->
                        </div> <!-- end card-body-->

                        <div class="card-footer border-0">
                            <div class="align-items-center justify-content-between row text-center text-sm-start">
                                <div class="col-sm-auto mt-3 mt-sm-0">
                                    <ul class="pagination pagination-boxed pagination-sm mb-0 justify-content-center">
                                        <?php echo getPaginationLinks($page, PAGINATION, '?p='.$page.''.$url,$pages,$bytype); ?>
                                    </ul>
                                </div>
                            </div> <!-- -->
                        </div>

                    </div> <!-- end card-->
                </div> <!-- end col-->

            </div> <!-- end row-->

        <?php }
        break; // end "user_group" ?>
        
        <?php 
        case "news": 
            if ($admin && checkAccess("news",$admin_details['user_group_id'],$admin_details['user_type'])) {
        
                if (isset($_POST['act']) && $_POST['act'] == 'do_add') {

                        //$full_text_bbcode = $_POST['full_text_bbcode'] ?? '';
                        $full_text_bbcode = str_replace("\xc2\xa0", ' ', $_POST['full_text_bbcode'] ?? '');
                        require_once("plugins/jbbcode.php");
                        $full_text_html   = parseBBCodeToHtml($full_text_bbcode);
                        preg_match('/\[img\](.*?)\[\/img\]/i', $full_text_bbcode, $match);
                        $thumbnail = isset($match[1]) ? trim($match[1]) : $settings['siteurl']."/theme/no_image.webp";

                        $data = array(
                            "category" => $_POST["category"],
                            "thumbnail" => $thumbnail,
                            "title" => $_POST["title"],
                            "full_text_bbcode" => $full_text_bbcode,
                            "full_text_html" => $full_text_html,
                            "created_at" => date('Y-m-d H:i:s', strtotime($_POST["created_at"])),
                            "status" => $_POST["status"]
                        );
                        $lastInsertID = pdo_insert("news", $data, []);
                        if ($lastInsertID) {
                            if(isset($_POST["sendNotifAndroid"]) && $_POST["sendNotifAndroid"]==1){
                                // kirim notifikasi android
                                $sendArray = array(
                                    "type" => "topics",
                                    "uid" => "",
                                    "login_as" => ""
                                );
                                sendNotifAndroid($sendArray,
                                "Berita Terbaru!", 
                                $_POST["title"], 
                                [
                                    "type" => "news",
                                    "news_id" => $lastInsertID,
                                    "title" => "Berita Terbaru!",
                                    "body" => $_POST["title"]
                                ]
                                );
                            }
                            $msg = array(
                                "result" => 'success',
                                "msg" => "Berita berhasil di simpan"
                            );
                        } else {
                            $msg = array(
                                "result" => 'error',
                                "msg" => "Error Menambahkan Berita"
                            );
                        }
                }
                if (isset($_POST['act']) && $_POST['act'] == 'do_edit') {

                        //$full_text_bbcode = $_POST['full_text_bbcode'] ?? '';
                        $full_text_bbcode = str_replace("\xc2\xa0", ' ', $_POST['full_text_bbcode'] ?? '');
                        require_once("plugins/jbbcode.php");
                        $full_text_html   = parseBBCodeToHtml($full_text_bbcode);
                        preg_match('/\[img\](.*?)\[\/img\]/i', $full_text_bbcode, $match);
                        $thumbnail = isset($match[1]) ? trim($match[1]) : $settings['siteurl']."/theme/no_image.webp";

                        $data = array(
                            "category" => $_POST["category"],
                            "thumbnail" => $thumbnail,
                            "title" => $_POST["title"],
                            "full_text_bbcode" => $full_text_bbcode,
                            "full_text_html" => $full_text_html,
                            "created_at" => date('Y-m-d H:i:s', strtotime($_POST["created_at"])),
                            "status" => $_POST["status"]
                        );
                        $doUpdate = pdo_update("news", $data, ['id'=>$_POST["id"]], ['id'=>'required|numeric']);
                        if ($doUpdate) {
                            if(isset($_POST["sendNotifAndroid"]) && $_POST["sendNotifAndroid"]==1){
                                // kirim notifikasi android
                                $sendArray = array(
                                    "type" => "topics",
                                    "uid" => "",
                                    "login_as" => ""
                                );
                                sendNotifAndroid($sendArray,
                                "Berita Terbaru!", 
                                $_POST["title"], 
                                [
                                    "type" => "news",
                                    "news_id" => $_POST["id"],
                                    "title" => "Berita Terbaru!",
                                    "body" => $_POST["title"]
                                ]
                                );
                            }
                            $msg = array(
                                "result" => 'success',
                                "msg" => "Berita berhasil di update"
                            );
                        } else {
                            $msg = array(
                                "result" => 'error',
                                "msg" => "Error Edit Berita"
                            );
                        }
                }

        ?>
        
        <?php if($page_cat_name == "act" && $page_cat=="add"){
            
            $allparent_id = pdo_select('news_category', ['status' => 1], ['id', 'name'], [], [], null, 'name ASC');
            $options_parent_id = '';
            foreach($allparent_id as $key => $value){
                $options_parent_id .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
        ?>

            <link rel="stylesheet" href="plugins/sceditor/minified/themes/default.min.css" />
            <script src="plugins/sceditor/minified/sceditor.min.js"></script>
            <script src="plugins/sceditor/minified/formats/bbcode.min.js"></script>
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="?p=news">Berita</a></li>
                        <li class="breadcrumb-item active">Tambah Baru</li>
                    </ol>
                </div>
            </div>
            <form name="mainform" action="?p=<?=$page;?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="act" value="do_add">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                                <h4 class="header-title">Berita Baru</h4>
                            </div>

                            <div class="card-body">

                            <div class="form-group mb-3">
                                <label for="category">Kategori</label>
                                <select name="category" class="form-select">
                                    <?=$options_parent_id;?>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="title" class="form-label">Judul</label>
                                <input type="text" id="title" name="title" class="form-control" placeholder="Judul Berita">
                            </div>

                            <div class="form-group mb-3">
                                <label for="full_text_bbcode">Isi Berita</label>
                                <textarea id="editor" class="form-control" rows="8" cols="70" name="full_text_bbcode"></textarea>
                            </div>

                            <div class="row g-2">
                            <div class="form-group mb-3 col-md-6">
                                <div class="form-group">
                                <label class="font-weight-bold" for="created_at">Tanggal Berita</label>
                                <input type="datetime-local" name="created_at" class="form-control" id="created_at" value="<?=date("Y-m-d\TH:i:s");?>" step="any">
                                </div>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <div class="form-group">
                                <label class="font-weight-bold" for="status">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1">Aktif</option>
                                    <option value="0">Non Aktif</option>
                                </select>
                                </div>
                            </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
            
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex align-items-center">
                    <h4 class="header-title">Gunakan untuk mengupload file gambar</h4>
                </div>

                <div class="card-body">
                    <form action="/" method="post" class="dropzone dz-clickable" id="myAwesomeDropzone" data-plugin="dropzone" data-imgtype="default" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                        

                        <div class="dz-message needsclick">
                            <i class="ti ti-cloud-upload h1 text-muted"></i>
                            <h3>Jatuhkan files disini atau klik untuk upload.</h3>
                            <span class="text-muted fs-13">(Gambar akan di upload di server dan akan
                                <strong>tampil</strong> dibawah.)</span>
                        </div>
                    </form>

                    <!-- Preview -->
                    <div class="dropzone-previews mt-3" id="file-previews"></div>
                </div>
                <!-- file preview template -->
                <div class="d-none" id="uploadPreviewTemplate">
                    <div class="card mt-1 mb-0 shadow-none border">
                        <div class="p-2">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="">
                                </div>
                                <div class="col ps-0">
                                    <a href="javascript:void(0);" class="text-muted fw-bold" data-dz-name></a>
                                    <p class="mb-0" data-dz-size></p>
                                </div>
                                <div class="col-auto">
                                    <!-- Button -->
                                    <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                        <i class="ti ti-x"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card-body -->
            </div>
        <script>
          var textarea = document.getElementById('editor');
          sceditor.create(textarea, {
              format: 'bbcode',
              style: 'plugins/sceditor/minified/themes/content/default.min.css',
              toolbar: 'bold,italic,underline,strike|left,center,right,justify|font,size,color,removeformat|bulletlist,orderedlist|table,code,quote,horizontalrule|image,youtube,link,unlink|source',
              autoExpand: true,
              resizeHeight: true,
              resizeMinHeight: 400,
              autoUpdate: true,
              autoParagraph: false,
                emoticons: false
          });
          sceditor.instance(textarea).css(`
            img {
                max-width: 50%;
                height: auto;
                margin: 5px auto;
            }
            `);
        </script>

        <?php }elseif($page_cat_name == "act" && $page_cat=="edit"){

          $item = pdo_select($page, ['id'=>$page_val], '*', [], ['id'=>'required|numeric'], 1);
            $allparent_id = pdo_select('news_category', ['status' => 1], ['id', 'name'], [], [], null, 'name ASC');
            $options_parent_id = '';
            foreach($allparent_id as $key => $value){
                $selected = ($item["category"] == $value['id']) ? 'selected' : '';
                $options_parent_id .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
        ?>

            <link rel="stylesheet" href="plugins/sceditor/minified/themes/default.min.css" />
            <script src="plugins/sceditor/minified/sceditor.min.js"></script>
            <script src="plugins/sceditor/minified/formats/bbcode.min.js"></script>
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="?p=news">Berita</a></li>
                        <li class="breadcrumb-item active">Edit Data</li>
                    </ol>
                </div>
            </div>
            <form name="mainform" action="?p=<?=$page;?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="act" value="do_edit">
                <input type="hidden" name="id" value="<?=$item['id'];?>">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                                <h4 class="header-title">Edit Berita</h4>
                            </div>

                            <div class="card-body">

                            <div class="form-group mb-3">
                                <label for="category">Kategori</label>
                                <select name="category" class="form-select">
                                    <?=$options_parent_id;?>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="title" class="form-label">Judul</label>
                                <input type="text" id="title" name="title" class="form-control" placeholder="Judul Berita" value="<?=$item['title'];?>">
                            </div>

                            <div class="form-group mb-3">
                                <label for="full_text_bbcode">Isi Berita</label>
                                <textarea id="editor" class="form-control" rows="8" cols="70" name="full_text_bbcode"><?=$item['full_text_bbcode'];?></textarea>
                            </div>

                            <div class="row g-2">
                            <div class="form-group mb-3 col-md-6">
                                <div class="form-group">
                                <label class="font-weight-bold" for="created_at">Tanggal Berita</label>
                                <input type="datetime-local" name="created_at" class="form-control" id="created_at" value="<?=date_format(new DateTime($item['created_at']), 'Y-m-d\TH:i:s');?>" step="any">
                                </div>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <div class="form-group">
                                <label class="font-weight-bold" for="status">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" <?php if($item['status']==1){echo 'selected';};?>>Aktif</option>
                                    <option value="0" <?php if($item['status']==0){echo 'selected';};?>>Nonaktif</option>
                                </select>
                                </div>
                            </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex align-items-center">
                    <h4 class="header-title">Gunakan untuk mengupload file gambar</h4>
                </div>

                <div class="card-body">
                    <form action="/" method="post" class="dropzone dz-clickable" id="myAwesomeDropzone" data-plugin="dropzone" data-imgtype="default" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                        

                        <div class="dz-message needsclick">
                            <i class="ti ti-cloud-upload h1 text-muted"></i>
                            <h3>Jatuhkan files disini atau klik untuk upload.</h3>
                            <span class="text-muted fs-13">(Gambar akan di upload di server dan akan
                                <strong>tampil</strong> dibawah.)</span>
                        </div>
                    </form>

                    <!-- Preview -->
                    <div class="dropzone-previews mt-3" id="file-previews"></div>
                </div>
                <!-- file preview template -->
                <div class="d-none" id="uploadPreviewTemplate">
                    <div class="card mt-1 mb-0 shadow-none border">
                        <div class="p-2">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="">
                                </div>
                                <div class="col ps-0">
                                    <a href="javascript:void(0);" class="text-muted fw-bold" data-dz-name></a>
                                    <p class="mb-0" data-dz-size></p>
                                </div>
                                <div class="col-auto">
                                    <!-- Button -->
                                    <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                        <i class="ti ti-x"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card-body -->
            </div>
        <script>
          var textarea = document.getElementById('editor');
          sceditor.create(textarea, {
              format: 'bbcode',
              style: 'plugins/sceditor/minified/themes/content/default.min.css',
              toolbar: 'bold,italic,underline,strike|left,center,right,justify|font,size,color,removeformat|bulletlist,orderedlist|table,code,quote,horizontalrule|image,youtube,link,unlink|source',
              autoExpand: true,
              resizeHeight: true,
              resizeMinHeight: 400,
              autoUpdate: true,
              autoParagraph: false,
                emoticons: false
          });
          sceditor.instance(textarea).css(`
            img {
                max-width: 50%;
                height: auto;
                margin: 5px auto;
            }
            `);
        </script>

        <?php }else{
          if($page_cat_name == 'status'){
            $bytype = 'WHERE status ='.$page_cat.'';
            $url = '&status='.$page_cat.'';
          }else{
            $bytype = '';
            $url = '';
          }
          $getData = getPagination($page, ($pages-1) * PAGINATION, PAGINATION, 'ORDER BY id DESC',$bytype);
        ?>
            
            <div class="row">
                <div class="col-12">
                    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-18 text-uppercase fw-bold m-0"><?=strtoupper($page);?></h4>
                        </div>
                        <div class="mt-3 mt-sm-0">
                            <form action="javascript:void(0);">
                                <div class="row g-2 mb-0 align-items-center">
                                    <div class="col-auto">
                                        <a href="?p=news&act=add" class="btn btn-sm btn-secondary">Tambah <i class="ti ti-plus ms-1"></i></a>
                                    </div>
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Semua Berita</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover text-nowrap mb-0">
                                    <thead class="bg-dark-subtle">
                                        <tr>
                                            <th class="checkbox-column text-center" width="1%">
                                                <label class="new-control new-checkbox checkbox-primary" style="height: 18px; margin: 0 auto;">
                                                    <input name="boxesAll" type="checkbox" class="form-check-input todochkbox" id="boxesAll">
                                                </label>
                                            </th>
                                            <th>Judul</th>
                                            <th>Status</th>
                                            <th class="text-center" style="width: 120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if ($getData): $no = (($pages - 1) * PAGINATION) + 1;?>
                                        <?php foreach ($getData as $item):
                                            $statusText = ($item['status'] == "0") ? "Nonaktif" : (($item['status'] == "2") ? "Unknown" : "Aktif");
                                            $iconText = ($item['status'] == "0") ? "text-danger" : (($item['status'] == "2") ? "text-muted" : "text-success");
                                        ?>
                                        <tr id="<?=$page.''.$item['id'];?>">
                                            <td class="checkbox-column text-center border-left border-width-4px" width="1%">
                                                <label class="new-control new-checkbox checkbox-primary" style="height: 18px; margin: 0 auto;">
                                                <input name="boxes[]" type="checkbox" class="form-check-input todochkbox" id="<?=$item["id"];?>" value="<?=$item["id"];?>">
                                                </label>
                                            </td>
                                            <td><?=substr($item["title"], 0, 35);?>...<br/><?=DateToIndo(date("d F Y", strtotime($item['created_at'])));?></td>
                                            <td>
                                                <span class="badge bg-primary-subtle <?=$iconText;?> fs-12 p-1"><?=$statusText;?></span>
                                            </td>
                                            <td class="pe-3">
                                                <div class="hstack gap-1 justify-content-end">
                                                    <a href="?p=news&act=edit&id=<?=$item["id"];?>" class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip" data-placement="top" title="Edit data"> <i class="ti ti-edit fs-16"></i></a>
                                                    <a href="?p=news&act=delete&id=<?=$item["id"];?>" class="btn btn-soft-danger btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip" data-placement="top" title="Hapus data"> <i class="ti ti-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    <?php endif;?>

                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive-->
                        </div> <!-- end card-body-->

                        <div class="card-footer border-0">
                            <div class="align-items-center justify-content-between row text-center text-sm-start">
                                
                                <div class="col-sm-auto mt-3 mt-sm-0">
                                    <div id="count_item" class="text-dark font-weight-bold"></div>
                                    <a id="delete" class="btn btn-dark btn-act float-left btn-sm me-2" data-bs-toggle="tooltip" data-placement="top" title="Hapus semua data" class="mx-lg-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                    <a id="active" class="btn btn-success btn-act float-left btn-sm me-2" data-bs-toggle="tooltip" data-placement="top" title="Tetapkan sebagai aktif" class="mx-lg-2">Aktif</a>
                                    <a id="disable" class="btn btn-danger btn-act float-left btn-sm me-2" data-bs-toggle="tooltip" data-placement="top" title="Tetapkan sebagai Nonaktif" class="mx-lg-2">Nonaktifkan</a>
                                </div>

                                <div class="col-sm-auto mt-3 mt-sm-0">
                                    <ul class="pagination pagination-boxed pagination-sm mb-0 justify-content-center">
                                        <?php echo getPaginationLinks($page, PAGINATION, '?p='.$page.''.$url,$pages,$bytype); ?>
                                    </ul>
                                </div>
                            </div> <!-- -->
                        </div>

                    </div> <!-- end card-->
                </div> <!-- end col-->

            </div> <!-- end row-->
        <?php } ?>

        <?php }
        break; // end "news" ?>

        <?php 
        case "news_category": 
            if ($admin && checkAccess("news",$admin_details['user_group_id'],$admin_details['user_type'])) {
        
          if($page_cat_name == 'status'){
            $bytype = 'WHERE status ='.$page_cat.'';
            $url = '&status='.$page_cat.'';
          }else{
            $bytype = '';
            $url = '';
          }
          $getData = getPagination($page, ($pages-1) * PAGINATION, PAGINATION, 'ORDER BY id DESC',$bytype);
        ?>
            <div class="row">
                <div class="col-12">
                    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-18 text-uppercase fw-bold m-0"><?=strtoupper($page);?></h4>
                        </div>
                        <div class="mt-3 mt-sm-0">
                            <form action="javascript:void(0);">
                                <div class="row g-2 mb-0 align-items-center">
                                    <div class="col-auto">
                                        <a href="javascript: void(0);" class="btn btn-outline-primary">
                                            <i class="ti ti-sort-ascending me-1"></i> Sort By
                                        </a>
                                    </div>
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Semua Kategori</h4>
                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#dataModal" id="dataBtn" data-act="add" data-table="<?=$page;?>" data-judul="Data Kategori" class="btn btn-sm btn-secondary">Tambah <i class="ti ti-plus ms-1"></i></a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-custom table-centered table-sm table-nowrap table-hover mb-0">
                                    <tbody>

                                    <?php if ($getData): $no = (($pages - 1) * PAGINATION) + 1;?>
                                        <?php foreach ($getData as $item): 
                                        $statusText = ($item['status'] == "0") ? "Nonaktif" : (($item['status'] == "2") ? "Unknown" : "Aktif");
                                        $iconText = ($item['status'] == "0") ? "text-danger" : (($item['status'] == "2") ? "text-muted" : "text-success");

                                        ?>
                                        <tr id="<?=$page.''.$item['id'];?>">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <span class="text-muted fs-12">Nama (ID)</span> <br />
                                                        <h5 class="fs-14 mt-1" id="itemName<?=$item['id'];?>"><?=$item['name'];?> (<?=$item['id'];?>)</h5>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-12">Slug</span>
                                                <h5 class="fs-14 mt-1 fw-normal" id="itemSlug<?=$item['id'];?>"><?=$item['slug'];?></h5>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-12">Kategori ID</span> <br />
                                                <h5 class="fs-14 mt-1 fw-normal" id="itemID<?=$item['id'];?>"><?=$item['id'];?></h5>
                                            </td>
                                            <td>
                                                <span class="text-muted fs-12">Status</span>
                                                <h5 class="fs-14 mt-1 fw-normal">
                                                    <i id="itemStatus<?=$item['id'];?>" class="ti ti-circle-filled fs-12 <?=$iconText;?>"></i> <?=$statusText;?>
                                                </h5>
                                            </td>
                                            <td style="width: 30px;">
                                                <div class="dropdown">
                                                    <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#dataModal" id="dataBtn" data-act="edit" data-judul="Data Kategori" data-table="<?=$page;?>" data-id="<?=$item['id'];?>" href="javascript:void(0)"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-info"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg> Edit</a>
                                                        <a class="dropdown-item" href="?p=<?=$page;?>&act=delete&id=<?=$item['id'];?>" onclick='return confirm("Apakah kamu yakin akan menghapus data?")'><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Hapus</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    <?php endif;?>

                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive-->
                        </div> <!-- end card-body-->

                        <div class="card-footer border-0">
                            <div class="align-items-center justify-content-between row text-center text-sm-start">
                                <div class="col-sm-auto mt-3 mt-sm-0">
                                    <ul class="pagination pagination-boxed pagination-sm mb-0 justify-content-center">
                                        <?php echo getPaginationLinks($page, PAGINATION, '?p='.$page.''.$url,$pages,$bytype); ?>
                                    </ul>
                                </div>
                            </div> <!-- -->
                        </div>

                    </div> <!-- end card-->
                </div> <!-- end col-->

            </div> <!-- end row-->

        <?php }
        break; // end "news_category" ?>

        <?php 
        case "contents": 
            if ($admin && checkAccess("contents",$admin_details['user_group_id'],$admin_details['user_type'])) {
        
                if (isset($_POST['act']) && $_POST['act'] == 'do_add') {

                        //$full_text_bbcode = $_POST['page_content_bbcode'] ?? '';
                        $full_text_bbcode = str_replace("\xc2\xa0", ' ', $_POST['page_content_bbcode'] ?? '');
                        require_once("plugins/jbbcode.php");
                        $full_text_html   = parseBBCodeToHtml($full_text_bbcode);
                        preg_match('/\[img\](.*?)\[\/img\]/i', $full_text_bbcode, $match);

                        $data = array(
                            "name" => $_POST["name"],
                            "page_content_bbcode" => $full_text_bbcode,
                            "page_content_html" => $full_text_html,
                            "created_at" => date('Y-m-d H:i:s'),
                            "status" => $_POST["status"]
                        );
                        $lastInsertID = pdo_insert("contents", $data, []);
                        if ($lastInsertID) {
                            $msg = array(
                                "result" => 'success',
                                "msg" => "Halaman berhasil di simpan"
                            );
                        } else {
                            $msg = array(
                                "result" => 'error',
                                "msg" => "Error Menambahkan Halaman"
                            );
                        }
                }
                if (isset($_POST['act']) && $_POST['act'] == 'do_edit') {

                        //$full_text_bbcode = $_POST['page_content_bbcode'] ?? '';
                        $full_text_bbcode = str_replace("\xc2\xa0", ' ', $_POST['page_content_bbcode'] ?? '');
                        require_once("plugins/jbbcode.php");
                        $full_text_html   = parseBBCodeToHtml($full_text_bbcode);

                        $data = array(
                            "name" => $_POST["name"],
                            "page_content_bbcode" => $full_text_bbcode,
                            "page_content_html" => $full_text_html,
                            "created_at" => date('Y-m-d H:i:s'),
                            "status" => $_POST["status"]
                        );
                        $doUpdate = pdo_update("contents", $data, ['id'=>$_POST["id"]], ['id'=>'required|numeric']);
                        if ($doUpdate) {
                            $msg = array(
                                "result" => 'success',
                                "msg" => "Halaman berhasil di update"
                            );
                        } else {
                            $msg = array(
                                "result" => 'error',
                                "msg" => "Error Edit Halaman"
                            );
                        }
                }

        ?>
        
        <?php if($page_cat_name == "act" && $page_cat=="add"){
            
            $allparent_id = pdo_select('contents', ['status' => 1], ['id', 'name'], [], [], null, 'name ASC');
            $options_parent_id = '';
            foreach($allparent_id as $key => $value){
                $options_parent_id .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
        ?>

            <link rel="stylesheet" href="plugins/sceditor/minified/themes/default.min.css" />
            <script src="plugins/sceditor/minified/sceditor.min.js"></script>
            <script src="plugins/sceditor/minified/formats/bbcode.min.js"></script>
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="?p=contents">Menu</a></li>
                        <li class="breadcrumb-item active">Tambah Baru</li>
                    </ol>
                </div>
            </div>
            <form name="mainform" action="?p=<?=$page;?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="act" value="do_add">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                                <h4 class="header-title">Halaman Baru</h4>
                            </div>

                            <div class="card-body">

                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nama Halaman</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Nama Halaman">
                            </div>

                            <div class="form-group mb-3">
                                <label for="page_content_bbcode">Isi Halaman</label>
                                <textarea id="editor" class="form-control" rows="8" cols="70" name="page_content_bbcode"></textarea>
                            </div>

                            <div class="row g-2">
                            <div class="form-group mb-3 col-md-12">
                                <div class="form-group">
                                <label class="font-weight-bold" for="status">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1">Aktif</option>
                                    <option value="0">Non Aktif</option>
                                </select>
                                </div>
                            </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
            
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex align-items-center">
                    <h4 class="header-title">Gunakan untuk mengupload file gambar</h4>
                </div>

                <div class="card-body">
                    <form action="/" method="post" class="dropzone dz-clickable" id="myAwesomeDropzone" data-plugin="dropzone" data-imgtype="default" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                        

                        <div class="dz-message needsclick">
                            <i class="ti ti-cloud-upload h1 text-muted"></i>
                            <h3>Jatuhkan files disini atau klik untuk upload.</h3>
                            <span class="text-muted fs-13">(Gambar akan di upload di server dan akan
                                <strong>tampil</strong> dibawah.)</span>
                        </div>
                    </form>

                    <!-- Preview -->
                    <div class="dropzone-previews mt-3" id="file-previews"></div>
                </div>
                <!-- file preview template -->
                <div class="d-none" id="uploadPreviewTemplate">
                    <div class="card mt-1 mb-0 shadow-none border">
                        <div class="p-2">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="">
                                </div>
                                <div class="col ps-0">
                                    <a href="javascript:void(0);" class="text-muted fw-bold" data-dz-name></a>
                                    <p class="mb-0" data-dz-size></p>
                                </div>
                                <div class="col-auto">
                                    <!-- Button -->
                                    <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                        <i class="ti ti-x"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card-body -->
            </div>
        <script>
          var textarea = document.getElementById('editor');
          sceditor.create(textarea, {
              format: 'bbcode',
              style: 'plugins/sceditor/minified/themes/content/default.min.css',
              toolbar: 'bold,italic,underline,strike|left,center,right,justify|font,size,color,removeformat|bulletlist,orderedlist|table,code,quote,horizontalrule|image,youtube,link,unlink|source',
              autoExpand: true,
              resizeHeight: true,
              resizeMinHeight: 400,
              autoUpdate: true,
              autoParagraph: false,
                emoticons: false
          });
          sceditor.instance(textarea).css(`
            img {
                max-width: 50%;
                height: auto;
                margin: 5px auto;
            }
            `);
        </script>

        <?php }elseif($page_cat_name == "act" && $page_cat=="edit"){

          $item = pdo_select($page, ['id'=>$page_val], '*', [], ['id'=>'required|numeric'], 1);
        ?>

            <link rel="stylesheet" href="plugins/sceditor/minified/themes/default.min.css" />
            <script src="plugins/sceditor/minified/sceditor.min.js"></script>
            <script src="plugins/sceditor/minified/formats/bbcode.min.js"></script>
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="?p=news">Halaman</a></li>
                        <li class="breadcrumb-item active">Edit Data</li>
                    </ol>
                </div>
            </div>
            <form name="mainform" action="?p=<?=$page;?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="act" value="do_edit">
                <input type="hidden" name="id" value="<?=$item['id'];?>">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                                <h4 class="header-title">Edit Halaman</h4>
                            </div>

                            <div class="card-body">

                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nama Halaman</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Nama Halaman" value="<?=$item['name'];?>">
                            </div>

                            <div class="form-group mb-3">
                                <label for="page_content_bbcode">Isi Halaman</label>
                                <textarea id="editor" class="form-control" rows="8" cols="70" name="page_content_bbcode"><?=$item['page_content_bbcode'];?></textarea>
                            </div>

                            <div class="row g-2">
                            <div class="form-group mb-3 col-md-12">
                                <div class="form-group">
                                <label class="font-weight-bold" for="status">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" <?php if($item['status']==1){echo 'selected';};?>>Aktif</option>
                                    <option value="0" <?php if($item['status']==0){echo 'selected';};?>>Nonaktif</option>
                                </select>
                                </div>
                            </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex align-items-center">
                    <h4 class="header-title">Gunakan untuk mengupload file gambar</h4>
                </div>

                <div class="card-body">
                    <form action="/" method="post" class="dropzone dz-clickable" id="myAwesomeDropzone" data-plugin="dropzone" data-imgtype="default" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                        

                        <div class="dz-message needsclick">
                            <i class="ti ti-cloud-upload h1 text-muted"></i>
                            <h3>Jatuhkan files disini atau klik untuk upload.</h3>
                            <span class="text-muted fs-13">(Gambar akan di upload di server dan akan
                                <strong>tampil</strong> dibawah.)</span>
                        </div>
                    </form>

                    <!-- Preview -->
                    <div class="dropzone-previews mt-3" id="file-previews"></div>
                </div>
                <!-- file preview template -->
                <div class="d-none" id="uploadPreviewTemplate">
                    <div class="card mt-1 mb-0 shadow-none border">
                        <div class="p-2">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="">
                                </div>
                                <div class="col ps-0">
                                    <a href="javascript:void(0);" class="text-muted fw-bold" data-dz-name></a>
                                    <p class="mb-0" data-dz-size></p>
                                </div>
                                <div class="col-auto">
                                    <!-- Button -->
                                    <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                        <i class="ti ti-x"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card-body -->
            </div>
        <script>
          var textarea = document.getElementById('editor');
          sceditor.create(textarea, {
              format: 'bbcode',
              style: 'plugins/sceditor/minified/themes/content/default.min.css',
              toolbar: 'bold,italic,underline,strike|left,center,right,justify|font,size,color,removeformat|bulletlist,orderedlist|table,code,quote,horizontalrule|image,youtube,link,unlink|source',
              autoExpand: true,
              resizeHeight: true,
              resizeMinHeight: 400,
              autoUpdate: true,
              autoParagraph: false,
                emoticons: false
          });
          sceditor.instance(textarea).css(`
            img {
                max-width: 50%;
                height: auto;
                margin: 5px auto;
            }
            `);
        </script>

        <?php }else{
          if($page_cat_name == 'status'){
            $bytype = 'WHERE status ='.$page_cat.'';
            $url = '&status='.$page_cat.'';
          }else{
            $bytype = '';
            $url = '';
          }
          $getData = getPagination($page, ($pages-1) * PAGINATION, PAGINATION, 'ORDER BY name ASC',$bytype);
        ?>

            <div class="row">
                <div class="col-12">
                    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-18 text-uppercase fw-bold m-0">Halaman</h4>
                        </div>
                        <div class="mt-3 mt-sm-0">
                            <form action="javascript:void(0);">
                                <div class="row g-2 mb-0 align-items-center">
                                    <div class="col-auto">
                                        <a href="?p=contents&act=add" class="btn btn-sm btn-secondary">Tambah <i class="ti ti-plus ms-1"></i></a>
                                    </div>
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Semua Halaman</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover text-nowrap mb-0">
                                    <thead class="bg-dark-subtle">
                                        <tr>
                                            <th class="checkbox-column text-center" width="1%">
                                                <label class="new-control new-checkbox checkbox-primary" style="height: 18px; margin: 0 auto;">
                                                    <input name="boxesAll" type="checkbox" class="form-check-input todochkbox" id="boxesAll">
                                                </label>
                                            </th>
                                            <th>Nama</th>
                                            <th>Status</th>
                                            <th class="text-center" style="width: 120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if ($getData): $no = (($pages - 1) * PAGINATION) + 1;?>
                                        <?php foreach ($getData as $item):
                                            $statusText = ($item['status'] == "0") ? "Nonaktif" : (($item['status'] == "2") ? "Unknown" : "Aktif");
                                            $iconText = ($item['status'] == "0") ? "text-danger" : (($item['status'] == "2") ? "text-muted" : "text-success");
                                        ?>
                                        <tr id="<?=$page.''.$item['id'];?>">
                                            <td class="checkbox-column text-center border-left border-width-4px" width="1%">
                                                <label class="new-control new-checkbox checkbox-primary" style="height: 18px; margin: 0 auto;">
                                                <input name="boxes[]" type="checkbox" class="form-check-input todochkbox" id="<?=$item["id"];?>" value="<?=$item["id"];?>">
                                                </label>
                                            </td>
                                            <td><?=$item["name"];?></td>
                                            <td>
                                                <span class="badge bg-primary-subtle <?=$iconText;?> fs-12 p-1"><?=$statusText;?></span>
                                            </td>
                                            <td class="pe-3">
                                                <div class="hstack gap-1 justify-content-end">
                                                    <a href="?p=contents&act=edit&id=<?=$item["id"];?>" class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip" data-placement="top" title="Edit data"> <i class="ti ti-edit fs-16"></i></a>
                                                    <a href="?p=contents&act=delete&id=<?=$item["id"];?>" class="btn btn-soft-danger btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip" data-placement="top" title="Hapus data"> <i class="ti ti-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    <?php endif;?>

                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive-->
                        </div> <!-- end card-body-->

                        <div class="card-footer border-0">
                            <div class="align-items-center justify-content-between row text-center text-sm-start">
                                
                                <div class="col-sm-auto mt-3 mt-sm-0">
                                    <div id="count_item" class="text-dark font-weight-bold"></div>
                                    <a id="delete" class="btn btn-dark btn-act float-left btn-sm me-2" data-bs-toggle="tooltip" data-placement="top" title="Hapus semua data" class="mx-lg-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                    <a id="active" class="btn btn-success btn-act float-left btn-sm me-2" data-bs-toggle="tooltip" data-placement="top" title="Tetapkan sebagai aktif" class="mx-lg-2">Aktif</a>
                                    <a id="disable" class="btn btn-danger btn-act float-left btn-sm me-2" data-bs-toggle="tooltip" data-placement="top" title="Tetapkan sebagai Nonaktif" class="mx-lg-2">Nonaktifkan</a>
                                </div>

                                <div class="col-sm-auto mt-3 mt-sm-0">
                                    <ul class="pagination pagination-boxed pagination-sm mb-0 justify-content-center">
                                        <?php echo getPaginationLinks($page, PAGINATION, '?p='.$page.''.$url,$pages,$bytype); ?>
                                    </ul>
                                </div>
                            </div> <!-- -->
                        </div>

                    </div> <!-- end card-->
                </div> <!-- end col-->

            </div> <!-- end row-->

        <?php } ?>

        <?php }
        break; // end "contents" ?>

        
        <?php 
        case "supports": 
            if ($admin && checkAccess("supports",$admin_details['user_group_id'],$admin_details['user_type'])) {
        
                if (isset($_POST['act']) && $_POST['act'] == 'do_add') {

                        //$full_text_bbcode = $_POST['page_content_bbcode'] ?? '';
                        $full_text_bbcode = str_replace("\xc2\xa0", ' ', $_POST['page_content_bbcode'] ?? '');
                        require_once("plugins/jbbcode.php");
                        $full_text_html   = parseBBCodeToHtml($full_text_bbcode);
                        preg_match('/\[img\](.*?)\[\/img\]/i', $full_text_bbcode, $match);

                        $data = array(
                            "name" => $_POST["name"],
                            "page_content_bbcode" => $full_text_bbcode,
                            "page_content_html" => $full_text_html,
                            "created_at" => date('Y-m-d H:i:s'),
                            "status" => $_POST["status"]
                        );
                        $lastInsertID = pdo_insert("contents", $data, []);
                        if ($lastInsertID) {
                            $msg = array(
                                "result" => 'success',
                                "msg" => "Halaman berhasil di simpan"
                            );
                        } else {
                            $msg = array(
                                "result" => 'error',
                                "msg" => "Error Menambahkan Halaman"
                            );
                        }
                }
                if (isset($_POST['act']) && $_POST['act'] == 'do_edit') {

                        //$full_text_bbcode = $_POST['page_content_bbcode'] ?? '';
                        $full_text_bbcode = str_replace("\xc2\xa0", ' ', $_POST['page_content_bbcode'] ?? '');
                        require_once("plugins/jbbcode.php");
                        $full_text_html   = parseBBCodeToHtml($full_text_bbcode);

                        $data = array(
                            "name" => $_POST["name"],
                            "page_content_bbcode" => $full_text_bbcode,
                            "page_content_html" => $full_text_html,
                            "created_at" => date('Y-m-d H:i:s'),
                            "status" => $_POST["status"]
                        );
                        $doUpdate = pdo_update("contents", $data, ['id'=>$_POST["id"]], ['id'=>'required|numeric']);
                        if ($doUpdate) {
                            $msg = array(
                                "result" => 'success',
                                "msg" => "Halaman berhasil di update"
                            );
                        } else {
                            $msg = array(
                                "result" => 'error',
                                "msg" => "Error Edit Halaman"
                            );
                        }
                }

        ?>
        
        <?php 
          if($page_cat_name == 'status'){
            $bytype = 'WHERE status ='.$page_cat.'';
            $url = '&status='.$page_cat.'';
          }else{
            $bytype = '';
            $url = '';
          }
          $getData = getPagination($page, ($pages-1) * PAGINATION, PAGINATION, 'ORDER BY name ASC',$bytype);
        ?>

            <div class="row">
                <div class="col-12">
                    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-18 text-uppercase fw-bold m-0"><?=strtoupper($page);?></h4>
                        </div>
                        <div class="mt-3 mt-sm-0">
                            <form action="javascript:void(0);">
                                <div class="row g-2 mb-0 align-items-center">
                                    <div class="col-auto">
                                        <a href="javascript: void(0);" class="btn btn-outline-primary">
                                            <i class="ti ti-sort-ascending me-1"></i> Sort By
                                        </a>
                                    </div>
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>

            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Semua Bantuan</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover text-nowrap mb-0">
                                    <thead class="bg-dark-subtle">
                                        <tr>
                                            <th class="checkbox-column text-center" width="1%">
                                                <label class="new-control new-checkbox checkbox-primary" style="height: 18px; margin: 0 auto;">
                                                    <input name="boxesAll" type="checkbox" class="form-check-input todochkbox" id="boxesAll">
                                                </label>
                                            </th>
                                            <th>Judul</th>
                                            <th>Dari</th>
                                            <th class="text-center" style="width: 120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if ($getData): $no = (($pages - 1) * PAGINATION) + 1;?>
                                        <?php foreach ($getData as $item):
                                            $statusText = ($item['status'] == "0") ? "Unread" : (($item['status'] == "2") ? "Readed" : "Replied");
                                            $iconText = ($item['status'] == "0") ? "text-danger" : (($item['status'] == "2") ? "text-muted" : "text-success");
                                        ?>
                                        <tr id="<?=$page.''.$item['id'];?>">
                                            <td class="checkbox-column text-center border-left border-width-4px" width="1%">
                                                <label class="new-control new-checkbox checkbox-primary" style="height: 18px; margin: 0 auto;">
                                                <input name="boxes[]" type="checkbox" class="form-check-input todochkbox" id="<?=$item["id"];?>" value="<?=$item["id"];?>">
                                                </label>
                                            </td>
                                            <td><?=substr($item["subject"], 0, 55);?>...<br/><?=DateToIndo(date("d F Y", strtotime($item['created_at'])));?></td>
                                            <td>
                                                <?=$item["email"];?><br/>
                                                <span class="badge bg-primary-subtle <?=$iconText;?> fs-12 p-1"><?=$statusText;?></span>
                                            </td>
                                            <td class="pe-3">
                                                <div class="hstack gap-1 justify-content-end">
                                                    <a href="?p=supports&act=edit&id=<?=$item["id"];?>" class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip" data-placement="top" title="Edit data"> <i class="ti ti-edit fs-16"></i></a>
                                                    <a href="?p=supports&act=delete&id=<?=$item["id"];?>" class="btn btn-soft-danger btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip" data-placement="top" title="Hapus data"> <i class="ti ti-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    <?php endif;?>

                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive-->
                        </div> <!-- end card-body-->

                        <div class="card-footer border-0">
                            <div class="align-items-center justify-content-between row text-center text-sm-start">
                                
                                <div class="col-sm-auto mt-3 mt-sm-0">
                                    <div id="count_item" class="text-dark font-weight-bold"></div>
                                    <a id="delete" class="btn btn-dark btn-act float-left btn-sm me-2" data-bs-toggle="tooltip" data-placement="top" title="Hapus semua data" class="mx-lg-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                    <a id="active" class="btn btn-success btn-act float-left btn-sm me-2" data-bs-toggle="tooltip" data-placement="top" title="Tetapkan sebagai aktif" class="mx-lg-2">Aktif</a>
                                    <a id="disable" class="btn btn-danger btn-act float-left btn-sm me-2" data-bs-toggle="tooltip" data-placement="top" title="Tetapkan sebagai Nonaktif" class="mx-lg-2">Nonaktifkan</a>
                                </div>

                                <div class="col-sm-auto mt-3 mt-sm-0">
                                    <ul class="pagination pagination-boxed pagination-sm mb-0 justify-content-center">
                                        <?php echo getPaginationLinks($page, PAGINATION, '?p='.$page.''.$url,$pages,$bytype); ?>
                                    </ul>
                                </div>
                            </div> <!-- -->
                        </div>

                    </div> <!-- end card-->
                </div> <!-- end col-->

            </div> <!-- end row-->

        <?php }
        break; // end "supports" ?>

        <?php 
        case "settings_general": 
        case "settings_seo": 
        case "settings_security": 
            if ($admin && checkAccess("settings",$admin_details['user_group_id'],$admin_details['user_type'])) {
        
        ?>
            <div class="row">
                <div class="col-12">
                    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-18 text-uppercase fw-bold m-0"><?=strtoupper($page);?></h4>
                        </div>
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            
            <?php if ($page == "settings_general") { 
                
                $timezones = array(
                'Pacific/Midway'       => "(GMT-11:00) Midway Island",
                'US/Samoa'             => "(GMT-11:00) Samoa",
                'US/Hawaii'            => "(GMT-10:00) Hawaii",
                'US/Alaska'            => "(GMT-09:00) Alaska",
                'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
                'America/Tijuana'      => "(GMT-08:00) Tijuana",
                'US/Arizona'           => "(GMT-07:00) Arizona",
                'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
                'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
                'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
                'America/Mexico_City'  => "(GMT-06:00) Mexico City",
                'America/Monterrey'    => "(GMT-06:00) Monterrey",
                'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
                'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
                'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
                'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
                'America/Bogota'       => "(GMT-05:00) Bogota",
                'America/Lima'         => "(GMT-05:00) Lima",
                'America/Caracas'      => "(GMT-04:30) Caracas",
                'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
                'America/La_Paz'       => "(GMT-04:00) La Paz",
                'America/Santiago'     => "(GMT-04:00) Santiago",
                'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
                'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
                'Greenland'            => "(GMT-03:00) Greenland",
                'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
                'Atlantic/Azores'      => "(GMT-01:00) Azores",
                'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
                'Africa/Casablanca'    => "(GMT) Casablanca",
                'UTC'                  => "(GMT) UTC",
                'Europe/Dublin'        => "(GMT) Dublin",
                'Europe/Lisbon'        => "(GMT) Lisbon",
                'Europe/London'        => "(GMT) London",
                'Africa/Monrovia'      => "(GMT) Monrovia",
                'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
                'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
                'Europe/Berlin'        => "(GMT+01:00) Berlin",
                'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
                'Europe/Brussels'      => "(GMT+01:00) Brussels",
                'Europe/Budapest'      => "(GMT+01:00) Budapest",
                'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
                'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
                'Europe/Madrid'        => "(GMT+01:00) Madrid",
                'Europe/Paris'         => "(GMT+01:00) Paris",
                'Europe/Prague'        => "(GMT+01:00) Prague",
                'Europe/Rome'          => "(GMT+01:00) Rome",
                'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
                'Europe/Skopje'        => "(GMT+01:00) Skopje",
                'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
                'Europe/Vienna'        => "(GMT+01:00) Vienna",
                'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
                'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
                'Europe/Athens'        => "(GMT+02:00) Athens",
                'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
                'Africa/Cairo'         => "(GMT+02:00) Cairo",
                'Africa/Harare'        => "(GMT+02:00) Harare",
                'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
                'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
                'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
                'Europe/Kiev'          => "(GMT+02:00) Kyiv",
                'Europe/Minsk'         => "(GMT+02:00) Minsk",
                'Europe/Riga'          => "(GMT+02:00) Riga",
                'Europe/Sofia'         => "(GMT+02:00) Sofia",
                'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
                'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
                'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
                'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
                'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
                'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
                'Europe/Moscow'        => "(GMT+03:00) Moscow",
                'Asia/Tehran'          => "(GMT+03:30) Tehran",
                'Asia/Baku'            => "(GMT+04:00) Baku",
                'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
                'Asia/Muscat'          => "(GMT+04:00) Muscat",
                'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
                'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
                'Asia/Kabul'           => "(GMT+04:30) Kabul",
                'Asia/Karachi'         => "(GMT+05:00) Karachi",
                'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
                'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
                'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
                'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
                'Asia/Almaty'          => "(GMT+06:00) Almaty",
                'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
                'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
                'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
                'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
                'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
                'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
                'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
                'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
                'Australia/Perth'      => "(GMT+08:00) Perth",
                'Asia/Singapore'       => "(GMT+08:00) Singapore",
                'Asia/Taipei'          => "(GMT+08:00) Taipei",
                'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
                'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
                'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
                'Asia/Seoul'           => "(GMT+09:00) Seoul",
                'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
                'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
                'Australia/Darwin'     => "(GMT+09:30) Darwin",
                'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
                'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
                'Australia/Canberra'   => "(GMT+10:00) Canberra",
                'Pacific/Guam'         => "(GMT+10:00) Guam",
                'Australia/Hobart'     => "(GMT+10:00) Hobart",
                'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
                'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
                'Australia/Sydney'     => "(GMT+10:00) Sydney",
                'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
                'Asia/Magadan'         => "(GMT+12:00) Magadan",
                'Pacific/Auckland'     => "(GMT+12:00) Auckland",
                'Pacific/Fiji'         => "(GMT+12:00) Fiji",
                );
                ?>
                
                <form id="submitForm" data-submit="update" method="post" name="mainform">
                    <input type="hidden" name="id" value="1">
                    <input type="hidden" name="act" value="do_update">
                    <input type="hidden" name="datatable" value="settings">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom border-dashed d-flex align-items-center">
                                    <h4 class="header-title">Pengaturan Umum</h4>
                                </div>

                                <div class="card-body">

                                <div class="form-group mb-3">
                                    <label for="sitename" class="form-label">Nama Website</label>
                                    <input type="text" id="sitename" name="sitename" class="form-control" placeholder="Nama Website" value="<?=$settings['sitename'];?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="siteurl" class="form-label">URL Website</label>
                                    <input type="text" id="siteurl" name="siteurl" class="form-control" placeholder="URL Website" value="<?=$settings['siteurl'];?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="siteaddress" class="form-label">Alamat Website</label>
                                    <input type="text" id="siteaddress" name="siteaddress" class="form-control" placeholder="Alamat Website" value="<?=$settings['siteaddress'];?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="sitelicense" class="form-label">Lisensi Website</label>
                                    <input type="text" id="sitelicense" class="form-control" placeholder="Lisensi Website" value="<?=$settings['sitelicense'];?>" readonly>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="sitetimezone" class="form-label">Wilayah Waktu Website</label>
                                    <select class="form-control" name="sitetimezone">
                                    <?php foreach ($timezones as $key => $value): ?>
                                        <option value="<?=$key;?>" <?php if ($key == $settings['sitetimezone']) {echo 'selected';}?>><?=$value;?></option>
                                    <?php endforeach;?>
                                    </select>
                                </div>

                                </div>

                            </div>
                            
                            <div class="card">
                                <div class="card-header border-bottom border-dashed d-flex align-items-center">
                                    <h4 class="header-title">Pengaturan Admin</h4>
                                </div>

                                <div class="card-body">

                                <div class="form-group mb-3">
                                    <label for="system_email" class="form-label">Sistem Email</label>
                                    <input type="email" id="system_email" name="system_email" class="form-control" placeholder="Sistem Email" value="<?=$settings['system_email'];?>">
                                </div>
                                <div class="form-group mb-3">
                                <label for="upload_place" class="form-label">Tempat Upload gambar?</label>
                                    <select name="upload_place" class="form-control" onchange='show_hide(1,"upload_place")'>
                                    <option value="0" <?php if ($settings['upload_place'] == 0) {echo 'selected';};?>>Server Sendiri/Hosting</option>
                                    <option value="1" <?php if ($settings['upload_place'] == 1) {echo 'selected';};?>>di imgbb.com</option>
                                    <option value="2" <?php if ($settings['upload_place'] == 2) {echo 'selected';};?>>di imgur.com</option>
                                    </select>
                                </div>
                                <div id="show_hide_id1" class="form-row mb-3" style="display:none;">
                                <div class="form-group col-md-12">
                                    <label for="upload_place_key" class="help" onclick="javascript:alert('Specify API Key for upload photo.');">Masukkan API Key upload gambar</label>
                                    <input type="text" name="upload_place_key" class="form-control" id="upload_place_key" value="<?=$settings['upload_place_key']?>">
                                </div>
                                </div>

                                <button id="submitFormBtn" type="submit" class="btn btn-primary">Save</button>
                                </div>

                            </div>

                        </div>
                    </div>
                </form>
                <script>
                    function show_hide(el,name){
                    if(name=="register_confirm"){
                        a = document.getElementById("show_hide_id"+el);
                        b = document.mainform.register_confirm.selectedIndex;
                        if(a) a.style.display = (b == 3 ? '' : 'none');
                    }else if(name=="upload_place"){
                        a = document.getElementById("show_hide_id"+el);
                        b = document.mainform.upload_place.selectedIndex;
                        if(a) a.style.display = (b >= 1 ? '' : 'none');
                    }
                    }
                    show_hide(1,"upload_place");
                </script>
            <?php } ?>  
            <?php if ($page == "settings_seo") { 
                ?>
                
                <form id="submitForm" data-submit="update" method="post" name="mainform">
                    <input type="hidden" name="id" value="1">
                    <input type="hidden" name="act" value="do_update">
                    <input type="hidden" name="datatable" value="settings">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom border-dashed d-flex align-items-center">
                                    <h4 class="header-title">Pengaturan SEO</h4>
                                </div>

                                <div class="card-body">

                                <div class="form-group mb-3">
                                    <label for="keywords" class="form-label">Kata Kunci Website</label>
                                    <input type="text" id="keywords" name="keywords" class="form-control" placeholder="Pisahkan dengan koma" value="<?=$settings['keywords'];?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Deskripsi Website</label>
                                    <input type="text" id="description" name="description" class="form-control" placeholder="Deskripsi Website" value="<?=$settings['description'];?>">
                                </div>

                                </div>

                            </div>
                            
                            <div class="card">
                                <div class="card-header border-bottom border-dashed d-flex align-items-center">
                                    <h4 class="header-title">Link SEO</h4>
                                </div>

                                <div class="card-body">

                                <div class="form-group mb-3">
                                    <label for="seourl_account" class="form-label">URL Account</label>
                                    <input type="text" id="seourl_account" name="seourl_account" class="form-control" value="<?=$settings['seourl_account'];?>">
                                    <small class="text-muted">default: account</small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="seourl_news" class="form-label">URL Berita</label>
                                    <input type="text" id="seourl_news" name="seourl_news" class="form-control" value="<?=$settings['seourl_news'];?>">
                                    <small class="text-muted">default: news</small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="seourl_faq" class="form-label">URL FAQ</label>
                                    <input type="text" id="seourl_faq" name="seourl_faq" class="form-control" value="<?=$settings['seourl_faq'];?>">
                                    <small class="text-muted">default: faq</small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="seourl_contents" class="form-label">URL Konten</label>
                                    <input type="text" id="seourl_contents" name="seourl_contents" class="form-control" value="<?=$settings['seourl_contents'];?>">
                                    <small class="text-muted">default: contents</small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="seourl_login" class="form-label">URL Login</label>
                                    <input type="text" id="seourl_login" name="seourl_login" class="form-control" value="<?=$settings['seourl_login'];?>">
                                    <small class="text-muted">default: login</small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="seourl_security" class="form-label">URL Security</label>
                                    <input type="text" id="seourl_security" name="seourl_security" class="form-control" value="<?=$settings['seourl_security'];?>">
                                    <small class="text-muted">default: security</small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="seourl_supports" class="form-label">URL Supports</label>
                                    <input type="text" id="seourl_supports" name="seourl_supports" class="form-control" value="<?=$settings['seourl_supports'];?>">
                                    <small class="text-muted">default: supports</small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="seourl_logout" class="form-label">URL Logout</label>
                                    <input type="text" id="seourl_logout" name="seourl_logout" class="form-control" value="<?=$settings['seourl_logout'];?>">
                                    <small class="text-muted">default: logout</small>
                                </div>

                                <button id="submitFormBtn" type="submit" class="btn btn-primary">Save</button>
                                </div>

                            </div>

                        </div>
                    </div>
                </form>
            <?php } ?>  

        <?php }
        break; // end "settings" ?>

        <?php 
        case "logout": 
            session_destroy();
            $arr_cookie_options = array(
                'expires'  => time() - 36000,
                'path'     => $urldetail['dir'],
                'domain'   => '.' . $_SERVER['SERVER_NAME'],
                'secure'   => false,
                'httponly' => true,
                'samesite' => '',
            );
            setcookie("password", '', $arr_cookie_options);
            header('Location: ' . $settings['siteurl']);exit();
        break; // end "logout" ?>

        <?php 
          default:
        ?>
            <!-- Page Title START -->

            <div class="row">
                <div class="col-12">
                    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-18 text-uppercase fw-bold m-0">Dashboard</h4>
                        </div>
                        <div class="mt-3 mt-sm-0">
                            <form action="javascript:void(0);">
                                <div class="row g-2 mb-0 align-items-center">
                                    <div class="col-auto">
                                        <a href="javascript: void(0);" class="btn btn-outline-primary">
                                            <i class="ti ti-sort-ascending me-1"></i> Sort By
                                        </a>
                                    </div>
                                    <!--end col-->
                                    <div class="col-sm-auto">
                                        <div class="input-group">
                                            <input type="text" class="form-control" data-provider="flatpickr" data-deafult-date="01 May to 31 May" data-date-format="d M" data-range-date="true">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="ti ti-calendar fs-15"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div> <!-- end row-->

            <div class="row">
                <div class="col">
                    <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1 text-center">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-muted fs-13 text-uppercase" title="Number of Orders">Total Users</h5>
                                    <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                                        <div class="user-img fs-42 flex-shrink-0">
                                            <span class="avatar-title text-bg-primary rounded-circle fs-22">
                                                <iconify-icon icon="solar:users-group-two-rounded-outline"></iconify-icon>
                                            </span>
                                        </div>
                                        <h3 class="mb-0 fw-bold"><?=pdo_count('users');?></h3>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->

                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-muted fs-13 text-uppercase" title="Number of Orders">Total Berita</h5>
                                    <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                                        <div class="user-img fs-42 flex-shrink-0">
                                            <span class="avatar-title text-bg-primary rounded-circle fs-22">
                                                <iconify-icon icon="solar:book-outline"></iconify-icon>
                                            </span>
                                        </div>
                                        <h3 class="mb-0 fw-bold"><?=pdo_count('news');?></h3>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->

                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-muted fs-13 text-uppercase" title="Number of Orders">Total Halaman</h5>
                                    <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                                        <div class="user-img fs-42 flex-shrink-0">
                                            <span class="avatar-title text-bg-primary rounded-circle fs-22">
                                                <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                            </span>
                                        </div>
                                        <h3 class="mb-0 fw-bold"><?=pdo_count('contents');?></h3>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->

                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-muted fs-13 text-uppercase" title="Number of Orders">Avg. Sales Earnings</h5>
                                    <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                                        <div class="user-img fs-42 flex-shrink-0">
                                            <span class="avatar-title text-bg-primary rounded-circle fs-22">
                                                <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                                            </span>
                                        </div>
                                        <h3 class="mb-0 fw-bold">$98.24 <small class="text-muted">USD</small></h3>
                                    </div>
                                    <p class="mb-0 text-muted">
                                        <span class="text-success me-2"><i class="ti ti-caret-up-filled"></i> 3.51%</span>
                                        <span class="text-nowrap">Since last month</span>
                                    </p>
                                </div>
                            </div>
                        </div><!-- end col -->

                    </div><!-- end row -->

                    <div class="row d-none">
                        <div class="col-xxl-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center border-bottom border-dashed">
                                    <h4 class="header-title">Top Traffic by Source</h4>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="javascript:void(0);" class="dropdown-item">Refresh Report</a>
                                            <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div id="multiple-radialbar" class="apex-charts" data-colors="#6ac75a,#313a46,#ce7e7e,#669776"></div>

                                    <div class="row mt-2">
                                        <div class="col">
                                            <div class="d-flex justify-content-between align-items-center p-1">
                                                <div>
                                                    <i class="ti ti-circle-filled fs-12 align-middle me-1 text-primary"></i>
                                                    <span class="align-middle fw-semibold">Direct</span>
                                                </div>
                                                <span class="fw-semibold text-muted float-end"><i class="ti ti-arrow-badge-down text-danger"></i> 965</span>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center p-1">
                                                <div>
                                                    <i class="ti ti-circle-filled fs-12 text-success align-middle me-1"></i>
                                                    <span class="align-middle fw-semibold">Social</span>
                                                </div>
                                                <span class="fw-semibold text-muted float-end"><i class="ti ti-arrow-badge-up text-success"></i> 75</span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="d-flex justify-content-between align-items-center p-1">
                                                <div>
                                                    <i class="ti ti-circle-filled fs-12 text-secondary align-middle me-1"></i>
                                                    <span class="align-middle fw-semibold"> Marketing</span>
                                                </div>
                                                <span class="fw-semibold text-muted float-end"><i class="ti ti-arrow-badge-up text-success"></i> 102</span>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center p-1">
                                                <div>
                                                    <i class="ti ti-circle-filled fs-12 text-danger align-middle me-1"></i>
                                                    <span class="align-middle fw-semibold">Affiliates</span>
                                                </div>
                                                <span class="fw-semibold text-muted float-end"><i class="ti ti-arrow-badge-down text-danger"></i> 96</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-->
                        </div> <!-- end col-->

                        <div class="col-xxl-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="header-title">Overview</h4>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle drop-arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <!-- item-->
                                            <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                            <!-- item-->
                                            <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                            <!-- item-->
                                            <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                            <!-- item-->
                                            <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-danger bg-opacity-10">
                                    <div class="row text-center">
                                        <div class="col-md-3 col-6">
                                            <p class="text-muted mt-3 mb-1">Revenue</p>
                                            <h4 class="mb-3">
                                                <span class="ti ti-square-rounded-arrow-down text-success me-1"></span>
                                                <span>$29.5k</span>
                                            </h4>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <p class="text-muted mt-3 mb-1">Expenses</p>
                                            <h4 class="mb-3">
                                                <span class="ti ti-square-rounded-arrow-up text-danger me-1"></span>
                                                <span>$15.07k</span>
                                            </h4>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <p class="text-muted mt-3 mb-1">Investment</p>
                                            <h4 class="mb-3">
                                                <span class="ti ti-chart-infographic me-1"></span>
                                                <span>$3.6k</span>
                                            </h4>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <p class="text-muted mt-3 mb-1">Savings</p>
                                            <h4 class="mb-3">
                                                <span class="ti ti-pig me-1"></span>
                                                <span>$6.9k</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body pt-0">
                                    <div dir="ltr">
                                        <div id="revenue-chart" class="apex-charts" data-colors="#6ac75a,#313a46,#ce7e7e,#669776"></div>
                                    </div>
                                </div>
                            </div> <!-- end card-->
                        </div> <!-- end col-->
                    </div> <!-- end row-->

                    <div class="row d-none">
                        <div class="col-xxl-6">
                            <div class="card">
                                <div class="d-flex card-header justify-content-between align-items-center">
                                    <h4 class="header-title">Brands Listing</h4>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-secondary">Add Brand <i class="ti ti-plus ms-1"></i></a>
                                </div>
                                <div class="card-body p-0">
                                    <div class="bg-success bg-opacity-10 py-1 text-center">
                                        <p class="m-0"><b>69</b> Active brands out of <span class="fw-medium">102</span></p>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-custom table-centered table-sm table-nowrap table-hover mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-md flex-shrink-0 me-2">
                                                                <span class="avatar-title bg-primary-subtle rounded-circle">
                                                                    <img src="<?= $themePath ?>/assets/images/products/logo/logo-1.svg" alt="" height="22">
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <span class="text-muted fs-12">Clothing</span> <br />
                                                                <h5 class="fs-14 mt-1">Zaroan - Brazil</h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Established</span>
                                                        <h5 class="fs-14 mt-1 fw-normal">Since 2020</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Stores</span> <br />
                                                        <h5 class="fs-14 mt-1 fw-normal">1.5k</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Products</span>
                                                        <h5 class="fs-14 mt-1 fw-normal">8,950</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Status</span>
                                                        <h5 class="fs-14 mt-1 fw-normal"><i class="ti ti-circle-filled fs-12 text-success"></i> Active</h5>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="javascript:void(0);" class="dropdown-item">Refresh Report</a>
                                                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-md flex-shrink-0 me-2">
                                                                <span class="avatar-title bg-info-subtle rounded-circle">
                                                                    <img src="<?= $themePath ?>/assets/images/products/logo/logo-4.svg" alt="" height="22">
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <span class="text-muted fs-12">Clothing</span> <br />
                                                                <h5 class="fs-14 mt-1">Jocky-Johns - USA</h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Established</span>
                                                        <h5 class="fs-14 mt-1 fw-normal">Since 1985</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Stores</span> <br />
                                                        <h5 class="fs-14 mt-1 fw-normal">205</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Products</span>
                                                        <h5 class="fs-14 mt-1 fw-normal">1,258</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Status</span>
                                                        <h5 class="fs-14 mt-1 fw-normal"><i class="ti ti-circle-filled fs-12 text-success"></i> Active</h5>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="javascript:void(0);" class="dropdown-item">Refresh Report</a>
                                                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-md flex-shrink-0 me-2">
                                                                <span class="avatar-title bg-secondary-subtle rounded-circle">
                                                                    <img src="<?= $themePath ?>/assets/images/products/logo/logo-5.svg" alt="" height="22">
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <span class="text-muted fs-12">Lifestyle</span> <br />
                                                                <h5 class="fs-14 mt-1">Ginne - India</h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Established</span>
                                                        <h5 class="fs-14 mt-1 fw-normal">Since 2001</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Stores</span> <br />
                                                        <h5 class="fs-14 mt-1 fw-normal">89</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Products</span>
                                                        <h5 class="fs-14 mt-1 fw-normal">338</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Status</span>
                                                        <h5 class="fs-14 mt-1 fw-normal"><i class="ti ti-circle-filled fs-12 text-success"></i> Active</h5>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="javascript:void(0);" class="dropdown-item">Refresh Report</a>
                                                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-md flex-shrink-0 me-2">
                                                                <span class="avatar-title bg-danger-subtle rounded-circle">
                                                                    <img src="<?= $themePath ?>/assets/images/products/logo/logo-6.svg" alt="" height="22">
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <span class="text-muted fs-12">Fashion</span> <br />
                                                                <h5 class="fs-14 mt-1">DDoen - Brazil</h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Established</span>
                                                        <h5 class="fs-14 mt-1 fw-normal">Since 1995</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Stores</span> <br />
                                                        <h5 class="fs-14 mt-1 fw-normal">650</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Products</span>
                                                        <h5 class="fs-14 mt-1 fw-normal">6,842</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Status</span>
                                                        <h5 class="fs-14 mt-1 fw-normal"><i class="ti ti-circle-filled fs-12 text-success"></i> Active</h5>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="javascript:void(0);" class="dropdown-item">Refresh Report</a>
                                                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-md flex-shrink-0 me-2">
                                                                <span class="avatar-title bg-primary-subtle rounded-circle">
                                                                    <img src="<?= $themePath ?>/assets/images/products/logo/logo-8.svg" alt="" height="22">
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <span class="text-muted fs-12">Manufacturing</span> <br />
                                                                <h5 class="fs-14 mt-1">Zoddiak - Canada</h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Established</span>
                                                        <h5 class="fs-14 mt-1 fw-normal">Since 1963</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Stores</span> <br />
                                                        <h5 class="fs-14 mt-1 fw-normal">109</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Products</span>
                                                        <h5 class="fs-14 mt-1 fw-normal">952</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-12">Status</span>
                                                        <h5 class="fs-14 mt-1 fw-normal"><i class="ti ti-circle-filled fs-12 text-success"></i> Active</h5>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="javascript:void(0);" class="dropdown-item">Refresh Report</a>
                                                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div> <!-- end table-responsive-->
                                </div> <!-- end card-body-->

                                <div class="card-footer border-0">
                                    <div class="align-items-center justify-content-between row text-center text-sm-start">
                                        <div class="col-sm">
                                            <div class="text-muted">
                                                Showing <span class="fw-semibold">5</span> of <span class="fw-semibold">15</span> Results
                                            </div>
                                        </div>
                                        <div class="col-sm-auto mt-3 mt-sm-0">
                                            <ul class="pagination pagination-boxed pagination-sm mb-0 justify-content-center">
                                                <li class="page-item disabled">
                                                    <a href="#" class="page-link"><i class="ti ti-chevron-left"></i></a>
                                                </li>
                                                <li class="page-item active">
                                                    <a href="#" class="page-link">1</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="#" class="page-link">2</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="#" class="page-link">3</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="#" class="page-link"><i class="ti ti-chevron-right"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> <!-- -->
                                </div>

                            </div> <!-- end card-->
                        </div> <!-- end col-->

                        <div class="col-xxl-6">
                            <div class="card">
                                <div class="card-header d-flex flex-wrap align-items-center gap-2 border-bottom border-dashed">
                                    <h4 class="header-title me-auto">Top Selling Products</h4>

                                    <div class="d-flex gap-2 justify-content-end text-end">
                                        <a href="javascript:void(0);" class="btn btn-sm btn-secondary">Import <i class="ti ti-download ms-1"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-sm btn-primary">Export <i class="ti ti-file-export ms-1"></i></a>
                                    </div>
                                </div>

                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-custom align-middle table-nowrap table-hover mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="avatar-lg">
                                                            <img src="<?= $themePath ?>/assets/images/products/p-1.png" alt="Product-1" class="img-fluid rounded-2">
                                                        </div>
                                                    </td>
                                                    <td class="ps-0">
                                                        <h5 class="fs-14 my-1"><a href="apps-ecommerce-product-details.html" class="link-reset">ASOS High Waist Tshirt</a></h5>
                                                        <span class="text-muted fs-12">07 April 2024</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="fs-14 my-1">$79.49</h5>
                                                        <span class="text-muted fs-12">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="fs-14 my-1">82</h5>
                                                        <span class="text-muted fs-12">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <div class="me-2">
                                                                <h5 class="fs-14 my-1">$6,518.18</h5>
                                                                <span class="text-muted fs-12">Amount</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="avatar-lg">
                                                            <img src="<?= $themePath ?>/assets/images/products/p-7.png" alt="Product-1" class="img-fluid rounded-2">
                                                        </div>
                                                    </td>
                                                    <td class="ps-0">
                                                        <h5 class="fs-14 my-1"><a href="apps-ecommerce-product-details.html" class="link-reset">Marco Single Sofa</a></h5>
                                                        <span class="text-muted fs-12">25 March 2024</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="fs-14 my-1">$128.50</h5>
                                                        <span class="text-muted fs-12">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="fs-14 my-1">37</h5>
                                                        <span class="text-muted fs-12">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <div class="me-2">
                                                                <h5 class="fs-14 my-1">$4,754.50</h5>
                                                                <span class="text-muted fs-12">Amount</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="avatar-lg">
                                                            <img src="<?= $themePath ?>/assets/images/products/p-4.png" alt="Product-1" class="img-fluid rounded-2">
                                                        </div>
                                                    </td>
                                                    <td class="ps-0">
                                                        <h5 class="fs-14 my-1"><a href="apps-ecommerce-product-details.html" class="link-reset">Smart Headphone </a></h5>
                                                        <span class="text-muted fs-12">17 March 2024</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="fs-14 my-1">$39.99</h5>
                                                        <span class="text-muted fs-12">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="fs-14 my-1">64</h5>
                                                        <span class="text-muted fs-12">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <div class="me-2">
                                                                <h5 class="fs-14 my-1">$2,559.36</h5>
                                                                <span class="text-muted fs-12">Amount</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="avatar-lg">
                                                            <img src="<?= $themePath ?>/assets/images/products/p-5.png" alt="Product-1" class="img-fluid rounded-2">
                                                        </div>
                                                    </td>
                                                    <td class="ps-0">
                                                        <h5 class="fs-14 my-1"><a href="apps-ecommerce-product-details.html" class="link-reset">Lightweight Jacket</a></h5>
                                                        <span class="text-muted fs-12">12 March 2024</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="fs-14 my-1">$20.00</h5>
                                                        <span class="text-muted fs-12">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="fs-14 my-1">184</h5>
                                                        <span class="text-muted fs-12">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <div class="me-2">
                                                                <h5 class="fs-14 my-1">$3,680.00</h5>
                                                                <span class="text-muted fs-12">Amount</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="avatar-lg">
                                                            <img src="<?= $themePath ?>/assets/images/products/p-6.png" alt="Product-1" class="img-fluid rounded-2">
                                                        </div>
                                                    </td>
                                                    <td class="ps-0">
                                                        <h5 class="fs-14 my-1"><a href="apps-ecommerce-product-details.html" class="link-reset">Marco Shoes</a></h5>
                                                        <span class="text-muted fs-12">05 March 2024</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="fs-14 my-1">$28.49</h5>
                                                        <span class="text-muted fs-12">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="fs-14 my-1">69</h5>
                                                        <span class="text-muted fs-12">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <div class="me-2">
                                                                <h5 class="fs-14 my-1">$1,965.81</h5>
                                                                <span class="text-muted fs-12">Amount</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div> <!-- end table-responsive-->
                                </div> <!-- end card-body-->

                                <div class="card-footer border-0">
                                    <div class="align-items-center justify-content-between row text-center text-sm-start">
                                        <div class="col-sm">
                                            <div class="text-muted">
                                                Showing <span class="fw-semibold">5</span> of <span class="fw-semibold">10</span> Results
                                            </div>
                                        </div>
                                        <div class="col-sm-auto mt-3 mt-sm-0">
                                            <ul class="pagination pagination-boxed pagination-sm mb-0 justify-content-center">
                                                <li class="page-item disabled">
                                                    <a href="#" class="page-link"><i class="ti ti-chevron-left"></i></a>
                                                </li>
                                                <li class="page-item active">
                                                    <a href="#" class="page-link">1</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="#" class="page-link">2</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="#" class="page-link"><i class="ti ti-chevron-right"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> <!-- -->
                                </div>
                            </div> <!-- end card-->
                        </div> <!-- end col-->
                    </div> <!-- end row-->

                </div> <!-- end col-->

                <div class="col-auto info-sidebar">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex mb-3 justify-content-between align-items-center">
                                <h4 class="header-title">Recent Orders:</h4>
                                <div>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary rounded-circle btn-icon"><i class="ti ti-plus"></i></a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 position-relative mb-2">
                                <div class="avatar-md flex-shrink-0">
                                    <img src="<?= $themePath ?>/assets/images/products/p-6.png" alt="product-pic" height="36">
                                </div>
                                <div>
                                    <h5 class="fs-14 my-1"><a href="apps-ecommerce-order-details.html" class="stretched-link link-reset">Marco Shoes</a></h5>
                                    <span class="text-muted fs-12">$29.99 x 1 = $29.99</span>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge badge-soft-success px-2 py-1">Sold</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 position-relative mb-2">
                                <div class="avatar-md flex-shrink-0">
                                    <img src="<?= $themePath ?>/assets/images/products/p-1.png" alt="product-pic" height="36">
                                </div>
                                <div>
                                    <h5 class="fs-14 my-1"><a href="apps-ecommerce-order-details.html" class="stretched-link link-reset">High Waist Tshirt</a></h5>
                                    <span class="text-muted fs-12">$9.99 x 3 = $29.97</span>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge badge-soft-success px-2 py-1">Sold</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 position-relative mb-2">
                                <div class="avatar-md flex-shrink-0">
                                    <img src="<?= $themePath ?>/assets/images/products/p-3.png" alt="product-pic" height="36">
                                </div>
                                <div>
                                    <h5 class="fs-14 my-1"><a href="apps-ecommerce-order-details.html" class="stretched-link link-reset">Comfirt Chair</a></h5>
                                    <span class="text-muted fs-12">$49.99 x 1 = $49.99</span>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge badge-soft-danger px-2 py-1">Return</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 position-relative mb-2">
                                <div class="avatar-md flex-shrink-0">
                                    <img src="<?= $themePath ?>/assets/images/products/p-4.png" alt="product-pic" height="36">
                                </div>
                                <div>
                                    <h5 class="fs-14 my-1"><a href="apps-ecommerce-order-details.html" class="stretched-link link-reset">Smart Headphone</a></h5>
                                    <span class="text-muted fs-12">$39.99 x 1 = $39.99</span>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge badge-soft-success px-2 py-1">Sold</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 position-relative">
                                <div class="avatar-md flex-shrink-0">
                                    <img src="<?= $themePath ?>/assets/images/products/p-2.png" alt="product-pic" height="36">
                                </div>
                                <div>
                                    <h5 class="fs-14 my-1"><a href="apps-ecommerce-order-details.html" class="stretched-link link-reset">Laptop Bag</a></h5>
                                    <span class="text-muted fs-12">$12.99 x 4 = $51.96</span>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge badge-soft-success px-2 py-1">Sold</span>
                                </div>
                            </div>

                            <div class="mt-3 text-center">
                                <a href="#!" class="text-decoration-underline fw-semibold ms-auto link-offset-2 link-dark">View All</a>
                            </div>
                        </div>
                        <div class="card-body p-0 border-top border-dashed">
                            <h4 class="header-title px-3 mb-2 mt-3">Recent Activity:</h4>
                            <div class="my-3 px-3" data-simplebar style="max-height: 370px;">
                                <div class="timeline-alt py-0">
                                    <div class="timeline-item">
                                        <i class="ti ti-basket bg-info-subtle text-info timeline-icon"></i>
                                        <div class="timeline-item-info">
                                            <a href="javascript:void(0);" class="link-reset fw-semibold mb-1 d-block">You sold an item</a>
                                            <span class="mb-1">Paul Burgess just purchased “My - Admin Dashboard”!</span>
                                            <p class="mb-0 pb-3">
                                                <small class="text-muted">5 minutes ago</small>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="timeline-item">
                                        <i class="ti ti-rocket bg-primary-subtle text-primary timeline-icon"></i>
                                        <div class="timeline-item-info">
                                            <a href="javascript:void(0);" class="link-reset fw-semibold mb-1 d-block">Product on the Theme Market</a>
                                            <span class="mb-1">Reviewer added
                                                <span class="fw-medium">Admin Dashboard</span>
                                            </span>
                                            <p class="mb-0 pb-3">
                                                <small class="text-muted">30 minutes ago</small>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="timeline-item">
                                        <i class="ti ti-message bg-info-subtle text-info timeline-icon"></i>
                                        <div class="timeline-item-info">
                                            <a href="javascript:void(0);" class="link-reset fw-semibold mb-1 d-block">Robert Delaney</a>
                                            <span class="mb-1">Send you message
                                                <span class="fw-medium">"Are you there?"</span>
                                            </span>
                                            <p class="mb-0 pb-3">
                                                <small class="text-muted">2 hours ago</small>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="timeline-item">
                                        <i class="ti ti-photo bg-primary-subtle text-primary timeline-icon"></i>
                                        <div class="timeline-item-info">
                                            <a href="javascript:void(0);" class="link-reset fw-semibold mb-1 d-block">Audrey Tobey</a>
                                            <span class="mb-1">Uploaded a photo
                                                <span class="fw-medium">"Error.jpg"</span>
                                            </span>
                                            <p class="mb-0 pb-3">
                                                <small class="text-muted">14 hours ago</small>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="timeline-item">
                                        <i class="ti ti-basket bg-info-subtle text-info timeline-icon"></i>
                                        <div class="timeline-item-info">
                                            <a href="javascript:void(0);" class="link-reset fw-semibold mb-1 d-block">You sold an item</a>
                                            <span class="mb-1">Paul Burgess just purchased “My - Admin Dashboard”!</span>
                                            <p class="mb-0 pb-3">
                                                <small class="text-muted">16 hours ago</small>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="timeline-item">
                                        <i class="ti ti-rocket bg-primary-subtle text-primary timeline-icon"></i>
                                        <div class="timeline-item-info">
                                            <a href="javascript:void(0);" class="link-reset fw-semibold mb-1 d-block">Product on the Bootstrap Market</a>
                                            <span class="mb-1">Reviewer added
                                                <span class="fw-medium">Admin Dashboard</span>
                                            </span>
                                            <p class="mb-0 pb-3">
                                                <small class="text-muted">22 hours ago</small>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="timeline-item">
                                        <i class="ti ti-message bg-info-subtle text-info timeline-icon"></i>
                                        <div class="timeline-item-info">
                                            <a href="javascript:void(0);" class="link-reset fw-semibold mb-1 d-block">Robert Delaney</a>
                                            <span class="mb-1">Send you message
                                                <span class="fw-medium">"Are you there?"</span>
                                            </span>
                                            <p class="mb-0 pb-2">
                                                <small class="text-muted">2 days ago</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- end timeline -->
                            </div> <!-- end slimscroll -->
                        </div>

                    </div> <!-- end card-->
                </div> <!-- end col-->

                        <div class="card-body">
                            <div class="card bg-warning bg-opacity-25">
                                <div class="card-body" style="background-image: url(<?= $themePath ?>/assets/images/png/arrows.svg); background-size: contain; background-repeat: no-repeat; background-position: right bottom;">
                                    <h4 class="text-warning">Script Dibuat oleh:</h4>
                                    <p class="text-warning text-opacity-75"><i class="ti ti-user text-warning"></i> Abu Puja</p>
                                    <a href="mailto:poejanetwork@gmail.com" class="btn btn-sm rounded-pill btn-primary me-2">Email Pembuat</a> <a href="https://t.me/abu_puja" class="btn btn-sm rounded-pill btn-info me-2">Telegram Pembuat</a> <a href="https://wa.me/6285374012013" class="btn btn-sm rounded-pill btn-success me-2">Whatsapp Pembuat</a>
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div>
            </div> <!-- end row-->

            <!-- Page Title END -->
            
        <?php 
          break;
          // end "default"
        ?>
        <?php 
        }
        ?>


            </div> <!-- container -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="page-container">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start">
                            <script>document.write(new Date().getFullYear())</script> © CMS - By <span class="fw-bold text-decoration-underline text-uppercase text-reset fs-12">Abu_puja</span>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end footer-links d-none d-md-block">
                                <a href="mailto:poejanetwork@gmail.com">About</a>
                                <a href="https://wa.me/6285374012013">Support</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Theme Settings -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="theme-settings-offcanvas">
        <div class="d-flex align-items-center gap-2 px-3 py-3 offcanvas-header border-bottom border-dashed">
            <h5 class="flex-grow-1 mb-0">Theme Settings</h5>

            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-0 h-100" data-simplebar>
            <div class="p-3 border-bottom border-dashed">
                <h5 class="mb-3 fs-16 fw-bold">Color Scheme</h5>

                <div class="row">
                    <div class="col-4">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-color-light" value="light">
                            <label class="form-check-label p-3 w-100 d-flex justify-content-center align-items-center" for="layout-color-light">
                                <iconify-icon icon="solar:sun-bold-duotone" class="fs-32 text-muted"></iconify-icon>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Light</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-color-dark" value="dark">
                            <label class="form-check-label p-3 w-100 d-flex justify-content-center align-items-center" for="layout-color-dark">
                                <iconify-icon icon="solar:cloud-sun-2-bold-duotone" class="fs-32 text-muted"></iconify-icon>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Dark</h5>
                    </div>
                </div>
            </div>

            <div class="p-3 border-bottom border-dashed">
                <h5 class="mb-3 fs-16 fw-bold">Topbar Color</h5>

                <div class="row">
                    <div class="col-3">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-topbar-color" id="topbar-color-light" value="light">
                            <label class="form-check-label p-0 avatar-lg w-100 bg-light" for="topbar-color-light">
                                <span class="d-flex align-items-center justify-content-center h-100">
                                    <span class="p-2 d-inline-flex shadow rounded-circle bg-white"></span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Light</h5>
                    </div>

                    <div class="col-3">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-topbar-color" id="topbar-color-dark" value="dark">
                            <label class="form-check-label p-0 avatar-lg w-100 bg-light" for="topbar-color-dark">
                                <span class="d-flex align-items-center justify-content-center h-100">
                                    <span class="p-2 d-inline-flex shadow rounded-circle bg-dark"></span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Dark</h5>
                    </div>
                </div>
            </div>

            <div class="p-3 border-bottom border-dashed">
                <h5 class="mb-3 fs-16 fw-bold">Menu Color</h5>

                <div class="row">
                    <div class="col-3">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-menu-color" id="sidenav-color-light" value="light">
                            <label class="form-check-label p-0 avatar-lg w-100 bg-light" for="sidenav-color-light">
                                <span class="d-flex align-items-center justify-content-center h-100">
                                    <span class="p-2 d-inline-flex shadow rounded-circle bg-white"></span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Light</h5>
                    </div>

                    <div class="col-3" style="--ct-dark-rgb: 64,73,84;">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-menu-color" id="sidenav-color-dark" value="dark">
                            <label class="form-check-label p-0 avatar-lg w-100 bg-light" for="sidenav-color-dark">
                                <span class="d-flex align-items-center justify-content-center h-100">
                                    <span class="p-2 d-inline-flex shadow rounded-circle bg-dark"></span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Dark</h5>
                    </div>
                </div>
            </div>

            <div class="p-3 .border-bottom .border-dashed">
                <h5 class="mb-3 fs-16 fw-bold">Sidebar Size</h5>

                <div class="row">
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-default" value="default">
                            <label class="form-check-label p-0 avatar-xl w-100" for="sidenav-size-default">
                                <span class="d-flex h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 border-end  flex-column p-1 px-2">
                                            <span class="d-block p-1 bg-dark-subtle rounded mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Default</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-compact" value="compact">
                            <label class="form-check-label p-0 avatar-xl w-100" for="sidenav-size-compact">
                                <span class="d-flex h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 border-end  flex-column p-1">
                                            <span class="d-block p-1 bg-dark-subtle rounded mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Compact</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-small" value="condensed">
                            <label class="form-check-label p-0 avatar-xl w-100" for="sidenav-size-small">
                                <span class="d-flex h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 border-end flex-column" style="padding: 2px;">
                                            <span class="d-block p-1 bg-dark-subtle rounded mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Condensed</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-small-hover" value="sm-hover">
                            <label class="form-check-label p-0 avatar-xl w-100" for="sidenav-size-small-hover">
                                <span class="d-flex h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 border-end flex-column" style="padding: 2px;">
                                            <span class="d-block p-1 bg-dark-subtle rounded mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                            <span class="d-block border border-3 border-secondary border-opacity-25 rounded w-100 mb-1"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Hover View</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-full" value="full">
                            <label class="form-check-label p-0 avatar-xl w-100" for="sidenav-size-full">
                                <span class="d-flex h-100">
                                    <span class="flex-shrink-0">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="d-block p-1 bg-dark-subtle mb-1"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Full Layout</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-fullscreen" value="fullscreen">
                            <label class="form-check-label p-0 avatar-xl w-100" for="sidenav-size-fullscreen">
                                <span class="d-flex h-100">
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-14 text-center text-muted mt-2">Hidden</h5>
                    </div>
                </div>
            </div>

            <div class="p-3 border-bottom border-dashed d-none">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fs-16 fw-bold mb-0">Container Width</h5>

                    <div class="btn-group radio" role="group">
                        <input type="radio" class="btn-check" name="data-container-position" id="container-width-fixed" value="fixed">
                        <label class="btn btn-sm btn-soft-primary w-sm" for="container-width-fixed">Full</label>

                        <input type="radio" class="btn-check" name="data-container-position" id="container-width-scrollable" value="scrollable">
                        <label class="btn btn-sm btn-soft-primary w-sm ms-0" for="container-width-scrollable">Boxed</label>
                    </div>
                </div>
            </div>

            <div class="p-3 border-bottom border-dashed d-none">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fs-16 fw-bold mb-0">Layout Position</h5>

                    <div class="btn-group radio" role="group">
                        <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-fixed" value="fixed">
                        <label class="btn btn-sm btn-soft-primary w-sm" for="layout-position-fixed">Fixed</label>

                        <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-scrollable" value="scrollable">
                        <label class="btn btn-sm btn-soft-primary w-sm ms-0" for="layout-position-scrollable">Scrollable</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 px-3 py-2 offcanvas-header border-top border-dashed">
            <button type="button" class="btn w-50 btn-soft-danger" id="reset-layout">Reset</button>
            <button type="button" class="btn w-50 btn-soft-info">Buy Now</button>
        </div>

    </div>

    <div class="modal fade" id="dataModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-bg-primary border-0">
                    <h5 class="modal-title" id="dataModalLabel">Modal title</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> <!-- end modal header -->
                <div id="dataResult" class="modal-body">
                    <div class="d-block text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin btn-spins"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg> Loading data...</div>
                </div>
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div>

    <!-- Vendor js -->
    <script src="<?= $themePath ?>/assets/js/vendor.min.js"></script>
    <!-- App js -->
    <script src="<?= $themePath ?>/assets/js/app.js"></script>

    <?php if ($page == 'news') { ?>
    <!-- Dropzone File Upload js -->
    <script src="<?= $themePath ?>/assets/vendor/dropzone/dropzone-min.js"></script>
    <script>Dropzone.autoDiscover = false;

$('[data-plugin="dropzone"]').each(function () {
    var previewsContainer = '#file-previews';
    var previewTemplateSelector = document.querySelector('#uploadPreviewTemplate').innerHTML;
    var previewTemplate = previewTemplateSelector ? $(previewTemplateSelector).html() : null;
    var imageType = $('#myAwesomeDropzone').data('imgtype');

    var myDropzone = new Dropzone(this, {
        previewsContainer: previewsContainer,
        previewTemplate: previewTemplate,
        maxFilesize: 5,
        autoProcessQueue: false,
        init: function () {
            this.on("addedfile", function (file) {

                // Tambahkan progress bar manual (karena pakai fetch)
                let progressBarContainer = document.createElement("div");
                progressBarContainer.classList.add("progress", "mt-2");
                progressBarContainer.style.height = "6px";

                let progressBar = document.createElement("div");
                progressBar.classList.add("progress-bar", "progress-bar-striped", "progress-bar-animated");
                progressBar.setAttribute("role", "progressbar");
                progressBar.style.width = "0%";
                progressBarContainer.appendChild(progressBar);

                if (file.previewElement)
                    file.previewElement.appendChild(progressBarContainer);

                // Fungsi update progress
                function updateProgress(percent) {
                    progressBar.style.width = percent + "%";
                }
                // Fungsi hapus progress bar
                function removeProgressBar() {
                    if (progressBarContainer && progressBarContainer.parentNode) {
                        setTimeout(() => {
                            progressBarContainer.remove();
                        }, 500); // hilang 0.5 detik setelah 100%
                    }
                }

                // ===================================================
                // =============== UPLOAD KE SERVER SENDIRI ==========
                // ===================================================
                <?php if (isset($settings['upload_place']) && $settings['upload_place'] == 0): ?>
                var formData = new FormData();
                formData.append('file', file);

                const xhr = new XMLHttpRequest();
                xhr.open("POST", '<?=$settings["siteurl"] . "/" . $settings["admin_dir"];?>?p=upload_img&act=upload&type=' + imageType, true);

                xhr.upload.onprogress = function (e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        updateProgress(percent);
                    }
                };

                xhr.onload = function () {
                    const data = JSON.parse(xhr.responseText);
                    if (xhr.status === 200 && data.result === 'success') {
                        updateProgress(100);
                        removeProgressBar();
                        handleUploadResult(file, data.url, true);
                    } else {
                        showUploadError(file, data.msg || "Gagal upload");
                    }
                };

                xhr.onerror = function () {
                    showUploadError(file, 'Terjadi error saat upload');
                };

                xhr.send(formData);
                <?php endif; ?>


                // ===================================================
                // ================== UPLOAD KE IMGUR ===============
                // ===================================================
                <?php if (isset($settings['upload_place']) && $settings['upload_place'] == 2): ?>
                var CLIENT_ID = '<?=$settings['upload_place_key'];?>';
                const readerImgur = new FileReader();

                readerImgur.onloadend = function () {
                    const base64Image = readerImgur.result.split(',')[1];
                    const formData = new FormData();
                    formData.append("image", base64Image);

                    // gunakan XMLHttpRequest agar bisa pakai progress
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "https://api.imgur.com/3/image", true);
                    xhr.setRequestHeader("Authorization", "Client-ID " + CLIENT_ID);

                    xhr.upload.onprogress = function (e) {
                        if (e.lengthComputable) {
                            const percent = Math.round((e.loaded / e.total) * 100);
                            updateProgress(percent);
                        }
                    };

                    xhr.onload = function () {
                        const data = JSON.parse(xhr.responseText);
                        if (xhr.status === 200 && data.success) {
                            updateProgress(100);
                            removeProgressBar();
                            handleUploadResult(file, data.data.link, true);
                        } else {
                            showUploadError(file, data.data?.error || "Gagal upload Imgur");
                        }
                    };

                    xhr.onerror = function () {
                        showUploadError(file, 'Terjadi error saat upload ke Imgur');
                    };

                    xhr.send(formData);
                };
                readerImgur.readAsDataURL(file);
                <?php endif; ?>


                // ===================================================
                // ================== UPLOAD KE IMGBB ===============
                // ===================================================
                <?php if (isset($settings['upload_place']) && $settings['upload_place'] == 1): ?>
                var APIkey = '<?=$settings['upload_place_key'];?>';
                const readerImgbb = new FileReader();

                readerImgbb.onloadend = function () {
                    const base64Image = readerImgbb.result.replace(/^data:image\/(png|jpeg|jpg);base64,/, '');
                    const formData = new FormData();
                    formData.append('key', APIkey);
                    formData.append('image', base64Image);

                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "https://api.imgbb.com/1/upload", true);

                    xhr.upload.onprogress = function (e) {
                        if (e.lengthComputable) {
                            const percent = Math.round((e.loaded / e.total) * 100);
                            updateProgress(percent);
                        }
                    };

                    xhr.onload = function () {
                        const data = JSON.parse(xhr.responseText);
                        if (xhr.status === 200 && data.success) {
                            updateProgress(100);
                            removeProgressBar();
                            let imageUrl = data.data.url;
                            imageUrl = imageUrl.replace('i.ibb.co', 'i.ibb.co.com');
                            handleUploadResult(file, imageUrl, true);
                        } else {
                            showUploadError(file, data.error?.message || "Gagal upload ImgBB");
                        }
                    };

                    xhr.onerror = function () {
                        showUploadError(file, 'Terjadi error saat upload ke ImgBB');
                    };

                    xhr.send(formData);
                };
                readerImgbb.readAsDataURL(file);
                <?php endif; ?>

            });

            // =======================
            // Fungsi bantuan umum
            // =======================
            function handleUploadResult(file, imageUrl, success, errorMsg) {
                if (!file.previewElement) return;

                if (success) {
                    // tampilkan thumbnail
                    let img = file.previewElement.querySelector('img[data-dz-thumbnail]');
                    if (img) img.src = imageUrl;

                    // hapus pesan/error lama
                    let oldInfo = file.previewElement.querySelector('.dz-success-message, .dz-action-buttons, .dz-error-message');
                    if (oldInfo) oldInfo.remove();

                    // buat container tombol
                    let btnContainer = document.createElement('div');
                    btnContainer.classList.add('dz-action-buttons');
                    btnContainer.style.marginTop = '10px';

                    // tombol: tambah ke editor
                    let btnAddImg = document.createElement('button');
                    btnAddImg.type = 'button';
                    btnAddImg.classList.add('btn', 'btn-sm', 'btn-primary', 'me-2');
                    btnAddImg.textContent = 'Tambah ke Editor';
                    btnAddImg.onclick = function () {
                        var editorInstance = sceditor.instance(textarea);
                        if (editorInstance) {
                            editorInstance.insert('[img]' + imageUrl + '[/img]');
                            swalAlert("success", 'Gambar ditambahkan ke editor');
                        } else {
                            swalAlert("error", 'Editor belum siap');
                        }
                    };

                    // tombol: salin URL
                    let btnCopyUrl = document.createElement('button');
                    btnCopyUrl.type = 'button';
                    btnCopyUrl.classList.add('btn', 'btn-sm', 'btn-secondary');
                    btnCopyUrl.textContent = 'Salin URL';
                    btnCopyUrl.onclick = function () {
                        navigator.clipboard.writeText(imageUrl).then(
                            () => swalAlert("success", 'URL gambar berhasil disalin'),
                            () => swalAlert("error", 'Gagal menyalin URL gambar')
                        );
                    };

                    btnContainer.appendChild(btnAddImg);
                    btnContainer.appendChild(btnCopyUrl);
                    file.previewElement.appendChild(btnContainer);
                } else {
                    showUploadError(file, errorMsg);
                }
            }

            function showUploadError(file, msg) {
                if (!file.previewElement) return;
                let errorMsg = file.previewElement.querySelector('.dz-error-message');
                if (!errorMsg) {
                    errorMsg = document.createElement('div');
                    errorMsg.classList.add('dz-error-message');
                    errorMsg.style.color = 'red';
                    errorMsg.textContent = msg;
                    file.previewElement.appendChild(errorMsg);
                }
            }
        }
    });
});

    </script>
    <?php } ?>

    <!-- Apex Chart js -->
    <script src="<?= $themePath ?>/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="<?= $themePath ?>/assets/vendor/sweetalert2/sweetalert2.min.js"></script>

    <?php 
    // global script
    if(isset($msg) && !empty($msg) && is_array($msg)){ ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
               swalAlert('<?= addslashes($msg['result']) ?>', '<?= addslashes($msg['msg']) ?>');
            });
        </script>
    <?php }

    if ($page == 'users' || $page == 'news' || $page == 'news_category' || $page == 'contents' || $page == 'settings_general' || $page == 'settings_seo' || $page == 'settings_security' || $page == 'supports') { ?>
    <script>
    document.addEventListener('DOMContentLoaded', () => {

        $(document).on('click', '#dataBtn', function () {
            var id = $(this).data('id');
            var dataTable = $(this).data('table');
            var dataAct = $(this).data('act');
            var dataTitle = $(this).data('judul');
            var page = '<?=$page;?>';

            $.ajax({
                type: 'POST',
                url: '<?=BASE_URL;?>?p=getForm&table='+dataTable+'&act='+dataAct+'&id='+id,
                data: {page:page,id:id},
                beforeSend: function() {
                    $("#dataBtn").attr("disabled", true);
                    $('#notif_process').removeClass('d-none');
                    $("#dataModalLabel").html(dataTitle);
                    $("#dataResult").html('<div class="d-block text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin btn-spins"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg> Loading data...</div>');
                }
            }).done(function(t) {
                setTimeout(function() {
                    data = JSON.parse(t);
                    if(data['result']=='success'){
                    $("#dataResult").html(data['msg']);
                    }
                    $('#notif_process').addClass('d-none');
                    $("#dataBtn").attr("disabled", false);
                }, 500);
                });
        });

        
        $(document).on('submit', '#submitForm', function (e) {
            e.preventDefault();
            var formData = $('#submitForm').serialize();
            var dataID = $("#submitForm input[name='id']").val();
            $.ajax({
                type: 'POST',
                url: '<?=$settings['siteurl'] . '/' . $settings['admin_dir'];?>?p=submitData',
                data: formData,
                beforeSend: function() {
                    $("#submitFormBtn").attr("disabled", true);
                    $("#submitFormBtn").html('<span class="spinner-border spinner-border-sm align-middle me-2" role="status" aria-hidden="true"></span> Please wait...');
                }
            }).done(function(t) {
                setTimeout(function() {
                    data = JSON.parse(t);
                    const tablename = data['datatable'];
                    $("#submitFormBtn").attr("disabled", false);
                    $("#submitFormBtn").html('Save');
                    $('#dataModal').modal('hide');
                    if(data['result']=='success'){
                    if(tablename=="users"){
                        if(data['type']=="insert"){
                        const user = data.data;
                        const statusText = user.status == "0" ? "Nonaktif" : user.status == "2" ? "Unknown" : "Aktif";
                        const iconText = user.status == "0" ? "text-danger" : user.status == "2" ? "text-muted" : "text-success";
                        const newRow = `<tr id="${tablename}${user.id}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <span class="text-muted fs-12">Username</span> <br />
                                                    <h5 class="fs-14 mt-1" id="itemUsername${user.id}">${user.username}</h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">Email</span>
                                            <h5 class="fs-14 mt-1 fw-normal" id="itemEmail${user.id}">${user.email}</h5>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">User Type</span> <br />
                                            <h5 class="fs-14 mt-1 fw-normal" id="itemUserType${user.id}">${user.user_type}</h5>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">Status</span>
                                            <h5 class="fs-14 mt-1 fw-normal">
                                                <i class="ti ti-circle-filled fs-12 ${iconText}"></i> ${statusText}
                                            </h5>
                                        </td>
                                        <td style="width: 30px;">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="?p=users&act=view&id=${user.id}" class="dropdown-item"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> Detail</a>
                                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#dataModal" id="dataBtn" data-act="edit" data-judul="Data User" data-table="${tablename}" data-id="${user.id}" href="javascript:void(0)"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-info"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg> Edit</a>
                                                    <a class="dropdown-item" href="?p=users&act=delete&id=${user.id}" onclick='return confirm("Apakah kamu yakin akan menghapus data?")'><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Hapus</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                                $("#dataTable tbody").prepend(newRow);
                        }else{
                            $("#itemUsername"+dataID).html(data['data']['username']);
                            $("#itemEmail"+dataID).html(data['data']['email']);
                        }
                    }else if(tablename=="user_group"){
                        if(data['type']=="insert"){
                        const item = data.data;
                        const statusBorder = item.status == "0" ? "border-danger" : item.status == "2" ? "border-warning" : item.status == "1" ? "border-success" : "";
                        const newRow = `
                                <tr id="${item.id}">
                                    <td class="checkbox-column text-center border-left ${statusBorder} border-width-4px" width="1%">
                                        <label class="new-control new-checkbox checkbox-primary" style="height: 18px; margin: 0 auto;">
                                            <input name="boxes[]" type="checkbox" class="new-control-input todochkbox" id="${item.id}" value="${item.id}">
                                            <span class="new-control-indicator"></span>
                                        </label>
                                    </td>
                                    <td width="1%">${item.id}</td>
                                    <td><span id="dataName${item.id}">${item.group_name}</span>
                                    </td>
                                    <td>
                                    <ul class="table-controls">
                                        <li>
                                        <a data-toggle="modal" data-target="#dataModal" id="dataBtn" data-act="edit" data-judul="Data Grup" data-table="${tablename}" data-id="${item.id}" class="pb-2 mx-lg-2" href="javascript:void(0)" data-toggles="tooltip" data-placement="top" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-info"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                        </a>
                                        </li>
                                        <li>
                                        <a class="pb-2 mx-lg-1" href="?p=${tablename}&act=delete&id=${item.id}" onclick="return confirm('Apakah kamu yakin akan menghapus data?')" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        </a>
                                        </li>
                                    </ul>
                                    </td>
                                </tr>
                                `;
                                $("#dataTable tbody").prepend(newRow);
                        }else{
                        $("#dataName"+dataID).html(data['data']['group_name']);
                        }
                    }else if(tablename=="user_privilege"){
                        if(data['type']=="insert"){
                        const item = data.data;
                        const statusBorder = item.status == "0" ? "border-danger" : item.status == "2" ? "border-warning" : item.status == "1" ? "border-success" : "";
                        const newRow = `
                                <tr id="${item.id}">
                                    <td class="checkbox-column text-center border-left ${statusBorder} border-width-4px" width="1%">
                                        <label class="new-control new-checkbox checkbox-primary" style="height: 18px; margin: 0 auto;">
                                            <input name="boxes[]" type="checkbox" class="new-control-input todochkbox" id="${item.id}" value="${item.id}">
                                            <span class="new-control-indicator"></span>
                                        </label>
                                    </td>
                                    <td width="1%">${item.id}</td>
                                    <td><b>Akses:</b> <span id="dataName${item.id}">${item.module_name}</span><br/><b>Grup:</b> <span id="dataNameGrup${item.id}">${item.user_group_name}</span>
                                    </td>
                                    <td>
                                    <ul class="table-controls">
                                        <li>
                                        <a data-toggle="modal" data-target="#dataModal" id="dataBtn" data-act="edit" data-judul="Data Hak Akses" data-table="${tablename}" data-id="${item.id}" class="pb-2 mx-lg-2" href="javascript:void(0)" data-toggles="tooltip" data-placement="top" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-info"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                        </a>
                                        </li>
                                        <li>
                                        <a class="pb-2 mx-lg-1" href="?p=${tablename}&act=delete&id=${item.id}" onclick="return confirm('Apakah kamu yakin akan menghapus data?')" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        </a>
                                        </li>
                                    </ul>
                                    </td>
                                </tr>
                                `;
                                $("#dataTable tbody").prepend(newRow);
                        }else{
                        $("#dataName"+dataID).html(data['data']['module_name']);
                        $("#dataNameGrup"+dataID).html(data['data']['user_group_name']);
                        }
                    }else if(tablename=="news_category"){
                        if(data['type']=="insert"){
                        const getData = data.data;
                        const statusText = getData.status == "0" ? "Nonaktif" : getData.status == "2" ? "Unknown" : "Aktif";
                        const iconText = getData.status == "0" ? "text-danger" : getData.status == "2" ? "text-muted" : "text-success";
                        const newRow = `<tr id="${tablename}${getData.id}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <span class="text-muted fs-12">Name (ID)</span> <br />
                                                    <h5 class="fs-14 mt-1" id="itemName${getData.id}">${getData.name} (${getData.id})</h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">Slug</span>
                                            <h5 class="fs-14 mt-1 fw-normal" id="itemSlug${getData.id}">${getData.slug}</h5>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">Kategori ID</span> <br />
                                            <h5 class="fs-14 mt-1 fw-normal" id="itemID${getData.id}">${getData.id}</h5>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">Status</span>
                                            <h5 class="fs-14 mt-1 fw-normal">
                                                <i class="ti ti-circle-filled fs-12 ${iconText}"></i> ${statusText}
                                            </h5>
                                        </td>
                                        <td style="width: 30px;">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#dataModal" id="dataBtn" data-act="edit" data-judul="Data Kategori" data-table="${tablename}" data-id="${getData.id}" href="javascript:void(0)"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-info"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg> Edit</a>
                                                    <a class="dropdown-item" href="?p=${tablename}&act=delete&id=${getData.id}" onclick='return confirm("Apakah kamu yakin akan menghapus data?")'><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Hapus</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                                $("#dataTable tbody").prepend(newRow);
                        }else{
                            $("#itemName"+dataID).html(data['data']['name']);
                            $("#itemSlug"+dataID).html(data['data']['slug']);
                            $("#itemID"+dataID).html(data['data']['id']);
                        }
                    }
                }
                swalAlert(data['result'],data['msg']);
            }, 100);
            });
        });
        
        // global checkbox
        
        $("#boxesAll").click(function () {
            $('input[name="boxes[]').prop('checked', this.checked);
            $delete.toggle( $submit_box.is(":checked") );
            $mp.toggle( $submit_box.is(":checked") );
            $count_item.toggle( $submit_box.is(":checked") );
            if($(this).is(':checked')){
            var numberChecked = $('input[name="boxes[]"]:checked').length;
            $('#count_item').html(numberChecked +' items selected');
                $('tr>td:first-child').addClass('bg-warning');
            } else {
                $('tr>td:first-child').removeClass('bg-warning');
            }
        });

            var id,resp,
            $delete = $(".btn-act").hide(),
            $mp = $(".btn-mp").hide(),
            $count_item = $("#count_item").hide(),
            $submit_box = $('input[name="boxes[]"]').click(function() {
                $delete.toggle( $submit_box.is(":checked") );
                $mp.toggle( $submit_box.is(":checked") );
                $count_item.toggle( $submit_box.is(":checked") );
            $('input[name="boxes[]"]').change(function() {
            $(this).parents('tr>td').toggleClass('bg-warning', $(this).is(':checked'));
            });
            var numberChecked = $('input[name="boxes[]"]:checked').length;
            $('#count_item').html(numberChecked +' items selected');
            });

        $('.btn-act').click(function(){
            var btnid = jQuery(this).attr("id");
            var page = '<?=$page;?>';
            id = [];
            $(':checkbox:checked').each(function(i){
            id[i] = $(this).val();
            });
            if (confirm("Are you sure to "+btnid+" these items?")) {
            if(id.length === 0){
            alert("Please Select atleast one item(s).");
            }else{
                $.ajax({
                    type: 'POST',
                    url: '<?=$settings['siteurl'] . '/' . $settings['admin_dir'];?>?p=submitAction&act='+btnid,
                    data: {page:page,id:id},
                    beforeSend: function() {
                        $(".btn-act").attr("disabled", true);
                        $(".btn-act").html('<span class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"><span class="sr-only">Loading...</span></span> Please wait...');
                    }
                }).done(function(t) {
                    setTimeout(function() {
                        data = JSON.parse(t);
                        if(data['result']=='success'){
                        var pages = "<?=$settings['siteurl'] . '/' . $settings['admin_dir'];?>?p="+page;
                        if(data['type']=='delete'){
                            for(var i=0; i<id.length; i++){
                            $('tr#'+id[i]+'').fadeOut('slow');
                            }
                            $(".btn-act").html('Deleted');
                            $(".btn-act").attr("disabled", true);
                            location.reload();
                        }else if(data['type']=='active'){
                            $(".btn-act").html('Active');
                            $(".btn-act").attr("disabled", true);
                            location.reload();
                        }else if(data['type']=='suspend'){
                            $(".btn-act").html('Suspend');
                            $(".btn-act").attr("disabled", true);
                            location.reload();
                        }else if(data['type']=='disable'){
                            $(".btn-act").html('Disabled');
                            $(".btn-act").attr("disabled", true);
                            location.reload();
                        }else if(data['type']=='accept'){
                            $(".btn-act").html('Accept');
                            $(".btn-act").attr("disabled", true);
                            location.reload();
                        }else if(data['type']=='decline'){
                            $(".btn-act").html('Decline');
                            $(".btn-act").attr("disabled", true);
                            location.reload();
                        }else if(data['type']=='lulus'){
                            $(".btn-act").html('Lulus');
                            $(".btn-act").attr("disabled", true);
                            location.reload();
                        }else if(data['type']=='lulusadm'){
                            $(".btn-act").html('Lulus ADM');
                            $(".btn-act").attr("disabled", true);
                            location.reload();
                        }else if(data['type']=='gagal'){
                            $(".btn-act").html('Gagal');
                            $(".btn-act").attr("disabled", true);
                            location.reload();
                        }else if(data['type']=='payments'){
                            $(".btn-act").html('All Payment Paid');
                            $(".btn-act").attr("disabled", true);

                            $("#action_response").append('<div id="resp">'+data['msg']+'</div>');
                        }
                        }else{
                            $(".btn-act").attr("disabled", false);
                            $(".btn-act").html('Failed to action');
                        }
                    }, 3000);
                    });
                }
            }
        });
    });
    </script>
    <?php } ?>
    
    
</body>
</html>
<?php } ?>

<?php 
if ($admin==false) {
  header('Location: ' . $settings['siteurl']);exit();
}
?>
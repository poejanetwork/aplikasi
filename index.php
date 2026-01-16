<?php
require('libs/Smarty.class.php');
use Smarty\Smarty;

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

// start smarty
if (isset($_GET['ddoscode']) && is_numeric($_GET['ddoscode'])) {
} else {
    $smarty = new Smarty();
    $smarty->error_reporting = E_ALL & ~E_NOTICE;
    // $smarty->setCompileLocking(false);
    // $smarty->setForceCompile(true);
    // $smarty->cache_locking = true;
    // $smarty->debugging = true;
    // $smarty->caching = true;
    // $smarty->cache_lifetime = 120;

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
    // seourl settings
    $seourl_account = $settings['seourl_account'] ?: 'account';
    $seourl_result = $settings['seourl_result'] ?: 'result';
    $seourl_security = $settings['seourl_security'] ?: 'security';
    $seourl_integration = $settings['seourl_integration'] ?: 'integration';
    $seourl_login = $settings['seourl_login'] ?: 'login';
    $seourl_register = $settings['seourl_register'] ?: 'register';
    $seourl_forgot = $settings['seourl_forgot'] ?: 'forgot';
    // pages
    $seourl_contents = $settings['seourl_contents'] ?: 'contents';

    if (isset($settings['use_seourl']) && $settings['use_seourl']) {
        function surl($link)
        {
            $url             = strtolower($link);
            $patterns        = $replacements        = array();
            $patterns[0]     = '/([?&])p=/i';
            $replacements[0] = '/';
            $patterns[1]     = '/[^ \w-]/i';
            $replacements[1] = '/';
            $patterns[2]     = '/(-$|^-)/i';
            $replacements[2] = '';
            $patterns[3]     = '/(-$|^-)/i';
            $replacements[3] = '';
            $url             = preg_replace($patterns, $replacements, $url);
            return $url;
        }
        $main_page = explode('/', $_SERVER['REQUEST_URI']);
        if (strpos($main_page[1], '?p=') !== false) {
            $page = substr(strtolower(str_ireplace(array("?p="), '', $main_page[1])), 0, 20);
        } else {
            $page = substr(strtolower(preg_replace('([^ \w-])', '', $main_page[1])), 0, 20);
        }
        $page_cat      = isset($main_page[3]) ? $main_page[3] : null;
        $page_cat_name = isset($main_page[2]) ? $main_page[2] : null;
        $page_val      = isset($main_page[5]) ? $main_page[5] : null;
        $page_val_name = isset($main_page[4]) ? $main_page[4] : null;
        $page_subcat      = isset($main_page[7]) ? $main_page[7] : null;
        $page_subcat_name = isset($main_page[6]) ? $main_page[6] : null;
        if (strpos($_SERVER['REQUEST_URI'], 'page') !== false) {
            $paginates = !empty(end($main_page)) ? end($main_page) : 1;
        } else {
            $paginates = 1;
        }
    } else {
        function surl($link)
        {
            return $link;
        }
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
        $page_subcat      = isset($url_pages[3]) ? $url_pages[3] : null;
        $page_subcat_name = isset($url_page[3]) ? $url_page[3] : null;

        $page      = isset($_GET['p']) ? $_GET['p'] : null;
        $paginates = isset($_GET['page']) ? $_GET['page'] : 1;
    }
    $smarty->registerPlugin("modifier", "surl", "surl");
    $smarty->registerPlugin("modifier", "stristr", "stristr");
    $smarty->registerPlugin("modifier", "strtoupper", "strtoupper");
    $smarty->registerPlugin("modifier", "ucfirst", "ucfirst");

    // detect user agent and IP
    $getUserAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
    $getUserIp = getRealIpAddr();
    function isMobile($getUserAgent)
    {
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $getUserAgent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($getUserAgent, 0, 4))) {
            return true;
        } else {
            return false;
        }
    }
    // theme

    $theme_default_mobile_android      = isset($settings['theme_default_mobile_android']) ? $settings['theme_default_mobile_android'] : $settings['theme_default'];

    if (isMobile($getUserAgent) && $settings['theme_default_mobile'] != 0) {
        $themedefault = $settings['theme_default_mobile'];
    } else {
        $themedefault = $settings['theme_default'];
    }

    if (isset($_GET['mobile'])) {
        $themedefault = $theme_default_mobile_android;
        $_SESSION['theme_default_mobile_android'] = 1;
    } elseif ($page == 'mobile' && !empty($page_cat_name) && $page_cat_name == 1) {
        $themedefault = $theme_default_mobile_android;
        $_SESSION['theme_default_mobile_android'] = 1;
    } elseif ($page == 'mobile' && !empty($page_cat_name) && $page_cat_name == 0) {
        $themedefault = $theme_default_mobile_android;
        $_SESSION['theme_default_mobile_android'] = 0;
    }
    if (isset($_SESSION['theme_default_mobile_android']) && $_SESSION['theme_default_mobile_android'] == 1) {
        $themedefault = $theme_default_mobile_android;
    }
    // android Webview mobile agent
    if (strpos($getUserAgent, "wv)") !== false || strpos($getUserAgent, "idevlinkapp#" . $_SERVER["SERVER_NAME"]) !== false) {
        $themedefault = $theme_default_mobile_android;
    }

    //plugins
    if (isset($_SESSION['loggedin']) && isset($_SESSION['id'])) {
        if ($settings['theme_folder_user_id'] != 0 && $_SESSION['id'] == $settings['theme_folder_user_id']) {
            $smarty->setTemplateDir('./theme/' . $settings['theme_folder'] . '/templates')
                ->setCompileDir('./theme/' . $settings['theme_folder'] . '/templates_c');
            $theme = '/theme/' . $settings['theme_folder'];
        } else {
            $smarty->setTemplateDir('./theme/' . $themedefault . '/templates')
                ->setCompileDir('./theme/' . $themedefault . '/templates_c');
            $theme = '/theme/' . $themedefault;
        }
    } else {
        $smarty->setTemplateDir('./theme/' . $themedefault . '/templates')
            ->setCompileDir('./theme/' . $themedefault . '/templates_c');
        $theme = '/theme/' . $themedefault;
    }
    $smarty->assign('theme', $theme);

    // start function

    // Generate pagination links
    function getPaginationLinks($table, $results_per_page, $pagename, $pages, $where = null)
    {
        global $settings;
        $url = surl('?p=' . $pagename . '&page=');
        $pagination_links = '';
        global $conn;
        $stmt = $conn->prepare("SELECT COUNT(id) AS total FROM " . $table . " " . $where);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $res = $stmt->fetch();

        $total_pages        = ceil($res["total"] / $results_per_page); // calculate total pages with results
        $after_before_pages = 3;
        $start_pages        = ($pages > $after_before_pages) ? $pages - $after_before_pages : 1; // Start number pages
        $end_pages          = ($pages < ($total_pages - $after_before_pages)) ? $pages + $after_before_pages : $total_pages; // End number pages
        if (!empty($total_pages)) {
            $pagination_links .= ($pages >= 2) ? '<li class="page-item px-1"><a class="page-link py-1" href="' . $url . '1">&laquo;First</a></li>' : '';

            for ($i = $start_pages; $i <= $end_pages; $i++) {
                $active_pages = ($pages == '' || $pages == $i) ? 'active' : '';
                $pagination_links .= '<li class="page-item px-1 ' . $active_pages . '" id="' . $i . '"><a class="page-link py-1" href="' . $url . '' . $i . '">' . $i . '</a></li>';
            }
            $pagination_links .= ($pages == $total_pages) ? '' : '<li class="page-item px-1"><a class="page-link py-1" href="' . $url . '' . $total_pages . '">Last&raquo;</a></li>';
        }
        return $pagination_links;
    }
    $pagination = $settings['show_per_page'];
    if (is_numeric($paginates)) {
        $start_from = ($paginates - 1) * $pagination;
    } else {
        $start_from = (1 - 1) * $pagination;
    }
    $paginator  = 'LIMIT ' . $start_from . ', ' . $pagination . '';

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


    $captcha_use = $captcha_status = 0;
    foreach ($settings['captcha_check'] as $k => $v) {
        if ($page == $seourl_account) {
            $showpage = "account";
        } elseif ($page == $seourl_security) {
            $showpage = "security";
        } elseif ($page == $seourl_integration) {
            $showpage = "integration";
        } elseif ($page == $seourl_login) {
            $showpage = "login";
        } elseif ($page == $seourl_register) {
            $showpage = "register";
        } elseif ($page == $seourl_forgot) {
            $showpage = "forgot";
        } else {
            $showpage = '';
        }
        if (strpos($showpage, $k) !== false) {
            $captcha_status = $v;
            if ($settings['captcha_use'] == 1) {
                $captcha_use = 1;
            } elseif ($settings['captcha_use'] == 2) {
                $captcha_use = $settings['captcha_recaptcha']['recaptcha_version'] == 2 ? $settings['captcha_recaptcha']['recaptcha_version'] : 3;
            }
        }
        if (empty($showpage) && $k == "supports") {
            $captcha_status = $v;
            if ($settings['captcha_use'] == 1) {
                $captcha_use = 1;
            } elseif ($settings['captcha_use'] == 2) {
                $captcha_use = $settings['captcha_recaptcha']['recaptcha_version'] == 2 ? $settings['captcha_recaptcha']['recaptcha_version'] : 3;
            }
        }
    }
    // captcha_check
    // [register] [login] [edit_account] [security_account] [supports] [forgot_password] [withdrawal] [withdraw_principal] [internal_transfer]
    $captcha = array(
        "status" => $captcha_status,
        "type"   => $captcha_use,
        "check"  => $settings['captcha_check'],
    );
    $smarty->assign('captcha', $captcha);

    function post_captcha($user_response)
    {
        global $settings;
        $fields_string = '';
        $fields        = array(
            'secret'   => $settings['captcha_recaptcha']['recaptcha_secret_key'],
            'response' => $user_response,
        );
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }

        $fields_string = rtrim($fields_string, '&');
        $ch            = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }

    function generateCsrfToken(): array
    {
        global $settings;
        global $enkey;
        $secret = $settings['sitelicense'] ?: $enkey;

        // ID acak
        $id = bin2hex(random_bytes(16));
        // Token acak
        $token = bin2hex(random_bytes(32));
        // Signature berbasis HMAC → mencegah manipulasi
        $signature = hash_hmac('sha256', session_id() . $id . $token, $secret);

        // Simpan di session → sekali pakai
        $_SESSION['csrf'][$id] = [
            'token' => $token,
            'sig'   => $signature,
            'time'  => time()
        ];

        return [
            'id'    => $id,
            'token' => $token,
            'sig'   => $signature
        ];
    }
    function validateCsrfToken(string $id, string $token, string $signature): bool
    {
        global $settings;
        global $enkey;
        $secret = $settings['sitelicense'] ?: $enkey;

        // Pastikan ID ada di session
        if (!isset($_SESSION['csrf'][$id])) {
            return false;
        }

        $data = $_SESSION['csrf'][$id];

        // Cek token cocok
        if (!hash_equals($data['token'], $token)) {
            return false;
        }

        // Cek signature valid
        $expectedSig = hash_hmac('sha256', session_id() . $id . $token, $secret);
        if (!hash_equals($expectedSig, $signature)) {
            return false;
        }

        // Cek expired (maks 60 detik)
        if (time() - $data['time'] > 60) {
            unset($_SESSION['csrf'][$id]);
            return false;
        }

        // Hapus agar token sekali pakai
        unset($_SESSION['csrf'][$id]);

        return true;
    }

    // START PAGE
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    // ambil token
    if (isset($_SERVER['REQUEST_URI']) && str_contains($_SERVER['REQUEST_URI'], 'proof')) {
        header('Content-Type: application/json');
        $tokenset = generateCsrfToken();
        $response = $tokenset
            ? ["response" => "success", "token" => $tokenset]
            : ["response" => "fail", "token" => null];
        echo json_encode($response);
        exit;
    }
    // end function

    // start page
    $tokenset = generateCsrfToken();
    $token = ''; 
    $token .= '<input type="hidden" value="' . htmlspecialchars($tokenset['id'], ENT_QUOTES) . '" name="form_id">';
    $token .= '<input type="hidden" value="' . htmlspecialchars($tokenset['token'], ENT_QUOTES) . '" name="form_token">';
    $token .= '<input type="hidden" value="' . htmlspecialchars($tokenset['sig'], ENT_QUOTES) . '" name="form_sig">';
    $smarty->assign('token', $token);
    $empty = array();

    switch ($page) {
        case $seourl_login;
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['act'] ?? '') === 'do_login') {
                header('Content-Type: application/json');
                $errorCode = 0;
                // cek form
                $id  = $_POST['form_id']    ?? '';
                $tok = $_POST['form_token'] ?? '';
                $sig = $_POST['form_sig']   ?? '';
                // cek apakah token valid
                if (!validateCsrfToken($id, $tok, $sig)) {
                    http_response_code(403);
                    $errorCode = 1;
                }

                if ($captcha_status && $captcha_use != 0) {
                    if ($captcha_use == 1) {
                    if (strcasecmp($_SESSION['captcha'], $_POST['captcha']) != 0) {
                        $error['captcha'] = "Invalid Captcha Provided.";
                        $errorCode = 2;
                    }
                    } elseif ($captcha_use == 2) {
                    if ($settings['captcha_recaptcha']['recaptcha_version'] == 2 || $settings['captcha_recaptcha']['recaptcha_version'] == 3) {
                        if (!empty($settings['captcha_recaptcha']['recaptcha_site_key']) && !empty($settings['captcha_recaptcha']['recaptcha_secret_key'])) {
                        if (isset($_POST['g-recaptcha-response'])) {
                            $res = post_captcha($_POST['g-recaptcha-response']);
                            if (!$res['success']) {
                            $error['captcha'] = "Please Check the Captcha Box.";
                            $errorCode = 2;
                            }
                        } else {
                            $error['captcha'] = "Please Check the Captcha Box.";
                            $errorCode = 2;
                        }
                        }
                    }
                    }
                }
                
                $getPost = $_POST;
                unset($_POST);

                if(empty($getPost['email']) || empty($getPost['password'])){
                    $errorCode = 25;
                }
                if($errorCode === 0){
                    // check if administrator
                    if ($getPost['email'] == $settings['email'] && password_verify($getPost['password'], $settings['password'])) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['adminapitime'] = time();
                        $_SESSION['adminapi'] = true;
                        $_SESSION['adminapiip'] = getRealIpAddr();
                        $_SESSION['admindetails'] = array(
                            "username" => "Administrator",
                            "user_group_id" => "Administrator", 
                            "user_type" => "superuser", 
                            "email" => $settings['email']
                        );
                        $arr_cookie_options = array(
                            'expires'  => 0,
                            'path'     => '/',
                            'domain'   => '.' . $_SERVER['SERVER_NAME'],
                            'secure'   => isset($_SERVER['HTTPS']),
                            'httponly' => true,
                            'samesite' => 'Lax',
                        );
                        setcookie("sig", md5($settings['email']), $arr_cookie_options);

                        $conn = getDBConnection();
                        $data = array(
                            'user_id' => 0,
                            'adate' => date('Y-m-d H:i:s'),
                            'ip' => $getUserIp,
                            'user_agent' => $getUserAgent
                        );
                        pdo_insert('access_log', $data, $empty);

                        if($isAjax){
                            $msg = ['result' => 'success', 'msg' => getSuccessCode(1), 'redirect' => $settings['siteurl'] . '/' . $settings['admin_dir']];
                        }else{
                            header('Location: ' . $settings['siteurl'] . '/' . $settings['admin_dir'], true, 303);exit();
                        }
                    }else{
                        // check if users exist
                        $rules = [
                            'status' => 'required|numeric',
                            'email' => 'required|email'
                        ];
                        $getData = pdo_select('users', ['email' => $getPost['email'], 'status' => 1], ['id', 'fullname', 'username', 'email','password','user_type','user_group_id','status'], $empty, $rules, 1);

                        if($getData){
                            // check user password
                            if ($getData['status'] == 1 && password_verify($getPost['password'], $getData['password'])) {
                                $_SESSION['loggedin'] = true;
                                $_SESSION['adminapitime'] = time();
                                $_SESSION['adminapi'] = true;
                                $_SESSION['adminapiip'] = getRealIpAddr();
                                $_SESSION['admindetails'] = $getData;
                                $arr_cookie_options = array(
                                    'expires'  => 0,
                                    'path'     => '/',
                                    'domain'   => '.' . $_SERVER['SERVER_NAME'],
                                    'secure'   => isset($_SERVER['HTTPS']),
                                    'httponly' => true,
                                    'samesite' => 'Lax',
                                );
                                setcookie("sig", md5($settings['email']), $arr_cookie_options);

                                $conn = getDBConnection();
                                $data = array(
                                    'user_id' => 0,
                                    'adate' => date('Y-m-d H:i:s'),
                                    'ip' => $getUserIp,
                                    'user_agent' => $getUserAgent
                                );
                                pdo_insert('access_log', $data, $empty);

                                if($isAjax){
                                    $msg = ['result' => 'success', 'msg' => getSuccessCode(1), 'redirect' => $settings['siteurl'] . '/' . $settings['admin_dir']];
                                }else{
                                    header('Location: ' . $settings['siteurl'] . '/' . $settings['admin_dir'], true, 303);exit();
                                }
                            }
                        }
                    }
                   
                }
            }
            $page = "login.tpl";
            $smarty->assign('pagecanonical', $seourl_login);
            $smarty->assign('pagetitle', $seourl_login);
            $smarty->assign('pagename', "Login");
        break;
        case $seourl_contents;
        
            if($page_cat_name){
                $rules = [
                    'id' => 'required|numeric'
                ];
                $getData = pdo_select('contents', ['id' => $page_cat_name, 'status' => 1], ['id', 'name', 'page_content_html'], $empty, $rules, 1);
                
                $smarty->assign('pagecontent', $getData['page_content_html']);

                $page = "contents.tpl";
                $smarty->assign('pagecanonical', $seourl_contents);
                $smarty->assign('pagetitle', $seourl_contents);
                $smarty->assign('pagename', "Halaman");
            }

        break;
        default:
        
            $allNewsData = pdo_select('news n', ['n.status' => 1], ['n.id', 'nc.name as category', 'n.thumbnail', 'n.title', 'n.created_at','n.status'], [
                [
                    'type' => 'LEFT',
                    'table' => 'news_category nc',
                    'on' => 'n.category = nc.id'
                ]
            ], $empty, 8, ['n.id' => 'DESC']);
            $allNews = [];
            foreach ($allNewsData as $value) {
                $allNews[] = [
                    "id" => $value['id'],
                    "category" => $value['category'],
                    "thumbnail" => $value['thumbnail'],
                    "title" => $value['title'],
                    "slug" => slugify($value['title']),
                    "created_at" => $value['created_at'],
                    "status" => $value['status'],
                ];
            }
            $smarty->assign('allNews', $allNews);


            if(isset($settings['custom_pages'])){
                $getcustopages = explode(",", $settings['custom_pages']);
                if (in_array($page, $getcustopages)) {
                foreach ($getcustopages as $key => $value) {
                    if ($page == $value) {
                    if (file_exists('./' . $theme . '/templates/' . $page . ".tpl")) {
                        $pagename = $page;
                        $page = $page . ".tpl";
                        $smarty->assign('pagecanonical', $pagename);
                        $smarty->assign('pagetitle', $pagename);
                        $smarty->assign('pagename', ucfirst($pagename) . ' -');
                    } else {
                        $page = "index.tpl";
                        $smarty->assign('pagecanonical', '');
                        $smarty->assign('pagetitle', 'home');
                        $smarty->assign('pagename', 'home');
                    }
                    }
                }
                }else{
                $page = "index.tpl";
                $smarty->assign('pagecanonical', '');
                $smarty->assign('pagetitle', 'home');
                $smarty->assign('pagename', 'home');
                }
            }else{
                $page = "index.tpl";
                $smarty->assign('pagecanonical', '');
                $smarty->assign('pagetitle', 'home');
                $smarty->assign('pagename', 'home');
            }
        break;
    }
    // end page

    $smarty->assign('sc_copyright', 'Abu_Puja');
    foreach ($settings as $key => $value) {
        unset($settings['email']);
        unset($settings['sitelicense']);
        unset($settings['admin_dir']);
        unset($settings['smtp_host']);
        unset($settings['smtp_user']);
        unset($settings['smtp_pass']);
        unset($settings['smtp_port']);
        unset($settings['smtp_secure']);
        unset($settings['smtp_user']);
        unset($settings['ban_ip']);
        unset($settings['telegram_token']);
        unset($settings['telegram_username']);
    }
    $smarty->assign('settings', $settings);
    $smarty->display($page);
}

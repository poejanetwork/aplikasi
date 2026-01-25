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

if (file_exists('install.php')) {
  die('Please Install Script First.');
}
// start smarty
$smarty = new Smarty();
$smarty->error_reporting = E_ALL & ~E_NOTICE;
// $smarty->setCompileLocking(false);
// $smarty->setForceCompile(true);
// $smarty->cache_locking = true;
// $smarty->debugging = true;
// $smarty->caching = true;
// $smarty->cache_lifetime = 120;
$smarty->registerPlugin("modifier", "stristr", "stristr");
$smarty->registerPlugin("modifier", "strtoupper", "strtoupper");
$smarty->registerPlugin("modifier", "ucfirst", "ucfirst");
$smarty->registerPlugin("modifier", "print_r", "print_r");
//plugins
if (isset($_SESSION['loggedin']) && isset($_SESSION['adminapi'])) {
    $smarty->setTemplateDir('./plugins/default/templates')
            ->setCompileDir('./plugins/default/templates_c')
            ->setCacheDir('./plugins/default/cache')
            ->setConfigDir('./plugins/default/configs');
    $theme = '/plugins/default';
    $smarty->assign('theme', $theme);
} else {
    header('Location: /', true, 303);exit();
}

define('ROOT_PATH', realpath(__DIR__));
// define('ROOT_PATH', dirname(__FILE__));

// main function
$config_dir = $smarty->getConfigDir(0);
require_once $config_dir.'env.php';
require_once $config_dir.'helpers.php';
$smarty->registerPlugin("modifier", "date_format_id", "date_format_id");
$smarty->registerPlugin("modifier", "format_file_size", "format_file_size");
$smarty->registerPlugin("modifier", "surl", "surl");
date_default_timezone_set(env('SITE_TIMEZONE'));

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
        1062 => 'Data sudah ada (duplikat)',
        1452 => "Relasi data tidak valid",
        1048 => "Ada kolom wajib yang kosong",
        1406 => "Data terlalu panjang",
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


// ==================================
// LOAD ROUTES
// ==================================
$smarty->assign('BASE_URL', env('BASE_URL'));
$smarty->assign('ADMIN_URL', env('ADMIN_URL'));
$uploadConfig = [
    'place' => (int) env('UPLOAD_PLACE'),
    'key'   => env('UPLOAD_PLACE_KEY'),
    'uploadUrlImg' => env('BASE_URL').''.env('ADMIN_URL') . '/upload/img',
    'uploadUrlFile' => env('BASE_URL').''.env('ADMIN_URL') . '/upload/file'
];
$smarty->assign('uploadConfig', $uploadConfig);
require_once $config_dir.'routes.php';
?>
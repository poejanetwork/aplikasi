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
$smarty->registerPlugin("modifier", "surl", "surl");
$smarty->registerPlugin("modifier", "stristr", "stristr");
$smarty->registerPlugin("modifier", "strtoupper", "strtoupper");
$smarty->registerPlugin("modifier", "ucfirst", "ucfirst");

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
// function
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


$page = "index.tpl";
$smarty->assign('pagecanonical', '');
$smarty->assign('pagetitle', 'home');
$smarty->assign('pagename', 'home');
$smarty->assign('BASE_URL', '/admin.php');
$smarty->display($page);
?>
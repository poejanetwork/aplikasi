<?php
require('libs/Smarty.class.php');
use Smarty\Smarty;

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // jika HTTPS
$urldetails       = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$urldetail        = parse_url($urldetails);
$urldetail['dir'] = dirname($_SERVER['PHP_SELF']);
if (session_status() === PHP_SESSION_NONE) {
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
//theme
$smarty->setTemplateDir('./theme/default/templates')
        ->setCompileDir('./theme/default/templates_c')
        ->setCacheDir('./theme/default/cache')
        ->setConfigDir('./plugins/default/configs');
$theme = '/theme/default';
$smarty->assign('theme', $theme);

define('ROOT_PATH', realpath(__DIR__));
// define('ROOT_PATH', dirname(__FILE__));

// main function
$config_dir = $smarty->getConfigDir(0);
require_once $config_dir.'env.php';
function surl($path = '') {
    return "/" . ltrim($path, '/');
}
require_once $config_dir.'helpers.php';
ini_set('display_errors', env('APP_DEBUG') ? '1' : '0');
$smarty->registerPlugin("modifier", "date_format_id", "date_format_id");
$smarty->registerPlugin("modifier", "format_file_size", "format_file_size");
$smarty->registerPlugin("modifier", "surl", "surl");
$smarty->registerPlugin('function', 'csrf', function () {
    $token = csrf_token();
    return '<input type="hidden" name="_token" value="' . htmlspecialchars($token) . '">';
});
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
function get_popular_tags(int $limit = 10): array
{
    return pdo_select(
        "news_tags nt",
        [],
        [
            "t.name",
            "t.slug",
            "SUM(nt.weight) AS total"
        ],
        [
            ["type" => "LEFT", "table" => "tags t", "on" => "nt.tag_id = t.id"],
        ],
        [],
        $limit,
        ["total" => "DESC"],
        [],
        ["t.id"]
    );
}

function getSidebarData() {    
    // Popular News: Top 5 views all time
    $data = [];
    $popular_news_limit = getSetting('popular_limit');
    $popular_news = pdo_select(
        'news',
        [],
        [
            'news.id',
            'news.title',
            'news.thumbnail',
            'news.created_at',
            'news.views_count',
            'u.fullname as username',
            'nc.name as category'
        ],
        [[
                'type' => 'LEFT',
                'table' => 'news_category nc',
                'on' => 'news.category_id = nc.id'
            ],
            [
                'type'  => 'LEFT',
                'table' => 'users u',
                'on'    => 'news.user_id = u.id'
            ]
        ],
        [],
        $popular_news_limit,
        ['news.views_count' => 'DESC', 'news.created_at' => 'DESC']
    );
    foreach ($popular_news as $value) {
        $data[] = [
            "id" => $value['id'],
            "username" => $value['username'],
            "category" => $value['category'],
            "thumbnail" => $value['thumbnail'],
            "title" => $value['title'],
            "slug" => slugify($value['title']),
            "created_at" => date_format_id($value['created_at'])
        ];
    }
    
    $popular_tags = get_popular_tags();
    
    return [
        'popular_news' => $data,
        'popular_tags' => $popular_tags
    ];
}

// ==============================
// HOME
// ==============================
function getSettings() {
    return [
        'APP_NAME' => env('APP_NAME'),
        'APP_DESCRIPTION' => getSetting('app_description'),
        'APP_KEYWORD' => getSetting('app_keyword'),
        'BASE_URL'  => env('BASE_URL'),
        'SITE_ADDRESS' => env('SITE_ADDRESS'),
        'SITE_TIMEZONE' => env('SITE_TIMEZONE'),
        'SYSTEM_EMAIL'  => env('SYSTEM_EMAIL'),
        'SITE_PHONE'  => env('SITE_PHONE'),
        'FACEBOOK'  => getSetting('facebook'),
        'TWITTER'  => getSetting('twitter'),
        'INSTAGRAM'  => getSetting('instagram'),
        'YOUTUBE'  => getSetting('youtube'),
        'GOOGLE_MAP' => getSetting('google_map'),
    ];
}

// ==============================
// HOME
// ==============================
route('GET', '/', function () {
    $sidebar_data = getSidebarData();

    $data = [];
    $allNewsData = pdo_select(
        'news',
        [
            'news.status' => 1
        ],
        [
            'news.id',
            'news.title',
            'news.thumbnail',
            'news.full_text_html',
            'news.created_at',
            'u.fullname as username',
            'nc.name as category'
        ],
         [
            [
                'type' => 'LEFT',
                'table' => 'news_category nc',
                'on' => 'news.category_id = nc.id'
            ],
            [
                'type'  => 'LEFT',
                'table' => 'users u',
                'on'    => 'news.user_id = u.id'
            ]
        ],
        [],
        11,
        'created_at DESC'
    );
    foreach ($allNewsData as $value) {
        $data[] = [
            "id" => $value['id'],
            "username" => $value['username'],
            "category" => $value['category'],
            "thumbnail" => $value['thumbnail'],
            "title" => $value['title'],
            "slug" => slugify($value['title']),
            "created_at" => date_format_id($value['created_at'])
        ];
    }

    view('index', [
        'content'   => 'home.tpl',
        'sidebar'   => 'sidebar.tpl',
        'sidebar_data' => $sidebar_data,
        'settings'  =>  getSettings(),
        'pagetitle' => 'Home',
        'pagename'  => 'home',
        'page_js'   => 'home.js',
        'data'  => $data
    ]);
});

// ==============================
// NEWS
// ==============================
routeGroup('/news', function () {

    route('GET', '/', function () {
        $sidebar_data = getSidebarData();
        $data = [];
        $allNewsData = pdo_select(
            'news',
            [
                'news.status' => 1
            ],
            [
                'news.id',
                'news.title',
                'news.thumbnail',
                'news.full_text_html',
                'news.created_at',
                'u.fullname as username',
                'nc.name as category'
            ],
            [
                [
                    'type' => 'LEFT',
                    'table' => 'news_category nc',
                    'on' => 'news.category_id = nc.id'
                ],
                [
                    'type'  => 'LEFT',
                    'table' => 'users u',
                    'on'    => 'news.user_id = u.id'
                ]
            ],
            [],
            11,
            'created_at DESC'
        );
        foreach ($allNewsData as $value) {
            $data[] = [
                "id" => $value['id'],
                "username" => $value['username'],
                "category" => $value['category'],
                "thumbnail" => $value['thumbnail'],
                "title" => $value['title'],
                "slug" => slugify($value['title']),
                "created_at" => date_format_id($value['created_at'])
            ];
        }

        view('index', [
            'content'   => 'news/index.tpl',
            'sidebar'   => 'sidebar.tpl',
            'sidebar_data' => $sidebar_data,
            'settings'  =>  getSettings(),
            'pagetitle' => 'Home',
            'pagename'  => 'home',
            'data'  => $data
        ]);
    });

    route('GET', '/read/{id}/{slug}', function ($id, $slug) {
        $sidebar_data = getSidebarData();
        
        if (!is_numeric($id)) {
            response_json(400, false, 'Invalid ID');
            return;
        }

        $data = pdo_select_first(
            'news',
            ['news.id' => (int)$id],
            [
                'news.id',
                'u.fullname as username',
                'nc.name as category',
                'news.thumbnail',
                'news.title',
                'news.full_text_bbcode',
                'news.created_at',
                'news.views_count'
            ],
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
            1
        );
        $html = showBBcodes($data['full_text_bbcode']);
        // Hapus IMG pertama saja
        $html = preg_replace(
            '/<img\b[^>]*>/i',
            '',
            $html,
            1 // hanya 1 kali (gambar pertama)
        );
        $data['full_text_bbcode'] = $html;
        $data['created_at'] = date_format_id($data['created_at']);
        $data['tags'] = extract_title_tags($data['title']);

        // update views
        if ($data['views_count']>=0) {
            $new_views = ($data['views_count'] ?? 0) + 1;
            pdo_update('news', ['views_count' => $new_views], ['id' => (int)$data['id']], []);
        }

        view('index', [
            'content'   => 'news/read.tpl',
            'sidebar'   => 'sidebar.tpl',
            'sidebar_data' => $sidebar_data,
            'settings'  =>  getSettings(),
            'pagetitle' => $data['title'],
            'pagename'  => 'news_detail',
            'data' => $data
        ]);
    });

    route('GET', '/tag/search', function () {
        $sidebar_data = getSidebarData();

        $query = trim($_GET['q'] ?? '');
        $allNewsData = pdo_select(
            'news',
            ['news.title LIKE' => "%$query%"],
            [
                'news.id',
                'news.title', 
                'news.thumbnail',
                'news.created_at',
                'u.fullname as username',
                'nc.name as category'
            ],
            [
                ['type' => 'LEFT', 'table' => 'users u', 'on' => 'news.user_id = u.id'],
                ['type' => 'LEFT', 'table' => 'news_category nc', 'on' => 'news.category_id = nc.id']
            ],
            [],
            10 // Limit hasil
        );
        $data = [];
        foreach ($allNewsData as $value) {
            $data[] = [
                "id" => $value['id'],
                "username" => $value['username'],
                "category" => $value['category'],
                "thumbnail" => $value['thumbnail'],
                "title" => $value['title'],
                "slug" => slugify($value['title']),
                "created_at" => date_format_id($value['created_at'])
            ];
        }

        view('index', [
            'content'   => 'news/tags.tpl',
            'sidebar'   => 'sidebar.tpl',
            'sidebar_data' => $sidebar_data,
            'settings'  =>  getSettings(),
            'pagetitle' => 'Search Tags: '.$query,
            'pagename'  => 'search_tags',
            'query' => $query,
            'data' => $data
        ]);
    });

});

// ==============================
// CONTENTS
// ==============================
routeGroup('/contents', function () {

    route('GET', '/{slug}', function ($slug) {
        $sidebar_data = getSidebarData();

        $data = pdo_select_first(
            'contents',
            ['slug' => $slug],
            ['name','slug','page_content_bbcode'], [], [], 1
        );

        abort_if(!$data, 404);

        view('index', [
            'content'   => 'contents.tpl',
            'sidebar'   => 'sidebar.tpl',
            'sidebar_data' => $sidebar_data,
            'settings'  =>  getSettings(),
            'pagetitle' => $data['name'],
            'pagename'  => $data['slug'],
            'pagecontent'  => showBBcodes($data['page_content_bbcode'])
        ]);
    });
    
});

// ==============================
// LOGIN
// ==============================
routeGroup('/login', function () {

    route('GET', '/', function () {
        view('index', [
            'content'   => 'login.tpl',
            'settings'  =>  getSettings(),
            'pagetitle' => 'Login',
            'pagename'  => 'login',
            'page_js'   => 'login.js'
        ]);
    });
    
    route('POST', '/', function () {
        require_csrf();
        
        function loadUserPrivileges(int $groupId): array
        {
            if ($groupId === 0) {
                return ['*']; // superadmin
            }

            $rows = pdo_select(
                'user_privilege',
                [
                    'user_group_id' => $groupId,
                    'status' => 1
                ],
                ['module_name']
            );

            return array_map(
                fn($m) => strtolower(trim($m)),
                array_column($rows, 'module_name')
            );
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $userIp       = getRealIpAddr();

        if ($email === '' || $password === '') {
            response_json(200, false, 'Email dan password wajib di isi');
            return;
        }
        
        if (is_login_blocked($email, $userIp)) {
            header('Content-Type: application/json');
            response_json(
                429,
                false,
                'Terlalu banyak percobaan login. Coba lagi setelah 5 menit.'
            );
            return;
        }

        $user = pdo_select_first('users', ['email' => $email], '*', [], [], 1);
        
        if (!$user || !password_verify($password, $user['password'])) {
            header('Content-Type: application/json');
            record_login_failure($email, $userIp);
            response_json(200, false, 'Detail login salah');
            return;
        }

        clear_login_attempts($email, $userIp);
        session_regenerate_id(true);

        $_SESSION['loggedin'] = true;
        $_SESSION['adminapitime'] = time();
        $_SESSION['adminapi'] = true;
        $_SESSION['adminapiip'] = getRealIpAddr();
        $_SESSION['admindetails'] = $user;
        $_SESSION['privileges'] = loadUserPrivileges((int)$user['user_group_id']);
        $arr_cookie_options = array(
            'expires'  => 0,
            'path'     => '/',
            'domain'   => '.' . $_SERVER['SERVER_NAME'],
            'secure'   => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax',
        );
        setcookie("sig", md5($user['email']), $arr_cookie_options);

        $data = array(
            'user_id' => $user['id'] ?? 0,
            'adate' => date('Y-m-d H:i:s'),
            'ip' => $userIp,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        );
        pdo_insert('access_log', $data, []);

        unset($_SESSION['_csrf_token'], $_SESSION['_csrf_exp']);

        header('Content-Type: application/json');
        response_json(
            200,
            true,
            'Login Berhasil'
        );

    });

});

// ==============================
// NEWSLETTER
// ==============================
routeGroup('/newsletter', function () {
    
    route('POST', '/subscribe', function () {
        require_csrf();

        $email = $_POST['email'] ?? '';
        $result = newsletter_subscribe($email);

        unset($_SESSION['_csrf_token'], $_SESSION['_csrf_exp']);
        
        response_json(200, $result['status'], $result['message']);
    });
    
    route('GET', '/confirm/{token}', function ($token) {
        view('index', [
            'content' => newsletter_confirm($token)
                ? 'newsletter/success.tpl'
                : 'newsletter/failed.tpl'
        ]);
    });

    route('GET', '/unsubscribe/{token}', function ($token) {
        newsletter_unsubscribe($token);
        view('index', ['content' => 'newsletter/unsubscribed.tpl']);
    });

});

// ==============================
// CONTACT US / SUPPORT
// ==============================
routeGroup('/contact', function () {
    
    route('GET', '/', function () {
        $sidebar_data = getSidebarData();

        view('index', [
            'content'   => 'contact.tpl',
            'sidebar'   => 'sidebar.tpl',
            'sidebar_data' => $sidebar_data,
            'settings'  =>  getSettings(),
            'pagetitle' => 'Contact Us',
            'pagename'  => 'contact_us',
            'page_js'   => 'contact.js'
        ]);
    });

    route('POST', '/send', function () {
        require_csrf();

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ];

        $data = [
            'name'  => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'subject' => $_POST['subject'] ?? '',
            'message' => $_POST['message'] ?? '',
        ];
        
        $id = pdo_insert('supports', $data, $rules);

        if (is_array($id) && isset($id['errors'])) {
            response_json(
                $id['errors']['code'] ?? 400,
                false,
                $id['errors']['message']
            );
            return;
        }

        unset($_SESSION['_csrf_token'], $_SESSION['_csrf_exp']);

        response_json(
            200,
            true,
            'Pesan berhasil terkirim'
        );
    });

});

// ==============================
// 404 Not Found
// ==============================
abort(404);
?>
<?php

require_once 'vendor/autoload.php';
require_once 'src/mf/utils/AbstractClassLoader.php';
require_once 'src/mf/utils/ClassLoader.php';

$loader = new \mf\utils\ClassLoader('src');
$loader->register();

use mf\view\AbstractView;

$keys = parse_ini_file("conf/config.ini");

$serveur = $keys["serveur"];
$base = $keys["base"];
$user = $keys["user"];
$pass = $keys["pass"];

$config = [
    'driver'    => 'sqlite',
    'database'  => __DIR__ . "/$base",
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => ''
];

$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();

$router = new \mf\router\Router();

$router->addRoute(
    'maison',
    '/home/',
    '\tweeterapp\control\TweeterController',
    'viewHome',
    \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE
);

$router->addRoute(
    'tweet',
    '/tweet/',
    '\tweeterapp\control\TweeterController',
    'viewTweet',
    \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE
);

$router->addRoute(
    'userTweets',
    '/tweets/',
    '\tweeterapp\control\TweeterController',
    'viewUserTweets',
    \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE
);

$router->addRoute(
    'postTweet',
    '/post/',
    '\tweeterapp\control\TweeterController',
    'viewTweetPost',
    \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER
);

$router->addRoute(
    'sendTweet',
    '/send/',
    '\tweeterapp\control\TweeterController',
    'sendTweet',
    \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER
);

$router->addRoute(
    'login',
    '/login/',
    '\tweeterapp\control\TweeterAdminController',
    'login',
    \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE
);

$router->addRoute(
    'checkLogin',
    '/check_login/',
    '\tweeterapp\control\TweeterAdminController',
    'checkLogin',
    \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE
);

$router->addRoute(
    'logout',
    '/logout/',
    '\tweeterapp\control\TweeterAdminController',
    'logout',
    \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER
);

$router->addRoute(
    'signup',
    '/signup/',
    '\tweeterapp\control\TweeterAdminController',
    'signup',
    \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE
);

$router->addRoute(
    'checkSignUp',
    '/check_signup/',
    '\tweeterapp\control\TweeterAdminController',
    'checkSignup',
    \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE
);

$router->addRoute(
    'following',
    '/following/',
    '\tweeterapp\control\TweeterController',
    'following',
    \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE
);

AbstractView::addStyleSheet("/html/style.css");
AbstractView::setAppTitle("Tweeter");

$router->setDefaultRoute('/home/');

session_start();

$router->run();

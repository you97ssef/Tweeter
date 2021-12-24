<?php

namespace tweeterapp\control;

use Exception;
use mf\router\Router;
use tweeterapp\auth\TweeterAuthentification;
use tweeterapp\view\TweeterView;

class TweeterAdminController extends \mf\control\AbstractController
{

    public function login()
    {
        $view = new TweeterView(null);

        echo $view->render("renderLogin");
    }

    public function checkLogin()
    {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $auth = new TweeterAuthentification();
        try {
            $user = $auth->loginUser($username, $password);
            $view = new TweeterView($user);
            echo $view->render("renderFollowers");
        } catch (Exception $ex) {
            Router::executeRoute("maison");
        }
    }

    public function logout()
    {
        $auth = new TweeterAuthentification();

        $auth->logout();
        Router::executeRoute("maison");
    }

    public function signup()
    {
        $view = new TweeterView(null);

        echo $view->render("renderSignup");
    }

    public function checkSignup()
    {
        $username = strip_tags($_POST["username"]);
        $fullname = strip_tags($_POST["fullname"]);
        $password = strip_tags($_POST["password"]);
        $password_verify = $_POST["password_verify"];

        if ($password === $password_verify) {
            $auth = new TweeterAuthentification();

            try {
                $auth->createUser($username, $password, $fullname);
                Router::executeRoute("maison");
            } catch (Exception $ex) {
                $view = new TweeterView(null);

                echo $view->render("renderSignup");
            }
        }
    }
}

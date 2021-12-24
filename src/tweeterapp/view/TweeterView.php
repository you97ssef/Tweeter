<?php

namespace tweeterapp\view;

use mf\router\Router;
use \tweeterapp\auth\TweeterAuthentification;
use \mf\view\AbstractView;

class TweeterView extends AbstractView
{
    public function __construct($data)
    {
        parent::__construct($data);
    }

    private function renderHeader()
    {
        $html = <<<EOT
            <h1>Mini Tweeter</h1>
            {$this->renderTopMenu()}
        EOT;

        return $html;
    }

    private function renderFooter()
    {
        return 'La super app crée par <a href="https://youssefb.netlify.app/">Youssef BAHI</a> &copy;2021';
    }

    private function renderHome()
    {
        $router = new Router();

        $html = "<h2>Latest Tweets</h2>";

        foreach ($this->data as $tweet) {
            $html .= <<<EOT
                    <div class="tweet">
                        <a href="{$router->urlFor("tweet", [["id",$tweet->id]])}">
                            <div class="tweet-text">
                                $tweet->text
                            </div>
                        </a>
                        <div class="tweet-footer">
                            <span class="tweet-timestamp">
                                $tweet->created_at
                            </span>
                            <span class="tweet-author">
                                <a href="{$router->urlFor("userTweets", [["author",$tweet->author]])}">
                                    {$tweet->tweeter->fullname}
                                </a>
                            </span>
                        </div>
                    </div>
                EOT;
        }

        return $html;
    }

    private function renderUserTweets()
    {
        $router = new Router();

        $author = $this->data;

        $html = "<h2>Tweets from {$author->fullname}</h2>";

        foreach ($author->tweets as $tweet) {
            $html .= <<<EOT
                    <div class="tweet">
                        <a href="{$router->urlFor("tweet", [["id",$tweet->id]])}">
                            <div class="tweet-text">
                                $tweet->text
                            </div>
                        </a>
                        <div class="tweet-footer">
                            <span class="tweet-timestamp">
                                $tweet->created_at
                            </span>
                            <span class="tweet-author">
                                <a href="{$router->urlFor("userTweets", [["author",$tweet->author]])}">
                                    {$tweet->tweeter->fullname}
                                </a>
                            </span>
                        </div>
                    </div>
                EOT;
        }

        return $html;
    }

    private function renderViewTweet()
    {
        $router = new Router();

        $tweet = $this->data;

        $html = <<<EOT
            <div class="tweet">
                <a href="{$router->urlFor("tweet", [["id",$tweet->id]])}">
                    <div class="tweet-text">
                        $tweet->text
                    </div>
                </a>
                <div class="tweet-footer">
                    <span class="tweet-timestamp">
                        $tweet->created_at
                    </span>
                    <span class="tweet-author">
                        <a href="{$router->urlFor("userTweets", [["author",$tweet->author]])}">
                            {$tweet->tweeter->fullname}
                        </a>
                    </span>
                </div>
                <div class="tweet-footer">
                    <hr>
                    <span class="tweet-score tweet-control">{$tweet->score}</span>
                </div>
            </div>
        EOT;

        return $html;
    }

    protected function renderPostTweet()
    {
        $router = new Router();

        $html = <<<EOT
            <form action="{$router->urlFor("sendTweet")}" method="post">
                <textarea id="tweet-form" name="text" placeholder="Enter your tweet..." maxlength="140"></textarea>
                <div>
                    <input id="send_button" type="submit" name="send" value="Send">
                </div>
            </form> 
        EOT;

        return $html;
    }

    protected function renderBody($selector)
    {
        $html = <<<EOT
            <header class="theme-backcolor1"> {$this->renderHeader()} </header>
            <section>
                <article class="theme-backcolor2"> 
                    {$this->$selector()} 
                </article>
        EOT;

        if (TweeterAuthentification::ACCESS_LEVEL_NONE !== $_SESSION['access_level']) {
            $html .= $this->renderBottomMenu();
        }

        $html .= <<<EOT
            </section>
            <footer class="theme-backcolor1"> {$this->renderFooter()} </footer>
        EOT;

        return $html;
    }

    public function renderLogin()
    {
        $router = new Router();

        $html = <<<EOT
            <form class="forms" action="{$router->urlFor("checkLogin")}" method="post">
                <input class="forms-text" type="text" name="username" placeholder="username">
                <input class="forms-text" type="password" name="password" placeholder="password">
                <button class="forms-button" name="login_button" type="submit">Login</button>
            </form>
        EOT;

        return $html;
    }

    public function renderFollowers()
    {
        $user = $this->data;
        $followers = $user->followedBy()->get();

        $router = new Router();

        $html = <<<EOT
            <h2>Currently following</h2>
            <ul id="followees">
        EOT;

        foreach ($followers as $follower) {
            $html .= <<<EOT
            <li>
                <a href="{$router->urlFor("userTweets", [["author",$follower->id]])}">
                    {$follower->fullname}
                </a>
            </li>
            EOT;
        }

        $html .= <<<EOT
            </ul>
        EOT;

        return $html;
    }

    public function renderSignup()
    {
        $router = new Router();

        $html = <<<EOT
            <form class="forms" action="{$router->urlFor('checkSignUp')}" method="post">
                <input class="forms-text" type="text" name="fullname" placeholder="full Name">
                <input class="forms-text" type="text" name="username" placeholder="username">
                <input class="forms-text" type="password" name="password" placeholder="password">
                <input class="forms-text" type="password" name="password_verify" placeholder="retype password">

                <button class="forms-button" name="login_button" type="submit">Create</button>
            </form>
        EOT;

        return $html;
    }

    private function renderBottomMenu()
    {
        $router = new Router();

        $html = <<<EOT
            <nav id="menu" class="theme-backcolor1"> 
                <div id="nav-menu">
                    <div class="button theme-backcolor2">
                        <a href="{$router->urlFor("postTweet")}">New</a>
                    </div>
                </div> 
            </nav>
        EOT;

        return $html;
    }

    private function renderTopMenu()
    {
        $router = new Router();

        $htmlState = "";

        $app_root = (new \mf\utils\HttpRequest())->root;

        if (TweeterAuthentification::ACCESS_LEVEL_NONE === $_SESSION['access_level']) {
            $htmlState = <<<EOT
                <a class="tweet-control" href="{$router->urlFor("login")}">
                    <img alt="login" src="$app_root/html/login.png">
                </a>
                <a class="tweet-control" href="{$router->urlFor("signup")}">
                    <img alt="signup" src="$app_root/html/signup.png">
                </a>
            EOT;
        } else {
            $htmlState = <<<EOT
                <a class="tweet-control" href="{$router->urlFor("following")}">
                    <img alt="following" src="$app_root/html/following.png">
                </a>
                <a class="tweet-control" href="{$router->urlFor("logout")}">
                    <img alt="logout" src="$app_root/html/logout.png">
                </a>
            EOT;
        }


        $html = <<<EOT
        <nav id="navbar">
            <a class="tweet-control" href="{$router->urlFor("maison")}">
                <img alt="home" src="$app_root/html/home.png">
            </a>
            $htmlState
        </nav>
        EOT;

        return $html;
    }
}

<?php

namespace tweeterapp\control;

use tweeterapp\model\Tweet;
use tweeterapp\model\User;
use tweeterapp\view\TweeterView;

class TweeterController extends \mf\control\AbstractController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function viewHome()
    {
        $tweets = Tweet::all();

        $view = new TweeterView($tweets);

        echo $view->render("renderHome");
    }

    public function viewTweet()
    {
        $tweet_id = $_GET["id"];

        $tweet = Tweet::find($tweet_id);

        $view = new TweeterView($tweet);

        echo $view->render("renderViewTweet");
    }

    public function viewUserTweets()
    {
        $auteur_id = $_GET["author"];

        $author = User::find($auteur_id);

        $view = new TweeterView($author);

        echo $view->render("renderUserTweets");
    }

    public function viewTweetPost()
    {
        $view = new TweeterView(null);

        echo $view->render("renderPostTweet");
    }

    public function sendTweet()
    {
        $username = $_SESSION['user_login'];
        $user = User::where("username", "=", $username)->first();

        $tweet = new Tweet();
        $tweet->text = strip_tags($_POST["text"]);
        $tweet->author = $user->id;
        $tweet->score = 0;

        $tweet->save();

        $this->viewHome();
    }

    public function following()
    {
        $username = $_SESSION['user_login'];

        $user = User::where("username", "=", $username)->first();

        $view = new TweeterView($user);

        echo $view->render("renderFollowers");
    }
}

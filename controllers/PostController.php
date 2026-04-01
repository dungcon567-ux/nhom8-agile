<?php
require_once 'config/database.php';
require_once 'models/Post.php';

class PostController {
    private $postModel;

    public function __construct() {
        $db = (new Database())->connect();
        $this->postModel = new Post($db);
    }

    public function index() {
        $posts = $this->postModel->getAllPosts();
        require_once 'views/layouts/head.php';
        require_once 'views/posts/index.php';
    }
}
?>
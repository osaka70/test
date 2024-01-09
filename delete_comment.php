<?php
// delete_comment.php

// セッションの開始
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// ログインしていない場合はログインページにリダイレクト
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// db.phpをインクルード
require_once 'db.php';

// コメントIDを取得
if (isset($_GET['comment_id'])) {
    $comment_id = $_GET['comment_id'];

    // コメント情報を取得
    $stmt_comment = $pdo->prepare("SELECT * FROM comments WHERE comment_id = :comment_id");
    $stmt_comment->bindParam(':comment_id', $comment_id);
    $stmt_comment->execute();
    $comment = $stmt_comment->fetch(PDO::FETCH_ASSOC);

    // ログインしているユーザーがコメントの投稿者でなければエラーとする
    if ($_SESSION['user_id'] !== $comment['user_id']) {
        die("コメントの投稿者以外は削除できません。");
    }

    // コメントを削除
    $stmt_delete_comment = $pdo->prepare("DELETE FROM comments WHERE comment_id = :comment_id");
    $stmt_delete_comment->bindParam(':comment_id', $comment_id);
    $stmt_delete_comment->execute();

    // 削除が成功したら、該当の投稿の詳細ページにリダイレクト
    header("Location: post.php?post_id=" . $comment['post_id']);
    exit();
} else {
    // コメントIDが指定されていない場合はエラーとするか、リダイレクトするなどの処理を行う
    die("コメントIDが指定されていません。");
}

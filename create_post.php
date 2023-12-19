<?php
// create_post.php

// データベース接続
require_once 'db.php';

// セッションの開始（必要に応じて）
session_start();

// ログイン状態の確認
if (!isset($_SESSION['user_id'])) {
    // ログインしていない場合、ログインページにリダイレクト
    header("Location: login.php");
    exit();
}

// POSTリクエストがあるかどうかを確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームからのデータを取得
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id']; // セッションからユーザーIDを取得

    try {
        // データベースに新しい投稿を追加
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content) VALUES (:user_id, :title, :content)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->execute();

        // 投稿が成功したら、ホームページにリダイレクト
        header("Location: index 1.php");
        exit();
    } catch (PDOException $e) {
        die("エラー: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <!-- 必要に応じてスタイルシートのリンクを追加 -->
</head>
<body>
    <h1>Create a New Post</h1>

    <!-- 投稿フォーム -->
    <form method="post" action="create_post.php">
        <label for="title">Title:</label>
        <input type="text" name="title" required>

        <label for="content">Content:</label>
        <textarea name="content" rows="4" required></textarea>

        <button type="submit">Create Post</button>
    </form>

    <!-- 必要に応じてフッターや他のコンテンツを追加 -->

</body>
</html>
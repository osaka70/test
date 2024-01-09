<?php
// delete_post.php

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

// 投稿IDを取得
if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // 投稿情報を取得
    $stmt_post = $pdo->prepare("SELECT * FROM posts WHERE post_id = :post_id");
    $stmt_post->bindParam(':post_id', $post_id);
    $stmt_post->execute();
    $post = $stmt_post->fetch(PDO::FETCH_ASSOC);

    // ログインしているユーザーが投稿者でなければエラーとする
    if ($_SESSION['user_id'] !== $post['user_id']) {
        die("投稿者以外は削除できません。");
    }

    // POSTリクエストを処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // データベースから投稿を削除
        $stmt_delete_post = $pdo->prepare("DELETE FROM posts WHERE post_id = :post_id");
        $stmt_delete_post->bindParam(':post_id', $post_id);
        $stmt_delete_post->execute();

        // 関連するコメントも削除
        $stmt_delete_comments = $pdo->prepare("DELETE FROM comments WHERE post_id = :post_id");
        $stmt_delete_comments->bindParam(':post_id', $post_id);
        $stmt_delete_comments->execute();

        // 削除が成功したら、投稿一覧ページにリダイレクト
        header("Location: index.php");
        exit();
    }
} else {
    // 投稿IDが指定されていない場合はエラーとするか、リダイレクトするなどの処理を行う
    die("投稿IDが指定されていません。");
}

// HTMLヘッダー部分をインクルード
include 'header.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Post</title>
    <link rel="stylesheet" href="style.css"> <!-- スタイルシートのリンクを追加 -->
</head>
<body>

<div class="container">
    <h2>Delete Post</h2>
    <p>本当に投稿を削除しますか？</p>

    <form action="delete_post.php?post_id=<?php echo $post_id; ?>" method="post">
        <button type="submit">削除する</button>
        <a href="post.php?post_id=<?php echo $post_id; ?>">キャンセル</a>
    </form>
</div>

<?php include 'footer.php'; ?>

</body>
</html>

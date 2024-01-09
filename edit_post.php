<?php
// edit_post.php

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
        die("投稿者以外は編集できません。");
    }

    // POSTリクエストを処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_title = $_POST['new_title'];
        $new_content = $_POST['new_content'];

        // データベースの投稿を更新
        $stmt_update_post = $pdo->prepare("UPDATE posts SET title = :title, content = :content WHERE post_id = :post_id");
        $stmt_update_post->bindParam(':title', $new_title);
        $stmt_update_post->bindParam(':content', $new_content);
        $stmt_update_post->bindParam(':post_id', $post_id);
        $stmt_update_post->execute();

        // 更新が成功したら、詳細ページにリダイレクト
        header("Location: post.php?post_id=$post_id");
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
    <title>Edit Post</title>
    <link rel="stylesheet" href="style.css"> <!-- スタイルシートのリンクを追加 -->
</head>
<body>

<div class="container">
    <h2>Edit Post</h2>
    <form action="edit_post.php?post_id=<?php echo $post_id; ?>" method="post">
        <label for="new_title">New Title:</label>
        <input type="text" name="new_title" id="new_title" value="<?php echo $post['title']; ?>" required>

        <label for="new_content">New Content:</label>
        <textarea name="new_content" id="new_content" rows="4" required><?php echo $post['content']; ?></textarea>

        <button type="submit">Update Post</button>
    </form>
</div>

<?php include 'footer.php'; ?>

</body>
</html>

<?php
// edit_comment.php

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
        die("コメントの投稿者以外は編集できません。");
    }

    // POSTリクエストを処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // フォームから送信されたデータを取得
        $edited_content = $_POST['edited_content'];

        // コメントを更新
        $stmt_update_comment = $pdo->prepare("UPDATE comments SET content = :content WHERE comment_id = :comment_id");
        $stmt_update_comment->bindParam(':content', $edited_content);
        $stmt_update_comment->bindParam(':comment_id', $comment_id);
        $stmt_update_comment->execute();

        // 更新が成功したら、該当の投稿の詳細ページにリダイレクト
        header("Location: post.php?post_id=" . $comment['post_id']);
        exit();
    }
} else {
    // コメントIDが指定されていない場合はエラーとするか、リダイレクトするなどの処理を行う
    die("コメントIDが指定されていません。");
}

// HTMLヘッダー部分をインクルード
include 'header.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Comment</title>
    <link rel="stylesheet" href="style.css"> <!-- スタイルシートのリンクを追加 -->
</head>
<body>

<div class="container">
    <h2>Edit Comment</h2>

    <form action="edit_comment.php?comment_id=<?php echo $comment_id; ?>" method="post">
        <label for="edited_content">Edit Comment:</label>
        <textarea name="edited_content" id="edited_content" rows="4" required><?php echo $comment['content']; ?></textarea>
        <button type="submit">Update Comment</button>
    </form>
</div>

<?php include 'footer.php'; ?>

</body>
</html>

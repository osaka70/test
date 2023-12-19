<?php
// create_comment.php

// セッションの開始
session_start();

// ログインしていない場合はログインページにリダイレクト
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// データベース接続情報と関数を含むdb.phpをインクルード
include('db.php');

// POSTリクエストを処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームから送信されたデータを取得
    $post_id = $_POST['post_id'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id']; // セッションからユーザーIDを取得

    try {
        // コメントをデータベースに挿入
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)");
        $stmt->bindParam(':post_id', $post_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':content', $content);
        $stmt->execute();

        // コメントが正常に挿入されたら、投稿の詳細ページにリダイレクト
        header("Location: post.php?post_id=$post_id");
        exit();
    } catch (PDOException $e) {
        die("エラー: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Comment</title>
    <link rel="stylesheet" href="style.css"> <!-- スタイルシートのリンクを追加 -->
</head>
<body>

<?php include('header.php'); ?> <!-- ヘッダーをインクルード -->

<div class="container">
    <h2>Create Comment</h2>
    <form action="create_comment.php" method="post">
        <input type="hidden" name="post_id" value="<?php echo $_GET['post_id']; ?>">
        <label for="content">Comment:</label>
        <textarea name="content" id="content" rows="4" required></textarea>
        <button type="submit">Submit Comment</button>
    </form>
</div>

<?php include('footer.php'); ?> <!-- フッターをインクルード -->

</body>
</html>


<?php
// db.php ファイルをインクルード
require_once 'db.php';

// セッションの開始（必要に応じて）
session_start();

// ログイン状態の確認
if (!isset($_SESSION['user_id'])) {
    $login_name = "";
    $login_status = "<form action='login.php'>
    <button type='submit'>ログイン</button>
</form>";
} else {
$login_name = $_SESSION['username'];
    $login_status = "<form action='logout.php'>
    <button type='submit'>ログアウト</button>
</form>";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
    <title>掲示板</title>
</head>
<body>
    <header>
        <h1>掲示板</h1>
        <?php
        echo "<h2>" . $login_name . "</h2>";
        ?>
        <form action="create_post.php">
             <button type="submit">新規投稿</button>
        </form>
        <?php
        echo $login_status;
        ?>
        <form action="register1.php">
             <button type="submit">新規登録</button>
        </form>
        <!-- ナビゲーションメニューなどの追加 -->
    </header>
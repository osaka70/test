<?php
// db.phpをインクルード
require_once('db.php');

// POSTリクエストを処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームから送信されたデータを取得
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // パスワードのハッシュ化
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // ユーザーをデータベースに追加
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // 登録成功のメッセージなどを表示することができます
        echo "ユーザーが正常に登録されました。";
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
    <title>新規登録</title>
</head>
<body>
    <h2>新規登録</h2>
    <form method="post" action="register.php">
        <label for="username">ユーザー名:</label>
        <input type="text" name="username" required><br>

        <label for="password">パスワード:</label>
        <input type="password" name="password" required><br>

        <label for="email">メールアドレス:</label>
        <input type="email" name="email" required><br>

        <input type="submit" value="登録">
    </form>
</body>
</html>
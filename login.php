
<?php
// db.php からデータベース接続のコードを読み込む
require("header.php");
require_once("db.php");

// セッションの開始
@session_start();

// POST メソッドで送信された場合のみ処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームから受け取ったユーザー名とパスワード
    $username = $_POST["username"];
    $password = $_POST["password"];

    // 入力値のバリデーション（例: 空白チェック）
    if (empty($username) || empty($password)) {
        $error = "ユーザー名とパスワードを入力してください。";
    } else {
        // ユーザーが存在するか確認
        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $pdo->prepare($query);
        $statement->bindParam(":username", $username, PDO::PARAM_STR);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            // ログイン成功
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["username"] = $user["username"];
            header("Location: index 1.php"); // ログイン後のリダイレクト先を指定
            exit();
        } else {
            // ログイン失敗
            $error = "ユーザー名またはパスワードが正しくありません。";
        }
    }
}

// HTML部分（ログインフォームなど）
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link rel="stylesheet" href="style.css"> <!-- スタイルシートのリンク -->
</head>
<body>
    <h2>ログイン</h2>

    
    <?php
    // エラーメッセージがある場合は表示
    if (isset($error)) {
        echo "<p class='error'>$error</p>";
    }
    ?>

    <form method="post" action="login.php">
        <label for="username">ユーザー名:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">パスワード:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">ログイン</button>
    </form>
</body>
</html>
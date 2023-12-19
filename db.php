<?php
// データベース接続情報
$host = 'localhost'; // データベースホスト名
$db_name = 'bulletin_board'; // データベース名
$username = 'root'; // データベースユーザー名
$password = ''; // データベースパスワード

// PDOオブジェクトの作成
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    // エラーモードを例外モードに設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベースに接続できません: " . $e->getMessage());
}

// ユーザーのログイン処理
function loginUser($username, $password) {
    global $pdo;

    try {
        // ユーザーの存在確認
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // ログイン成功
            return $user;
        } else {
            // ログイン失敗
            return false;
        }
    } catch (PDOException $e) {
        die("エラー: " . $e->getMessage());
    }
}
?>

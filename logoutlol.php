<?php require 'header.php'; ?>
<?php @session_start();
if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
    echo 'ログアウトしました。';
    echo header("Refresh:1");
} else {
    echo 'ログアウトしています。';
}
?>

<?php require 'main.php'; ?>
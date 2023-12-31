
<?php require 'header.php'; ?>

<?php


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

    // 投稿に対するコメントを取得
    $stmt_comments = $pdo->prepare("SELECT * FROM comments WHERE post_id = :post_id");
    $stmt_comments->bindParam(':post_id', $post_id);
    $stmt_comments->execute();
    $comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);
} else {
    // 投稿IDが指定されていない場合はエラーとするか、リダイレクトするなどの処理を行う
    die("投稿IDが指定されていません。");
}

// HTMLヘッダー部分をインクルード
// include 'header.php';
?>

<!-- 投稿の詳細表示 -->
<div>
    <h2><?php echo $post['title']; ?></h2>
    <p><?php echo $post['content']; ?></p>
    <p>投稿日時: <?php echo $post['timestamp']; ?></p>
</div>

<!-- コメントの表示 -->
<div>
    <h3>コメント</h3>
    <?php if (count($comments) > 0) : ?>
        <ul>
            <?php foreach ($comments as $comment) : ?>
                <li>
                    <p><?php echo $comment['content']; ?></p>
                    <p>投稿日時: <?php echo $comment['timestamp']; ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>まだコメントはありません。</p>
    <?php endif; ?>
</div>
<!-- 
<form method="post" action="create_comment.php">
        <label for="content">Content:</label>
        <textarea name="content" id="post_id" value=".<?php $post_id ?>."rows="4" required></textarea>
        
        <button type="submit">Create Comment</button>
</form> -->
<form action="create_comment.php" method="post">
        <input type="hidden" name="post_id" value="<?php echo $_GET['post_id']; ?>">
        <label for="content">Comment:</label>
        <textarea name="content" id="content" rows="4" required></textarea>
        <button type="submit">Create Comment</button>
</form>

<!-- 投稿の編集などのリンク -->
<div>
    <!-- 編集リンクなどを追加 -->
    <!-- 例：<a href="edit_post.php?post_id=<?php echo $post['post_id']; ?>">投稿を編集する</a> -->
</div>

<?php
// HTMLフッター部分をインクルード
// include 'footer.php';

?>

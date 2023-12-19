<?php
// ホームページのコンテンツを取得
function getHomePageContent() {
    global $pdo;

    try {
        // 最新の投稿を取得
        $stmt = $pdo->query("SELECT * FROM posts ORDER BY timestamp DESC LIMIT 10");
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 投稿を表示
        foreach ($posts as $post) {
            echo '<div class="post">';
            echo '<h2>' . $post['title'] . '</h2>';
            echo '<p>' . $post['content'] . '</p>';
            echo '<p>投稿者: ' . $post['user_id'] . '</p>';
            echo '<p>投稿日時: ' . $post['timestamp'] . '</p>';
            echo '<a href="post.php?post_id=' . $post['post_id'] . '">詳細を見る</a>';
            echo '</div>';
        }
    } catch (PDOException $e) {
        die("エラー: " . $e->getMessage());
    }
}
?>



    <main>
        <section>
            <h2>最新の投稿</h2>
            <?php
            // ホームページのコンテンツを表示
            getHomePageContent();
            ?>
        </section>
    </main>

    <footer>
        <!-- フッターの内容 -->
    </footer>
</body>
</html>

<?php
// 関数ファイルを読み込む
require_once __DIR__ . '/../common/functions.php';

// セッション開始
session_start();

$group = '';

if (isset($_GET['group_id'])) {
    $group = find_user_by_id($_GET['group_id']);
} elseif (isset($_GET['err'])) {
    $err = $_GET['err'];
}

?>
<!DOCTYPE html>
<?php include_once __DIR__ . '/../common/_head.html' ?>

<body>
    <?php include_once __DIR__ . '/../common/_header.php' ?>

    <main class="wrapper">

        <?php if (isset($err)) : ?>
            エラーです。
        <?php else : ?>
                <div class="content">
            <p>投稿完了</p>
            <p>ありがとうございました。</p>
            </div>
        <?php endif; ?>


    </main>

    <?php include_once __DIR__ . '/../common/_footer.html' ?>
    <script src="../js/app.js"></script>
</body>

</html>

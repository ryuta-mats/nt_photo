<?php
// 関数ファイルを読み込む
require_once __DIR__ . '/../common/functions.php';

// セッション開始
session_start();

$group = '';

if (isset($_GET['group_id'])) {
    $group = find_group_by_id($_GET['group_id']);
} elseif (isset($_GET['err'])) {
    $err = $_GET['err'];
}

?>
<!DOCTYPE html>
<?php include_once __DIR__ . '/../common/_head.html' ?>

<body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-sm-3 col-md-3 mr-0" href="#"><i class="fa-regular fa-snowflake"></i>NRTA Photo App</a>
    </nav>

    <main class="wrapper mt-4">

        <?php if (isset($err)) : ?>
            エラーです。
        <?php else : ?>
            <section class="jumbotron mt-4">
                <p>投稿完了</p>
                <p>ありがとうございました。</p>
            </section>
        <?php endif; ?>


    </main>

    <?php include_once __DIR__ . '/../common/_footer.html' ?>
    <script src="../js/app.js"></script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</body>

</html>

<?php
// 関数ファイルを読み込む
require_once __DIR__ . '/../common/functions.php';

//すべての団体名を連想配列にする
$groups = find_group_all();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['prosess']) {
        case 0:
        //団体の情報の呼び出し
            //対象の団体の情報を連想配列にする
            $group = find_group_by_id($_GET['groupid']);

            //対象の団体のpfoto情報を連想入れるにする
            $photos = find_photo_by_id($_GET['groupid']);
            break;

        case 1:
            //団体情報の登録
            //対象の団体の情報を連想配列にする
            //対象の団体のpfoto情報を連想入れるにする
            break;

        case 2:
            break;

        case 2:
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<?php include_once __DIR__ . '/../common/_head.html' ?>

<body>
<header class="page_header wrapper">
    <h1>
        <div class="logo">
            <p>
            <?php if(!empty($group)) : ?>
                <?= $group['name'] ?>様</p>
            <?php endif ; ?>
            <p>
                フォトコンテストアプリ管理画面
            </p>
        </div>
    </h1>
</header>
    <pre><?= var_dump($_POST) ?></pre>


    <main class="wrapper idnex_main">
        <div class="left_content">
            <h2 class="login_title">団体名</h2>
            <ul class="group_wrap">
                <li><a href="index.php?group_id=1">ニセコ高校</a></li>
            </tl>
            <form class="group_form" action="index.php?" method="post">
                <input type="hidden" name="prosess" value="0">
                <input type="date" name="date" placeholder="実施日">
                <input type="text" name="group_name" placeholder="団体名">
                <input type="submit" value="登録">

            </form>


        </div>

        <div class="right_content">
                    <h2 class="login_title">写真</h2>
    <pre><?= var_dump($groups) ?></pre>

            <div class="grid">
                <div class="grid_item">
                    <a href="show.php"><img src="https://picsum.photos/200/300" height="200"></a>
                </div>
                <div class="grid_item">
                    <a href="show.php"><img src="https://picsum.photos/200/300" height="300"></a>
                </div>
                <div class="grid_item">
                    <a href="show.php"><img src="https://picsum.photos/200/300" height="250"></a>
                </div>
                <div class="grid_item">
                    <a href="show.php"><img src="https://picsum.photos/200/300" height="300"></a>
                </div>
                <div class="grid_item">
                    <a href="show.php"><img src="https://picsum.photos/200/300" height="250"></a>
                </div>
                <div class="grid_item">
                    <a href="show.php"><img src="https://picsum.photos/200/300" height="200"></a>
                </div>
                <div class="grid_item">
                    <a href="show.php"><img src="https://picsum.photos/200/300" height="250"></a>
                </div>
                <div class="grid_item">
                    <a href="show.php"><img src="https://picsum.photos/200/300" height="300"></a>
                </div>
                <div class="grid_item">
                    <a href="show.php"><img src="https://picsum.photos/200/300" height="350"></a>
                </div>
                <div class="grid_item">
                    <a href="show.php"><img src="https://picsum.photos/200/300" height="300"></a>
                </div>
                <div class="grid_item">
                    <a href="show.php"><img src="https://picsum.photos/200/300" height="200"></a>
                </div>
            </div>

        </div>
    </main>

    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>

</body>

</html>

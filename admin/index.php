<?php
// 関数ファイルを読み込む
require_once __DIR__ . '/../common/functions.php';

//すべての団体名を連想配列にする
$groups = find_group_all();
$group = [];
$photos = [];
$c_date = '';
$c_group_name = '';
$e_date = '';
$e_group_name = '';

if (isset($_GET['group_id'])) {
    //対象の団体の情報を連想配列にする
    $group = find_group_by_id($_GET['group_id']);

    //対象の団体のpfoto情報を連想入れるにする
    $photos = find_photo_by_id($_GET['group_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['prosess']) {
        case 0: //団体の情報の呼び出し
            break;

        case 1: //団体情報の登録
            //postの内容を配列にいれる
            $c_date = filter_input(INPUT_POST, 'c_date');
            $c_group_name = filter_input(INPUT_POST, 'c_group_name');
            $c_description = filter_input(INPUT_POST, 'c_description');

            //バリデーションを行う
            $c_errors = group_create_valodate($c_date, $c_group_name, $c_description);
            //DBにinsertする
            if(empty($c_errors)){
            //リダイレクトする
            }

            break;

        case 2: //団体情報の変更
            //postの内容を配列にいれる
            $e_date = filter_input(INPUT_POST, 'e_date');
            $e_group_name = filter_input(INPUT_POST, 'e_group_name');
            //バリデーションを行う
            //DBをuodateする
            //リダイレクトする
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
                    フォトコンテストアプリ管理画面
                </p>
            </div>
        </h1>
    </header>
    <pre><?= var_dump($groups) ?></pre>


    <main class="wrapper idnex_main">
        <div class="left_content">
            <h2 class="login_title">団体リスト</h2>
            <ul class="group_wrap">
                <?php foreach ($groups as $group) : ?>
                    <li><a href="index.php?group_id=<?= h($group['id']) ?>"><?= h($group['name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
            新規登録
            <form class="group_form" action="index.php" method="post">
                <input class="input_item" type="hidden" name="prosess" value="1">
                <label for="c_date">
                    <input class="input_item" id="c_date" type="date" name="c_date" placeholder="実施日">
                </label>
                <label for="c_group_name">
                    <input class="input_item" id="c_group_name" type="text" name="c_group_name" placeholder="団体名">
                </label>
                    <label for="c_description">
                        <textarea class="input_item" name="c_description" id="c_description" rows="5" placeholder="投稿フォームの説明文"></textarea>
                    </label>
                <input type="submit" value="登録" class="upload_submit">
            </form>


        </div>

        <div class="right_content">
            <h2 class="login_title">団体情報</h2>
            <pre><?= var_dump($group) ?></pre>
            <form class="group_form" action="index.php" method="post">
                <input class="input_item" type="hidden" name="prosess" value="2">
                <div class="input_wrap">
                    <label for="e_date">実施日
                        <input class="input_item" id="e_date" type="date" name="e_date" placeholder="実施日" value="<?php !empty($group) && print h($group['day']) ?>">
                    </label>
                    <label for="e_group_name">団体名
                        <input class="input_item" id="e_group_name" type="text" name="e_group_name" placeholder="団体名" value="<?php !empty($group) && print h($group['name']) ?>">
                    </label>
                    <label for="e_description">説明
                        <textarea class="input_item" name="e_description" id="e_description" rows="5" placeholder="投稿フォームの説明文"><?php !empty($group) && print h($group['description']); ?></textarea>
                    </label>
                </div>
                <input type="submit" value="変更" class="upload_submit">
            </form>
            <?php if (!empty($group)) : ?>
                <a href="../photos/upload.php?group_id=<?= h($group['id']) ?>">フォーム</a>
            <?php endif; ?>
            <h2 class="login_title">写真</h2>

            <div class="grid">
                <?php foreach ($photos as $photo) : ?>
                    <div class="grid_item">
                        <img src="../images/<?= h($photo['image']) ?>" alt="<?= h($photo['team_name']) ?>">
                        <p><?= h($photo['name']) ?></p>
                        <p><?= h($photo['team_name']) ?></p>
                        <p><?= h($photo['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </main>

</body>

</html>

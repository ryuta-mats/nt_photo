<?php
// 関数ファイルを読み込む
require_once __DIR__ . '/../common/functions.php';

//すべての団体名を連想配列にする
$groups = find_group_all();
$group = [];
$photos = [];
$c_date = '';
$c_group_name = '';
$c_description = '';
$e_date = '';
$e_group_name = '';
$e_description = '';

if (!empty($_GET['group_id'])) {
    //対象の団体の情報を連想配列にする
    $e_group = find_group_by_id($_GET['group_id']);

    //対象の団体のpfoto情報を連想入れるにする
    $photos = find_photo_by_id($_GET['group_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['prosess']) {
        case 0: //
            break;

        case 1: //団体情報の登録
            //postの内容を配列にいれる
            $c_date = filter_input(INPUT_POST, 'c_date');
            $c_group_name = filter_input(INPUT_POST, 'c_group_name');
            $c_description = filter_input(INPUT_POST, 'c_description');

            //バリデーションを行う
            $c_errors = group_insert_validate($c_date, $c_group_name, $c_description);
            if (empty($c_errors)) {
                //DBにinsertする
                $id = insert_group($c_date, $c_group_name, $c_description);
                //echo var_dump($id);
                if ($id) {
                    //リダイレクトする
                    header('Location: index.php?group_id=' . $id);
                    exit;
                }
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

        case 3: //ダウンロード
            $chk = filter_input(INPUT_POST, 'check', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
            if (!empty($chk)) {
                // Zip ファイル名
                $fileName = "photo.zip";
                // ファイルディレクトリ
                $dir =  __DIR__ . '/../images';
                // Zip ファイルパス
                $zipPath = $dir . "/" . $fileName;
                // インスタンス作成
                $zip = new ZipArchive();
                // Zip ファイルをオープン
                $res = $zip->open($zipPath, ZipArchive::CREATE);

                // Zip ファイルのオープンに成功した場合
                if ($res === true) {
                    foreach ($chk as $value) {
                        $newname = str_replace($dir . "/", "", $value);
                        // 圧縮するファイルを追加
                        $zip->addFile($dir . '/' . $value, $newname);
                    }

                    // Zip ファイルをクローズ
                    $zip->close();
                    mb_http_output("pass");
                    header("Content-Type: application/zip");
                    header("Content-Transfer-Encoding: Binary");
                    header("Content-Length: " . filesize($zipPath));
                    header('Content-Disposition: attachment; filename*=UTF-8\'\'' . $fileName);
                    ob_end_clean();
                    readfile($zipPath);
                    // zipを削除
                    unlink($zipPath);
                }
            }

            break;
    }
}


?>
<!DOCTYPE html>
<html lang="ja">
<?php include_once __DIR__ . '/../common/_head.html' ?>

<body>
    <header class="index_header wrapper">
        <h1>
            フォトコンテストアプリ管理画面
        </h1>
    </header>

    <main class="wrapper idnex_main">
        <div class="left_content">
            <h2 class="login_title">団体リスト</h2>
            <ul class="group_wrap">
                <?php foreach ($groups as $c_group) : ?>
                    <li><a href="index.php?group_id=<?= h($c_group['id']) ?>"><?= h($c_group['name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
            <?php if (!empty($c_errors)) : ?>
                <ul class="errors">
                    <?php foreach ($c_errors as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form class="group_form" action="index.php" method="post">
                <input class="input_item" type="hidden" name="prosess" value="1">
                <label for="c_date">
                    <input class="input_item" id="c_date" type="date" name="c_date" placeholder="実施日" value="<?= h($c_date) ?>">
                </label>
                <label for="c_group_name">
                    <input class="input_item" id="c_group_name" type="text" name="c_group_name" placeholder="団体名" value="<?= h($c_group_name) ?>">
                </label>
                <label for="c_description">
                    <textarea class="input_item" name="c_description" id="c_description" rows="5" placeholder="投稿フォームの説明文"><?= h($c_description) ?></textarea>
                </label>
                <input type="submit" value="新規登録" class="upload_submit">
            </form>


        </div>

        <div class="right_content">
            <h2 class="login_title">団体情報</h2>
            <form class="group_form" action="index.php" method="post">
                <input class="input_item" type="hidden" name="prosess" value="2">
                <div class="input_wrap">
                    <label for="e_date">実施日
                        <input class="input_item" id="e_date" type="date" name="e_date" placeholder="実施日" value="<?php !empty($e_group) && print h($e_group['day']) ?>">
                    </label>
                    <label for="e_group_name">団体名
                        <input class="input_item" id="e_group_name" type="text" name="e_group_name" placeholder="団体名" value="<?php !empty($e_group) && print h($e_group['name']) ?>">
                    </label>
                    <label for="e_description">説明
                        <textarea class="input_item" name="e_description" id="e_description" rows="5" placeholder="投稿フォームの説明文"><?php !empty($e_group) && print h($e_group['description']); ?></textarea>
                    </label>
                </div>
                <input type="submit" value="変更" class="upload_submit">
            </form>
            <?php if (!empty($e_group)) : ?>
                <a class="form_btn" href="../photos/upload.php?group_id=<?= h($e_group['id']) ?>">フォーム</a>
            <?php endif; ?>

            <div class="photo">
                <h2 class="login_title">写真</h2>
                <form name="photo_form" action="index.php" method="post">
                    <input class="input_item" type="hidden" name="prosess" value="3">
                    <input type="submit" value="ダウンロード" class="upload_submit">
                    <p>
                        <input class="tgl_btn" type="button" value="全てチェック" onclick="allcheck(true);">
                        <input class="tgl_btn" value="全てチェックを外す" onclick="allcheck(false);">
                    </p>

                    <div class="grid">
                        <?php foreach ($photos as $photo) : ?>
                            <div class="grid_item">
                                <p>
                                    <label><input type="checkbox" name="check[]" value="<?= $photo['image'] ?>" />
                                        <?= $photo['image'] ?></label>
                                </p>
                                <img src="../images/<?= h($photo['image']) ?>" alt="<?= h($photo['team_name']) ?>">
                                <p><?= h($photo['team_name']) ?></p>
                                <p><?= h($photo['description']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>

        </div>
    </main>
    <script>
        function allcheck(tf) {
            var ElementsCount = document.photo_form.elements.length; // チェックボックスの数
            for (i = 0; i < ElementsCount; i++) {
                document.photo_form.elements[i].checked = tf; // ON・OFFを切り替え
            }
        }
    </script>
</body>

</html>

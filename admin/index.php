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
            $group_id = filter_input(INPUT_POST, 'group_id');
            $e_date = filter_input(INPUT_POST, 'e_date');
            $e_group_name = filter_input(INPUT_POST, 'e_group_name');
            $e_description = filter_input(INPUT_POST, 'e_description');
            //バリデーションを行う
            $e_errors = group_update_validate($e_date, $e_group_name, $e_description);

            if (empty($e_errors)) {

                //DBをuodateする
                if (update_group($group_id, $e_date, $e_group_name, $e_description)) {
                    //リダイレクトする
                    header('Location: index.php?group_id=' . $group_id);
                    exit;
                }
            }
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
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-sm-2 col-md-3 mr-0" href="#"><i class="fa-regular fa-snowflake"></i>NRTA Photo App Admin</a>

        <ul class="navbar-nav px-3">
            <div class="row">
                <li class="nav-item text-nowrap mr-4">
                    <a class="nav-link" href="#"></a>
                </li>
                <li class="nav-item text-nowrap mr-4">
                    <a class="nav-link" href="#"></a>
                </li>
            </div>
        </ul>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="home"></span>
                                Group List<span class="sr-only">(current)</span>
                            </a>
                            <ul class="group_wrap ml-2">
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
                            <form class="form group_form" action="index.php" method="post">
                                <input class="input_item" type="hidden" name="prosess" value="1">
                                <input class="form-control form-control-sm col-10 m-1" id="c_date" type="date" name="c_date" placeholder="実施日" value="<?= h($c_date) ?>">
                                <input class="form-control form-control-sm col-10 m-1" id="c_group_name" type="text" name="c_group_name" placeholder="団体名" value="<?= h($c_group_name) ?>">
                                <textarea class="form-control form-control-sm col-10 m-1" name="c_description" id="c_description" rows="5" placeholder="投稿フォームの説明文"><?= h($c_description) ?></textarea>
                                <input type="submit" value="New Group" class="btn btn-sm btn-outline-secondary col-10">
                            </form>



                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="layers"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-9 px-4">


                <div class="right_content">
                    <h2 class="login_title mt-3">Group Infomation</h2>
                    <form class="form" action="index.php?group_id=<?= h($e_group['id']) ?>" method="post">
                        <p>
                            <input type="submit" value="Edit" class="btn btn-sm btn-secondary">
                            <?php if (!empty($e_group)) : ?>
                                <a class="btn btn-sm btn-outline-secondary" href="../photos/upload.php?group_id=<?= h($e_group['id']) ?>" target="_blank" rel="noopener noreferrer">Create Form<i class="fa-solid fa-arrow-up-right-from-square ml-1"></i></a>
                            <?php endif; ?>
                        </p>
                        <input class="input_item" type="hidden" name="prosess" value="2">
                        <input class="input_item" type="hidden" name="group_id" value="<?= h($e_group['id']) ?>">
                        <div class="form-row">
                            <label class="col-3" for="e_date">実施日
                                <input class="form-control form-control-sm" id="e_date" type="date" name="e_date" placeholder="実施日" value="<?php !empty($e_group) && print h($e_group['day']) ?>">
                            </label>
                            <label class="col-3" for="e_group_name">団体名
                                <input class="form-control form-control-sm" id="e_group_name" type="text" name="e_group_name" placeholder="団体名" value="<?php !empty($e_group) && print h($e_group['name']) ?>">
                            </label>

                            <label class="col-6" for="e_description">説明
                                <textarea class="form-control" name="e_description" id="e_description" rows="5" placeholder="投稿フォームの説明文"><?php !empty($e_group) && print h($e_group['description']); ?></textarea>
                            </label>
                        </div>
                    </form>

                    <div class="photo">
                        <h2 class="login_title mt-3">Photo List</h2>
                        <form name="photo_form" action="index.php?group_id=<?= h($e_group['id']) ?>" method="post">
                            <input class="input_item" type="hidden" name="prosess" value="3">
                            <input class="input_item" type="hidden" name="group_id" value="<?= h($e_group['id']) ?>">
                            <input type="submit" value="Download" class="btn btn-secondary">
                            <div class="btn-group btn-group-toggle ml-2" data-toggle="buttons">
                                <input type="button" class="btn btn-sm btn-outline-secondary" value="All Check" onclick="allcheck(true);">
                                <input type="button" class="btn btn-sm btn-outline-secondary" value="All Check Out" onclick="allcheck(false);">
                            </div>
                            </p>

                            <div class="card-deck">
                                <div class="row">
                                    <?php foreach ($photos as $photo) : ?>
                                        <div class="col-sm-4 mb-5">
                                            <div class="card shadow-sm h-100">
                                                <input type="checkbox" class="form-control form-control-sm position-absolute col-1 ml-2 mb-1 cb" id="<?= h($photo['id']) ?>" name="check[]" value="<?= $photo['image'] ?>">
                                                <img class="card-img-top" data-toggle="modal" data-target=".bd-example-modal-lg" src="../images/<?= h($photo['image']) ?>" alt="<?= h($photo['team_name']) ?>">
                                                <div class="card-body">
                                                    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <img class="modal-content" data-bs-toggle="modal" data-bs-target="#exampleModal" src="../images/<?= h($photo['image']) ?>" alt="<?= h($photo['team_name']) ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="card-title"><span class="mr-1"></span><?= h($photo['name']) ?></p>
                                                    <p class="card-text text-muted mt-1"><span class="mr-1"><i class="fa-solid fa-comment"></i></span><?= h($photo['description']) ?></p>
                                                </div>
                                                <div class="card-footer">
                                                    <small class="blockquote-footer">by: <?= h($photo['team_name']) ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                    </div>

                    </form>
                </div>

        </div>


        </main>

    </div>
    </div>
    <?php include_once __DIR__ . '/../common/_footer.html' ?>
    <script>
        function allcheck(tf) {
            var ElementsCount = document.photo_form.elements.length; // チェックボックスの数
            for (i = 0; i < ElementsCount; i++) {
                document.photo_form.elements[i].checked = tf; // ON・OFFを切り替え
            }
        }
    </script>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


</body>

</html>

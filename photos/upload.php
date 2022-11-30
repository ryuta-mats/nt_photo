<?php
// 関数ファイルを読み込む
require_once __DIR__ . '/../common/functions.php';

$group = '';
$description = '';
$title = '';
$team_name = '';
$upload_file = '';
$upload_tmp_file = '';
$errors = [];
$image_name = '';

if (isset($_GET['group_id'])) {
    $group = find_group_by_id($_GET['group_id']);
    if (!$group) {
        header('Location: completion.php?err=1');
        exit;
    }
} else {
    header('Location: completion.php?err=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = filter_input(INPUT_POST, 'description');
    $title = filter_input(INPUT_POST, 'title');
    $team_name = filter_input(INPUT_POST, 'team_name');
    // アップロードした画像のファイル名
    $upload_file = $_FILES['image']['name'];
    // サーバー上で一時的に保存されるテンポラリファイル名
    $upload_tmp_file = $_FILES['image']['tmp_name'];

    $errors = photo_insert_validate($description, $title, $team_name, $upload_file);

    if (empty($errors)) {
        $file_info = pathinfo($upload_file);
        $img_extension = strtolower($file_info['extension']);
        $image_name = date('YmdHis') . '_' . $group['name'] . '.' . $img_extension;
        $path = '../images/' . strtolower($image_name);

        if ((move_uploaded_file($upload_tmp_file, $path)) &&
            insert_photo($title, $group['id'], $team_name, $image_name, $description)
        ) {
            header('Location: completion.php?group_id=' . $group['id']);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<?php include_once __DIR__ . '/../common/_head.html' ?>

<body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-sm-3 col-md-3 mr-0" href="#"><i class="fa-regular fa-snowflake"></i>NRTA Photo App</a>
    </nav>
    <section class="jumbotron mt-4 jumbotron_head">
        <div class="container text-center">
            <h1 class="jumbotron-heading main_headline">
                <p>
                    <?= h($group['name']) ?>
                </p>
            </h1>
            <p class="lead">
                <small class="form_text">フォトコンテスト専用の写真投稿アプリです。<br>下記のルール確認して投稿をお願いします。</small>
            </p>
        </div>
    </section>
    <section class="text-center">
        <div class="container">
            <p><i class="fa-solid fa-circle-exclamation" style="font-size: 1.5rem;"></i></p>

            <small>
                <p class="text-muted form_text"><?= h($group['description']) ?></p>
            </small>

        </div>
        </div>

        <main class="main_content wrapper jumbotron pt-4 pb-4">
            <h2 class="jumbotron-heading">Form</h2>
            <div class="form_flex">
                <div class="content-left">
                    <?php include_once __DIR__ . '/../common/_errors.php' ?>
                </div>
                <form action="" method="post" class="upload_content_form" enctype="multipart/form-data">
                    <label id="preview" class="upload_content_label" for="file_upload">
                        <span id="plus_icon" class="plus_icon"><i class="fas fa-plus-circle"></i></span>
                        <span id="upload_text" class="upload_text">写真を追加</span>
                    </label>
                    <input class="input_file" type="file" id="file_upload" name="image" onchange="imgPreView(event)">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="title">title</span>
                        </div>
                        <input type="text" class="form-control" placeholder="タイトルを入力" name="title" value="<?= h($title) ?>">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">description</span>
                        </div>
                        <textarea class="form-control" rows="5" name="description" placeholder="写真の説明を入力" name="description"><?= h($description) ?></textarea>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Team</span>
                        </div>
                        <input type="text" class="form-control" placeholder="チーム名を入力" name="team_name" value="<?= h($team_name) ?>">
                    </div>
                    <label for="send" class="btn btn-secondary btn-lg btn-block">Send <i class="fa-solid fa-paper-plane"></i>
                        <input type="submit" id="send" value="">
                    </label>
                </form>
            </div>
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

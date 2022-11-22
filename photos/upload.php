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
        $image_name = date('YmdHis') . '_' . $group['name'] . '_' . $team_name . '_' . $title . '.' . $img_extension;
        $path = '../images/' . $image_name;

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
    <?php include_once __DIR__ . '/../common/_header.php' ?>

    <main class="main_content content_center wrapper">
        <div class="form_flex">
            <p class="upload_description"><?= h($group['description']) ?></p>
            <?php include_once __DIR__ . '/../common/_errors.php' ?>
            <form action="" method="post" class="upload_content_form" enctype="multipart/form-data">
                <label id="preview" class="upload_content_label" for="file_upload">
                    <span id="plus_icon" class="plus_icon"><i class="fas fa-plus-circle"></i></span>
                    <span id="upload_text" class="upload_text">写真を追加</span>
                </label>
                <input class="input_file" type="file" id="file_upload" name="image" onchange="imgPreView(event)">
                <textarea class="input_text" name="title" rows="2" placeholder="写真のタイトルを入力してください"><?= h($title) ?></textarea>
                <textarea class="input_text" name="description" rows="5" placeholder="写真の説明を入力してください"><?= h($description) ?></textarea>
                <textarea class="input_text" name="team_name" rows="2" placeholder="チーム名を入力してください"><?= h($team_name) ?></textarea>

                <input type="submit" value="送信" class="upload_submit">
            </form>
        </div>
    </main>

    <?php include_once __DIR__ . '/../common/_footer.html' ?>
    <script src="../js/app.js"></script>
</body>

</html>

<?php
require_once __DIR__ . '/config.php';
// 接続処理を行う関数
function connect_db()
{
    try {
        return new PDO(
            DSN,
            USER,
            PASSWORD,
            [PDO::ATTR_ERRMODE =>
            PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}
// エスケープ処理を行う関数
function h($str)
{
    // ENT_QUOTES: シングルクオートとダブルクオートを共に変換する。
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function signup_validate($email, $name, $password)
{
    $errors = [];

    if (empty($email)) {
        $errors[] = MSG_EMAIL_REQUIRED;
    }

    if (empty($name)) {
        $errors[] = MSG_NAME_REQUIRED;
    }

    if (empty($password)) {
        $errors[] = MSG_PASSWORD_REQUIRED;
    }

    if (
        empty($errors) &&
        check_exist_user($email)
    ) {
        $errors[] = MSG_EMAIL_DUPLICATE;
    }

    return $errors;
}

function insert_user($email, $name, $password)
{
    try {
        $dbh = connect_db();

        $sql = <<<EOM
        INSERT INTO
            users
            (email, name, password)
        VALUES
            (:email, :name, :password);
        EOM;

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $pw_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindValue(':password', $pw_hash, PDO::PARAM_STR);

        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function check_exist_user($email)
{
    $dbh = connect_db();

    $sql = <<<EOM
    SELECT 
        * 
    FROM 
        users 
    WHERE 
        email = :email;
    EOM;

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty($user)) {
        return true;
    } else {
        return false;
    }
}

function login_validate($email, $password)
{
    $errors = [];

    if (empty($email)) {
        $errors[] = MSG_EMAIL_REQUIRED;
    }

    if (empty($password)) {
        $errors[] = MSG_PASSWORD_REQUIRED;
    }

    return $errors;
}

function find_user_by_email($email)
{
    $dbh = connect_db();

    $sql = <<<EOM
    SELECT
        *
    FROM
        users
    WHERE
        email = :email;
    EOM;

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function find_group_by_id($id)
{
    $dbh = connect_db();

    $sql = <<<EOM
    SELECT
        *
    FROM
        groups
    WHERE
        id = :id;
    EOM;

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function find_group_all()
{
    // データベースに接続
    $dbh = connect_db();

    // $id を使用してデータを取得
    $sql = <<<EOM
    SELECT
        *
    FROM
        groups
    ;
    EOM;

    // プリペアドステートメントの準備
    $stmt = $dbh->prepare($sql);

    // プリペアドステートメントの実行
    $stmt->execute();

    // 結果の取得
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function user_login($user)
{
    $_SESSION['current_user']['id'] = $user['id'];
    $_SESSION['current_user']['name'] = $user['name'];
    header('Location: ../photos/index.php');
    exit;
}

function photo_insert_validate($description, $title, $team_name, $upload_file)
{
    $errors = [];

    if (empty($description)) {
        $errors[] = MSG_NO_DESCRIPTION;
    }
    if (empty($title)) {
        $errors[] = MSG_NO_TITLE;
    }
    if (empty($team_name)) {
        $errors[] = MSG_NO_TEAM_NAME;
    }

    if (empty($upload_file)) {
        $errors[] = MSG_NO_IMAGE;
    } else {
        if (check_file_image_ext($upload_file)) {
            $errors[] = MSG_NOT_ABLE_EXT;
        }
    }

    return $errors;
}

//拡張子が画像ファイルかどうか確認する関数
function check_file_image_ext($upload_file)
{
    $file_ext = pathinfo($upload_file, PATHINFO_EXTENSION);
    if (!in_array($file_ext, EXTENSION_IMAGE)) {
        return true;
    } else {
        return false;
    }
}

//拡張子がPDFかどうか確認する関数
function check_file_pdf_ext($upload_file)
{
    $file_ext = pathinfo($upload_file, PATHINFO_EXTENSION);
    if (!in_array($file_ext, EXTENSION_PDF)) {
        return true;
    } else {
        return false;
    }
}

function insert_photo($name, $group_id, $team_name, $image_name, $description)
{
    try {
        $dbh = connect_db();

        $sql = <<<EOM
        INSERT INTO 
            photos
            (name, group_id ,team_name ,image, description) 
        VALUES 
            (:name, :group_id, :team_name, :image, :description);
        EOM;
        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':team_name', $team_name, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':image', $image_name, PDO::PARAM_STR);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function group_insert_validate($c_date, $c_group_name, $c_description)
{
    $errors = [];

    if (empty($c_date)) {
        $errors[] = MSG_NO_DATE;
    }
    if (empty($c_group_name)) {
        $errors[] = MSG_NO_GROUP_NAME;
    }
    if (empty($c_description)) {
        $errors[] = MSG_NO_DESCRIPTION;
    }

    return $errors;
}

function group_update_validate($date, $group_name, $description)
{
    $errors = [];

    if (empty($date)) {
        $errors[] = MSG_NO_DATE;
    }
    if (empty($group_name)) {
        $errors[] = MSG_NO_GROUP_NAME;
    }
    if (empty($description)) {
        $errors[] = MSG_NO_DESCRIPTION;
    }

    return $errors;
}


function update_group($id, $day, $name, $description)
{
    try {
        $dbh = connect_db();

        $sql = <<<EOM
        UPDATE
            groups
        SET
            day = :day,
            name = :name,
            description = :description
        WHERE
            id = :id;
        EOM;

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':day', $day, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}


function insert_group($day, $name, $description)
{
    try {
        $dbh = connect_db();

        $sql = <<<EOM
        INSERT INTO 
            groups
            (name, day, description) 
        VALUES 
            (:name, :day, :description);
        EOM;
        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':day', $day, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->execute();

        $id = $dbh->lastInsertId();

        return $id;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function find_photo_by_id($group_id)
{
    $dbh = connect_db();

    $sql = <<<EOM
    SELECT
        *
    FROM
        photos
    WHERE
        group_id = :group_id;
    EOM;

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':group_id', $group_id, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

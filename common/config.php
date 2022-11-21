<?php

define('DSN', 'mysql:host=db;dbname=nt_photo;charset=utf8');
define('USER', 'nt_admin');
define('PASSWORD', '1234');

define('EXTENSION_IMAGE', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('EXTENSION_PDF', ['pdf']);


define('MSG_NO_DESCRIPTION', '詳細を入力してください');
define('MSG_NO_TITLE', '写真のタイトルを入力してください');
define('MSG_NO_TEAM_NAME', '写真の説明を入力してください');
define('MSG_NO_IMAGE', '画像を選択してください');
define('MSG_NOT_ABLE_EXT', '選択したファイルの拡張子が有効ではありません');

define('MSG_EMAIL_REQUIRED', 'メールアドレスが未入力です');
define('MSG_NAME_REQUIRED', 'ユーザー名が未入力です');
define('MSG_PASSWORD_REQUIRED', 'パスワードが未入力です');
define('MSG_EMAIL_PASSWORD_NOT_MATCH', 'メールアドレスかパスワードが間違っています');
define('MSG_EMAIL_DUPLICATE', 'そのメールアドレスは既に会員登録されています');

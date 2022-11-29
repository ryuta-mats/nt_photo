# フォトコンテストアップロードアプリ


## 概要

ニセコで開催するフォトコンテスト用のフォームを作成することができる


### ADMIN画面
・新規団体（Group）の登録

・団体（Group）の情報修正

・フォームの生成

・投稿され写真の一覧表示

・チェック済み写真をZipでダウンロード


## FORM画面
・団体の情報を反映したフォーム

・画像アップロード

・アップロード画像の参照

・タイトルの入力

・説明の入力

・チーム名の入力

・バリデーション機能


## データベースとユーザーの作成

```sql
CREATE DATABASE IF NOT EXISTS nt_photo;
CREATE USER IF NOT EXISTS nt_admin IDENTIFIED BY '1234';
GRANT ALL ON nt_photo.* TO nt_admin;

```


## テーブルの作成
以下のコマンドを実行して、テーブルをセットアップします。
```bash
$ docker-compose exec app php photo_app/db/db_setup.php
```

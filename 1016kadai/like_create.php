<?php
// var_dump($_GET);
// exit();

include('functions.php');
//functions.phpを読み込んで関数とか使えるようにする

$user_id = $_GET['user_id'];
$todo_id = $_GET['todo_id'];

$pdo = connect_to_db();
//function.phpから読み込んで入る関数

$sql = 'SELECT COUNT(*) FROM like_table WHERE user_id=:user_id AND todo_id=:todo_id';
//飛んできたユーザーIDかつtodoをカウントしている
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
$stmt->bindValue(':todo_id', $todo_id, PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
$error = $stmt->errorInfo();
echo json_encode(["error_msg" => "{$error[2]}"]);
exit();
} else {
    $like_count = $stmt->fetchColumn();
    // まずはデータ確認
    // var_dump($like_count);
    // exit();
    // like_create.php

if ($like_count != 0) {
    // いいねされている状態、０じゃない場合
    $sql = 'DELETE FROM like_table WHERE user_id=:user_id AND todo_id=:todo_id';
    //削除のSQLが発信される
} else {
    // いいねされていない状態、0の場合、データの登録（インサートの
    $sql = 'INSERT INTO like_table (id, user_id, todo_id, created_at) VALUES (NULL, :user_id, :todo_id, sysdate())';
}

  // 以下は前項と変更なし
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
$stmt->bindValue(':todo_id', $todo_id, PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    header("Location:todo_read.php");
    //成功したら一覧画面に戻る
    exit();
}


}

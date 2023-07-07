<?php
// データベース接続情報
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kadai_03tanaka";

// 削除するアンケート結果のオプション
if (isset($_POST['option'])) {
    $option = $_POST['option'];
} else {
    $option = "";
}

// データベースへの接続
$conn = new mysqli("localhost", "root", "","kadai_03tanaka" );

// 接続エラーの確認
if ($conn->connect_error) {
    die("データベースへの接続に失敗しました: " . $conn->connect_error);
}

// アンケート結果の削除
$stmt = $conn->prepare("DELETE FROM survey_results WHERE sns = ?");
$stmt->bind_param("s", $option);

$response = "";

if ($stmt->execute()) {
    $response = "success";
} else {
    $response = "failure";
}

$stmt->close();

// データベース接続のクローズ
$conn->close();

// 削除後に再度入力されるのを防ぐために、リダイレクトする
header("Location: http://localhost/gs_code/kadai_03tanaka/submit.php?deleted=true");
exit();
?>

<?php
// データベース接続情報
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kadai_03tanaka";

// アンケート結果の取得
if (isset($_POST['sns'])) {
    $sns = $_POST['sns'];
} else {
    $sns = "";
}

if (isset($_POST['other_sns'])) {
    $other_sns = $_POST['other_sns'];
} else {
    $other_sns = "";
}

// データベースへの接続
$conn = new mysqli("localhost", "root", "","kadai_03tanaka" );

// 接続エラーの確認
if ($conn->connect_error) {
    die("データベースへの接続に失敗しました: " . $conn->connect_error);
}

// アンケート結果のデータベースへの挿入
$stmt = $conn->prepare("INSERT INTO survey_results (sns, other_sns) VALUES (?, ?)");
$stmt->bind_param("ss", $sns, $other_sns);

if ($stmt->execute()) {
    echo "アンケート結果が正常に保存されました";
} else {
    echo "エラー: " . $stmt->error;
}

$stmt->close();

// アンケート結果の取得と表示
$sql = "SELECT sns, COUNT(*) as count FROM survey_results GROUP BY sns";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>アンケート結果</h2>";
    echo "<div class='chart'>";
    while ($row = $result->fetch_assoc()) {
        $sns = $row['sns'];
        $count = $row['count'];

        // バーの表示
        echo "<div class='bar' style='width: " . ($count * 20) . "px;'>" . htmlspecialchars($sns) . ": " . $count . "</div>";
    }
    echo "</div>";
} else {
    echo "アンケート結果はありません";
}

// データベース接続のクローズ
$conn->close();
?>
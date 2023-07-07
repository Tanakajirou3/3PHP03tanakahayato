<?php
if (isset($_GET['deleted']) && $_GET['deleted'] === "true") {
    // 削除成功の場合、再度入力されるのを防ぐためにセッションをクリア
    session_start();
    session_unset();
    session_destroy();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>アンケート結果</title>
    <style>
        .chart {
            width: 400px;
            height: 300px;
            border: 1px solid #ccc;
            margin-top: 20px;
        }

        .bar {
            background-color: #428bca; /* グラフのバーの背景色を指定 */
            height: 20px;
            margin-bottom: 5px;
            color: #fff;
            text-align: right;
            padding-right: 5px;
            line-height: 20px;
        }

        .other-answers {
            margin-top: 20px;
        }

        .delete-button {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <h1>アンケート結果</h1>

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
// アンケート結果をデータベースに保存
$stmt = $conn->prepare("INSERT INTO survey_results (sns, other_sns) VALUES (?, ?)");
$stmt->bind_param("ss", $sns, $other_sns);

$sns = $_POST['sns'];
$other_sns = $_POST['other_sns'];

if ($stmt->execute() === false) {
    echo "データの保存に失敗しました: " . $stmt->error;
}

$stmt->close();

// アンケート結果の集計
$results = array(
    'Twitter' => 0,
    'Instagram' => 0,
    'Facebook' => 0,
    'Threads' => 0 // テキストボックスの名前を変更
);

// アンケート結果の取得
$query = "SELECT sns, COUNT(*) as count FROM survey_results GROUP BY sns";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sns = $row['sns'];
        $count = $row['count'];

        if ($sns === 'Twitter' || $sns === 'Instagram' || $sns === 'Facebook' || $sns === 'Threads') {
            $results[$sns] += $count;
        }
    }
}

// アンケート結果の表示
echo '<div class="chart">';
foreach ($results as $option => $count) {
    echo '<div class="bar" style="width: ' . ($count * 100) . 'px;">' . $count . '</div>';
    echo '<span>' . $option . '</span>';
    echo '<button class="delete-button" onclick="deleteResult(\'' . $option . '\')">削除</button><br>';
}
echo '</div>';

// テキストボックスの記入内容の表示と保持
if ($other_sns !== "") {
    echo '<div class="other-answers">';
    echo '<h2>その他の回答:</h2>'; // テキストボックスの名前を表示
    echo '<p>' . htmlspecialchars($other_sns) . '</p>'; // テキストボックスの内容を表示
    echo '</div>';
}

// データベース接続のクローズ
$conn->close();
?>

<br>
<button onclick="window.history.back();">戻る</button>

<script>
    function deleteResult(option) {
        if (confirm("この結果を削除しますか？")) {
            // 削除処理を実行
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    location.reload();
                }
            };
            xhr.send("option=" + option);
        }
    }
</script>
</body>
</html>

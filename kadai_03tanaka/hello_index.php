<!DOCTYPE html>
<html>
<head>
    <title>アンケート</title>
</head>
<body>
    <h1>多く利用しているSNSアプリは何ですか？</h1>
    <form method="POST" action="submit.php">
        <input type="radio" name="sns" value="Twitter">Twitter<br>
        <input type="radio" name="sns" value="Instagram">Instagram<br>
        <input type="radio" name="sns" value="Facebook">Facebook<br>
        <input type="radio" name="sns" value="Threads">Threads<br>
        <br>
        <input type="text" name="other_sns" placeholder="上記以外、好きに書いてください"><br>
        <br>
        <input type="submit" value="送信">
    </form>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $dsn = new PDO('mysql:host=localhost;dbname=webna;charset=utf8', 'root', '');
        $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $dsn->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            echo "<p>ログイン成功！</p>";
        } else {
            echo "<p>メールアドレスまたはパスワードが違います。</p>";
        }
    } catch(PDOException $e) {
        exit($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="logo"><a href="index.html"><h1>ロゴ</h1></a></div>

    <section>
        <form action="login.php" method="post">
            <h1 class="log">ログイン</h1>

            <div class="inputbox">
                <ion-icon name="mail-outline"></ion-icon>
                <input type="email" name="email" required>
                <label for="">ユーザー</label>
            </div>

            <div class="inputbox">
                <ion-icon name="lock-closed-outline"></ion-icon>
                <input type="password" name="password" required>
                <label for="">パスワード</label>
            </div>

            <div class="forget">
                <label for=""><input type="checkbox">次回から自動的にログイン</label>
                <a href="#">パスワードをお忘れですか？</a>
            </div>

            <button type="submit">ログイン</button>

            <div class="register">
                <p>アカウントをお持ちでないですか？ <a href="account.php">登録</a></p>
            </div>
        </form>
    </section>
</body>
</html>


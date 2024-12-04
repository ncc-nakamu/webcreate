<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="logo"><a href="index.html"><h1>ロゴ</h1></a></div>
    <section>
        <form method="POST" action="account.php">
            <h1 class="log">新規登録</h1>

            <!-- メールアドレス -->
            <div class="inputbox">
                <input type="email" name="email" required>
                <label for="">メールアドレス</label>
            </div>

            <!-- パスワード -->
            <div class="inputbox">
                <input type="password" name="password" required>
                <label for="">パスワード</label>
            </div>

            <!-- パスワード確認 -->
            <div class="inputbox">
                <input type="password" name="confirm_password" required>
                <label for="">パスワード確認</label>
            </div>

            <button type="submit">登録</button>

            <div class="login">
                <p>すでにアカウントをお持ちですか？ <a href="login.php">ログイン</a></p>
            </div>
        </form>
    </section>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            echo "<p>パスワードが一致しません。</p>";
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $dsn = new PDO('mysql:host=localhost;dbname=webna;charset=utf8', 'root', '');
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("INSERT INTO user (email, password) VALUES (:email, :password)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->execute();

            header("Location: http://localhost/%E3%83%97%E3%83%AD%E3%82%B0%E3%83%A9%E3%83%A0/1114/index.html");
            exit();
        } catch(PDOException $e) {
            echo "<p>登録できませんでした。もう一度お試しください。</p>";
            exit();
        }
    }
    ?>
</body>
</html>

<?php
session_start(); // セッションを開始

if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    $logout_message = "ログアウトしました。";
}

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
            session_regenerate_id(true); // セッション固定攻撃を防ぐためにセッションIDを再生成

            // ログイン成功時にセッションにユーザー情報を保存
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // index.phpにリダイレクト
            header("Location: index.php");
            exit();
        } else {
            $error_message = "メールアドレスまたはパスワードが違います。";
        }
    } catch(PDOException $e) {
        error_log($e->getMessage(), 3, '/var/log/php_errors.log');
        $error_message = "システムエラーが発生しました。再度お試しください。";
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="logo"><a href="index.html"><h1>ロゴ</h1></a></div>

    <section>
        <form action="login.php" method="post">
            <h1 class="log">ログイン</h1>

            <?php if (!empty($logout_message)): ?>
                <p><?php echo htmlspecialchars($logout_message); ?></p>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <p><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>

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





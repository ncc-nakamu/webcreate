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

            header("Location: http://localhost/websys/web/webcreate/index.php");
            exit();
        } catch(PDOException $e) {
            echo "<p>登録できませんでした。もう一度お試しください。</p>";
            exit();
        }
    }
    ?>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="logo"><a href="index.php"><h1>ロゴ</h1></a></div>
    <section>
        <form method="POST" action="account.php">
            <h1 class="log">新規登録</h1>

            <div class="inputbox">
                <input type="email" name="email" required>
                <label for="">メールアドレス</label>
            </div>

            <div class="inputbox">
                <input type="password" name="password" required>
                <label for="">パスワード</label>
            </div>

 
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

    </body>
</html>







<?php 
/**
 * セッションスタート
 */
ini_set('session.gc_maxlifetime', 1800);
ini_set('session.gc_divisor', 1);
session_start();
session_regenerate_id(); // セッションIDを新しいものに置き換える（★セッションハイジャック）

/**
 * ログインしていなければログイン画面へ強制リダイレクト
 */
if (! isset($_SESSION['user'])) {
    header('Location: ./login.php');
    exit();
}

/**
 * ログアウト
 */
if (isset($_POST['logout'])) {

    // トークンチェック（★CSRF）
    if (empty($_SESSION['logout_token']) || ($_SESSION['logout_token'] !== $_POST['logout_token'])) exit('不正な投稿です');
    if (isset($_SESSION['logout_token'])) unset($_SESSION['logout_token']);//トークン破棄
    if (isset($_POST['logout_token'])) unset($_POST['logout_token']);//トークン破棄
    
    /**
     * セッションを破棄する（★セッションハイジャック）
     */
    // セッション変数の中身をすべて破棄
    $_SESSION = array();
    // クッキーに保存されているセッションIDを破棄
    if (isset($_COOKIE["PHPSESSID"])) setcookie("PHPSESSID", '', time() - 1800, '/');
    // セッションを破棄
    session_destroy();

    // ログインページに戻る
    $msg = urlencode("ログアウトしました。");
    header('Location: ./login.php?msg=' . $msg);
    exit();
}

?>


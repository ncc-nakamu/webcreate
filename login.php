<?php 

/**
 * セッションスタート
 */
ini_set('session.gc_maxlifetime', 1800);
ini_set('session.gc_divisor', 1);
session_start();
session_regenerate_id(); // セッションIDを新しいものに置き換える（★セッションハイジャック）

/**
 * DB接続情報
 */
const DB_HOST     = 'mysql:host=localhost;dbname=webna;charset=utf8';
const DB_USER     = 'root';
const DB_PASSWORD = '';

// 会員登録・ログアウト完了メッセージの取得
if ( isset( $_GET['msg'] ) ) $success_logout_msg = $_GET['msg'];

/**
 * ログイン
 */
if (isset($_POST['login_btn']) && 
   (isset($_POST['login_id'])  && $_POST['login_id'] != '') &&
   (isset($_POST['password'])  && $_POST['password'] != '')
   ) 
{
    /**
     * トークンチェック（★CSRF）
     */
    if (empty($_SESSION['login_token']) || ($_SESSION['login_token'] !== $_POST['login_token'])) exit('不正なリクエストです');
    if (isset($_SESSION['login_token'])) unset($_SESSION['login_token']);//トークン破棄
    if (isset($_POST['login_token']))    unset($_POST['login_token']);//トークン破棄

    // POSTデータの取得
    $login_id = $_POST['login_id'];
    $password = $_POST['password'];
    
    try {
        /**
         * DB接続処理
         */
        $pdo = new PDO(DB_HOST, DB_USER, DB_PASSWORD, [
            PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION, // 例外が発生した際にスローする
            PDO::ATTR_EMULATE_PREPARES => false,                  // （★SQLインジェクション対策）
        ]);

        /**
         * ログイン処理
         */
        $sql = ('
            SELECT login_id, password, name
            FROM user1
            WHERE login_id = :LOGIN_ID
        ');
        $stmt = $pdo->prepare($sql);
        // プレースホルダーに値をセット
        $stmt->bindValue(':LOGIN_ID', $login_id, PDO::PARAM_STR);
        // SQL実行
        $stmt->execute();
        
        /**
         * ログイン情報が正しいかをチェック
         */
        $user_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($user_info) && password_verify( $password, $user_info[0]['password'] )) {

            // ログイン状態確認用にセッションにデータ保存（★ログイン機能の実現）
            $_SESSION['user'] = array(
                'name'     => $user_info[0]['name'],
                'login_id' => $user_info[0]['login_id'],
            );

            // ログイン後はトップページへ遷移する
            header('Location: ./index.php');
            exit();
        } else {
            $err_msg = 'ログイン情報に誤りがあります。';
        }

    } catch (PDOException $e) {
          echo '接続失敗' . $e->getMessage();
          exit();
    }
    // DBとの接続を切る
    $pdo = null;
    $stmt = null;
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
    <div class="logo"><a href="index.php"><h1>ロゴ</h1></a></div>

    <section>
        <form action="login.php" method="post">
            <h1 class="log">ログイン</h1>

            <!-- エラーメッセージ表示 -->
            <?php if(isset($success_logout_msg)) echo '<p class="success_logout_msg">' . $success_logout_msg . '</p>'; ?>
            <?php if(isset($err_msg)) echo '<p class="err-msg">' . $err_msg . '</p>'; ?>

            <div class="inputbox">
                <ion-icon name="mail-outline"></ion-icon>
                <input type="email" name="login_id" required>
                <label for="login_id">ユーザー</label>
            </div>

            <div class="inputbox">
                <ion-icon name="lock-closed-outline"></ion-icon>
                <input type="password" name="password" required>
                <label for="password">パスワード</label>
            </div>

            <button type="submit" name="login_btn">ログイン</button>


            <div class="forget">
                <label for=""><input type="checkbox">次回から自動的にログイン</label>
                <a href="#">パスワードをお忘れですか？</a>
            </div>

            <div class="register">
                <p>アカウントをお持ちでないですか？ <a href="regist.php">登録</a></p>
            </div>

            <?php 
            // 不正リクエストチェック用のトークン生成（★CSRF）
            $token = bin2hex(random_bytes(32));
            $_SESSION['login_token'] = $token;
            echo '<input type="hidden" name="login_token" value="'.$token.'" />';
            ?>
        </form>
    </section>

    <script>
        // フォームの初期状態でのラベルの位置を確認するためのスクリプト
        document.querySelectorAll('.inputbox input').forEach(input => {
            input.addEventListener('input', function() {
                if (this.value !== '') {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });
            if (input.value !== '') {
                input.classList.add('has-value');
            }
        });
    </script>
</body>
</html>


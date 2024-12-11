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

/**
 * 会員登録
 */
if (isset($_POST['regist_btn']) && 
   (isset($_POST['name'])       && $_POST['name']     != '') &&
   (isset($_POST['login_id'])   && $_POST['login_id'] != '') &&
   (isset($_POST['password'])   && $_POST['password'] != '')
) {
    /**
     * トークンチェック（★CSRF）
     */
    if (empty($_SESSION['regist_token']) || ($_SESSION['regist_token'] !== $_POST['regist_token'])) exit('不正なリクエストです');
    if (isset($_SESSION['regist_token'])) unset($_SESSION['regist_token']);//トークン破棄
    if (isset($_POST['regist_token']))    unset($_POST['regist_token']);//トークン破棄

    // POSTデータの取得
    $name     = $_POST['name'];
    $login_id = $_POST['login_id'];
    $password = $_POST['password'];

    // パスワードをハッシュ化する（★SQLインジェクション）
    $password_hash = password_hash( $password, PASSWORD_DEFAULT );

    try {
        /**
         * DB接続処理
         */
        $pdo = new PDO(DB_HOST, DB_USER, DB_PASSWORD, [
            PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION, // 例外が発生した際にスローする
            PDO::ATTR_EMULATE_PREPARES => false,                  // （★SQLインジェクション）
        ]);

        /**
         * 会員情報重複チェック
         * 入力されたIDがすでに登録済みかどうかをチェックする
         */
        $sql = ('
            SELECT login_id 
            FROM user1 
            WHERE login_id = :LOGIN_ID;
        ');
        $stmt = $pdo->prepare($sql);
        // プレースホルダーに値をセット
        $stmt->bindValue(':LOGIN_ID', $login_id, PDO::PARAM_STR);
        // SQL実行
        $stmt->execute();
        // ユーザ情報の取得
        $user_info = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // ユーザ情報が取得できている＝件数が「1」の場合はエラーメッセージを返す
        if (count($user_info)) {
            $err_msg = 'そのIDはすでに使用されています。';
        } else {
            /**
             * 会員情報登録処理
             */
            $sql = ('
                INSERT INTO
                    user1 (name, login_id, password)
                VALUES
                    (:NAME, :LOGIN_ID, :PASSWORD)
            ');
            $stmt = $pdo->prepare($sql);
            // プレースホルダーに値をセット
            $stmt->bindValue(':NAME',     $name,          PDO::PARAM_STR);
            $stmt->bindValue(':LOGIN_ID', $login_id,      PDO::PARAM_STR);
            $stmt->bindValue(':PASSWORD', $password_hash, PDO::PARAM_STR);
            // SQL実行
            $stmt->execute();

            // ログイン画面へ遷移
            $msg = urlencode("会員登録が完了しました。");
            header('Location: ./login.php?msg=' . $msg);
            exit();
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
    <title>ユーザー登録</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="logo"><a href="index.php"><h1>ロゴ</h1></a></div>
    <section>
        <form method="POST" action="account.php">
            <h1 class="log">新規登録</h1>

            <!-- エラーメッセージ表示 -->
            <?php if(isset($err_msg)) echo '<p class="err-msg">' . $err_msg . '</p>'; ?>

            <div class="inputbox">
                <input type="text" name="name" required>
                <label for="name">ニックネーム</label>
            </div>

            <div class="inputbox">
                <input type="email" name="login_id" required>
                <label for="login_id">メールアドレス</label>
            </div>

            <div class="inputbox">
                <input type="password" name="password" required>
                <label for="password">パスワード</label>
            </div>

            <button type="submit" name="regist_btn">登録</button>

            <div class="login">
                <p>すでにアカウントをお持ちですか？ <a href="login.php">ログイン</a></p>
            </div>

            <?php 
            // 不正リクエストチェック用のトークン生成（★CSRF）
            $token = bin2hex(random_bytes(32));
            $_SESSION['regist_token'] = $token;
            echo '<input type="hidden" name="regist_token" value="'.$token.'" />';
            ?>
        </form>
    </section>
</body>
</html>





    



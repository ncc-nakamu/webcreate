<?php
session_start(); // セッションを開始

// セッション固定攻撃を防ぐためにセッションIDを再生成
session_regenerate_id(true);

// セッションを破棄してログアウト
session_unset();
session_destroy();

// ログインページにリダイレクト
header("Location: login.php?logout=success");
exit();
?>

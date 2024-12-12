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
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

</head>

<body>
    <header>
        <div class="header-logo">
            <a href="index.html">
                <img src="images\ncc.png" alt="logo" width="50" height="60" class="logo-image">
            </a>
        </div>

        <nav>
            <div class="links">
                <ul>
                    <li><a class="active" href="#">農園とは</a></li>
                    <li><a href="#">野菜について</a></li>
                    <li><a href="#store-top">お買い物</a></li>
                    <li><a href="#news-top">お知らせ</a></li>
                    <li><a href="#">お問い合わせ</a></li>
                </ul>
            </div>

            <input type="checkbox" id="overlay-input" />
            <label for="overlay-input" id="overlay-button"><span></span></label>
            <div class="bergar" id="overlay">
                <ul>
                <li><a href="#">ログイン</a></li>
                <li><a href="#">ログアウト</a></li>
                <li><a href="#">アカウント作成</a></li>
                </ul>
            </div>

            <div class="login-sec">
                <a href="login.php"><button>ログイン</button></a>
            </div>

        </nav>

            <div class="flex-box">
                <div><h1 class="flex-item1">四季のおいしいを</h1></div>
                <div><h1 class="flex-item">大切に育て</h1></div>
                <div><h1 class="flex-item">つないでいく</h1></div>
            </div>
        
    </header>

    <div class="stop">

    </div>

    <div class="clamh1">
        <h1>新潟の野菜</h1>
    </div>
    <div class="clam">
        <p>2024/11/8<br>
        【12月中旬-1月末発送】かぼちゃikkaプレミアムとさつまいもの予約販売を開始しました。サツマイモは11月20日以降発送です。<br>
        販売ページはこちらどうぞ。<br>
        .<br>
        ■ネギ<br>
        柔らかく甘い長ネギです。ネギの育ちやすい季節になり、太さも出てきました。甘味も増しています♪<br>
        切って焼くだけでも美味しいですが、色々な料理に使えます。生だと爽やかな辛味と香りがあり、薬味としても美味しいです。<br>
        .<br>
        ■ビーツ<br>
        栄養たっぷりの真っ赤なビーツです。ナチュレ片山さんでも販売していますが、直接販売することも可能です。もう少しで今期は終了です。<br>
        ・<br>
        ■■■ 販売している場所■■■<br>
        ・ネギ：原信地場野菜コーナー、ナチュレ片山さん<br>
        ・サツマイモ：原信地場野菜コーナー<br>
        ・ビーツ：ナチュレ片山さん<br>
        その他、飲食店様に向けて直接販売行っています。
        </p>
    </div>

    <div class="newsh1" id="news-top">
        <h1>お知らせ</h1>
    </div>
    <div class="news">
        <div class="news-clam">
            <div class="zoomIn"><a href="#"><span class="mask"><img src="images\zinenjo.png" alt="11月ニュース"></span></a></div>
                <h3>11月ニュースレター</h3>
                <p>2024.10.11</p>
        </div>
        <div class="news-clam">
            <div class="zoomIn"><a href="#"><span class="mask"><img src="images\ithigo.png" alt="10月ニュース"></span></a></div>
                <h3>10月ニュースレター</h3>
                <p>2024.11.11</p>
        </div>
        <div class="news-clam">
            <div class="zoomIn"><a href="#"><span class="mask"><img src="images\renkon.png" alt="9月ニュース"></span></a></div>
                <h3>9月ニュースレター</h3>
                <p>2024.9.11</p>
        </div>
    </div>   

    <div class="shikih1">
        <h1>四季を楽しむ野菜</h1>
    </div>

    <div class="swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide slide1"><img src="images\ithigo.png" alt="いちご"></div>
            <div class="swiper-slide slide2"><img src="images\renkon.png" alt="れんこん"></div>
            <div class="swiper-slide slide3"><img src="images\nihonnashi.png" alt="日本なし"></div>
            <div class="swiper-slide slide4"><img src="images\rurekuthe.png" alt="西洋なし"></div>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>

    <div class="storeh1" id="store-top">
        <h1>オンラインストア</h1>
    </div>
    
    <div class="store">
            <br>
            <div class="store-item">
                <div class="zoomIn"><a href="#"><span class="mask"><img src="images\zinenjo.png" alt="じねんじょ"></span></a></div>
                <h3>じねんじょ</h3>
                <p>自然の甘みと粘り強さ、<br>絶品の自然薯をお届けします。</p>
                <span>¥300</span>
            </div>
            <div class="store-item">
                <div class="zoomIn"><a href="#"><span class="mask"><img src="images\ningin.png" alt="にんじん"></span></a></div>
                <h3>にんじん</h3>
                <p>シャキシャキ食感と甘みの人参、<br>旬の味わいを楽しんでください。</p>
                <span>¥200</span>
            </div>
            <div class="store-item">
                <div class="zoomIn"><a href="#"><span class="mask"><img src="images\renkon.png" alt="れんこん"></span></a></div>
                <h3>れんこん</h3>
                <p>ほくほく食感の蓮根、<br>栄養たっぷりで食卓を彩ります。</p>
                <span>¥600</span>
            </div>
    </div>
    <div class="store">
            <div class="store-item">
                <div class="zoomIn"><a href="#"><span class="mask"><img src="images\rurekuthe.png" alt="西洋なし"></span></a></div>
                <h3>西洋なし</h3>
                <p>西洋なしの最高峰。<br>とろけるような食感と芳醇な香り。</p>
                <span>¥800</span>
            </div>
            <div class="store-item">
                <div class="zoomIn"><a href="#"><span class="mask"><img src="images\nihonnashi.png" alt="日本なし"></span></a></div>
                <h3>日本なし</h3>
                <p>ジューシーで甘い、日本梨の<br>本当の美味しさを味わってください。</p>
                <span>¥300</span>
            </div>
            <div class="store-item">
                <div class="zoomIn"><a href="#"><span class="mask"><img src="images\ithigo.png" alt="いちご"></span></a></div>
                <h3>いちご</h3>
                <p>甘くほどよい酸味とのバランス<br>もよくとてもジューシーないちご。</p>
                <span>¥400</span>
            </div>
            <!-- 他のメニューアイテムも同様に追加 -->
    </div>

    <div class="instagram">
        <h1>instagram</h1>
    </div>

    <footer>

    </footer>

    <script>
   window.addEventListener('scroll', () => {
    const flexBox = document.querySelector('.flex-box');
    const stop = document.querySelector('.stop');

    // .stopの位置と高さを取得
    const stopPosition = stop.getBoundingClientRect().top + window.scrollY;
    const stopHeight = stop.offsetHeight;

    // 現在のスクロール位置を取得
    const currentScroll = window.scrollY;

    // スクロール時に.flex-boxを.stopの下に固定
    if (currentScroll + flexBox.offsetHeight >= stopPosition + stopHeight) {
        flexBox.style.position = 'absolute';
        flexBox.style.top = `${stopPosition + stopHeight}px`;
    } else {
        flexBox.style.position = 'fixed';
        flexBox.style.top = '250px'; // 初期値
    }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
    const swiper = new Swiper(".swiper", {
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        },
        loop: true, //繰り返しをする
        centeredSlides: true, //アクティブなスライドを中央に表示
        slidesPerView: 3, //スライダーのコンテナ上に2枚同時に表示
        spaceBetween: 16, //スライド間の距離を16pxに
        speed: 600 //スライドの推移時間を600msに
    });
    </script>

</body>
</html>

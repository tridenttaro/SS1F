<?php
session_start();
//$_SESSION = array();
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ログオン画面</title>
    <link type="text/css" rel="stylesheet" href="https://cdn.firebase.com/libs/firebaseui/3.5.2/firebaseui.css" />
    <style>h1{text-align: center;}</style>
</head>
<body style="background-color:lightblue;">
    <h1>ログオン画面</h1>
    <div id="firebaseui-auth-container"></div>

    <script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/ui/3.5.2/firebase-ui-auth__ja.js"></script>
    <script src="./js/config.js"></script>
    <script language="javascript" type="text/javascript">
        //----------------------------------------------
        // Firebase UIの設定
        //----------------------------------------------
        var uiConfig = {
            // ログオン完了時のリダイレクト先
            signInSuccessUrl: './gz_logon2.php',

            // 利用する認証機能
            signInOptions: [
                firebase.auth.EmailAuthProvider.PROVIDER_ID //メール認証
//                firebase.auth.GoogleAuthProvider.PROVIDER_ID  //googleアカウント認証
            ],

//            // 利用規約のURL(任意で設定)
//            tosUrl: 'http://example.com/kiyaku/',
//            // プライバシーポリシーのURL(任意で設定)
//            privacyPolicyUrl: 'https://miku3.net/privacy.html'

        };

        var ui = new firebaseui.auth.AuthUI(firebase.auth());
        ui.start('#firebaseui-auth-container', uiConfig);
    </script>
    
    
    
</body>
</html>
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ログオン画面</title>
    <link rel="manifest" href="./manifest.json">
    <script>
        if('serviceWorker' in navigator){
            navigator.serviceWorker.register('./service-worker.js').then(function(){
                console.log("Service Worker is registered!!");
            });
        }
    </script>
</head>
<body style="background-color:lightblue;">
    <h1>...Please wait</h1>
    <div id="info"></div>
    <div id="info2"></div>
    <form>
        <button type="button" id="logout" style="display:none;">ログオフ</button>
    </form>
    
    <script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-auth.js"></script>
    <script src="./js/config.js"></script>
    
    <script language="javascript" type="text/javascript">
        
        // エンコード処理を行う関数を定義
        var encodeFormData = function(data) {
            if (!data) return '';
            var params = [];
            for (var name in data) {
                var key = encodeURIComponent(name.replace(' ', '+'));
                var value = encodeURIComponent(data[name].toString().replace(' ', '+'));
                params.push(key + '=' + value);
            }
            console.log(params.join('&'));
            return params.join('&');
        }
        
        firebase.auth().onAuthStateChanged( (user) => {
            let logout = document.getElementById("logout");
         
            
            //------------------------------------
            // 未ログオン状態で訪れた場合
            //------------------------------------
            if(user === null){
                showMessage('Not Login', 'ログオンが必要な画面です');
                info2.innerHTML = "<a href='gz_logon.php'>ログオンはこちら</a>";
                // ログオフボタンを非表示
                logout.style.display = "none";
                // ニックネーム項目非表示
                nickname.style.display = "none";
      
                return(false);
            }

            //------------------------------------
            // メアド確認済み
            //------------------------------------
            if( user.emailVerified ) {
                // 取得したアカウント情報をphpへ送信
                let request = new XMLHttpRequest();
                //確認
                let uid = user.uid;
//                let name = user.displayName;
                request.open('POST', `./gz_logon_pass.php`);
                
                // サーバに対して解析方法を指定する
                request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
                
                request.send(encodeFormData({name: uid}));
                
                // ログオフボタンを表示
                logout.style.display = "block";
                
                // 自動的に画面遷移
                location.href = "./gz_logon2_1.php";
                
                //----------------------------------
                // ログオフ
                //----------------------------------
                logout.addEventListener("click", ()=>{
                    firebase.auth().signOut()
                    .then(()=>{
                        console.log("ログオフしました");
                        <?php
                        session_destroy();
                        ?>
                        
                    })
                    .catch( (error)=>{
                        console.log(`ログオフ時にエラーが発生しました (${error})`);
                    });
                });
            } else {
                //------------------------------------
                // メアド未確認
                //------------------------------------  
                actionCodeSettings = {
                    url: `http://tridentziro.oops.jp/SS1F/gz_logon2.php`
                };

                user.sendEmailVerification(actionCodeSettings)
                .then(()=>{
                    showMessage('Send confirm mail', `${user.email}宛に確認メールを送信しました`);
                })
                .catch((error)=>{
                    showMessage('[Error] Can not send mail', `${user.email}宛に確認メールを送信できませんでした: ${error}`);
                });
            }
            
        });

        function showMessage(title, msg) {
            document.querySelector('h1').innerText    = title;
            document.querySelector('#info').innerHTML = msg;
        }

    </script>
  
</body>
</html>
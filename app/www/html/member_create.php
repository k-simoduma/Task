<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task-ログイン</title>
    <link rel="stylesheet" href="stylesheet/style_common.css">
    <link rel="stylesheet" href="stylesheet/style_01.css">
</head>

<body>
    <header>
        <div class="logo-link"><a href="index.php"><img src="image/task_logo.png" alt="Task"></a></div>
    </header>
    <main>
        <div class="contents">
            <div class="member-create-form">
                <div class="form-title">
                    <h2>新規メンバー作成</h2>
                </div>
                <form action="member_create_check.php" method="post">
                    <div class="form-item">
                        <input id="input-member-name" type="text" name="member-name" placeholder="名前">
                    </div>
                    <div class="form-item">
                        <input id="input-mail-address" type="text" name="mail-address" placeholder="メールアドレス">
                    </div>
                    <div class="form-item">
                        <input id="input-password" type="password" name="password" placeholder="パスワード">
                    </div>
                    <div class="form-item">
                        <input id="create-button" type="submit" name="create-btn" value="作成">
                    </div>
                </form>
            </div>
        </div>
    </main>
    <footer>
    </footer>
</body>

</html>
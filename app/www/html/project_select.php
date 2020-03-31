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
            <div class="project-select-form">
                <div class="form-title">
                    <h3>ログインするプロジェクトを入力してください。</h3>
                </div>
                <form action="project_login.php" method="post">
                    <div class="form-item">
                        <input id="input-project-name" type="text" name="project-name" placeholder="プロジェクト名">
                    </div>
                    <div class="form-item">
                        <input id="next-button" type="submit" name="next-btn" value="次へ">
                    </div>
                </form>
            </div>
        </div>
    </main>
    <footer>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script>
        $(function() {
            $('#input-project-name').on("keydown keyup keypress change", function() {
                var length = $(this).val().length;
                if (length > 1) {
                    $('#next-button').show('slow');
                } else {
                    $('#next-button').hide();
                }
            });
        });
    </script>
</body>

</html>
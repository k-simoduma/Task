<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task-プロジェクト作成</title>
    <link rel="stylesheet" href="stylesheet/style_common.css">
    <link rel="stylesheet" href="stylesheet/style_01.css">
</head>

<body>
    <header>
        <div class="logo-link"><a href="index.php"><img src="image/task_logo.png" alt="Task"></a></div>
    </header>
    <main>
        <div class="contents">
            <div class="project-create-form">
                <div class="form-title">
                    <h3>新規プロジェクト作成</h3>
                </div>
                <form action="project_create_check.php" method="post">
                    <div class="form-item">
                        <input id="input-new-project" type="text" name="project-name" placeholder="新規プロジェクト名">
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
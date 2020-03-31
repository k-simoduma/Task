INSERT INTO task.status(status_name) values('新規');
INSERT INTO task.status(status_name) values('担当者決定');
INSERT INTO task.status(status_name) values('内容確認済み');
INSERT INTO task.status(status_name) values('進行中');
INSERT INTO task.status(status_name) values('フィードバック');
INSERT INTO task.status(status_name) values('完了');

INSERT INTO task.category(category_name) values('タスク');
INSERT INTO task.category(category_name) values('バグ');

INSERT INTO task.priority(priority_name) values('低');
INSERT INTO task.priority(priority_name) values('中');
INSERT INTO task.priority(priority_name) values('高');
INSERT INTO task.priority(priority_name) values('緊急');

INSERT INTO task.action(action_sentence) values('を作成しました。');
INSERT INTO task.action(action_sentence) values('を編集しました。');
INSERT INTO task.action(action_sentence) values('の担当者を設定しました。');
INSERT INTO task.action(action_sentence) values('にコメントしました。');
INSERT INTO task.action(action_sentence) values('状態を変更しました。');

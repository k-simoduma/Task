<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION['login']) == false) {
    header('Location: http://localhost:8080/index.php');
    exit;
}

$_SESSION['filter-author'] = $_POST['author'];
$_SESSION['filter-charge'] = $_POST['charge'];
$_SESSION['filter-status'] = $_POST['status'];
$_SESSION['filter-category'] = $_POST['category'];
$_SESSION['filter-priority'] = $_POST['priority'];
$_SESSION['filter-word'] = htmlspecialchars($_POST['key-word'], ENT_QUOTES, 'UTF-8');
header('Location: http://localhost:8080/ticket_search.php');
exit;
<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION['login']) == false) {
    header('Location: http://localhost:8080/index.php');
    exit;
}

require_once __DIR__ . '/function/class/ticket_manager.php';

$project_id = $_SESSION['project-id'];
$project_name = $_SESSION['project-name'];
$member_id = $_SESSION['member-id'];
$member_name = $_SESSION['member-name'];

$ticket_id = htmlspecialchars($_POST['ticket-num'], ENT_QUOTES, 'UTF-8');

if (preg_match("/^[0-9]+$/", $ticket_id) == false){
    header('Location: http://localhost:8080/my_page.php');
    exit;
}

$ticket_manager = new \AppTask\TicketManeger();
$rec_ticket = $ticket_manager->GetTicket($ticket_id);

if($rec_ticket == false){
    header('Location: http://localhost:8080/my_page.php');
    exit;
}

header('Location: http://localhost:8080/ticket_view.php?id=' . $rec_ticket->id);
exit;
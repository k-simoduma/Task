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

$ticket_id = $_POST['ticket-id'];
$status_id = $_POST['status-id'];
$charge_id = $_POST['charge-id'];

if ($charge_id == 0) {
    header('Location: http://localhost:8080/ticket_view.php?id=' . $ticket_id);
    exit;
}

$ticket_manager = new \AppTask\TicketManeger();
$ticket_manager->UpdateTicketCharge($project_id, $member_id, $ticket_id, $charge_id);

if ($status_id == $ticket_manager->GetStatusID('new')) {
    $ticket_manager->UpdateTicketStatus($ticket_id, $ticket_manager->GetStatusID('charge'));
}

header('Location: http://localhost:8080/ticket_view.php?id=' . $ticket_id);
exit;

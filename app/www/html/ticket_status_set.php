<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION['login']) == false) {
    header('Location: http://localhost:8080/index.php');
    exit;
}

require_once __DIR__ . '/function/class/ticket_manager.php';

$ticket_id = $_POST['ticket-id'];
$status_id = $_POST['status-id'];

$ticket_manager = new \AppTask\TicketManeger();
$ticket_manager->UpdateTicketStatus($ticket_id, $status_id);

header('Location: http://localhost:8080/ticket_view.php?id=' . $ticket_id);
exit;

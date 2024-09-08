<?php
session_start();

$progress = isset($_SESSION['progress']) ? $_SESSION['progress'] : 0;

header('Content-Type: application/json');
echo json_encode(['progress' => $progress]);

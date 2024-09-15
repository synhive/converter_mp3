<?php
header('Content-Type: application/json');

$progressFile = 'progress.json';
if (file_exists($progressFile)) {
    echo file_get_contents($progressFile);
} else {
    echo json_encode(['progress' => 0]);
}

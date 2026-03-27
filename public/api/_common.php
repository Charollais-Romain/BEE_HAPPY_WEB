<?php

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

require_once dirname(dirname(__DIR__)) . '/includes/db.php';

function bee_json_error($message, $http = 400)
{
    http_response_code($http);
    echo json_encode(array('ok' => false, 'error' => $message), JSON_UNESCAPED_UNICODE);
    exit;
}

function bee_json_ok(array $data)
{
    echo json_encode(array('ok' => true) + $data, JSON_UNESCAPED_UNICODE);
    exit;
}

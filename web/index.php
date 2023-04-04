<?php

require_once dirname(__DIR__) . '/src/SimpleVote.php';

$vote = new SimpleVote('vote', true);
$response = $vote->exec();

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);

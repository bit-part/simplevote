<?php

require_once 'vendor/autoload.php';

$vote = new bitpart\simplevote\SimpleVote('vote', true);
$response = $vote->exec();

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);

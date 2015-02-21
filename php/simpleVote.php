<?php
// ini_set('display_errors', 1);
// Return as a plain text file
header('Content-Type: text/plain; charset=utf-8');

define('DATA_DIR_PATH', '/var/www/vhost/works.bit-part.net/simpleVote/data');
// Check parameters
if (empty($_POST['vote_id']) || empty($_POST['vote_type']) || empty($_POST['vote_token'])) {
    exit('error: 01');
}
// Check the token. But there is really no meaning for that.
if (strlen($_POST['vote_token']) != 51) {
    exit('error: 02');
}

// Initialization
$key = 'vote_' . $_POST['vote_id'];
$vote_type = $_POST['vote_type'];
$count = 0;

// Get the file of recording results
if ($content = file_get_contents(DATA_DIR_PATH . DIRECTORY_SEPARATOR . 'vote.json')) {
    // Returned objects will be converted into associative arrays.
    $json = json_decode($content, true);
    // Get the current count if this key is set.
    if (isset($json[$key])) {
        $count = $json[$key];
    }
    // Vote
    $count = vote($vote_type, $count);
    $json[$key] = $count;
}
else {
    if ($vote_type == 'inc') {
        // Initialize array.
        $json = array();
        // Vote
        $count = vote($vote_type, $count);
        $json[$key] = $count;
    }
}
// If $json is defined, overwrite current file.
if ($vote_type != 'get' && isset($json)) {
    file_put_contents(DATA_DIR_PATH . DIRECTORY_SEPARATOR . 'vote.json', json_encode($json));
    file_put_contents(DATA_DIR_PATH . DIRECTORY_SEPARATOR . 'vote_bk.json', json_encode($json));
}
// Return the current voted count
echo $count;

function vote($vote_type, $count) {
    if ($vote_type == 'inc') {
        $count++;
    }
    elseif ($vote_type == 'dec') {
        $count--;
    }
    if ($count < 0) {
        $count = 0;
    }
    return $count;
}
?>
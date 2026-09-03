<?php
require_once('../../../wp-load.php');
$comments = get_comments(array('status' => 'approve', 'number' => 20));
$out = "";
foreach($comments as $c) {
    if (strpos($c->comment_content, '<img') !== false) {
        $out .= $c->comment_content . "\n";
    }
}
file_put_contents('test_comments.txt', $out);
echo "Done";

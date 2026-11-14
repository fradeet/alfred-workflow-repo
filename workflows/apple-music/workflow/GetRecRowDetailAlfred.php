<?php
require_once (string)getenv("alfred_preferences") .
    DIRECTORY_SEPARATOR .
    "workflows" .
    DIRECTORY_SEPARATOR .
    (string)getenv("alfred_workflow_uid") .
    DIRECTORY_SEPARATOR .
    "scripts" .
    DIRECTORY_SEPARATOR .
    "AlfredAdapter.php";

$rid = (string)getenv("rid");
$cache_dir = (string)getenv("alfred_workflow_cache");
$cache_file_name = (string)getenv("cache_file_name");

$result = getRecRowDetailAlfred($rid, $cache_dir, $cache_file_name)
    |> (fn($x) => json_encode($x, JSON_UNESCAPED_UNICODE))
;
echo $result;

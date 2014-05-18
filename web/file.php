<?php
    require_once '../src/Thin/Helper.php';
    $file = (ake('file', $_REQUEST)) ? $_REQUEST['file'] : null;
    $name = (ake('name', $_REQUEST)) ? $_REQUEST['name'] : null;
    $type = (ake('type', $_REQUEST)) ? $_REQUEST['type'] : null;

    if (null !== $file && null !== $name && null !== $type) {
        $dwn = '../storage/cache/' . $file . '.' . $type;
        if (file_exists($dwn)) {
            $content = fgc($dwn);
            header("Content-type: application/$type");
            header("Content-Length: " . strlen($content));
            header("Content-Disposition: attachement; filename=\"$name.$type\"");
            echo $content;
            exit;
        } else {
            die('NOK1');
        }
    } else {
        die('NOK2');
    }

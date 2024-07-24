<?php

class Controller
{

    function sanitize($dirty)
    {
        $dirty = trim($dirty);
        $dirty = stripcslashes($dirty);
        $clean = htmlspecialchars($dirty);

        return $clean;
    }

    // function addToLog($action, $date, $idUser, $name) {
    //     // open files to append 
    //     $logFile = fopen('log.log', 'a') or die('Unable to open file!');
    //     // add log info
    //     $txt = "$action / user: $idUser, name: $name, $date\n";
    //     fwrite($logFile, $txt);
    //     // close file
    //     fclose($logFile);
    // }
}

<?php
if(!$_SESSION['csrf']) {
    switch($_SESSION['action']) {
        case "Activation":
            $om = new OMTM24h($_SESSION['callsign'], $_SESSION['pwd']);
            if ($om->CheckOMBD()) {
                $DataTM24h = new DataTM24h($_SESSION['callsign'], $_SESSION['pwd'], $_SESSION['band'], $_SESSION['mode'], $_SESSION['frequence'], $_SESSION['status']);
                $DataTM24h->SetDataTM24h();
                echo showStaticTable();
            }
            break;
        case "Log integral":
            echo showAllDataTM24h();
            break;
        case "Log OM":
            $om = new OMTM24h($_SESSION['callsign'], $_SESSION['pwd']);
            if ($om->CheckOMBD()) {
                echo showOMDataTM24h($_SESSION['callsign']);
            }
            break;
        default:
            echo showStaticTable();
            break;
    }
} else {
    show_message("JETON CSRF NON VALIDE !", false);
}
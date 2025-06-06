<?php
unset($SESSION);;
session_start();
include '../model/database.php';
$_SESSION['csrf'] = false;
$_SESSION['callsign'] = "";
$_SESSION['pwd'] = "";
$_SESSION['band'] = "";
$_SESSION['mode'] = "";
$_SESSION['frequence'] = "";
$_SESSION['status'] ="";
$_SESSION['action'] = "";
$liststatus = ["Active", "Inactive"];
$listaction = ["Tableau de bord", "Activation", "Log integral", "Log OM"];

/**
 * To generate a dropdown list in HTML code from a list of data.
 * @param array $list : array of data to be displayed in the dropdaown list.
 * @param string $name : name of the dropdown list (HTML).
 * @param string $id : id of the dropdown list (HTML).
 * @param string $class : CSS class of the dropdown list (HTML).
 * @return string : returns the HTML code to build the dropdown list. 
 */
function generateHtmlOptionList(array $list, string $name, string $id, string $class, string $value): string {
    array_unshift($list, "");
    $optionlisthtml = "<select name=\"{$name}\" id=\"{$id}\" class=\"{$class}\">\n";
    foreach($list as $pos) {
        if($pos==$_SESSION[$value]) {
            $selected = " selected";
        } else {
            $selected = "";
        }
        $optionlisthtml = "{$optionlisthtml}\t<option value=\"{$pos}\"{$selected}>{$pos}</option>\n";
    }
    return $optionlisthtml = "{$optionlisthtml}</select>\n";
}

/**
 * To generate the HTML code to build the dropdown list from data columns in the database.
 * @param string $table : table in the database where the data column is located.
 * @param string $idlist : name of the column containing the data list.
 * @param string $name : name of the dropdaow list.
 * @return string : returns the HTML code to build the dropdown list.
 */
function generateList(string $table, string $idlist, string $name): string {
    $list = DataBaseTM24h::GetListBD($table, $idlist);
    return generateHtmlOptionList($list, $name, $name, "input-form", $name);
}

/**
 * To Generate a table in HTML format from a set of columns and values.
 * @param string $cols : columns header.
 * @param array $vals : table of data.
 * @param string $params : parameters for the table setup (HTML).
 * @return string : returns HTML code corresponding to the table to display.
 */
function generateHtmlTable(string $cols, array $vals, string $params = ""): string {
    $cols = explode(',', $cols);
    $data = "<table {$params}>\n\t<thead>\n\t\t<tr>\n";
    foreach($cols as $v) {
        $data = "{$data}\t\t\t<th>{$v}</th>\n";
    }
    $data = "{$data}\t\t</tr>\n\t</thead>\n\t<tbody>\n";
    foreach($vals as $v) {
        $data = "{$data}\t\t<tr>\n";
        foreach($v as $v2) {
            $data = "{$data}\t\t\t<td>{$v2}</td>\n";
        }
        $data = "{$data}\t\t</tr>\n";
    }
    $data = "{$data}\t</tbody>\n</table>\n";
    return $data;
}

/**
 * Display the data from the database data_tm24h on the main view.
 * @param string $callsign : callsign from the form.
 * @param string $pwd : password from the form.
 * @param string $band : band from the form.
 * @param string $mode : mode from the form.
 * @param float $frequence : freqience from the form.
 * @param bool $status : status from the form.
 * @return void
 */
function showAllDataTM24h(): string {
    $DataTM24h = new DataTM24h("", "", "", "", 0, false);
    $data = $DataTM24h->GetDataTM24h();
    if(!empty($data)) {
        $nbrecords = count($data);
        return generateHtmlTable("Band, Mode, OM, Frequence (MHz), Activation start (UTC), Activation End (UTC), Status", $data, 'name="table" id="table" class="table"') . "<br><p class=\"records\">Records : {$nbrecords}</p>";
    } else {
        show_message("Pas de données disponibles.", false);
        return "";
    }
}

/**
 * Display the data from the database data_tm24h on the main view for the concerned OM only.
 * @param string $callsign : callsign from the form.
 * @return string
 */
function showOMDataTM24h(string $callsign): string {
    $DataTM24h = new DataTM24h($callsign, "", "", "", 0, false);
    $data = $DataTM24h->GetDataTM24hOM();
    if(!empty($data)) {
        $nbrecords = count($data);
        return generateHtmlTable("Band, Mode, Frequence (MHz), Activation start (UTC), Activation End (UTC)", $data, 'name="table" id="table" class="table"') . "<br><p class=\"records\">Records : {$nbrecords}</p>";
    } else {
        show_message("Pas de données disponibles.", false);
        return "";
    }
}

/**
 * Display the static table showing all active and non active bands.
 * @return string
 */
function showStaticTable(): string {
    $listband = DataBaseTM24h::GetListBD("band_tm24h", "bandtm24h");
    $listmode = DataBaseTM24h::GetListBD("mode_tm24h", "modetm24h");
    $DataTM24h = new DataTM24h("", "", "", "", 0, false);
    foreach($listband as $i => $linelistband) {
        foreach($listmode as $j => $linelistmode) {
            $data[] = $DataTM24h->getDataStaticTable($linelistband, $linelistmode);
        }
    }
    foreach($data as $tab1 => $val1) {
        foreach($val1 as $tab2 => $val2) {
            foreach($val2 as $tab3 => $val3) {
                $new_tab[$tab1][$tab3]=$val3;
            }
        }
    }
    return generateHtmlTable("Band, Mode, OM, Frequence (MHz), Activation start (UTC), Activation End (UTC), Status", $new_tab, 'name="table" id="table" class="table"');
}

// Checking of the input data from the form through the HTTP request and to avoid CSRF attack.
if (($_SERVER['REQUEST_METHOD'] === 'POST') && !empty($_POST)) {
    $callsign = $_POST['callsign'];
    $_SESSION['callsign'] = $callsign;
    $pwd = $_POST['pwd'];
    $_SESSION['pwd'] = $pwd;
    $action = $_POST['action'];
    $_SESSION['action'] = $action;
    if (!isset($callsign) || $callsign=="") {
        show_message("OM non sélectionné.", true);
    }
    else {
        filter_form($callsign);
    }
    if (!isset($action) || $action=="") {
        show_message("Quelle action souhaitez-vous faire SVP ?", true);
    }
    else {
        filter_form($action);
    }
    if($action=="Activation") {
        $band = $_POST['band'];
        $_SESSION['band'] = $band;
        $mode = $_POST['mode'];
        $_SESSION['mode'] = $mode;
        $frequence = $_POST['frequence'];
        $_SESSION['frequence'] = $frequence;
        $status = $_POST['status'];
        $_SESSION['status'] = $status;
        if (!isset($mode) || $mode=="") {
            show_message("Mode non renseigné.", true);
        }
        else {
            filter_form($mode);
        }
        if (!isset($frequence)) {
            show_message("Frequence non renseignée.", true);
        }
        else {
            // Checking if the entered frequency is within the allowable range.
            $pdo = DataBaseTM24h::setPDO();
            $query = $pdo->prepare('SELECT bandtm24hmin, bandtm24hmax FROM band_tm24h WHERE bandtm24h = :band');
            $query->bindValue(':band', $band, PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetchall(PDO::FETCH_NUM);
            $pdo = DataBaseTM24h::closePDO();
            $frequencemin = floatval($result[0][0]);
            $frequencemax = floatval($result[0][1]);
            if($frequence>=$frequencemin && $frequence<=$frequencemax) {
                filter_form($frequence);
            } else {
                show_message("Fréquence en dehors de la plage autorisée dans le TNRBF.", true);
            }
        }
        if (!isset($status) || $status=="") {
            show_message("Status non renseigné.", true);
        }
        else {
            filter_form($status);
        }
    }
    if (empty($_SESSION['csrf_token']) || empty($_POST['csrf_token']) || ($_SESSION['csrf_token'] !== $_POST['csrf_token'])) {
        $_SESSION['csrf'] = true;
    }
}

// To generate a new CSRF token.
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
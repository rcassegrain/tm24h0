<?php
/**
 * Show message in the initial view by using CSS class .msg.
 * @param string $msg : message to display in the initial view.
 * @param bool $exit : exit code if it is set to true.
 */
function show_message(string $msg, bool $exit) {
    if($exit) {
        echo "<html><link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\"/><p class=\"msg\">{$msg}</p></html>";
    } else {
        echo "<p class=\"msg\">{$msg}</p>";
    }
}

/**
 * Filter the data entered in the form to avoid SQL attack.
 * @param mixed $data : data entered in the fields of the form.
 * @return string : returns cleaned data.
 */
function filter_form($data): string {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES|ENT_HTML5, 'UTF-8');
    return $data;
}

/**
 * Static class database for safe database connexion.
 */
class DataBaseTM24h {
    /**
     * PDO activation for database access.
     * @return PDO : returns the active PDO object.
     */
    public static function setPDO() :pdo {
        $url = getenv('JAWSDB_MARIA_URL');
        if($url!==false) {
            $dbparts = parse_url($url);
            $host = $dbparts['host'];
            $user = $dbparts['user'];
            $password = $dbparts['pass'];
            $dbname = ltrim($dbparts['path'],'/');
        } else {
            $host = 'localhost';
            $user = 'user_activation_tm24h';
            $password = 'K}Sc)L_9wg2)6]N';
            $dbname = 'activation_tm24h';
        }
        try {    
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            show_message( "Erreur de connexion à la base de données : " . $e->getMessage(), false);
        }
        return $pdo;
    }

    /**
     * Close the active PDO.
     * @return null
     */
    public static function closePDO(): null {
        return null;
    }

    /**
     * To get the data list of specified column from a table.
     * @param string $table : table to search into.
     * @param string $idlist : column name.
     * @return array : returns the data list in 1D array.
     */
    public static function GetListBD(string $table, string $idlist): array {
        $pdo = self::setPDO();
        $query = $pdo->prepare('SELECT ' . $idlist . ' FROM '. $table);
        $query->execute();
        $result = $query->fetchall(PDO::FETCH_COLUMN);
        $pdo = self::closePDO();
        return $result;
    }

    /**
     * Modification of the password to be able to proceed with the activation.
     * @param string $pwd : password to modifiy.
     * @return void
     */
    public static function SetPwdTM24h(string $pwd) {
        $pdo = self::setPDO();
        $query = $pdo->prepare('UPDATE pwd_tm24h SET pwdtm24h = :pwd WHERE Idpwdtm24h = 0');
        $query->bindValue(':pwd', password_hash($pwd, PASSWORD_BCRYPT), PDO::PARAM_STR);
        $query->execute();
        $pdo = self::closePDO();
    }

    /**
     * Remove all data in the table data_tm24h.
     * @return void
     */
    public static function resetBDTM24h() {
        $pdo = self::setPDO();
        $query = $pdo->prepare('DELETE FROM data_tm24h');
        $query->execute();
        $pdo = self::closePDO();
    }
}

/**
 * OM and password management class.
 */
class OMTM24h {
    private string $callsign;
    private string $password;
    
    /**
     * Constructor function of OMTM24h class.
     * @param string $callsign : callsign from the form.
     * @param string $password : password from the form.
     */
    public function __construct(string $callsign, string $password) {
        $this->callsign = $callsign;
        $this->password = $password;
    }

    /**
     * Getter for callsign parameter.
     * @return string : returns the callsign from the form.
     */
    public function getCallsign(): string {
        return $this->callsign;
    }

    /**
     * Setter for callsign parameter.
     * @param string $callsign : callsign from the form.
     * @return void
     */
    public function setCallsign(string $callsign) {
        $this->callsign = $callsign;
    }

    /**
     * Getter for password parameter.
     * @return string : returns the password from the form.
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * Setter for password parameter.
     * @param string $password : password from the form.
     * @return void
     */
    public function setPassword(string $password) {
        $this->password = $password;
    }

    /**
     * Check if the callsign exists already in the database data_tm24h.
     * @return bool : returns a boolean value if it exists otherwise returns false.
     */
    public function CheckOMBD(): bool {
        $callsign = $this->getCallsign();
        $password = $this->getPassword();
        $pdo = DataBaseTM24h::setPDO();
        $stmtuser = $pdo->prepare('SELECT omtm24h FROM om_tm24h WHERE omtm24h = :callsign');
        $stmtuser->bindValue(':callsign', $callsign, PDO::PARAM_STR);
        $stmtpwd = $pdo->prepare('SELECT pwdtm24h FROM pwd_tm24h');
        $stmtuser->execute();
        $user = $stmtuser->fetchall(PDO::FETCH_COLUMN);
        if(empty($user)) {
            show_message("OM non identifié.", false);
            return false;
        } else {
            $stmtpwd->execute();
            $pwd = $stmtpwd->fetchall(PDO::FETCH_COLUMN);
            $pdo = DataBaseTM24h::closePDO();
            if(password_verify( $password,$pwd[0])) {
                return true;
            } else {
                show_message("Mot de passe incorrect, veuillez réessayer.", false);
                return false;
            }
        }
    }
}

/**
 * Data management in the SQL database data_tm24h.
 */
class DataTM24h {
    private string $callsign;
    private string $pwd;
    private string $band;
    private string $mode;
    private float $frequence;
    private string $status;
    
    /**
     * Constructor function of DataTM24h class.
     * @param string $callsign : callsign from the form.
     * @param string $pwd : password from the form.
     * @param string $band : band from the form.
     * @param string $mode : mode from the form.
     * @param float $frequence : frequence from the form.
     * @param string $status : status of activation from the form.
     */
    public function __construct(string $callsign, string $pwd, string $band, string $mode, float $frequence, string $status) {
        $this->callsign = $callsign;
        $this->pwd = $pwd;
        $this->band = $band;
        $this->mode = $mode;
        $this->frequence = $frequence;
        $this->status = $status;
    }

    /**
     * Getter for callsign parameter.
     * @return string : returns callsign from the form.
     */
    public function getCallsign(): string {
        return $this->callsign;
    }

    /**
     * Setter for callsign parameter.
     * @param string $callsign : callsign from the form.
     * @return void
     */
    public function setCallsign(string $callsign) {
        $this->callsign = $callsign;
    }

    /**
     * Getter for password parameter.
     * @return string : returns the password from the form.
     */
    public function getPwd(): string {
        return $this->pwd;
    }

    /**
     * Setter for password parameter.
     * @param string $pwd : password from the form.
     * @return void
     */
    public function setPwd(string $pwd) {
        $this->pwd = $pwd;
    }

    /**
     * Getter for band parameter.
     * @return string : returns the band from the form.
     */
    public function getBand(): string {
        return $this->band;
    }

    /**
     * Setter for band parameter.
     * @param string $band : band from the form.
     * @return void
     */
    public function setBand(string $band) {
        $this->band = $band;
    }

    /**
     * Getter for mode parameter.
     * @return string : returns the mode from the form.
     */
    public function getMode(): string {
        return $this->mode;
    }

    /**
     * Setter for mode parameter.
     * @param string $mode : mode from the form.
     * @return void
     */
    public function setMode(string $mode) {
        $this->mode = $mode;
    }

    /**
     * Getter for frequence parameter.
     * @return float : returns the frequence from the form.
     */
    public function getFrequence(): float {
        return $this->frequence;
    }

    /**
     * Setter for frequence parameter.
     * @param float $frequence : frequence from the form.
     * @return void
     */
    public function setFrequence(float $frequence) {
        $this->frequence = $frequence;
    }

    /**
     * Getter for status parameter.
     * @return bool : returns the activation status from the form (true or false).
     */
    public function getStatus(): bool {
        $status = $this->status;
        if($status=="Active") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Setter for status parameter.
     * @param bool $status : activation status from the form.
     * @return void
     */
    public function setStatus(): string {
        $status = $this->status;
        if($status) {
            return "Active";
        } else {
            return "Inactive";
        }
    }

    /**
     * Getter to request the full data list of a column from the corresponding table.
     * @param string|int $value : value or id to be searched into the specified column.
     * @param string $table : table where the column is located.
     * @param string $column : column where the value or id has to be searched.
     * @param string $idvalue : column where the id or value to be returned.
     * @return string|int : returns the value or id.
     */
    public function getData(string|int $value, string $table, string $column, string $idvalue): string|int {
        $pdo = DataBaseTM24h::setPDO();
        $query = $pdo->prepare('SELECT * FROM ' . $table . ' WHERE ' . $column . ' = :value');
        $query->bindValue(':value', $value, PDO::PARAM_STR|PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $pdo = DataBaseTM24h::closePDO();
        return $result[$idvalue];
    }

    /**
     * To write a set of data from the form in the the database data_tm24h (new line or update of an existing line)
     * @return void
     */
    public function SetDataTM24h() {
        // Collecting data to write.
        $callsign = $this->getCallsign();
        $band = $this->getBand();
        $mode = $this->getMode();
        $frequence = number_format($this->getFrequence(), 3,".", "");
        date_default_timezone_set('UTC');
        $status = $this->getStatus();
        $date = date_create()->format('Y-m-d H:i:s');
        $idcallsign = $this->getData($callsign, "om_tm24h", "omtm24h", "idomtm24h");
        $idband = $this->getData($band, "band_tm24h", "bandtm24h", "idbandtm24h");
        $idmode = $this->getData($mode, "mode_tm24h", "modetm24h", "Idmodetm24h");
        // Checking if activation already done.
        $pdo = DataBaseTM24h::setPDO();
        $query = $pdo->prepare('SELECT * FROM data_tm24h WHERE fkIdbandtm24h = :band AND fkidmodetm24h = :mode AND fkidomtm24h = :callsign AND datafreq = :frequence');
        $query->bindValue(':band', $idband, PDO::PARAM_INT);
        $query->bindValue(':mode', $idmode, PDO::PARAM_INT);
        $query->bindValue(':callsign', $idcallsign, PDO::PARAM_INT);
        $query->bindValue(':frequence', $frequence, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchall(PDO::FETCH_COLUMN);
        $pdo = DataBaseTM24h::closePDO();
        // Activation does not exist in the database.
        if(empty($result)) {
            // Activation to be executed.
            if($status=="Active") {
                $pdo = DataBaseTM24h::setPDO();
                $query = $pdo->prepare('INSERT INTO data_tm24h(fkIdbandtm24h, fkidmodetm24h, fkidomtm24h, datafreq, dataactivationstart, datastatus) VALUES(:band, :mode, :callsign, :frequence, :date, :status)');
                $query->bindValue(':band', $idband, PDO::PARAM_INT);
                $query->bindValue(':mode', $idmode, PDO::PARAM_INT);
                $query->bindValue(':callsign', $idcallsign, PDO::PARAM_INT);
                $query->bindValue(':frequence', $frequence, PDO::PARAM_STR);
                $query->bindValue(':date', $date, PDO::PARAM_STR);
                $query->bindValue(':status', $status, PDO::PARAM_BOOL);
                $query->execute();
                $pdo = DataBaseTM24h::closePDO();
            } else {
                show_message("Activation non effectuée au prélable, désactivation impossible.", false);
            }
        // Activation exists already in the database.
        } else {
            if($status=="Active") {
                show_message("Activation déjà effectuée.", false);
            } else {
                $pdo = DataBaseTM24h::setPDO();
                $query = $pdo->prepare('SELECT dataactivationstart, dataactivationend FROM data_tm24h WHERE fkIdbandtm24h = :band AND fkidmodetm24h = :mode AND fkidomtm24h = :callsign AND datafreq = :frequence');
                $query->bindValue(':band', $idband, PDO::PARAM_INT);
                $query->bindValue(':mode', $idmode, PDO::PARAM_INT);
                $query->bindValue(':callsign', $idcallsign, PDO::PARAM_INT);
                $query->bindValue(':frequence', $frequence, PDO::PARAM_STR);
                $query->execute();
                $result2 = $query->fetchall(PDO::FETCH_NUM);
                $pdo = DataBaseTM24h::closePDO();
                if($result2[0][0]!="" && $result2[0][1]!="") {
                    $status = false;
                }
                $pdo = DataBaseTM24h::setPDO();
                $query = $pdo->prepare('UPDATE data_tm24h SET dataactivationend = :date, datastatus = :status WHERE (IdData = :id)');
                $query->bindValue(':date', $date, PDO::PARAM_STR);
                $query->bindValue(':status', $status, PDO::PARAM_BOOL);
                $query->bindValue(':id', $result[0], PDO::PARAM_INT);
                $query->execute();
                $pdo = DataBaseTM24h::closePDO();
            }
        }
    }

    /**
     * Getter to read the full database content in the database data_tm24h.
     * @return array : returns an array with all set of data.
     */
    public function GetDataTM24h(): array {
        $pdo = DataBaseTM24h::setPDO();
        $query = $pdo->prepare('SELECT fkIdbandtm24h, fkidmodetm24h, fkidomtm24h, datafreq, dataactivationstart, dataactivationend, datastatus FROM data_tm24h ORDER BY fkIdbandtm24h, fkidmodetm24h, fkidomtm24h ASC');
        $query->execute();
        $result = $query->fetchall(PDO::FETCH_NUM);
        $pdo = DataBaseTM24h::closePDO();
        if(!empty($result)) {
            foreach($result as $j => $linedata) {
                foreach($linedata as $i => $columdata) {
                    if($i<=2) {
                        switch ($i) {
                            case 0:
                                $table = "band_tm24h";
                                $column = "idbandtm24h";
                                $idvalue = "bandtm24h";
                                break;
                            case 1:
                                $table = "mode_tm24h";
                                $column = "idmodetm24h";
                                $idvalue = "modetm24h";
                                break;
                            case 2:
                                $table = "om_tm24h";
                                $column = "idomtm24h";
                                $idvalue = "omtm24h";
                                break;
                        }
                        $columdata = $this->getData($columdata, $table,  $column, $idvalue);
                    }
                    if($i==4 || $i==5) {
                        if($columdata==null) {
                            $columdata = "";
                        } else {
                            $columdata = date('d/m/Y H:i:s',strtotime($columdata));
                        }
                    }
                    if($i==6) {
                        if($columdata==true) {
                            $columdata = '<img src="Onair.svg" alt="On air" class="pictures-table" role="img">';
                        } else {
                            $columdata = '<img src="Activate.svg" alt="Play" class="pictures-table" role="img">';
                        }
                    }
                    $linedata[$i] = $columdata;
                }
                $result[$j] = $linedata;
            }
        }
        return $result;
    }

    /**
     * Getter to read the data related to a callsign in the database data_tm24h.
     * @param string $callsign : call sign from the form.
     * @return array : returns an array with all set of data.
     */
    public function GetDataTM24hOM(): array {
        $callsign = $this->getCallsign();
        $idcallsign = $this->getData($callsign, "om_tm24h", "omtm24h", "idomtm24h");
        $pdo = DataBaseTM24h::setPDO();
        $query = $pdo->prepare('SELECT fkidbandtm24h, fkidmodetm24h, datafreq, dataactivationstart, dataactivationend FROM data_tm24h WHERE fkidomtm24h = :callsign AND dataactivationstart <> "NULL" AND dataactivationend <> "NULL" ORDER BY fkidbandtm24h, fkidmodetm24h ASC');
        $query->bindValue(':callsign', $idcallsign, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchall(PDO::FETCH_NUM);
        $pdo = DataBaseTM24h::closePDO();
        if(!empty($result)) {
            foreach($result as $j => $linedata) {
                foreach($linedata as $i => $columdata) {
                    if($i<=1) {
                        switch ($i) {
                            case 0:
                                $table = "band_tm24h";
                                $column = "idbandtm24h";
                                $idvalue = "bandtm24h";
                                break;
                            case 1:
                                $table = "mode_tm24h";
                                $column = "idmodetm24h";
                                $idvalue = "modetm24h";
                                break;
                        }
                        $columdata = $this->getData($columdata, $table,  $column, $idvalue);
                    }
                    if($i==3 || $i==4) {
                        $columdata = $columdata = date('d/m/Y H:i:s',strtotime($columdata));
                    }
                    $linedata[$i] = $columdata;
                }
                $result[$j] = $linedata;
            }
        }
        return $result;
    }

    /**
     * Getter to select the lines corresponding to a specific mode and band.
     * @param string $band : specific band.
     * @param string $mode : specific mode.
     * @return array : returns an array with all set of data.
     */
    public function getDataStaticTable(string $band, string $mode): array {
        $idband = $this->getData($band, "band_tm24h", "bandtm24h", "idbandtm24h");
        $idmode = $this->getData($mode, "mode_tm24h", "modetm24h", "Idmodetm24h");
        $pdo = DataBaseTM24h::setPDO();
        $query = $pdo->prepare('SELECT fkidbandtm24h, fkidmodetm24h, fkidomtm24h, datafreq, dataactivationstart, dataactivationend, datastatus FROM data_tm24h WHERE (fkIdbandtm24h = :band AND fkidmodetm24h = :mode)');
        $query->bindValue(':band', $idband, PDO::PARAM_INT);
        $query->bindValue(':mode', $idmode, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchall(PDO::FETCH_NUM);
        $pdo = DataBaseTM24h::closePDO();
        if(empty($result)) {
            $result=[[$idband, $idmode, "", "", "", "", false]];
        }
        foreach($result as $j => $linedata) {
            foreach($linedata as $i => $columdata) {
                if($i<=2) {
                    switch ($i) {
                        case 0:
                            $table = "band_tm24h";
                            $column = "idbandtm24h";
                            $idvalue = "bandtm24h";
                            break;
                        case 1:
                            $table = "mode_tm24h";
                            $column = "idmodetm24h";
                            $idvalue = "modetm24h";
                            break;
                        case 2:
                            $table = "om_tm24h";
                            $column = "idomtm24h";
                            $idvalue = "omtm24h";
                            break;
                    }
                    if($columdata!="") {
                        $columdata = $this->getData($columdata, $table,  $column, $idvalue);
                    }
                }
                if($i==4 || $i==5) {
                    if($columdata==null) {
                        $columdata = "";
                    } else {
                        $columdata = $columdata = date('d/m/Y H:i:s',strtotime($columdata));
                    }
                }
                if($i==6) {
                    if($columdata==true) {
                        $columdata = '<img src="Onair.svg" alt="On air" class="pictures-table" role="img">';
                    } else {
                        $columdata = '<img src="Activate.svg" alt="Play" class="pictures-table" role="img">';
                    }
                }
                $linedata[$i] = $columdata;
            }
            $result[$j] = $linedata;
        }
        return $result;
    }
}
<?php
class MySqlOp {
    private $server = 'localhost';
    private $dbName = 'partner';
    private $dbUser = 'root';
    private $dbPass = '';
    private $dbEncoding = 'UTF-8';
    private $connID = null;
    private $errorMsgs = array();
    private static  $instance=null;
    const MYSQL_HOSTNAME = 'localhost';
    const MYSQL_DATABASE = 'tests';
    const MYSQL_USERNAME = 'root';
    const MYSQL_PASSWORD = '';
    const MYSQL_ENCODING = 'utf8';

    public $rows = array();

    public static function getInstance(){
        if(!self::$instance)
            self::$instance=new MySqlOp(self::MYSQL_HOSTNAME, self::MYSQL_DATABASE, self::MYSQL_USERNAME, self::MYSQL_PASSWORD, self::MYSQL_ENCODING);
        return self::$instance;
    }

    private function __construct($server, $dbName, $dbUser, $dbPass, $encoding='') {
        $this->server = $server;
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
        $this->dbEncoding = $encoding;
    }

    private function __clone(){}

    private function __sleep(){}

    public function __destruct() {
    }

    private function Connect() {
        if ((!isset($this->connID))or(!@mysql_ping($this->connID))) {
            if (!$this->connID = @mysql_connect($this->server, $this->dbUser, $this->dbPass)) {
                $this->AddErrorMsg('MySQL ('.mysql_errno($this->connID).') '.mysql_error($this->connID), __FUNCTION__, __LINE__);
                return false;
            }
            if (!@mysql_select_db($this->dbName, $this->connID)) {
                $this->AddErrorMsg('MySQL ('.mysql_errno($this->connID).') '.mysql_error($this->connID), __FUNCTION__, __LINE__);
                return $this->connID = false;
            }
            if (($this->dbEncoding != '')and(!@mysql_set_charset($this->dbEncoding, $this->connID))) {
                $this->AddErrorMsg('MySQL ('.mysql_errno($this->connID).') '.mysql_error($this->connID), __FUNCTION__, __LINE__);
                return false;
            }
        }
        return true;
    }

    public function SelectDb($dbName='') {
        if (!$this->connID) {
            $this->AddErrorMsg('There is no connection with SQL server', __FUNCTION__, __LINE__);
            return false;
        }

        if ($dbName == '') $dbName = $this->dbName;
        if (!@mysql_select_db($dbName, $this->connID))
            return false;

        return true;
    }

    public function Query($query) {
        $this->Connect();

        if (!$this->connID) {
            $this->AddErrorMsg('There is no connection with SQL server', __FUNCTION__, __LINE__);
            return false;
        }

        $this->rows = array();
        if ($result = mysql_query($query, $this->connID)) {
            if ($amount = @mysql_num_rows($result)) {
                for ($i=0; $i<$amount; $i++) {
                    $fetch = mysql_fetch_array($result, MYSQL_ASSOC);
                    $this->rows[] = $fetch;
                }
            }
        } else {
            $this->AddErrorMsg('MySQL ('.mysql_errno($this->connID).') '.mysql_error($this->connID), __FUNCTION__, __LINE__);
            return false;
        }

        return true;
    }

    public function GetRows($tableName, $clause='', $fieldsToRead=array()) {
        $this->Connect();

        if (!$this->connID) {
            $this->AddErrorMsg('There is no connection with SQL server', __FUNCTION__, __LINE__);
            return false;
        }

        $filedsString = count($fieldsToRead) ? implode(',', $fieldsToRead) : '*';
        $query = 'SELECT '.$filedsString.' FROM '.$tableName;
        $query .= ($clause != '') ? ' '.$clause : '';

        if ($this->Query($query) !== false) return count($this->rows);
        return false;
    }

    public function AddRow($tableName, $rowFields) {
        $this->Connect();

        if (!$this->connID) {
            $this->AddErrorMsg('There is no connection with SQL server', __FUNCTION__, __LINE__);
            return false;
        }

        if (!count($rowFields)) {
            $this->AddErrorMsg('There is no (empty) row fields to add', __FUNCTION__, __LINE__);
            return false;
        }

        $fieldNames = $fieldValues = array();
        foreach ($rowFields as $rowName=>$rowValue) {
            $fieldNames[] = '`'.mysql_real_escape_string($rowName).'`';
            $fieldValue[] = "'".mysql_real_escape_string($rowValue)."'";
        }

        $query = 'INSERT INTO '.$tableName.'('.implode(',', $fieldNames).') VALUES ('.implode(',', $fieldValue).')';
        if (!$this->Query($query)) {
            $this->AddErrorMsg('MySQL ('.mysql_errno($this->connID).') '.mysql_error($this->connID), __FUNCTION__, __LINE__);
            return false;
        }

        return mysql_insert_id($this->connID);
    }

    public function UpdateRow($tableName, $rowFields, $whereClasue) {
        $this->Connect();

        if (!$this->connID) {
            $this->AddErrorMsg('There is no connection with SQL server', __FUNCTION__, __LINE__);
            return false;
        }

        if (!count($rowFields)) {
            $this->AddErrorMsg('There is no (empty) row fields to update', __FUNCTION__, __LINE__);
            return false;
        }

        $fieldsNameValue = array();
        foreach ($rowFields as $fieldName=>$fieldValue) {
            $fieldsNameValue[] = '`'.mysql_real_escape_string($fieldName).'`="'.mysql_real_escape_string($fieldValue).'"';
        }

        $query = 'UPDATE '.$tableName.' SET '.implode(',', $fieldsNameValue).' WHERE '.$whereClasue;
        return $this->Query($query);
    }

    public function DeleteRow($tableName, $whereClasue) {
        $this->Connect();

        if (!$this->connID) {
            $this->AddErrorMsg('There is no connection with SQL server', __FUNCTION__, __LINE__);
            return false;
        }

        $query = 'DELETE FROM '.$tableName.' WHERE '.$whereClasue;
        return $this->Query($query);
    }

    public function GetErrorMsgs () {
        return $this->errorMsgs;
    }

    private function AddErrorMsg($msg, $function, $line) {
        $this->errorMsgs[] = 'ERROR: '.$function.' ['.$line.'] "'.$msg.'"';
    }
}

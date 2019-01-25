<?php
/*
Copyright (c) 2019 Josiah Spackman

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE
*/

require_once dirname(__FILE__) . "/../config.php";

// Store and manage users info
class token_user {

    private $_mysqli;
    private $addr;
    public function __construct($addr = null, $host = DIGIID_DB_HOST, $user = DIGIID_DB_USER, $pass = DIGIID_DB_PASS, $name = DIGIID_DB_NAME) {
        @$this->_mysqli = new mysqli($host, $user, $pass, $name);
	if ($this->_mysqli->connect_errno) die ($this->_mysqli->connect_error);
	$this->addr = $addr;

        $this->checkInstalled();
    }

    /**
      * Create tables if not exists
      * @return bool
      */
    public function checkInstalled() {
        $required_tables = array (
            DIGIID_TBL_PREFIX . 'users' => '
                CREATE TABLE `' . DIGIID_TBL_PREFIX . "users` (
                    `addr` varchar(46) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
                    `fio` varchar(60) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
                    `isadmin` int(1) NOT NULL DEFAULT '0' COMMENT 'User is an Admin?',
                    `ispermitted` int(1) NOT NULL DEFAULT '0' COMMENT 'User is permitted to access?',
                    PRIMARY KEY (`addr`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
        );

        foreach ($required_tables as $name => $sql) {
            $table_exists = ($test = $this->_mysqli->query("SHOW TABLES LIKE '$name'")) && $test->num_rows == 1;
            if (!$table_exists) $this->_mysqli->query($sql);
        }
    }

    /**
     * Insert user detail in the database
     *
     * @param $addr
     * @param array $info
     * @return bool|mysqli_result
     */
    public function insert($info) {
        return $this->_mysqli->query(sprintf("INSERT INTO " . DIGIID_TBL_PREFIX . "users (`addr`, `fio`) VALUES ('%s', '%s')", $this->_mysqli->real_escape_string($this->addr), $this->_mysqli->real_escape_string($info['fio'])));
    }

    /**
     * Update table with user info
     *
     * @param $nonce
     * @param $address
     * @return bool|mysqli_result
     */
    public function update($info) {
        return $this->_mysqli->query(sprintf("UPDATE " . DIGIID_TBL_PREFIX . "users SET fio = '%s' WHERE addr = '%s' ", $this->_mysqli->real_escape_string($info['fio']), $this->_mysqli->real_escape_string($this->addr)));
    }

    /**
     * Request access to the system for the current user
     *
     * @param $nonce
     * @param $address
     * @return bool|mysqli_result
     * ispermitted uses 0 for unapproved, 1 for approved, 2 for rejected
     */
    public function requestaccess($info) {
        return $this->_mysqli->query(sprintf("UPDATE " . DIGIID_TBL_PREFIX . "users SET ispermitted = '1' WHERE addr = '%s' ", $this->_mysqli->real_escape_string($this->addr)));
    }

    /**
     * Grant or deny access to the system for the shown
     *
     * @param $nonce
     * @param $address
     * @return bool|mysqli_result
     * ispermitted uses 0 for unapproved, 1 for approved, 2 for rejected
     */
    public function grantaccess($info) {
        return $this->_mysqli->query(sprintf("UPDATE " . DIGIID_TBL_PREFIX . "users SET ispermitted = '%s' WHERE addr = '%s' ", $this->_mysqli->real_escape_string($info['ispermitted']), $this->_mysqli->real_escape_string($this->addr)));
    }


    /**
     * Forget the user
     *
     * @param $address
     * @return bool|mysqli_result
     */
    public function delete() {
        return $this->_mysqli->query(sprintf("DELETE FROM " . DIGIID_TBL_PREFIX . "users WHERE addr = '%s' ", $this->_mysqli->real_escape_string($this->addr)));
    }

    /**
     * Get current user info
     *
     * @return array
     */
    public function get_info() {
        $result = $this->_mysqli->query($sql = sprintf("SELECT fio FROM " . DIGIID_TBL_PREFIX . "users WHERE addr = '%s'", $this->_mysqli->real_escape_string($this->addr)));
        if($result) {
            $row = $result->fetch_assoc();
            if(count($row)) return $row;
        }
        return false;
    }

    /**
     * Get user permissions
     *
     * @return array
     */
    public function get_permissions() {
        $result = $this->_mysqli->query($sql = sprintf("SELECT isadmin,ispermitted FROM " . DIGIID_TBL_PREFIX . "users WHERE addr = '%s'", $this->_mysqli->real_escape_string($this->addr)));
        if($result) {
            $row = $result->fetch_assoc();
            if(count($row)) return $row;
        }
        return false;
    }

    /**
     * Get pending users that need to be allowed / denied
     *
     * @return array
     */
    public function get_pending_requests() {
        $query = $this->_mysqli->query(sprintf("SELECT addr,fio FROM " . DIGIID_TBL_PREFIX . "users WHERE ispermitted='0'"));
        return mysqli_fetch_all($query,MYSQLI_ASSOC);
    }

    /**
     * Get the first Admin user
     *
     * @return array
     */
    public function get_an_admin() {
        $result = $this->_mysqli->query($sql = sprintf("SELECT fio FROM " . DIGIID_TBL_PREFIX . "users WHERE isadmin='1'", $this->_mysqli->real_escape_string($this->addr)));
        if($result) {
            $row = $result->fetch_assoc();
            if(count($row)) return $row;
        }
        return false;
    }

    /**
     * Make this user the first admin user
     *
     * @param $address
     * @return bool|mysqli_result
     * isadmin uses 0 for normal user and 1 for administrative user
     */
    public function initialadmin($info) {
        return $this->_mysqli->query(sprintf("UPDATE " . DIGIID_TBL_PREFIX . "users SET isadmin = '1' WHERE addr = '%s' ", $this->_mysqli->real_escape_string($this->addr)));
    }




}

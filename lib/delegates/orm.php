<?php
/**
 * Delegate for basic ORM capabilities.
 * For now, requires Mysqli
 *
 * @author Lucas Oman <me@lucasoman.com>
 */

namespace Phling\Delegates;

class Orm extends \Phling\Delegate {
	/**
	 * set the name of the table for this class
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string table name
	 * @return null
	 */
	public function setTable($table) {
		$this->_table = mysql_real_escape_string((string)$table);
		$this->_tableData = $this->getTableData($this->_table);
	}

	/**
	 * set the primary id column for this class
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string id column name
	 * @return null
	 */
	public function setIdColumn($column) {
		$this->_idColumn = mysql_real_escape_string((string)$column);
	}

	/**
	 * set the mysqli object for this model to use
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param Mysqli object
	 * @return null
	 */
	public function setMysqli($mysqli) {
		$this->_mysqli = $mysqli;
	}

	/**
	 * load the object from the db
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param int id for the object
	 * @return bool successful?
	 */
	public function load($id) {
		$cleanId = (int)$id;
		$this->getDelegator()->id = $cleanId;
		$query = "
			SELECT
				*
			FROM
				{$this->_table}
			WHERE
				{$this->_idColumn}={$cleanId}
			LIMIT 1
			";
		if (($resource = $this->_mysqli->query($query)) && ($row = $resource->fetch_assoc())) {
			foreach ($row as $key=>$value) {
				$this->getDelegator()->$key = $value;
			}
			$this->_columns = array_keys($row);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * save this model to the db
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @return saved primary id or bool false if failure
	 */
	public function save() {
		$columns = array();
		foreach ($this->_columns as $colName) {
			$columns[] = "{$colName}='".mysql_real_escape_string($this->getDelegator()->$colName)."'";
		}
	}

	/**
	 * retrieve cached column metadata or retrieve new
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string table name (mysql escaped)
	 * @return array table data or false on failure
	 */
	private function getTableData($table) {
		if (isset(self::$_tables[$table])) {
			return self::$_tables[$table];
		} else {
			$query = "desc {$this->_table}";
			if ($resource = $this->_msqli->query($query)) {
				//this needs to be finished
			} else {
				return false;
			}
		}
	}

	/**
	 * table name
	 *
	 * @var string
	 */
	private $_table;

	/**
	 * primary id column name
	 *
	 * @var string
	 */
	private $_idColumn;

	/**
	 * mysqli object to use for db access
	 *
	 * @var mysqli
	 */
	private $_mysqli;

	/**
	 * all column names in the table
	 *
	 * @var array
	 */
	private $_columns;

	/**
	 * meta data about this table's columns
	 *
	 * @var array
	 */
	private $_tableData;

	/**
	 * cached table data
	 *
	 * @var array
	 */
	protected static $_tables = array();
}

?>

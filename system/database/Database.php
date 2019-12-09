<?php 
/**
 * Class to manage database interactions
 * 
 * Constants used by the constructor are defined in database_conf.php.
 * 
 * @author    Gregory Staimphin <staimphin.gregory@gmail.com>
 * @copyright Gregory Staimphin
 * @version   0.2
 * @var       object  $cnx       PDO Object for the connection
 * @var       object  $prepared  prepared statement handler
 * @var       boolean $status    status of the last query
 * @var       boolean $debugMode display debug info
 * @var       boolean $simMode   avoid running queries
 */
class Db
{
	/**
	 * The database object
	 *
	 * @access private
	 * @var object
	 */
	private $_connection;
	
	/**
	 * MySQLi database object
	 *
	 * @access private
	 * @var object
	 */
	private static $_instance;
	protected $prepared;
	protected $status;
	protected $logQuery;
	protected $_debug_data = array();

	protected $simMode;
	protected $Log;

	protected $data;
	protected $query;
	
	/**
	 * Constructor function
	 * 
	 * Here we explicitly enable emulated prepares because some queries fail
	 * if one named parameter is used more than once, like in User::getUserSession.
	 * 
	 * It's on by default but now we're sure it will stay that way. 
	 */
	private function __construct($config)
	{
        $this->Log = new Logger;
        $this->Log->log($config);
		try{
			$connection = new PDO(
				'mysql:host=' . $config['SERVER'] . ';dbname=' . $config['DATABASE'] . ';charset=utf8mb4',
                $config['DB_LOGIN'], $config['DB_PASS'],
				array(
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_EMULATE_PREPARES => true,
					PDO::ATTR_STRINGIFY_FETCHES => false
				)
			);
			$this->setDB($connection);
			$this->status          = true;
			$this->logQuery       = $config['log_query'] ;
			$this->simMode         = $config['debud_mode'] ;
		} catch(PDOException $ex) {
			$this->status = false;
            $this->Log->log($ex);
		}
	}
	
	public static function getInstance()
	{
		if (!self::$_instance) {
		    global $config;
			self::$_instance = new self($config['database']);
		}
		return self::$_instance;
	}
	
	/**
	 * Was the latest query successful?
	 * 
	 * @return bool Connection status
	 */
	public function isSuccessfull()
	{
		return $this->status;
	}
	
	/* PROTECTED functions */
	/**
	 * Store a PDO object into the local instance
	 * 
	 * @param object $pdo PDO Object to set
	 * 
	 * @return null
	 */
	protected function setDB($pdo)
	{
		$this->_connection = $pdo;
		
		return null;
	}
	
	/**
	 * Recover the PDO Object used by the instance
	 * 
	 * @return object PDO Object to get
	 */
	protected function getDB()
	{
		return $this->_connection;
	}
	
	/*
	 * All the CRUD (Create, Read, Update, Delete) functions of the object
	 */
	/**
	 * Example:
	 *  $dbh->doQuery('SELECT * FROM table WHERE id=1');
	 * 
	 * @param string  $query    SQL query to run directly
	 * @param boolean $noreturn when the query is INSERT, there is nothing to fetch, and this cause an error in a loop
	 * 
	 * @since  2016-08-02  direct query
	 * 
	 * @return array array indexed by column name as returned in the result set
	 */
	public function doQuery($query, $noreturn = false)
	{
		$result = $this->query($query, array());
		return ($result && $noreturn == false)
		       ? $result->fetchall(constant('PDO::FETCH_ASSOC'))
		       : array();
	}
	
	/**
	 * Prepared query execution
	 * 
	 * Examples:
	 *  To show everything about the user with the email address 'sender@host.com':
	 *    $dbh->query('SELECT * FROM users WHERE user_email = :sender', [['key' => 'sender', 'value' => 'sender@host.com', 'type' => 'PDO::PARAM_STR' ]]);
	 *  To list users from Scotland not having changed their password since X months ago:
     *    $dbh->query('SELECT * FROM users WHERE user_country = :country AND user_lastpasschange < DATE_SUB(NOW(), INTERVAL :nbmonths MONTH)', [['key' => 'country', 'value' => 'Scotland', 'type' => 'PDO::PARAM_STR'], ['key' =>, 'nbmonths', 'value' => '6', 'type' => 'PDO::PARAM_INT']] );
	 * 
	 * Parameters types are all the PDO::PARAM_ from http://php.net/manual/en/pdo.constants.php
	 * 
	 * @param string   $query prepared SQL query to run (with name placeholders)
	 * @param string[] $data  data to bind to the prepared query
	 * 
	 * @todo  support question mark placeholders
	 * @todo  check examples
	 * @todo  not sure about the return value...
	 * 
	 * @return bool|null query execution status, or null if simMode is true
	 */
	/////Refactoring in proresss
	public function query($query, $data)
	{
		$this->_debug_data = array();
		if ($this->simMode == true) {
			echo '*SimulationMode: Query is not Executed!*' . $query;
		} else {
			try {
				$preparedQuery = $this->_connection->prepare($query);
				
				if (is_array($data)) {
					foreach ($data as $binded) {
						$type = (isset($binded['type']))
							  ? $binded['type']
							  : constant('PDO::PARAM_STR');
						$preparedQuery->bindParam(':' . $binded['key'], $binded['value'], $type);
					}
				}
				$preparedQuery->execute();
				
					$this->_debug_data['data'] = $data;
					$this->_debug_data['query'] = $query;
					$this->_debug_data['prepared_query'] = $preparedQuery;
					//$this->_queryDebug('*debug mode on: query*' . $query);
					//$this->_queryDebug('query results*');
					//$this->_queryDebug($preparedQuery);

			} catch(PDOException $e) {
				if ($this->logQuery == true) {
					//$this->_queryDebug($e->getMessage());
					$this->_debug_data['error'] = $e->getMessage();
				}
			}
			if ($this->logQuery == true) {
				$this->showDebugInfo();
			}
			return $preparedQuery;
		}
		
		return null;
	}

/**
  * Refactored
  *
  *
  *
  */
	public function _query()
	{
		$this->_debug_data = array();
		if ($this->simMode == true) {
            $this->Log->log("*SimulationMode: Query is not Executed!*{$this->query}");
		} else {
					$this->_debug_data['data'] = $this->data;
					$this->_debug_data['query'] = $this->query;

			try {
				$preparedQuery = $this->_connection->prepare($this->query);
				
				if (is_array($this->data)) {
					foreach ($this->data as $binded) {
						$type = (isset($binded['type']))
							  ? $binded['type']
							  : constant('PDO::PARAM_STR');
						$preparedQuery->bindParam(':' . $binded['key'], $binded['value'], $type);
					}
				}
				
				$preparedQuery->execute();
				if ($this->logQuery == true) {
					$this->_debug_data['prepared_query'] = $preparedQuery;
				}
			} catch(PDOException $e) {
                $this->Log->log( $e->getMessage());
				if ($this->logQuery == true) {
					$this->_debug_data['error'] = $e->getMessage();
				}
			}
			if ($this->logQuery == true) {
				$this->showDebugInfo();
			}
			return $preparedQuery;
		}
		
		return null;
	}
	
	/**
	 * @author: gregory.staimphin@gmail.com
	 * 2019-08-03
	 * @param string   $table  table to import from
	 * @param string   $select column(s) to fetch
	 * @return array|null      array containing all of the result set rows
	 */
	public function select($table, $options)
	{
		$select = isset($options['select'])? $options['select'] : '*';
 		$where = isset($options['where'])? $options['where'] : '';
 		$data = isset($options['data'])? $options['data'] : array();
 		$fetch = isset($options['fetch'])? $options['fetch'] : PDO::FETCH_ASSOC;

		$query  = "SELECT {$select} FROM {$table}";
		$query .= ($where != '')
	             ? " WHERE {$where}"
		         : '';

		$result = $this->query($query, $data);
		return ($result)
		       ? $result->fetchall($fetch)
		       : null;
	}
	
	/**
	 * PDO UPDATE
	 * 
	 * @param string   $table    table to update
	 * @param string   $where    conditions
	 * @param string[] $whereKey array of data to bind to the prepared query
	 *                           array( array([key] => 'itemID',    [value] =>'' ,[type] => 1), ) 
	 * @param string[] $data     data to bind to the prepared query
	 * 
	 * @todo                     add checks against SQL injections
	 * 
	 * @return bool              query execution status
	 */
	public function update($table, $where, $whereKey = array(), $data = array())
	{
		$this->query = "UPDATE {$table} SET ";
		$query = '';
		for ($i=0, $max=count($data); $i<$max; $i++) {
			$row    = $data[$i];
			$query .= "{$row['key']} = :{$row['key']}";
			$query .= ($i != ($max-1))
			        ? ', '
			        : '';
		}
		
		$this->query .= " WHERE {$where}";
		$this->data   = array_merge($data, $whereKey);
		print_R($this->data);
		return $this->_query();
	}
	
	/**
	 * PDO INSERT
	 * 
	 * @param string  $table table to insert to
	 * @param array[] $data  data to bind to the prepared query
	 * 
	 * @todo                  add checks against SQL injections
	 * @todo                  add examples
	 * 
	 * @return bool           query execution status
	 */
	public function insert($table, $data = array())
	{
		$val = '';
		$col = '';
		$this->data = $data;
		/* Binding */
		for ($i=0, $max=count($data); $i<$max; $i++) {
			$row  = $data[$i];
			$eol  = ($i != ($max-1))
			      ? ', '
				  : '';
			$col .=(!empty( $row['key']))? "`{$row['key']}`{$eol}":'';
			$val .=(!empty( $row['key']))?  ":{$row['key']}{$eol}":'';
		}
		
		$this->query = "INSERT INTO {$table} ({$col}) VALUES ({$val})";
		return $this->_query();
	}
	
	/**
	 * prepare alias for PDO
	 * 
	 * http://php.net/manual/en/pdo.prepare.php
	 * 
	 * @param string $query the statement to prepare
	 * 
	 * @return object       the PDO object to be executed
	 */
	public function prepare($query)
	{
		$this->prepared = $this->_connection->prepare($query);
		
		return $this->prepared;
	}
	
	/**
	 * lastInsertId alias for PDO
	 * 
	 * http://php.net/manual/en/pdo.lastinsertid.php
	 * 
	 * @return string the ID of the last inserted row or sequence value
	 */
	public function lastInsertId()
	{
		return $this->_connection->lastInsertId();
	}


	public function getAutoIncrement($table)
	{
		$query = "SELECT `AUTO_INCREMENT` 
		FROM INFORMATION_SCHEMA.TABLES 
		WHERE TABLE_SCHEMA = '".DATABASE."' AND TABLE_NAME = '$table'";
		return $this->doQuery($query)[0]['AUTO_INCREMENT'];
	}
	
	/**
	 * errorInfo alias for PDO
	 * 
	 * http://php.net/manual/en/pdo.errorinfo.php
	 * 
	 * @return array error information (SQLSTATE code, driver code, driver message)
	 */
	public function errorInfo()
	{
		return $this->_connection->errorInfo();
	}
	
	/**
	 * PDO REPLACE
	 * 
	 * @param string   $table table to replace from/to
	 * @param string[] $data  data to bind to the prepared query
	 * 
	 * @todo                  add checks against SQL injections
	 * @todo                  add examples
	 * 
	 * @return bool           query execution status
	 */
	public function replace($table, $data)//create data
	{
		$this->query  = "REPLACE INTO $table ";
		$max    = count($data);
		$keys   = '';
		$values = '';
		/*build the query in order to match the prepare step*/
		for ($i = 0; $i < $max; $i++) {
			$row     = $data[$i];
			$separator    = ($i != ($max-1))
			          ? ', '
				      : '' ;
			$keys   .=  "`{$row['key']}` {$separator}";
			$values .= ":{$row['key']} {$separator}";
		}
		
		$this->query .= " ($keys) VALUE ($values)";
		$this->data = $data;
		
		return $this->_query();
	}
	


	/**
	 * PDO DELETE
	 * 
	 * @param string   $table      table to delete from
	 * @param string   $conditions conditions
	 * @param string[] $bind       data to bind to the prepared query
	 * 
	 * @todo                       add checks against SQL injections
	 * @todo                       add examples
	 * @todo                       explicitly return query execution status
	 * 
	 * @return null
	 */
	public function delete($table, $conditions, $bind = array())
	{
		$query = 'DELETE FROM ' . $table . ' WHERE ' . $conditions;
		
		return $this->query($query, $bind);
	}
	/**
	 * Sets the simMode property to debug queries
	 * 
	 * @param integer $mode ...
	 * 
	 * @return null
	 */
 	public function setSimMode($mode = 0)
	{
		$this->simMode = intval($mode);
		
		return null;
	}
	/* turn on or off the debug mode*/
	public function setLogQuery($mode=0)
	{
		$this->logQuery       = intval($mode);
	}
	/* should log into a file instead*/
	private function showDebugInfo()
	{

	}
	
	public function dbVersion()
	{
		return $this->_connection->getAttribute(PDO::ATTR_DRIVER_NAME).':'.
		$this->_connection->getAttribute(PDO::ATTR_SERVER_VERSION);
	}	
	public function tableList()
	{
		return $this->doQuery('SHOW FULL TABLES;');
	}
	public function drop($table)
	{
		return $this->doQuery("TRUNCATE `{$table}`;", true);
	}
}
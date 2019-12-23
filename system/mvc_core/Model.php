<?php
/**
  * Database related
  * Model
  * Have the CRUD and and generic DB search functions
  *
  *
  * to do:
 *  implement validation
  *
  */
class Model extends Core
{
	protected $db;
	protected $table;
	protected $prepared_query;
    /**
     * Model constructor.
     */
	public function __construct()
	{
        parent::__construct();
		$this->db = Db::getInstance();
	}

    /*
     * Shared Functions
     */
    /**
     * @param $options
     * @return array|null
     */
	public function find($options = array())
	{
		return $this->db->select($this->table, $options);
	}

    /**
     * @param $id
     * @return |null
     */
	public function findById($id)
	{
 		$options['where'] = "`id` = {$id}";
		$result = $this->db->select($this->table, $options);

		return $result ? $result[0] : null;
	}

    /**
     * @param $ids
     * @param int $makeList
     * @return array|null
     */
	public function findIdIn($ids = array(), $makeList = 0)
	{
	    if (!is_array($ids)) {
	        return null;
        }
 		$options['where'] = "`id` in ({explode($ids)})";
		$result = $this->db->select($this->table, $options);

		return $makeList ? $this->_makeList($result): $result;
	}

    /**
     * @param array $options
     * @return array|null
     */
	public function findByOptions($options = array())
	{
		if (!$options){
			return null;
		}

		$where = array();
		foreach($options as $key => $values){
			$search = (is_array($values)) ? implode(',', $values): $values;
			$where[] =  "`{$key}` = '{$search}'";
		}
 		$search_options['where'] = implode(' AND ', $where);
		$result = $this->db->select($this->table, $search_options);

		return $result ? $result : null;
		
	}

    /**
     * @param string $key DB field as List key,
     * @param string $field DB field as List value,
     * @return array
     */
	public function getList($key = 'id', $field = 'name')
	{
		$search_options['select'] = "`{$key}`, `{$field}`";
		$results = $this->db->select($this->table, $search_options);

		if (!$results) {
			return array();
		}

		return $this->_makeList($results);
	}

    /**
     * @param $results
     * @return array
     */
	protected function _makeList($results)
	{
		$list = array();

		foreach($results as $result){
			$list[$result['id']] = $result['name'];
		}

		return $list;
	}

	public function save($data = array())
	{
		$to_save = ($data) ? $data : $this->prepared_query;
		return $this->db->insert($this->table, $to_save);
	}

	public function update($data = array())
	{
		$to_save = ($data) ? $data : $this->prepared_query;
		$result = $this->db->replace($this->table, $to_save);
		return $result;
	}

	public function delete($id)
	{
		$delete_condition = "`id` = {$id}";
		$result = $this->db->delete($this->table, $delete_condition);

		return $result;
	}

	/**
	 * Prepare the data to insert in DB.
	 * @$input :array : should be the sanitized input
	 * @$dbKeys : the translation keys for input / db column
	 * @$rules: the input rules. in order to set the type of data: PDO::PARAM_INT :PDO::PARAM_STR
	 *
	 * return an array ready to use with the  db::insert and prepared statement
	 */
	protected function prepare($input, $rules)
	{
		$prepared=array();
		$dbKeys = array_keys($rules);
		// looking for field matching the databse
		foreach($dbKeys as $key)
		{
			$type =!empty($rules[$key]['type'] )
				? ( $rules[$key]['type'] === 'int' || $rules[$key]['type'] === 'bool' 
					?PDO::PARAM_INT 
					:PDO::PARAM_STR)
				:PDO::PARAM_STR;

			if ( !empty($rules[$key]['type'] ) &&
				$rules[$key]['type'] !== 'auto'){ 
				if(isset($input[$key])){
					$prepared[]= array('key'=>$key, 'type'=>$type,'value'=>
						(
							is_array($input[$key])
							? serialize( $input[$key])
							:$input[$key]
						)
					) ;
				}
			}
		}

		$this->data_query = $input;
		$this->prepared_query = $prepared;
	}

}
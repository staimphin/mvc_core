<?php

class XxxxxModel  extends model{
	protected $table;
	private $validation = array(
		'id' => array(
			'type' => 'int', 
			'requiered' => 0
		 ),
	);

	public function __construct() 
	{
		parent::__construct();
		$this->table = Helpers::setTable(get_class());
	}
}
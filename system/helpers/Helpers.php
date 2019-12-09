<?php 

class Helpers
{
	static function setTable($model_name)
	{
		return str_replace('model', '', strtolower($model_name));
	}
}
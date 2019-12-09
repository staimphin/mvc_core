<?php
/**
 * Class Forms
 * Framework core component
 */
class Forms {
	protected $_selected = '<select name="_NAME_">_LIST_</select>';
	protected $_hidden = '<input type="_hidden" name="_NAME_" value="__VALUE__"__ATTRIBUT__>'.PHP_EOL;

	public function select($name, $options)
	{
		$list = '';

		foreach($options['options_list'] as $key => $value){
			$selected = (isset($options['selected']) && $options['selected'] == $key) ? ' selected': '';
			$list .= "<option value='{$key}'{$selected}>{$value}</option>".PHP_EOL;
		}

		$select = str_replace('_NAME_', $name, $this->_selected);

		return str_replace('_LIST_', $list, $select);
	}

	public function radio($name, $options)
	{
		$radio = '';

		foreach($options['options_list'] as $key => $value){
			$selected = (isset($options['selected']) && $options['selected'] == $key) ? ' checked': '';
			$radio .= "<label><input type='radio' name='{$name}' value='{$key}'{$selected}>{$value}</label>";
		}

		return $radio;
	}

	public function convertImprovementsListToInputs($name, $string)
	{
		$improvements = explode(',', $string);
		$return ='';
		$_input = str_replace('__ATTRIBUT__', "disabled", $this->_hidden);

		foreach ($improvements as $improvement) {
			$tmp = explode('_', $improvement);
			$key = constant($tmp[0]);
			$value = isset($tmp[1]) ? $tmp[1]: 1;

		
			$input = str_replace('_NAME_', "{$name}[{$key}]", $_input);
			$return .= str_replace('__VALUE__', $value, $input);
		}
		return $return;
	}

	public function convertConstantToInput($name, $string)
	{
		$_input = str_replace('__ATTRIBUT__', "disabled", $this->_hidden);
		$input = str_replace('_NAME_', "{$name}[{$key}]", $_input);
		$value =  constant($string);
		$return .= str_replace('__VALUE__', $value, $input);
	
		return $return;
	}

    //for the view
    public function set($key, $value)
    {
    	$this->_data[$key] = $value;
    }
   	//for the view
    public function get($key)
    {
    	return $this->_data[$key];
    }
}
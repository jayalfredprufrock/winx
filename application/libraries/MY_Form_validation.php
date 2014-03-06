<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
	
	function __construct($rules = array())
    {
        parent::__construct($rules);
    }
	
	
	/**
	 * Error String
	 *
	 * Returns the error messages as a string, wrapped in the error delimiters
	 *
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	public function error_string($prefix = '', $suffix = '', $limit = FALSE)
	{
		// No errrors, validation passes!
		if (count($this->_error_array) === 0)
		{
			return '';
		}

		if ($prefix === '')
		{
			$prefix = $this->_error_prefix;
		}

		if ($suffix === '')
		{
			$suffix = $this->_error_suffix;
		}

		// Generate the error string
		$str = '';
		foreach ($this->_error_array as $val)
		{
				
			if ($limit !== FALSE && !$limit--)
			{
				break;
			}
				
			if ($val !== '')
			{
				$str .= $prefix.$val.$suffix."\n";
			}
		}

		return $str;
	}
	
	
	
	function exists($data, $args){
		
		//0=> table name, 1=> column name
		$args = explode(',',$args);
		
		if (!$this->CI->db->limit(1)->get_where($args[0],array('LOWER('.$args[1].')'=>strtolower($data)))->num_rows()){
				
			return FALSE;
		}

		return TRUE;
	}
	
	function is_unique($data, $args){

		//0=> table name, 1=> column name, 2=> id column indicating new/edit
		$args = explode(',',$args);
		
		$where = array('LOWER('.$args[1].')'=>strtolower($data));
		
		if (count($args) > 2){
			
			$where[$args[2] . ' !='] = $this->CI->input->post($args[2]);
		}
		
		if ($this->CI->db->limit(1)->get_where($args[0], $where)->num_rows()){
				
			return FALSE;
		}

		return TRUE;
	}
	
	function is_date($date){
		
		return strtotime($date) !== FALSE;
	}
	

	function is_future_date($date){
		
	    $datetime = strtotime($date);
		
	    if (!$this->is_date($date) || $datetime < time()+86400) {
	    	
	        $this->set_message('is_future_date', "<strong>$date</strong> is not a valid future date.");
			
	        return FALSE;
	    }
        return TRUE;
	}

	function is_date_before_date($date,$bdate){
	   
	    if (!$this->is_date($date) || strtotime($date) > strtotime($this->CI->input->post($bdate))) {
	    	
	        $this->set_message('is_date_before_date', "The %s date must come before <strong>" . humanize($bdate) . '</strong>.');
			
	        return FALSE;
	    }
        return TRUE;
	}

	
	
	function is_increasing($max, $previous){
		
		$previous = explode(',', $previous);
		
		$input = $previous[0];
		$cycle = $previous[1];
		
		$prev_input = FALSE;
		if (preg_match('/([^\[\]\s]+)(?:\[([^\[\]\s]+)\])?(?:\[([^\[\]\s]+)\])?(?:\[([^\[\]\s]+)\])?/', $input, $matches)){		
			$prev_input = $this->CI->input->post($matches[1]);
			for ($i=2;$i < count($matches); $i++){			
				if (is_array($prev_input) && isset($prev_input[$matches[$i]])){
					$prev_input = $prev_input[$matches[$i]];
				}
			}
		}
		

		if ($prev_input === FALSE || ($cycle > 0 && $prev_input[$cycle-1] >= $max)){
			
			 return FALSE;
		}
		
		return TRUE;
	}
	
	function sums($sum, $operands)
	{
		
		return !is_numeric($sum) || array_sum(explode('+',$operands)) != $sum;
	}
	
	function _clone(){

   		// Create new validation object     
  		return new CI_Form_validation($this->_config_rules);
	}

	/**
	 * Executes the Validation routines
	 *
	 * @param	array
	 * @param	array
	 * @param	mixed
	 * @param	int
	 * @return	mixed
	 */
	protected function _execute($row, $rules, $postdata = NULL, $cycles = 0)
	{
			
		// If the $_POST data is an array we will run a recursive call
		if (is_array($postdata))
		{
			foreach ($postdata as $key => $val)
			{
				//$this->_execute($row, $rules, $val, $key);
				$this->_execute($row, $rules, $val, $cycles);
				$cycles++;
			}

			return;
		}

		// If the field is blank, but NOT required, no further tests are necessary
		$callback = FALSE;
		if ( ! in_array('required', $rules) && is_null($postdata))
		{
			// Before we bail out, does the rule contain a callback?
			if (preg_match('/(callback_\w+(\[.*?\])?)/', implode(' ', $rules), $match))
			{
				$callback = TRUE;
				$rules = array(1 => $match[1]);
			}
			else
			{
				return;
			}
		}

		// Isset Test. Typically this rule will only apply to checkboxes.
		if (is_null($postdata) && $callback === FALSE)
		{
			if (in_array('isset', $rules, TRUE) OR in_array('required', $rules))
			{
				// Set the message type
				$type = in_array('required', $rules) ? 'required' : 'isset';

				if (isset($this->_error_messages[$type]))
				{
					$line = $this->_error_messages[$type];
				}
				elseif (FALSE === ($line = $this->CI->lang->line($type)))
				{
					$line = 'The field was not set';
				}

				// Build the error message
				$message = sprintf($line, $this->_translate_fieldname($row['label']));

				// Save the error message
				$this->_field_data[$row['field']]['error'] = $message;

				if ( ! isset($this->_error_array[$row['field']]))
				{
					$this->_error_array[$row['field']] = $message;
				}
			}

			return;
		}

		// --------------------------------------------------------------------

		// Cycle through each rule and run it
		foreach ($rules as $rule)
		{
			$_in_array = FALSE;

			// We set the $postdata variable with the current data in our master array so that
			// each cycle of the loop is dealing with the processed data from the last cycle
			if ($row['is_array'] === TRUE && is_array($this->_field_data[$row['field']]['postdata']))
			{
				// We shouldn't need this safety, but just in case there isn't an array index
				// associated with this cycle we'll bail out
				if ( ! isset($this->_field_data[$row['field']]['postdata'][$cycles]))
				{
					continue;
				}

				$postdata = $this->_field_data[$row['field']]['postdata'][$cycles];
				$_in_array = TRUE;
			}
			else
			{
				// If we get an array field, but it's not expected - then it is most likely
				// somebody messing with the form on the client side, so we'll just consider
				// it an empty field
				$postdata = is_array($this->_field_data[$row['field']]['postdata'])
						? NULL
						: $this->_field_data[$row['field']]['postdata'];
			}

			// Is the rule a callback?
			$callback = FALSE;
			if (strpos($rule, 'callback_') === 0)
			{
				$rule = substr($rule, 9);
				$callback = TRUE;
			}

			// Strip the parameter (if exists) from the rule
			// Rules can contain a parameter: max_length[5]
			$param = FALSE;
			if (preg_match('/(.*?)\[(.*)\]/', $rule, $match))
			{
				$rule	= $match[1];
				
				//allow access to the cycles variable
				$param	= str_replace('{cycle}',$cycles,$match[2]);
			}

			// Call the function that corresponds to the rule
			if ($callback === TRUE)
			{
				if ( ! method_exists($this->CI, $rule))
				{
					log_message('debug', 'Unable to find callback validation rule: '.$rule);
					$result = FALSE;
				}
				else
				{
					// Run the function and grab the result
					$result = $this->CI->$rule($postdata, $param);
				}

				// Re-assign the result to the master data array
				if ($_in_array === TRUE)
				{
					$this->_field_data[$row['field']]['postdata'][$cycles] = is_bool($result) ? $postdata : $result;
				}
				else
				{
					$this->_field_data[$row['field']]['postdata'] = is_bool($result) ? $postdata : $result;
				}

				// If the field isn't required and we just processed a callback we'll move on...
				if ( ! in_array('required', $rules, TRUE) && $result !== FALSE)
				{
					continue;
				}
			}
			elseif ( ! method_exists($this, $rule))
			{
				// If our own wrapper function doesn't exist we see if a native PHP function does.
				// Users can use any native PHP function call that has one param.
				if (function_exists($rule))
				{
					$result = ($param !== FALSE) ? $rule($postdata, $param) : $rule($postdata);

					if ($_in_array === TRUE)
					{
						$this->_field_data[$row['field']]['postdata'][$cycles] = is_bool($result) ? $postdata : $result;
					}
					else
					{
						$this->_field_data[$row['field']]['postdata'] = is_bool($result) ? $postdata : $result;
					}
				}
				else
				{
					log_message('debug', 'Unable to find validation rule: '.$rule);
					$result = FALSE;
				}
			}
			else
			{
				$result = $this->$rule($postdata, $param);

				if ($_in_array === TRUE)
				{
					$this->_field_data[$row['field']]['postdata'][$cycles] = is_bool($result) ? $postdata : $result;
				}
				else
				{
					$this->_field_data[$row['field']]['postdata'] = is_bool($result) ? $postdata : $result;
				}
			}

			// Did the rule test negatively? If so, grab the error.
			if ($result === FALSE)
			{
				if ( ! isset($this->_error_messages[$rule]))
				{
					if (FALSE === ($line = $this->CI->lang->line($rule)))
					{
							
						$line = 'Unable to access an error message corresponding to your field name.' . $rule;
					}
				}
				else
				{
					$line = $this->_error_messages[$rule];
				}

				// Is the parameter we are inserting into the error message the name
				// of another field? If so we need to grab its "field label"
				if (isset($this->_field_data[$param], $this->_field_data[$param]['label']))
				{
					$param = $this->_translate_fieldname($this->_field_data[$param]['label']);
				}

				// Build the error message
				$message = sprintf($line, $this->_translate_fieldname($row['label']), $param);

				// Save the error message
				$this->_field_data[$row['field']]['error'] = $message;


				if ( ! isset($this->_error_array[$row['field']]))
				{

					//provide support for non-indexed arrays
					//attach error to specific input
					if (substr($row['field'],-2) == '[]'){
						
						$row['field'] = substr($row['field'],0,-2) . '[' . $cycles . ']';
					}


					$this->_error_array[$row['field']] = $message;
				}

				return;
			}
		}
	}
	


}
<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A class for managing messages to be displayed to the user.
 * 
 */
class Messages {

   private $_ci;
   
  /**
   * Initialise this object.
	 * 
   * @param array parameter array
   */
  public function __construct()
  {
		
		$this->_ci =& get_instance();
  }

  /**
   * Adds a message to the a list.
   * 
   * The optional parameter $data may be a primitive value or an array.
   * If the $data parameter is present, $message must be a 
   * {@link http://www.php.net/manual/en/function.sprintf.php printf()}-style
   * template string that includes formatting for the value(s) in $data.
   * The $message formatting string is applied to the $data value(s) and 
   * the resulting message string is added to the list.
   * 
   * Eg. $this->messages->add('success', '%d files uploaded (%.2f kB)', array($num_files, $uploaded_size));
   * 
   * @param string $message message to be displayed
   * @param string $type one of the message types specified in the constructor params
   * @param mixed $data optional data to be included in the message
   * @return $this
   */
  public function add($type, $message, $data = FALSE, $title = FALSE)
  {
	  $messages = $this->_session_get();
	  
	  if (!is_array($messages)) {
		
		$messages = array();
	  }
	  
	  if (is_a($message, 'Exception')) {
		  	
		  $title = 'Exception Thrown!';
		  
		  $message = $message->getMessage();
		  
		  $type = 'error';
	  }

      $msg = array('type'=>$type,'title'=>$title);      
  	  
	  $msg['text'] = is_array($data) ? vsprintf($message, $data) : sprintf($message, $data);
   
	  $messages[] = (object)$msg;

      $this->_session_set($messages);	  
	   	
      return $this;
  }

  /**
   * Removes messages from the list.
   * 
   * If $type is given, clears only messages of that type
   * If $type is not given, clears all messages
	 * 
   * @param string $type type of messages to remove
   * @return $this 
   */
  public function clear($type = FALSE)
  {
    
	if ($type === FALSE){
		
		$this->_session_unset();
	}
	else {
		
		$messages = $this->_session_get();
		
		if (is_array($messages))
		{		
			foreach($messages as $i=>$message){
			
				if ($message->type == $type){
					
					unset($messages[$i]);
				}
			}
			
			if (!$this->count())
			{
				$this->clear();
			}
			else {
			
				$this->_session_set($messages);
			}
		}
	}
	
    return $this;
  }

  /**
   * Returns the number of messages stored in the session.
   * If $type is given, returns the number of messages
   * of the that type, otherwise returns total number of messages.
	 * 
   * @param string $type
   * @return int number of messages 
   */
  public function count($type = FALSE)
  {   
	$count = 0;
	
	$messages = $this->_session_get();
	
	if (is_array($messages)){
	
		if ($type === FALSE)
		{
			$count = count($messages);
		}
		else {
			
			foreach($messages as $message){
				
				if ($message->type == $type){
					
					$count++;
				}
			}
			
		}
	}
		
	return $count;
  }

  /**
   * Sames as peek(), but clears the list of messages.
	 * 
   * @param string $type type of messages to get
   * @return array messages to be displayed
   */
  public function get($type = FALSE)
  {
    $result = $this->peek($type);
	
    $this->clear($type);
	
    return $result;
  }

  /**
   * Returns an array of messages to be displayed without removing them from the session
   * 
   * If $type is given, returns an array of messages of that type
   * If $type is not given, returns an array of all messages objects, 
   * in the order they were added. Each message object contains two
   * properties, type and text.
   * 
   * @param string $type type of messages to peek at
   * @return array messages 
   */
  public function peek($type = FALSE)
  {
  
	$messages = $this->_session_get();
	
	if (!is_array($messages)){
		
		return array();
	}
	
    if ($type === FALSE)
    {
	   return $messages;
    }
    else
    {
		$tmessages = array();
		
		foreach($messages as $message){
		
			if ($message->type == $type){
				
				$tmessages[] = $message;
			}
		}
		
		return $tmessages;
    }
    
  }

  /**
   * Returns TRUE if there are messages available, otherwise FALSE.
   * 
   * If $type is given, returns TRUE if there are messages
   * of that type, otherwise FALSE.
   * @param string $type type of message to check for
   * @return boolean TRUE if messages exist
   */
  public function has_messages($type = FALSE)
  {
    return ($this->count($type) > 0);
  }

  /**
   * Add a message using the method name as message_type 
   * Eg. $this->messages->success('the operation was successful');
	 * 
   * @param string|array $message message or array of messages
   * @return Announce $this
   */
  public function __call($name, $arguments)
  {
    $type = $name;
	
    $message = $arguments[0];
    
	$data = isset($arguments[1]) ? $arguments[1] : FALSE;
		
	$title = isset($arguments[2]) ? $arguments[2] : FALSE;
		
    return $this->add($type, $message, $data, $title);
  }
  
  /**
  * Get (Magic Method)
  *
  * Used to allow the user to call the class with a message type as the property name.
  * When called it internally invokes the get function.
  *
  * @param string $type The type of messages to return
  *
  * @return array The specifed types messages or empty array
  * @access public
  */
  
  public function __get($type)
  {
		return $this->get($type);
  }

  private function _session_set($data)
  {
		return $this->_ci->session->set_userdata('ci_messages', $data);
  }

  private function _session_get()
  {
		return $this->_ci->session->userdata('ci_messages');
  }
  
  private function _session_unset()
  {
		return $this->_ci->session->unset_userdata('ci_messages');
  }

}

/* End of file messages.php */
/* Location: /sparks/messages/0.0.1/libraries/messages.php */

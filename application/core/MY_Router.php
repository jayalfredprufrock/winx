<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Router extends CI_Router
{ 
    public function set_class($class) 
    {
        parent::set_class($this->_repl($class));
    }

    public function set_method($method) 
    {
        parent::set_method($this->_repl($method));
    }

    public function _validate_request($segments)
    {
        if (isset($segments[0]))
            $segments[0] = $this->_repl($segments[0]);
        if (isset($segments[1]))
            $segments[1] = $this->_repl($segments[1]);

        return parent::_validate_request($segments);
    }

    private function _repl($s)
    {
        return str_replace('-', '_', $s);
    }
}
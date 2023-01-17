<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once BASEPATH."/plugins/phpword/PHPWord.php"; 
 
class CI_Phpword extends PHPWord { 
    public function CI_Phpword() { 
        parent::__construct(); 
    } 
}
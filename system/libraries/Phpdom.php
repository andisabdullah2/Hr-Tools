<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once( BASEPATH.'plugins/phpdom/simple_html_dom.php' );

class CI_Phpdom extends simple_html_dom {

    function __construct() {
        //php dom construct
        parent::__construct();
        
    }
}
// END PHPdom Class

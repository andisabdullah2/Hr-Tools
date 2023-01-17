<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once( BASEPATH.'plugins/tcpdf/tcpdf.php' );

class CI_Tcpdf extends TCPDF {

    function __construct() {
        // tcpdf constructor
        parent::__construct('p', 'mm', 'A4', true, 'UTF-8', false);

    }
}
// END TCPDF Class

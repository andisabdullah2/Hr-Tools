<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class template extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
    }

    // index
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "settings/sistem/template/index.html");
        // output
        parent::display();
    }

    // add
    public function add() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "settings/sistem/template/add.html");
        // load js
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        // output
        parent::display();
    }

}

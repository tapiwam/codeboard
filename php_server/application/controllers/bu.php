<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bu extends CI_Controller {

    private $data;
    
    function __construct() {
        parent::__construct();
        $this->load->dbforge();
        $this->load->dbutil();

        //$this->data = getenv('OPENSHIFT_DATA_DIR');
        $this->data = "";
        
        if(! $this->input->is_cli_request()){
            echo "These methods can only be accessed via CLI only!" . PHP_EOL;
            die();
        } else {
            echo "CLI verfied!" . PHP_EOL;
        }
    }

    function index() {
        
    }

    function optimize() {
        $result = $this->dbutil->optimize_database();

        if ($result !== FALSE) {
            print_r($result);
        }
    }
    
    function run_backup(){
        $filename = "cb_" . date("m-d-Y") . ".sql";
        echo "Back up file: $filename" . PHP_EOL;
        
        $zipfile = $filename . ".zip";
        
        $prefs = array(
            'format'      => 'zip',             // gzip, zip, txt
            'filename'    => $filename,    // File name - NEEDED ONLY WITH ZIP FILES
            'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
            'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
            'newline'     => "\n"               // Newline character used in backup file
          );
        
        $backup =& $this->dbutil->backup($prefs); 
        
        
        $this->load->helper('file');
        
        if(!(is_dir($this->data . "backups/" ))){
            $old = umask();
            mkdir($this->data . "backups/", 0777);
            umask($old);
        }
        
        
        $loc = $this->data."backups/" . $zipfile;
        echo "Location to write: $loc" . PHP_EOL;
        
        if( write_file($loc, $backup)){
            chmod($loc, 755);
            echo "Back up written to: $loc" . PHP_EOL;
        }
        
    }

}

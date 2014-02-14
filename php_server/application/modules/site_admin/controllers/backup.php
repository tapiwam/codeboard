<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Backup extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->user_model->is_logged_in();
        $this->user_model->is_admin();
    }

    function index() {
        $data['main_content'] = 'backup/backupHome';
        $this->load->view('includes/template', $data);
    }

    // ========================================
    // Downloads
    // ========================================
    function db_dl() {
        
    }

    function sql_dl() {
        // Load the DB utility class
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');

        if (!is_dir("files")) {$old = umask(0);mkdir("files", 0777);umask($old);}
        if (!is_dir("files/backups")) {$old = umask(0);mkdir("files/backups", 0777);umask($old);}
        
        $prefs = array('format' => 'zip','filename' => 'cb.sql');

        // Backup your entire database and assign it to a variable
        $backup = & $this->dbutil->backup($prefs);
        $db_name = 'cb-backup-' . date("Y-m-d_H-i-s") . '.zip';

        // send the file to your desktop
        force_download($db_name, $backup);
    }

    function mongo_dl() {
        
    }

    // ========================================
    // Restore from local backups
    // ========================================
    function restore_sql() {
        
    }

    function restore_mongo() {
        
    }

    // ========================================
    // Restore from uploaded backups
    // ========================================
    function sql_ul() {
        
    }

    function mongo_ul() {
        
    }
    
    // ========================================
    // Save to backdir
    // ========================================
    function db_save(){
        
    }
    
    function sql_save(){
        // Load the DB utility class
        $this->load->dbutil();
        $this->load->helper('file');

        if (!is_dir("files")) {$old = umask(0);mkdir("files", 0777);umask($old);}
        if (!is_dir("files/backups")) {$old = umask(0);mkdir("files/backups", 0777);umask($old);}
        
        $prefs = array('format' => 'zip','filename' => 'cb.sql');

        // Backup your entire database and assign it to a variable
        $backup = & $this->dbutil->backup($prefs);

        $db_name = 'cb-backup-' . date("Y-m-d_H-i-s") . '.zip';
        $save = 'files/backups/'.$db_name;
        
        if (write_file($save, $backup)) {
            echo "<br>File written<br />";
        }
    }
    
    function mongo_save(){
        
    }

}

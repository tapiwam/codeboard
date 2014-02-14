<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('email');
    }

    function index() {

        $this->email->set_newline("\r\n");

        $this->email->from('cop3014.lab@gmail.com', 'Tapiwa Maruni');
        $this->email->to('cop3014.lab@gmail.com');
        $this->email->subject('This is an email test');
        $this->email->message('Test only. It\'s working!');

        $path = $this->config->item('server_root');
        $file = $path . '/nettuts/attachments/info.txt';

        $this->email->attach($file);

        if ($this->email->send()) {
            echo "Your email was sent.";
        } else {
            show_error($this->email->print_debugger());
        }
    }

}
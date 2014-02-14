<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Seo extends CI_Controller {

    // ============================================================================
    // Extra Functions to validate certain information can be found in user_model
    // ============================================================================

    function index() {
        $this->sitemap();
    }

    function sitemap() {
        header("Content-Type: text/xml;charset=iso-8859-1");
        $this->load->view("seo/sitemap1");
    }

}
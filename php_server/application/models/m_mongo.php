<?php

class M_mongo extends CI_Model {

    private $mongo_db;
    
    function __construct() {
        parent::__construct();
        
        $user = "";
        $pass="";
        $host = "localhost";
        $dname = "";
        
        $mongo_db = \MongoQB\Builder(array(
        'dsn' => "mongodb://$user:$pass@$host:27017/$dbname"
        ));
        
    }

    function save_file($collection, $path, $filename, $meta = null) {
        echo "Trying to save in db<hr />";

        $grid = $this->mongo_db->getGridFS($collection);

        if ($meta != null) {
            $storedfile = $grid->storeFile($path . $filename, array("metadata" => array("filename" => $filename),
                "filename" => $filename));
        } else {
            $storedfile = $grid->storeFile($path . $filename, array("metadata" => $meta,
                "filename" => $filename));
        }


        // TESTS ==============================================
        $image = $grid->findOne("$filename");

        // Stream image to browser
        echo '<hr />';
        header('Content-Type: text/html');
        echo $image->getBytes();
        // TESTS ==============================================
    }

    function get_file($collection, $filename) {
        $grid = $this->mongo_db->getGridFS($collection);

        $image = $grid->findOne("$filename");
        $file = $image->getBytes();
        return $file;
    }

    //----------------------------------------------------


    function download_file($collection, $filename) {
        $this->load->helper('download');
        $grid = $this->mongo_db->getGridFS($collection);

        $image = $grid->findOne("$filename");
        $file = $image->getBytes();

        force_download($filename, $file);
    }

    function upload_file($collection) {
        $config['upload_path'] = 'files/uploads/';
        $config['allowed_types'] = 'gif|jpg|png|txt|doc|docx|xls|ppt|pptx|pdf|zip|rar|tar|gz';
        $config['max_size'] = '2500';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());
            return $error;
        } else {
            $meta = $this->upload->data();
            $meta['date'] = date('Y-m-d');

            echo '<pre>';
            var_dump($meta);
            echo '<hr />';
            echo '</pre><hr />'; //die();

            if (is_file('files/uploads/' . $meta['file_name'])) {
                echo 'File is in the right place<hr />';
            }

            return $this->save_file('tfiles', $meta['file_path'], $meta['file_name'], $meta);
        }
    }

    function list_files($collection) {
        $grid = $this->mongo_db->getGridFS($collection);
        $files = $grid->find();

        var_dump($files);
        die();
        //echo '<hr />';

        $df = array();
        foreach ($files as $f) {
            $d['name'] = $f->getFilename();
            $d['size'] = $f->getSize();

            $df[] = $d;
        }

        return $df;
    }

    function delete_file($collection, $filename) {
        $grid = $this->mongo_db->getGridFS($collection);

        $file = $grid->findOne($filename);
        $id = $file->file['_id'];
        $grid->delete($id);
    }

    function db_contains($collection, $filename) {
        $grid = $this->mongo_db->getGridFS($collection);
        $file = $grid->findOne($filename);
        if ($file != null || !empty($file))
            return true;
        else
            return false;
    }

}

?>
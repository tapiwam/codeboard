<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import_model extends CLASS_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('admin_model');
        $this->load->model('tc_model');

        $this->user_model->is_logged_in();
    }

    function import_assign($class_id, $session_id, $external_class_id, $external_session_id, $external_prog_id) {
        $e_classinfo = $this->admin_model->load_class_info($external_class_id);
        $e_classinfo = $e_classinfo[0];

        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];

        //$table = $classinfo->term . '_' . $classinfo->class_name . '_blogs';
        // grab prog details from program table
        $e_p_table = $e_classinfo->term . '_' . $e_classinfo->class_name . '_programs';
        $prog = $this->db->where('id', $external_prog_id)->get($e_p_table)->row();
        $prog = (array) $prog;

        //unset the id
        unset($prog['id']);

        // reset class_id, sess_id
        $prog["class_id"] = $class_id;
        $prog["session_id"] = $session_id;
        //echo '<pre>'; var_dump($prog); echo '</pre>'; die();
        // insert into new table
        $p_table = $classinfo->term . '_' . $classinfo->class_name . '_programs';

        $dbt = array(
            'class_id' => $class_id,
            'session_id' => $session_id,
            'prog_name' => $prog['prog_name'],
        );

        $c1 = $this->db->where($dbt)->count_all_results($p_table);

        if ($c1 == 0) {
            $q1 = $this->db->insert($p_table, $prog);
            $prog_id = $this->db->insert_id();
        } else {
            $q1 = $this->db->where($dbt)->set($prog)->update($p_table);
            $prog_id = $this->db->where($dbt)->select('id')->get($p_table)->row();
            $prog_id = $prog_id->id;
        }

        //echo "From $e_p_table to $p_table <br />"; var_dump($q1); echo '<hr />'; die();
        // grab supporting files from files
        $files = $this->tc_model->load_filedetails($external_class_id, $external_prog_id);

        //echo '<pre>'; var_dump($files); echo '</pre>'; //die();
        //var_dump($files); echo '<hr />';
        // update prog_id and insert into new table
        $f_table = $classinfo->term . '_' . $classinfo->class_name . '_files';
        foreach ($files as $f) {
            $f = (array) $f; // typecast just to make sure
            $f['prog_id'] = $prog_id;

            $dty = array(
                'prog_id' => $prog_id,
                'file_name' => $f['file_name'],
                'meta' => $f['meta'],
            );

            $c2 = $this->db->where($dty)->count_all_results($f_table);
            if ($c2 == 0) {
                unset($f['id']);
                $this->db->insert($f_table, $f);
            } else {
                unset($f['id']);
                $this->db->where($dty)->set($f)->update($f_table);
            }
        }
        
        // Insert entry into gradebook table
        $gtable = $classinfo->term . '_' . $classinfo->class_name . '_gradebook';
        $this->load->dbforge();
        $gbname = "a_" . $prog_id;
        if (!$this->db->field_exists($gbname, $gtable)) {
            $fields = array($gbname => array('type' => 'INT'));
            $this->dbforge->add_column($gtable, $fields);
        }

        // var_dump($files, true); echo '<hr />'; die();
        // grab test cases
        $e_t_table = $e_classinfo->term . '_' . $e_classinfo->class_name . '_testcases';
        $e_tc = $this->db->where('prog_id', $external_prog_id)->get($e_t_table)->result();

        //echo '<pre>'; var_dump($e_tc); echo '</pre>';  echo '<hr />';

        $t_table = $classinfo->term . '_' . $classinfo->class_name . '_testcases';

        // run through and insert/update new db
        $q1x = true; $q2x = true;
        
        foreach ($e_tc as $x) {
            $t = (array) $x;
            //echo '<pre>'; var_dump($t); echo '</pre><hr />'; 
            
            unset($t['id']);
            $t['prog_id'] = $prog_id;

            $dtx = array(
                'prog_id' => $prog_id,
                'tc_name' => $t["tc_name"],
                'tc_num' => $t["tc_num"]
            );

            $c3 = $this->db->where($dtx)->count_all_results($t_table);
            if ($c3 == 0) {
                $q1x .= $this->db->insert($t_table, $t);
            } else {
                $q2x .= $this->db->where($dtx)->set($t)->update($t_table);
            }
        }
        return ($q1x && $q2x);
    }

    function import_blog($class_id, $session_id, $external_class_id, $external_session_id, $external_blog_id) {
        $this->load->model('blog_model');

        // grab blog from external db
        $blog = $this->blog_model->get_one($external_class_id, $external_blog_id);

        // package everything up in an array
        $d = array(
            'session_id' => $session_id,
            'title' => $blog->title,
            'description' => $blog->description,
            'content' => $blog->content,
            'author' => $blog->author,
            'created' => $blog->created,
            'updated' => $blog->updated,
        );

        // insert into right table
        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];

        $table = $classinfo->term . '_' . $classinfo->class_name . '_blogs';
        // var_dump($table); echo '<hr />'; var_dump($d); die();
        
        $dcv = array(
            'session_id' => $session_id,
            'title' => $blog->title,
        );
        
        $c = $this->db->where($dcv)->count_all_results($table);
        if($c == 0 ){
            return $this->db->insert($table, $d);
        } else {
            return $this->db->where($dcv)->set($d)->update($table);
        }
    }
    
    function import_lab($class_id, $session_id, $external_class_id, $external_session_id){
        $c = true;
        $e_classinfo = $this->admin_model->load_class_info($external_class_id);
        $e_classinfo = $e_classinfo[0];

        $classinfo = $this->admin_model->load_class_info($class_id);
        $classinfo = $classinfo[0];

        // grab prog details from program table
        $e_p_table = $e_classinfo->term . '_' . $e_classinfo->class_name . '_programs';
        $progs = $this->db->where('session_id', $external_session_id)->get($e_p_table)->result();
        //echo "External PROG table: $e_p_table<br />"; var_dump($progs); 
        
        foreach($progs as $p){
            // echo "importing prog_id: $p->id <br />";
            $this->import_assign($class_id, $session_id, $external_class_id, $external_session_id, $p->id);
        }
        
        // echo "<hr />";
        
        // grab and replicate blogs
        $e_b_table = $e_classinfo->term . '_' . $e_classinfo->class_name . '_blogs';
        $blogs = $this->db->where('session_id', $external_session_id)->get($e_b_table)->result();
        // echo "External BLOG table: $e_b_table<br />"; var_dump($blogs);
        
        foreach($blogs as $b){
            // echo "importing blog_id: $b->id <br />";
            $c = $this->import_blog($class_id, $session_id, $external_class_id, $external_session_id, $b->id);
        }
        return $c;
    }

}

?>
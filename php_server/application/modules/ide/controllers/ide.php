<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ide extends MX_Controller {

    function index() {
        $data['main_content'] = 'home';
        $this->load->view('includes/template', $data);
    }

    function test() {
        $data['main_content'] = 'test';
        $this->load->view('includes/template', $data);
    }

// =================================================

    function basic_ide() {
        $ip = $_SERVER['REMOTE_ADDR'].rand(1, 999);
        $data['id'] = substr(md5($ip),0,8);
        
        $data['main_content'] = 'basic_c_cpp_py';
        $this->load->view('includes/template', $data);
    }

    function submit_ide() {
        $code = $this->input->post('code');
        $input = $this->input->post('input');
        $lang = $this->input->post('lang');
        $id = $this->input->post('id');

        $part = "ide_$id";
        $name = "ide_$id.$lang";
        $in = "input_$id.txt";
        $comp_name = "ide_$id.run";

        $location = "files/ide/$lang";

        if (!dir("files")) {
            mkdir("files", 0777);
        }
        if (!dir("files/ide")) {
            mkdir("files/ide", 0777);
        }
        if (!is_dir($location)) {
            mkdir($location, 0777);
        }

        $current = getcwd();
        chdir($location);

        write_file($name, $code);
        write_file($in, $input);

        switch ($lang) {
            case "cpp":
                $go = "g++ -Wall $name -o $comp_name 2>&1";
                exec($go, $out, $exitstat);
                break;
            case "c":
                $go = "gcc -Wall $name -o $comp_name 2>&1";
                exec($go, $out, $exitstat);
                break;
            case "java":
                $go = "javac -verbose -classpath " . $part . ".jar $name 2>&1";
                exec($go, $out, $exitstat);
                break;
            case "py":
                // this is a script so skip the compile part
                $exitstat = 0;
                break;
        }

        if ($exitstat != 0) {
            $data = new stdClass();
            $data->error = 1;
            $data->result = implode("\n", $out);
        } else {
            // run code here with given input
            switch ($lang) {
                case "cpp":
                case "c":
                    $go1 = "timeout 1 ./$comp_name < $in 2>&1";
                    break;

                case "py":
                    $go1 = "timeout 1 python $name < $in 2>&1";
                    break;
                
                case "java":
                    $go1 = "timeout 1  java -jar $part.jar < $in 2>&1";
                    break;
            }

            exec($go1, $out1, $exitstat);

            if ($exitstat == 0) {
                $data = new stdClass();
                $data->success = 1;
                $data->result = implode("\n", $out1);
            } else {
                $data = new stdClass();
                $data->success = 2;
                $data->result = implode("\n", $out1);
            }
        }

        chdir($current);
        echo json_encode($data);
        die();
    }

}

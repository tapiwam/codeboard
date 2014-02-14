<?php

class Ide_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	
	function compile_c($code, $input)
	{
		$t = time();
		if ( !is_dir('tmp') ){ $o=umaks(0); mkdir('tmp', 0777); umask($o); }
		if ( !is_dir("tmp/$t") ){ $o=umaks(0); mkdir("tmp/$t", 0777); umask($o); }
		$dir = "tmp/$t";
		
		$old_dir = getcwd();
		$output = "";
		
		chdir($dir);
		
		if( write_file('main.c', $code) ){
			if( write_file('input.txt', $input) ){
				
				$go = "gcc main.c -Wall -ansi -o test 2>&1";
				exec($go, $out, $exitstat);
				
				$output = implode('\n', $out);
				if( $exitstat != 0 ){
					$output = "*******************\n  COMPILE ERROR\n*******************\n$output\n";
				}
				
			}
		}
		
		chdir($old_dir);
		
		return $output;
	}
	
	function compile_cpp($code, $input)
	{
		$t = time();
		if ( !is_dir('tmp') ){ $o=umaks(0); mkdir('tmp', 0777); umask($o); }
		if ( !is_dir("tmp/$t") ){ $o=umaks(0); mkdir("tmp/$t", 0777); umask($o); }
		$dir = "tmp/$t";
		
		$old_dir = getcwd();
		$output = "";
		
		chdir($dir);
		
		if( write_file('main.cpp', $code) ){
			if( write_file('input.txt', $input) ){
				
				$go = "g++ main.c -Wall -ansi -o test 2>&1";
				exec($go, $out, $exitstat);
				
				$output = implode('\n', $out);
				if( $exitstat != 0 ){
					$output = "*******************\n  COMPILE ERROR\n*******************\n$output\n";
				} else {
					$go1 = "./test < input.txt 2>&1";
					exec($go1, $out1, $exitstat1);
					$output1 = implode('\n', $out);
				}
				
			}
		}
		
		chdir($old_dir);
		
		return $output;
	}
	
}

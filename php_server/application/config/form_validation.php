<?php

$config = array(
         'survey_personal' => array(
                            array(
                                    'field' => 'first_name',
                                    'label' => 'First Name',
                                    'rules' => 'trim|required|min_length[2]|max_length[12]'
                                 ),
                            array(
                                    'field' => 'last_name',
                                    'label' => 'Last Name',
                                    'rules' => 'trim|required'
                                 ),
                            array(
                                    'field' => 'email',
                                    'label' => 'Email Address',
                                    'rules' => 'trim|required|valid_email'
                                 ),
                            array(
                                    'field' => 'year_grad',
                                    'label' => 'Year Graduated',
                                    'rules' => 'required'
                                 )
                            ),
                            
             'survey_work' => array(
             				array(
                                    'field' => 'employer',
                                    'label' => 'Employer',
                                    'rules' => 'trim|alpha_numeric'
                                 ),
                            array(
                                    'field' => 'years_employed',
                                    'label' => 'Years Employed',
                                    'rules' => ''
                                 ),
                            array(
                                    'field' => 'months_before',
                                    'label' => 'Months Before',
                                    'rules' => ''
                                 ),
                            array(
                                    'field' => 'first_position',
                                    'label' => 'First Position',
                                    'rules' => ''
                                 ),
                            array(
                                    'field' => 'first_employer',
                                    'label' => 'First Employer',
                                    'rules' => ''
                                 )
                            ),		
             'survey_edu' => array(
             				array(
                                    'field' => 'grad_school',
                                    'label' => 'Grauate School',
                                    'rules' => ''
                                 ),
                            array(
                                    'field' => 'field',
                                    'label' => 'Field of study',
                                    'rules' => ''
                                 ),
                            array(
                                    'field' => 'start_date',
                                    'label' => 'Year Started',
                                    'rules' => ''
                                 ),
                            array(
                                    'field' => 'end_date',
                                    'label' => 'Year ended',
                                    'rules' => ''
                                 ),
                            array(
                                    'field' => 'degree',
                                    'label' => 'Degree',
                                    'rules' => ''
                                 )
			 				)
		);           				

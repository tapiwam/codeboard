<div class="container">

    <h2>Add an instructor</h2>

    <div class="row-fluid">
        <div class="span8">

            <?php
            // display as dropdown
            $ox = array();

            if (isset($instructors)) {
                // echo '<pre>'; var_dump($instructors); echo '</pre>'; die();
                
                foreach ($instructors as $row) {
                    if ($row->username != $this->session->userdata('username')) {
                        $options = new stdClass();
                        $options->id = $row->id; 
                        $options->name = $row->username; 
                        $options->display = $row->username . ' - ' . $row->first_name . ' ' . $row->last_name;
                        $ox[] = $options;
                    }
                }
                
                //echo '<pre>'; var_dump($ox); echo '</pre>'; die();
            }
            ?>

            <?php echo form_open("class_admin/manage/add_instructor/$class_id"); ?>
            <fieldset>

                <div class="control-group">
                    <?php echo form_label('Instructors', 'ins'); ?>
                    <div class="controls">
                        <?php
                        if (!empty($options)) {
                            $o = array();
                            $o[''] = 'Select an instructor ...';
                            foreach($ox as $i){ $o[$i->id] = $i->display; }
                            echo form_dropdown('ins', $o);
                        } else {
                            echo 'No other instructors available.';
                        }
                        ?>
                    </div>
                </div>

                <?php echo form_submit('submit', 'Add instructor...', 'class = "btn btn-primary"'); ?>

            </fieldset>

            <?php echo form_close(); ?>
        </div>


        <div class="span4">
            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>

            <?php if ($classinfo[0]->active == 1): ?>
                <ul class="nav nav-list">
                    <li class="nav-header">Class Availability</li>
                    <li><?php echo anchor('class_admin/manage/deactivate_class/' . $class_id, 'Deactivate Class'); ?></li>
                    <li><?php echo anchor('class_admin/manage/add_instructor_option/' . $class_id, 'Add an Instructor'); ?></li>
                    <li class="divider"></li>
                </ul>
            <?php else: ?>
                <ul class="nav nav-list">
                    <li class="nav-header">Class Availability</li>
                    <li><?php echo anchor('class_admin/manage/activate_class/' . $class_id, 'Reactivate Class'); ?></li>
                    <li class="divider"></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>


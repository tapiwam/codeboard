<div id="class_panel" class="container">

    <h2>Class: <?php echo $classinfo[0]->class_name; ?></h2>	
    <h4>Term: <?php echo $classinfo[0]->term; ?></h4>

    <hr />
    <div class="row">

        <div class="col-md-8">

            <div class='row'>
                <div class='col-md-6'>

                    <div class="panel panel-primary">
                        <div class='panel-heading'>
                            <h4><em><strong>Class Functions</strong></em></h4>
                        </div>
                        <ul class="list-group">
                            <?php echo anchor('class_admin/create_session_option/' . $class_id, 'Create a Lab', "class='list-group-item'"); ?> 
                            <?php echo anchor('class_admin/gradebook/' . $class_id, 'Grade Book', "class='list-group-item'"); ?> 
                            <?php echo anchor('ide', 'Online IDE', "class='list-group-item'"); ?> 
                            <?php echo anchor('file_cabinet/cls/' . $class_id, 'File Cabinet', "class='list-group-item'"); ?> 
                        </ul>     
                    </div>   
                </div>

                <div class='col-md-6'>
                    <div class="panel panel-primary">
                        <div class='panel-heading'>
                            <h4><em><strong>Announcements</strong></em></h4>
                        </div>
                        <ul class="list-group">

                            <?php
                            if (isset($announce)) {
                                foreach ($announce as $item) {
                                    echo anchor('announcements', '<li class="list-group-item">item</li>');
                                }
                            } else {
                                echo '<li class="list-group-item">No announcements at the moment!</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>


            <hr />
            <!---------------------------------------------->

            <div class="panel panel-primary">
                <div class="panel-heading"><h4><em><strong>Labs</strong></em></h4></div>
                <div class="panel-body">
                    <p>All current labs for this class are listed below.</p>
                </div>
                <ul class="list-group">
                    <?php
                    if (isset($sessions)) {
                        foreach ($sessions as $item) {
                            echo anchor('class_admin/sessions/' . $class_id . '/' . $item->id, $item->session_name . " (Due " . date("D, j M", strtotime($item->end)) . ")", "class='list-group-item'");
                        }
                    } else {
                        echo '<li class="list-group-item">No labs at the moment!</li>';
                    }
                    ?>
                </ul>

            </div>



            <hr />
            <button onclick="history.go(-1);" class="btn btn-primary btn-large">Back</button>
        </div>

        <div class="col-md-4">

            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>

            <?php if ($classinfo[0]->active == 1): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">Class Availability</div>
                    <div class="list-group">
                        <?php echo anchor('class_admin/manage/deactivate_class/' . $class_id, 'Deactivate Class', 'class="list-group-item"'); ?>
                        <?php echo anchor('class_admin/manage/add_instructor_option/' . $class_id, 'Add an Instructor', 'class="list-group-item"'); ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="panel panel-default">
                    <div class="panel-heading">Class Availability</div>
                    <div class="list-group">
                       <?php echo anchor('class_admin/manage/activate_class/' . $class_id, 'Reactivate Class'); ?> 
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <h2>Quizzes: Labs</h2>
        <h4><em><strong>Class: <?php echo $classinfo[0]->class_name; ?></strong></em></h4>

        <div class="span8">
            <ul class="nav nav-list">
                <li class="nav-header"><h4><em><strong>All labs</strong></em></h4></li>
                <?php
                if (isset($sessions)) {
                    foreach ($sessions as $item) {
                        echo '<li class="divider"></li>';
                        echo '<li>' . anchor("quiz/admin/session/$class_id/".$item->id , $item->session_name) . '</li>';
                    }
                } else {
                    echo '<li>No labs at the moment!</li>';
                }
                ?>
                <li class="divider"></li>
            </ul>

            <hr />
            <button onclick="history.go(-1);" class="btn btn-primary btn-large">Back</button>
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
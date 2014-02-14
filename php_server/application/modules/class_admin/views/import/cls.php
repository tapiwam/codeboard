<div class="container">
    <div class="row">
        <h2>Import</h2>

        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading"><h4><em><strong>Please select a lab...</strong></em></h4></div>
                
                <?php
                if (isset($external_sessions)) {
                    echo '<div class="list-group">';
                    foreach ($external_sessions as $item) {
                        echo anchor("class_admin/import/session/$class_id/$session_id/$external_class_id/" . $item->id, $item->session_name, 'class="list-group-item"');
                    }
                    echo '</div>';
                } else {
                    echo '<div class="panel-body>"><p>No labs at the moment!</p></div>';
                }
                ?>
            </div>

            <hr />
            <?php echo anchor("class_admin/import/index/$class_id/$session_id", 'Back', 'class="btn btn-primary"'); ?>

        </div>

        <div class="col-md-4">
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
<div class="container">
    <h2>Sorry, something went wrong...</h2>
    <div class="row">
        <div class="col-md-8">
            <p>We failed to add the instructor to the class. Please make sure that they are not already added..</p>
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
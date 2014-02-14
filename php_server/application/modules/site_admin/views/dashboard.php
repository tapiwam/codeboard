<div class="container">

    <div class="row">

        <div class="span4">
            <ul class="nav nav-list well">
                <li class="nav-header">Dashboard</li>
                <li class="active"><?php echo anchor('site_admin/class_dashboard', 'Class Management'); ?></li>
                <li>Database Management</li>
                <li>User Statistics</li>
            </ul>
        </div>
        
        <div class="span8">
            <table class="table">
                <thead>
                <th>Class Name</th>
                <th>Term</th>
                <th></th>
                <th></th>
                </thead>

                <?php print_r($classes, TRUE);
                if (!empty($classes) && isset($classes)) :
                    ?>

                <?php foreach ($classes as $class): ?>
                        <tr>
                            <td><?php echo $class->class_name ?></td>
                            <td><?php echo $class->term ?></td>
                            <td><?php echo anchor('site_admin/delete_class/' . $class->id, 'Delete', 'class="btn btn-primary"'); ?></td>
                            <td>
                                <?php 
                                if($class->active == 1){
                                    echo anchor('site_admin/deactivate_class/' . $class->id, 'Deactivate', 'class="btn btn-primary"');
                                } else {
                                    echo anchor('site_admin/activate_class/' . $class->id, 'Activate', 'class="btn btn-primary"');
                                }
                                ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
<?php endif; ?>

            </table>
        </div>

        

    </div>

</div>
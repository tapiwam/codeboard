<div class="panel panel-default">
    <div class="panel-heading"><h4>Class Related Menu</h4></div>
    <div class="list-group">
        <?php echo anchor("class_admin/classes/$class_id", 'Class', 'class="list-group-item"'); ?> 
        <?php echo anchor("class_admin/gradebk/grades/$class_id", 'Gradebook', 'class="list-group-item"'); ?>
        <?php echo anchor("class_admin/roster/cls/$class_id", 'Roster', 'class="list-group-item"'); ?>
        <?php echo anchor("file_cabinet/cls/$class_id", 'Class Documents', 'class="list-group-item"'); ?>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><h4>General Menu</h4></div>
    <div class="list-group">
        <?php echo anchor("class_admin", 'All Classes', 'class="list-group-item"'); ?>
        <?php echo anchor("class_admin/gradebk", 'All Gradebooks', 'class="list-group-item"'); ?>
        <?php echo anchor("class_admin/roster", 'All Rosters', 'class="list-group-item"'); ?>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><h4>Quick Options</h4></div>
    <div class="list-group">
        <?php echo anchor("class_admin/create_class_option", 'Create a Class', 'class="list-group-item"'); ?>
        <?php echo anchor("class_admin/create_session_option/$class_id", 'Create a Lab', 'class="list-group-item"'); ?>
    </div>
</div>


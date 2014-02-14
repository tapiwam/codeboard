<div class="panel panel-default">
    <div class="panel-heading"><h4>Class Menu</h4></div>
     <?php echo anchor("student/classes/$class_id", '<span class="glyphicon glyphicon-home"><span> Class home', 'class="list-group-item"'); ?> 
     <?php echo anchor("student/files/$class_id", '<span class="glyphicon glyphicon-download-alt"><span> Class documents', 'class="list-group-item"'); ?> 
     <?php echo anchor("student/grades/$class_id", '<span class="glyphicon glyphicon-book"><span> My Grades and Reports', 'class="list-group-item"'); ?>
</div>
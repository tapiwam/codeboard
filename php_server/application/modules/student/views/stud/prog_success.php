<div class="container">
    <div class="row">
        <div class="col-md-12">
                <h1>Report</h1>
                <?php echo $report; //foreach($report as $item) { echo "$item"; }  ?>

                <?php echo anchor("student/prog/".$info[0]->class_id.'/'.$info[0]->session_id.'/'.$info[0]->prog_name , "Re-submit", 'class="btn btn-primary btn-large"'). "   " ?>
                <?php echo anchor("student/sessions/".$info[0]->class_id.'/'.$info[0]->session_id , "Lab", 'class="btn btn-primary btn-large"'). "   " ?>
                <?php echo anchor("student/classes/".$info[0]->class_id , "Class", 'class="btn btn-primary btn-large"'). "   " ?>
                <?php echo anchor("student" , "Home", 'class="btn btn-primary btn-large"') ?>
        </div> 
    </div>
</div>
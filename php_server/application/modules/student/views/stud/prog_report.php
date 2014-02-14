<div class="container">
    <div class="row">
        <div class="col-md-12">
            
                <h1>Report : <?php echo $stud->first_name.' '.$stud->last_name ?></h1>
                
                <?php 
                if(!empty($report)) {
                    echo $report; 
                } else {
                    echo '<h4><strong><em>No report found</em></strong></h4><br />';
                }
                ?>

                <?php echo anchor("student/prog/".$info[0]->class_id.'/'.$info[0]->session_id.'/'.$info[0]->prog_name , "Re-take", 'class="btn btn-primary btn-large"'). "   " ?>
                <?php echo anchor("student/sessions/".$info[0]->class_id.'/'.$info[0]->session_id , "Lab", 'class="btn btn-primary btn-large"'). "   " ?>
                <?php echo anchor("student/classes/".$info[0]->class_id , "Class", 'class="btn btn-primary btn-large"'). "   " ?>
                <button onclick="history.go(-1);" class="btn btn-primary btn-large">Back</button>
        </div> 
    </div>
</div>
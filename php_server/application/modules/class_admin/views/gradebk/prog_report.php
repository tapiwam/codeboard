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

                <button onclick="history.go(-1);" class="btn btn-primary btn-large">Back </button>
        </div> 
    </div>
</div>
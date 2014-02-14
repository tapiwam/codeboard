<div class="container">
    <h2>Sorry something went wrong...</h2>
    <div class="row">
        <div class="col-md-8">
            
            <div class="alert alert-warning"><?php echo $error; ?></div>
            
            <?php echo anchor("class_admin/sessions/$class_id/$session_id", 'Back', 'class = "btn btn-large btn-primary"'); ?>
            
        </div>
        
        <div class="col-md-4">
            <?php $this->load->view('includes/student_sidebar'); ?>
            <?php $this->load->view('includes/student_class_sidebar', array('class_id' => $class_id)); ?>
        </div>
    </div>
</div>
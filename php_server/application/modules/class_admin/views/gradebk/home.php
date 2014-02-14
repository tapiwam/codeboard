<div class="container">
    <h2>My Gradebooks</h2>
    <div class="row">
        <div class="col-md-8">

            <p>Below are a list of all classes registered in your name. Please select a class.</p>

            <div class="panel panel-primary">
                <div class="panel-heading"><h4>Classes</h4></div>
                
                
                <?php if (isset($classes)): ?>
                    <div class="list-group">
                        <?php foreach ($classes as $class): ?>
                            <?php echo anchor('class_admin/gradebk/grades/' . $class->id, $class->class_name . '-' . $class->term, 'class="list-group-item"'); ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="panel-body">
                        <p>No Active classes at the moment</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <hr />
            <button onclick="history.go(-1);" class="btn btn-primary btn-large">Back</button>
        </div>

        <div class="col-md-4">

            <?php
            $this->load->view('includes/main_sidebar');
            ?>
        </div>
    </div>
</div>
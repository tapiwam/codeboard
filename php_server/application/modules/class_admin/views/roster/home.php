<div class="container">
    <h2>My Rosters</h2>
    <div class="row">
        <div class="col-md-8">


            <div class="panel panel-primary">
                <div class="panel-heading"><h4>Classes</h4></div>
                
                <?php if (isset($classes)): ?>
                    <div class="list-group">
                        <?php foreach ($classes as $class): ?>
                            <?php echo anchor('class_admin/roster/cls/' . $class->id, $class->class_name . '-' . $class->term, 'class="list-group-item"'); ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No Active classes at the moment</p>
                <?php endif; ?>
            </div>

            <hr />
            <button onclick="history.go(-1);" class="btn btn-primary btn-large">Back</button>
        </div>

        <div class="col-md-4">
            <?php $this->load->view('includes/main_sidebar'); ?>
        </div>
    </div>
</div>
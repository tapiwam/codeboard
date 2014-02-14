<div class="container">

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading"><h4>My Classes</h4></div>
            </div>

            <?php if (isset($classes)): ?>
                <div class="list-group">
                    <?php foreach ($classes as $class): ?>
                        <?php echo anchor('file_cabinet/cls/' . $class->id, $class->class_name . '-' . $class->term, 'class="list-group-item"'); ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No Active classes at the moment</p>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <?php $this->load->view('includes/main_sidebar'); ?>
        </div>
    </div>
</div>
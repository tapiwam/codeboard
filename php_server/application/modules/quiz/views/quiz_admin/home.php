<div class="container">
    <h2>Quizzes: home</h2>
    <div class="row-fluid">
        <div class="span8">

            <p>Below are a list of all classes registered in your name. Please select a class.</p>

            <ul class="nav nav-list">	
                <?php if (isset($classes)): ?>
                    <?php foreach ($classes as $class): ?>
                        <li class="divider"></li>
                        <li><?php echo anchor('quiz/admin/cls/' . $class->id, $class->class_name . '-' . $class->term); ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No Active classes at the moment</li>
                <?php endif; ?>

                <li class="divider"></li>
            </ul>
            
            <hr />
            <button onclick="history.go(-1);" class="btn btn-primary btn-large">Back</button>
        </div>

        <div class="span4">

            <?php
            $this->load->view('includes/main_sidebar');
            ?>
        </div>
    </div>
</div>
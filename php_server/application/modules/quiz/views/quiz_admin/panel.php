<div class="container">
    <h2>Quizzes</h2>

    <h4>
        <em><strong>
                Class: <?php echo $classinfo[0]->class_name; ?><br />
                Lab session: <?php echo $sessioninfo[0]->session_name; ?>
        </strong></em>
    </h4>
    <hr />

    <div class="row-fluid">
        <div class="span8">

            <ul class="nav nav-list">
                <li class="nav-header"><h4><em><strong>Quiz Questions</strong></em></h4></li>

                <?php if (isset($quiz)) : ?>
                    <table class="table table-hover table-striped">
                        <thead>
                        <th>Title</th>
                        <th># of Questions</th>
                        <th>Description</th>
                        <th></th>
                        </thead>
                        <?php
                        foreach ($quiz as $q) {
                            echo '<tr>';
                            echo '<td>' . $q->title . '</td>';
                            echo '<td>' . $q->num_ques . '</td>';
                            echo '<td>' . $q->description . '</td>';

                            echo '<td>' . anchor("quiz/admin/panel/$class_id/$session_id/" . $q->id, 'edit', 'class="btn btn-primary"');
                            echo '</tr>';
                        }
                        ?>
                    </table>
                    <?php else: ?>
                        <li>No questions in this quiz at the moment!</li><br />
                    <?php endif; ?>


            </ul>
            <hr />
            <?php echo anchor("quiz/admin/add/$class_id/$session_id/$quiz_id", "Create a question", 'class="btn btn-primary btn-large"'); ?>
            <?php echo anchor("quiz/admin/import/$class_id/$session_id/$quiz_id", "Import", 'class="btn btn-primary btn-large"'); ?>
            

            <a href="javascript:history.go(-1)" class="btn btn-primary btn-large">Back</a>
        </div>

        <div class="span4">
            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>

        </div>

    </div>
</div>
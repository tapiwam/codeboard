<div class="container">
    <div class="row">
        <h2>Gradebook</h2>
        <h4>
            <strong><em>
                    Class: <?php echo $classinfo[0]->class_name; ?><br />
                    Term: <?php echo $classinfo[0]->term; ?><br />
                    Instructor: <?php echo $classinfo[0]->instructor; ?>
                </em></strong>
        </h4>


        <div class="col-md-8">

            <?php echo form_open("class_admin/gradebk/grades/$class_id", 'class="form-inline well"'); ?>

            <?php echo form_radio('order', 'avg') . " Average   "; ?>
            <?php echo form_radio('order', 'uname') . " Username"; ?>
            <?php echo form_radio('order', 'lname') . " Surname"; ?>

            <?php echo form_submit('submit', 'filter', 'class="btn btn-primary"'); ?>
            <?php echo form_close(); ?>

            <table class="table table-bordered table-striped">
                <th>Student</th>
                <th>Username</th>
                <th>Total Points</th>
                <th>Possible Points</th>
                <th>Average</th>
                <th>Grade</th>

                <!-- --------------------------------------->

                <?php foreach ($students as $stud): ?>
                    <tr>

                        <?php
                        echo '<td>' . anchor('class_admin/gradebk/student_grades/' . $class_id . '/' . $stud->id, '<span class="glyphicon glyphicon-user"></span> '.$stud->name) . '</td>';
                        echo '<td>' . $stud->uname . '</td>';
                        echo '<td>' . $stud->total . '</td>';
                        echo '<td>' . $possible . '</td>';
                        echo '<td>' . $stud->avg . '</td>';
                        echo '<td>' . $stud->grade . '</td>';
                        ?>
                    </tr>
                <?php endforeach; ?>

            </table>

            <hr />
            <button onclick="history.go(-1);" class="btn btn-primary btn-large">Back</button>

        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading"><h4 class="nav-header">Gradebook Menu</h4></div>
                <div class="list-group">
                    <?php echo anchor('class_admin/gradebk/assignments', 'Assignments overview', 'class="list-group-item"'); ?>
                </div>
            </div>
            

            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>
        </div>

    </div>
</div>
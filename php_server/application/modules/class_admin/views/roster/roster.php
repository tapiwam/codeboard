<div class="container">

    <h2>Class Roster : <?php echo $classinfo[0]->class_name ?></h2>
    <h4>Term: <?php echo $classinfo[0]->term; ?></h4>

    <div class="row">
        <div class="col-md-8">
            <?php // print_r($students); ?>
            <?php // echo print_r($classinfo, TRUE); ?>
            
            <table class="table">
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Grades</th>

                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $i): ?>

                        <tr>
                            <td><?php echo $i->name; ?></td>
                            <td><?php echo $i->username; ?></td>
                            <td><?php echo mailto($i->email, $i->email, 'class="btn btn-primary"'); ?></td>
                            <td><?php echo anchor("class_admin/gradebk/student_grades/$class_id/" . $i->user_id, "grades", 'class="btn btn-primary"'); ?></td>
                        </tr>

                    <?php endforeach; ?>
                <?php endif; ?>

            </table>
            <hr />
            <button onclick="history.go(-1);" class="btn btn-primary btn-large">Back</button>
        </div>

        <div class="col-md-4">
            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>
        </div>
    </div>
</div>
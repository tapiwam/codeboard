<div class="container">

    <h2>Dashboard: <?php echo $sessioninfo[0]->session_name; ?></h2>
    <h4>Class: <?php echo $classinfo[0]->class_name; ?></h4>

    <hr />

    <div class="row">
        <div class="col-md-8 ">

            <div class="panel panel-primary">
                <div class="panel-heading"><h4>Lab Posts</h4></div>

                <?php
                if (isset($blogs) && count($blogs) > 0) {
                    ?>
                    <table class="table table-condensed table-striped">
                        <thead>
                        <th>Title</th>
                        <th>Last Updated</th>
                        <th>Description</th>
                        </thead>
                        <?php
                        foreach ($blogs as $blog) {
                            echo '<tr>';
                            echo '<td>' . anchor("student/post/$class_id/$session_id/" . $blog->id, $blog->title) . '</td>';
                            echo '<td>' . date("D F j, Y", strtotime($blog->updated)) . '</td>';
                            echo '<td>' . $blog->description . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                    <?php
                } else {
                    echo '<li>No lab posts at the moment!</li><br />';
                }
                ?>

            </div>
            <br />

            <div class="panel panel-primary">
                <div class="panel-heading"><h4>Assignments</h4></div>

                <?php
                if (isset($assignments)):
                    ?>
                    <table class="table table-condensed table-striped">
                        <thead>
                        <th>Program Name</th>
                        <th>Points</th>
                        <th>Graded/Extra Credit</th>
                        <th></th>
                        <th></th>
                        </thead>
                        
                        <?php foreach ($assignments as $item): ?>
                            <?php if ($item->active == 1): $chk = true; ?>

                                <tr>
                                    <td><?php echo anchor('student/assignment/' . $item->class_id . '/' . $item->session_id . '/' . $item->prog_name, $item->prog_name); ?></td>
                                    <td><?php echo $item->e_points + $item->s_points + $item->d_points + $item->c_points ?></td>
                                    <td><?php
                                        if ($item->graded == 1) {
                                            echo "Graded";
                                        } else {
                                            echo "Extra Credit";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo anchor('student/report/' . $item->class_id . '/' . $item->session_id . '/' . $item->prog_name, '<span class="glyphicon glyphicon-book"><span> Report', 'class="btn btn-primary"') ?>
                                    </td>
                                    <td>
                                        <?php echo anchor('student/download_code/' . $item->class_id . '/' . $item->session_id . '/' . $item->prog_name, '<span class="glyphicon glyphicon-cloud-download"><span> Download-my-code', 'class="btn btn-primary"') ?>
                                    </td>
                                </tr>

                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>

                <?php else: ?>
                    <p>No assignments at the moment!</p>
                <?php endif; ?>
            </div>

            <p><strong><em>Note:</em></strong> For Assignments marked as "Extra Credit", this does not necessarily mean it will be applied to your grade. Confront your Instructor to get their take on it.</p>
            <br />

            <?php echo anchor("student/classes/$class_id", "Back", 'class="btn btn-large btn-primary"'); ?>
        </div>

        <div class="col-md-4">
            <?php $this->load->view('includes/student_sidebar'); ?>
            <?php $this->load->view('includes/student_class_sidebar', array('class_id' => $class_id)); ?>
        </div>

    </div>
</div>

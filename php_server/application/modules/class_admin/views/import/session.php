<div class="container">
    <h2>Import from...</h2>

    <h4>Class: <?php echo $external_classinfo[0]->class_name; ?></h4>
    <h4><?php echo $external_sessioninfo[0]->session_name; ?></h4>
    <hr />

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-primary">
                <div class="panel-heading"><h4><em><strong>Posts</strong></em></h4></div>

                <table class="table table-hover table-striped">
                    <thead>
                    <th>Title</th>
                    <th>Date Created</th>
                    <th>Last Updated</th>
                    <th>Description</th>
                    <th></th>
                    <th></th>
                    </thead>
                    <?php
                    if (isset($blogs)) {
                        foreach ($blogs as $blog) {
                            echo '<tr>';
                            echo '<td>' . $blog->title . '</td>';
                            echo '<td>' . date("D, F j Y, H:i", strtotime($blog->created)) . '</td>';
                            echo '<td>' . date("D, F j Y, H:i", strtotime($blog->updated)) . '</td>';
                            echo '<td>' . $blog->description . '</td>';

                            echo '<td>' . anchor('class_admin/blog/v/' . $external_class_id . '/' . $external_session_id . '/' . $blog->id, 'view', 'class="btn btn-primary"');
                            echo '<td>' . anchor("class_admin/import/import_blog/$class_id/$session_id/$external_class_id/$external_session_id/" . $blog->id, 'import', 'class="btn btn-primary"');
                            echo '</tr>';
                        }
                    } else {
                        echo '<p>No blog posts at the moment!</p>';
                    }
                    ?>
                </table>
            </div>

            <!--------------------------------------------------------------->
            <hr />

            <div class="panel panel-primary">
                <div class="panel-heading"><h4><em><strong>Assignments</strong></em></h4></div>
                <?php
                if (isset($assignments)) {
                    ?>
                    <table class="table table-striped table-hover">
                        <thead>
                        <th>Assignment Name</th>
                        <th># of Test Cases</th>
                        <th># of Files</th>
                        <th>Points</th>
                        <th>Published</th>
                        <th>Active</th>
                        <th>Graded</th>
                        </thead>

                        <?php
                        foreach ($assignments as $item) {
                            echo '<tr>';
                            echo '<td>';
                            echo $item->prog_name . '</li>';
                            echo '</td>';

                            echo '<td>';
                            echo $item->num_tc;
                            echo '</td>';

                            echo '<td>';
                            echo $item->num_addition;
                            echo '</td>';

                            echo '<td>';
                            echo $item->s_points + $item->d_points + $item->e_points;
                            echo '</td>';

                            echo '<td>';
                            if ($item->published == 1) {
                                echo "Yes";
                            } else {
                                echo "No";
                            }
                            echo '</td>';

                            echo '<td>';
                            if ($item->active == 1) {
                                echo "Yes";
                            } else {
                                echo "No";
                            }
                            echo '</td>';

                            echo '<td>';
                            if ($item->graded == 1) {
                                echo "Yes";
                            } else {
                                echo "No";
                            }
                            echo '</td>';

                            echo '<td>' . anchor('class_admin/tc/view_published/' . $external_class_id . '/' . $external_session_id . '/' . $item->prog_name, 'view', 'class="btn btn-primary"');
                            echo '<td>' . anchor("class_admin/import/import_assign/$class_id/$session_id/$external_class_id/$external_session_id/" . $item->id, 'import', 'class="btn btn-primary"');

                            echo '</tr>';
                        }
                    } else {
                        echo '<p>No assignments at the moment!</p>';
                    }
                    ?>
                </table>
            </div>

            <hr />
            <a href="javascript:history.go(-1)" class="btn btn-primary btn-large">Back</a>
            <?php echo anchor("class_admin/import/import_lab/$class_id/$session_id/$external_class_id/$external_session_id", "Import Lab", 'class="btn btn-primary btn-large"'); ?>

        </div>

        <div class="col-md-4">
            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>

            <?php if ($classinfo[0]->active == 1): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">Class Availability</div>
                    <div class="list-group">
                    <?php echo anchor('class_admin/manage/deactivate_class/' . $class_id, 'Deactivate Class', 'class="list-group-item"'); ?>
                    <?php echo anchor('class_admin/manage/add_instructor_option/' . $class_id, 'Add an Instructor', 'class="list-group-item"'); ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="panel panel-default">
                    <div class="panel-heading">Class Availability</div>
                    <div class="list-group">
                    <?php echo anchor('class_admin/manage/activate_class/' . $class_id, 'Reactivate Class', 'class="list-group-item"'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>
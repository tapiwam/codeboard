<div class="container">

    <h2>Dashboard : <?php echo $sessioninfo[0]->session_name; ?></h2>
    <h4>Class: <?php echo $classinfo[0]->class_name; ?></h4>

    <div class="row">
        <div class="col-md-8">
            <div class="well">
                <h5>Start Date: <?php echo date("D F j, Y", strtotime($sessioninfo[0]->start)) . " at " . date("g:i a", strtotime($sessioninfo[0]->start)); ?></h5>
                <h5>Due Date: <?php echo date("D F j, Y", strtotime($sessioninfo[0]->end)) . " at " . date("g:i a", strtotime($sessioninfo[0]->end)); ?></h5>
                <h5>Late Submission: <?php echo date("D F j, Y", strtotime($sessioninfo[0]->late)) . " at " . date("g:i a", strtotime($sessioninfo[0]->late)); ?></h5>
            </div>

            <!--------------------------------------------------------------->
            <hr />

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4><em><strong>Posts</strong></em></h4>
                </div>
                <table class="table table-hover table-striped">
                    <thead>
                    <th>Title</th>
                    <th>Date Created</th>
                    <th>Last Updated</th>
                    <th>Description</th>
                    </thead>
                    <?php
                    if (isset($blogs)) {
                        foreach ($blogs as $blog) {
                            echo '<tr>';
                            echo '<td>' . anchor('class_admin/blog/view/' . $class_id . '/' . $session_id . '/' . $blog->id, '<i class="icon-plus-sign"></i>' . $blog->title);
                            echo '<td>' . date("D, F j Y, H:i", strtotime($blog->created)) . '</td>';
                            echo '<td>' . date("D, F j Y, H:i", strtotime($blog->updated)) . '</td>';
                            echo '<td>' . $blog->description . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<li>No blog posts at the moment!</li><br />';
                    }
                    ?>

                </table>
            </div>

            <?php echo anchor("class_admin/blog/create_option/$class_id/$session_id", 'Create a blog', 'class="btn btn-primary"'); ?>

            <!--------------------------------------------------------------->
            <hr />

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4><em><strong>Assignments</strong></em></h4></li>
                </div>

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
                            echo anchor('class_admin/assignments/' . $item->class_id . '/' . $item->session_id . '/' . $item->id, '<i class="glyphicon glyphicon-folder-open"></i> ' . $item->prog_name );
                            echo '</td>';

                            echo '<td>';
                            echo $item->num_tc;
                            echo '</td>';

                            echo '<td>';
                            echo $item->num_addition;
                            echo '</td>';

                            echo '<td>';
                            echo $item->s_points + $item->d_points + $item->e_points + $item->c_points;
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

                            echo '</tr>';
                        }
                        echo '</table>';
                    } else {
                        echo '<div class="panel-body"><p>No assignments at the moment!</p></div>';
                    }
                    ?>
            </div>

            <?php echo anchor('class_admin/create_assignment_option/' . $class_id . '/' . $session_id, 'Create an Assignment', 'class="btn btn-primary"'); ?>

            <!--------------------------------------------------------------->
            <hr />

            <?php echo anchor("class_admin/classes/$class_id", 'Back', 'class="btn btn-primary"'); ?>

        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Lab Management Menu</div>
                <div class ="list-group">
                    <?php
                    echo anchor('class_admin/create_assignment_option/' . $class_id . '/' . $session_id, 'Create an Assignment', "class='list-group-item'") ;
                    echo anchor("class_admin/blog/create_option/$class_id/$session_id", 'Create a blog', "class='list-group-item'") ;
                    echo anchor('class_admin/import/index/' . $class_id . '/' . $session_id, 'Import an item', "class='list-group-item'") ;

                    echo '<span id="menu_div" class="list-group-item"></span>';

                    echo anchor('class_admin/manage/assignments/' . $class_id . '/' . $session_id, 'Manage Assignments', "class='list-group-item'") ;
                    echo anchor('class_admin/blog/index/' . $class_id . '/' . $session_id, 'Manage Blogs', "class='list-group-item'") ;
                    echo anchor('class_admin/manage/session_dates/' . $class_id . '/' . $session_id, 'Change Dates', "class='list-group-item'") ;

                    if ($sessioninfo[0]->active == 1)
                        echo anchor('class_admin/manage/deactivate_session/' . $class_id . '/' . $session_id, 'Deactivate Lab', "class='list-group-item'") ;
                    else
                        echo anchor('class_admin/manage/activate_session/' . $class_id . '/' . $session_id, 'Activate Lab', "class='list-group-item'") ;
                    ?>
                    </div>
            </div>

            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>
        </div>

    </div>
</div>

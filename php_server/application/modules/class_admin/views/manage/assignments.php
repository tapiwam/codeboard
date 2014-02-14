<div class="container">

    <h2>Manage Assignments</h2>

    <div class="row">
        <div class="col-md-8">

            <?php if (isset($assignments)) { ?>

                <table class="table table-striped table-hover">
                    <thead>
                    <th>Assignment Name</th>
                    <th>Points</th>
                    <th>Published</th>
                    <th>Graded</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    </thead>

                    <?php
                    foreach ($assignments as $item) {
                        echo '<tr>';
                        echo '<td>';
                        echo anchor('class_admin/assignments/' . $item->class_id . '/' . $item->session_id . '/' . $item->id, '<i class="glyphicon glyphicon-folder-open"> ' . $item->prog_name . '</li>');
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
                        if ($item->graded == 1) {
                            echo "Yes";
                        } else {
                            echo "No";
                        }
                        echo '</td>';

                        $onclick = array(
                            'onclick' => "return confirm('Are you sure you want to delete this?')",
                            'class' => "btn btn-primary"
                        );


                        echo "<td>" . anchor('class_admin/tc_manage/description/' . $item->class_id . '/' . $item->session_id . '/' . $item->prog_name, 'description', 'class="btn btn-primary"') . "</td>";
                        echo "<td>" . anchor('class_admin/tc_manage/points/' . $item->class_id . '/' . $item->session_id . '/' . $item->prog_name, 'points', 'class="btn btn-primary"') . "</td>";
                        //echo "<td>" . anchor('class_admin/manage/delete_assignment/'.$item->class_id.'/'.$item->session_id.'/'.$item->prog_name , 'delete', 'class="btn btn-primary" onsubmit="javascript:return confirm(\'Are you sure you want to delete this assignment?\');"' ) . "</td>";
                        echo "<td>" . anchor('class_admin/manage/delete_assignment/' . $item->class_id . '/' . $item->session_id . '/' . $item->prog_name, 'delete', $onclick) . "</td>";
                        echo '</tr>';
                    }
                } else {
                    echo '<li>No assignments at the moment!</li><br />';
                }
                ?>
            </table>

            <hr />
            <?php //<button onclick="history.go(-1);" class="btn btn-primary btn-large">Back</button> ?>
            <?php echo anchor("class_admin/sessions/$class_id/$session_id", 'Back', 'class="btn btn-primary btn-large"'); ?>

        </div>
        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Lab Management Menu</h4>
                </div>
                <div class="list-group">
                    <?php
                    echo anchor('class_admin/create_assignment_option/' . $class_id . '/' . $session_id, 'Create an Assignment', 'class="list-group-item"');
                    echo anchor("class_admin/blog/create_option/$class_id/$session_id", 'Create a blog', 'class="list-group-item"');
                    echo anchor('class_admin/import/index/' . $class_id . '/' . $session_id, 'Import an item', 'class="list-group-item"');
                    echo "<p style=\"background-color: #f5f5f5;\" class='list-group-item'></p>";

                    echo anchor('class_admin/manage/assignments/' . $class_id . '/' . $session_id, 'Manage Assignments', 'class="list-group-item"');
                    echo anchor('class_admin/blog/index/' . $class_id . '/' . $session_id, 'Manage Blogs', 'class="list-group-item"');
                    echo anchor('class_admin/manage/session_dates/' . $class_id . '/' . $session_id, 'Change Dates', 'class="list-group-item"');

                    if ($sessioninfo[0]->active == 1)
                        echo anchor('class_admin/manage/deactivate_session/' . $class_id . '/' . $session_id, 'Deactivate Lab', 'class="list-group-item"');
                    else
                        echo anchor('class_admin/manage/activate_session/' . $class_id . '/' . $session_id, 'Activate Lab', 'class="list-group-item"');
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
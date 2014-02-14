<div class="container">

    <h2>Lab Session: <?php echo $sessioninfo[0]->session_name; ?></h2>	
    <h4>Assignment: <?php echo $prog[0]->prog_name; ?></h4>
    <hr />

    <div class="row">	
        <div class="col-md-6">
            <h4><strong><em>Stuff you have to turn in</em></strong></h4>
            <table  class="table table-bordered table-striped table-hover">
                <th>Item</th>
                <th>Type</th>

                <?php
                foreach ($fileinfo as $file) :
                    if ($file->admin_file == 0 && $file->stream_type != "output"):
                        ?>
                        <tr>
                            <td><?php echo $file->file_name; ?></td>
                            <td><?php echo $file->stream_type; ?></td>
                        </tr>
                        <?php
                    endif;
                endforeach;
                ?>
            </table>
        </div>

        <div class="col-md-6">
            <h4><strong><em>Points for the assignment</em></strong></h4>
            <table  class="table table-bordered table-striped table-hover">

                <thead>
                <th></th>
                <th>Points</th>
                </thead>

                <tr>
                    <td>On-time Submission</td>
                    <td><?php echo $prog[0]->s_points ?></td>
                </tr>

                <tr>
                    <td>Successfully Compile</td>
                    <td><?php echo $prog[0]->c_points ?></td>
                </tr>

                <tr>
                    <td>Execution</td>
                    <td><?php echo $prog[0]->e_points ?></td>
                </tr>

                <tr>
                    <td>Documentation</td>
                    <td><?php echo $prog[0]->d_points ?></td>
                </tr>

                <tr class='text-danger'>
                    <td>Deduction if Late</td>
                    <td>-<?php echo $prog[0]->late ?></td>
                </tr>
                
                <tr class="success">
                    <td>Possible points</td>
                    <td><?php echo $prog[0]->s_points + $prog[0]->c_points + $prog[0]->e_points + $prog[0]->d_points; ?></td>
                </tr>

            </table>

        </div>
    </div>

    <hr />
    <button onclick="history.go(-1);" class="btn btn-primary btn-large">Back</button>
    <?php
    if ( strtotime($sessioninfo[0]->late) > time() ) {
        echo anchor("student/sessions/" . $prog[0]->class_id . '/' . $prog[0]->session_id, 'Lab', 'class = "btn btn-large btn-primary"'). "  ";
        echo anchor("student/view_prog/" . $prog[0]->class_id . '/' . $prog[0]->session_id . '/' . $prog[0]->prog_name, 'View', 'class = "btn btn-large btn-primary"'). "  ";
        echo anchor("student/prog/" . $prog[0]->class_id . '/' . $prog[0]->session_id . '/' . $prog[0]->prog_name, 'Begin', 'class = "btn btn-large btn-primary"');
    } else {
        echo anchor("student/sessions/" . $prog[0]->class_id . '/' . $prog[0]->session_id, 'Lab', 'class = "btn btn-large btn-primary"'). "  ";
        echo anchor("student/view_prog/" . $prog[0]->class_id . '/' . $prog[0]->session_id . '/' . $prog[0]->prog_name, 'View', 'class = "btn btn-large btn-primary"'). "  ";
        echo '<button class="btn btn-large btn-primary" disabled>Begin</button>';
    }
    ?>
    
</div>
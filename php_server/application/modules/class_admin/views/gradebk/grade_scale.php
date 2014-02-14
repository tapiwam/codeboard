<div class="container">
    <pre><?php //print_r($programs);   ?></pre>
    <hr />

    <div class="row">

        <div class="col-md-8">
            <h2>Gradebook Scale</h2>
            <h4><?php echo "class name and term here"; ?></h4>

            <?php if (isset($error)) : ?>
                <div class="alert">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <br />
                    <fieldset id="error">
                        <legend><h2>Sorry, something went wrong...</h2></legend>
                        <pre class="error"><?php echo $error; ?></pre>
                    </fieldset>
                </div>	
            <?php endif; ?>

            <table class="table table-bordered table-striped">
                <th>Program ID</th>
                <th>Program Name</th>
                <th>Session Name</th>
                <th>Graded</th>
                <th>Active</th>

                <?php foreach ($programs as $prog): ?>
                    <tr>
                        <td><?php echo $prog->id; ?></td>
                        <td><?php echo $prog->prog_name; ?></td>
                        <td><?php echo $prog->session_name; ?></td>
                        <td><?php
                            if ($prog->graded == 0) {
                                echo "No";
                            } else if ($prog->graded == 1) {
                                echo "Yes";
                            } else if ($prog->graded == 2) {
                                echo "Extra";
                            }
                            ?>
                        </td>
                        <td><?php if ($prog->active == 1) {
                    echo "Yes";
                } else {
                    echo "No";
                } ?></td>

                    </tr>
<?php endforeach; ?>
            </table>

            <hr>
            <?php echo anchor("class_admin/classes/$class_id", 'Class', 'class="btn btn-primary btn-large"');
            echo "    "; ?>
<?php echo anchor("class_admin/gradebk/index/$class_id", 'Gradebook', 'class="btn btn-primary btn-large"');
echo "    "; ?>
<?php echo anchor("class_admin/gradebk/edit_scale/$class_id", 'Edit', 'class="btn btn-primary btn-large"'); ?>

        </div>

        <div class="col-md-4">
<?php
$data['class_id'] = $class_id;
$this->load->view('includes/class_sidebar', $data);
?>
        </div>

    </div>
</div>
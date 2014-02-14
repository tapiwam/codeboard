<div id="class_panel" class="container">

    <h2>Class: <?php echo $classinfo[0]->class_name; ?></h2>	
    <h4>Term: <?php echo $classinfo[0]->term; ?></h4>
    <hr>

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading"><h4>Labs</h4></div>
                <?php
                $startDate = time();
                $sd = date('Y-m-d H:i:s');
                //echo $sd . '<br />';
                //echo '<pre>' . print_r($sessions) . '</pre>'; die();
                ?>

                <?php if (isset($sessions)) : ?>

                    <table class="table">
                        <thead>
                        <th>Lab session</th>
                        <th>Availability</th>
                        <th>Dates</th>
                        </thead>

                        <?php foreach ($sessions as $item): ?>
                            <?php if (time() > strtotime($item->start) && $item->active == '1'): ?>

                                <?php
                                $x = "";

                                if (time() < strtotime($item->start)) {
                                    $x = '<em class="text-success">Early</em>';
                                } else if (strtotime($item->start) < time() && time() < strtotime($item->end)) {
                                    $x .= '<em class="text-success">On time</em>';
                                } else if (time() > strtotime($item->end) && time() < strtotime($item->late)) {
                                    $x .= '<em class="text-warning">Late submission</em>';
                                } else {
                                    $x .= '<em style="color:#d11;">Late and closed</em>';
                                }
                                ?>

                                <tr>
                                    <td>
                                        <?php echo $item->session_name; ?><br /><br />
                                        <?php echo anchor('student/sessions/' . $class_id . '/' . $item->id, 'Open', 'class="btn btn-primary"'); ?>
                                    </td>

                                    <td><?php echo $x; ?></td>
                                    <td>
                                        <table class="table table-condensed table-striped">
                                            <tr>
                                                <td>Open</td>
                                                <td><?php echo date("D F j, Y", strtotime($item->start)) . " at " . date("g:i a", strtotime($item->start)) ?></td>
                                            </tr>
                                            <tr>
                                                <td>On-time Submission</td>
                                                <td><?php echo date("D F j, Y", strtotime($item->end)) . " at " . date("g:i a", strtotime($item->end)); ?></td>
                                            </tr>
                                            <?php if ($item->late >= $item->end): ?>
                                                <tr>
                                                    <td>Final Deadline</td>
                                                    <td><?php echo date("D F j, Y", strtotime($item->late)) . " at " . date("g:i a", strtotime($item->late)); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </table>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </table>

                <?php endif; ?>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading"><h4>Announcements</h4></div>
                <ul class="list-group">
                    <?php
                    if (isset($announce)) {
                        foreach ($announce as $item) {
                            echo anchor('announcements', 'item', 'class="list-group-item"');
                        }
                    } else {
                        echo '<p class="list-group-item">No announcements at the moment!</p>';
                    }
                    ?>

                </ul>
            </div>

            <hr />
            <?php echo anchor("student", "Back", 'class="btn btn-large btn-primary"'); ?>

        </div>

        <div class="col-md-4">
            <?php $this->load->view('includes/student_sidebar'); ?>
            <?php $this->load->view('includes/student_class_sidebar', array('class_id' => $class_id)); ?>
        </div>

    </div>
</div>
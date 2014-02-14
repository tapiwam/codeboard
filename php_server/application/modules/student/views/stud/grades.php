<div class="container">

    <h2>Grades: <?php echo $stud->first_name . ' ' . $stud->last_name; ?></h2>

    <div class="well">
        <h4>Scores:   <?php echo $stud_avg->total . " / $possible (possible)" ; ?> </h4>
        <h4>Average:   <?php echo $stud_avg->avg ; ?>%</h4>
        <h4>Current Grade:  <?php echo $stud_avg->grade ; ?></h4>
    </div>
    
    <?php if (isset($grades) && !empty($grades)): ?>

        <table class="table table-striped">
            <thead>
            <th>Program</th>
            <th>Lab</th>
            <th>Graded</th>
            <th>Deadline</th>

            <th>Raw-Score</th>
            <th>Score</th>
            <th>Late</th>
            <th>Possible points</th>
            <th>Percentage</th>
            <th></th>

            </thead>

            <?php foreach ($grades as $item): ?>
                <tr>
                    <td><?php echo anchor("student/assignment/$class_id/" . $item->prog_info->session_id . "/" . $item->prog_info->prog_name, $item->prog_info->prog_name); ?></td>
                    <td><?php echo $item->prog_info->session_name; ?></td>
                    <td><?php echo $item->prog_info->graded; ?></td>
                    <td><?php echo date("F j, Y", strtotime($item->prog_info->late)); ?></td>

                    <td><?php echo $item->score ?></td>
                    <td><?php echo ($item->score-$item->late) ?></td>
                    <td><?php if($item->late > 0) {echo "Yes"; } else {echo "No"; }; ?></td>
                    <td><?php echo $item->prog_info->possible; ?></td>
                    <td><?php echo round(($item->score-$item->late) / $item->prog_info->possible * 100) . '%'; ?></td>
                    <td><?php echo anchor("student/report/$class_id/" . $item->prog_info->session_id . "/" . $item->prog_info->prog_name, "report", 'class="btn btn-primary"'); ?> </td>
                </tr>
            <?php endforeach; ?>

        </table>

    <?php else: ?>
        <p><em><strong>Looks like you haven't submitted anything yet.</strong></em></p>
    <?php endif; ?>

    <hr />
    <h4><strong><em>Not yet graded</em></strong></h4>
    <?php
    // find out which grades are missing
    $a = array();
    $b = array();
    $p = $programs;
    foreach ($p as $key => $prog) {
        foreach ($grades as $item) {
            // remove a program from the list if you find it
            if ($prog->prog_info->id == $item->prog_info->id) {
                unset($p[$key]);
            }
        }
    }
    ?>

    <?php if (!empty($p)): //var_dump($p); echo '<hr>'; ?>

        <table class="table table-striped">
            <thead>
            <th>Program</th>
            <th>Lab</th>
            <th>Graded</th>
            <th>Deadline</th>

            <th>Score</th>
            <th>Possible points</th>
            <th>Percentage</th>
            <th></th>

            </thead>

            <?php foreach ($p as $item): ?>
                <tr>
                    <td><?php echo anchor("student/assignment/$class_id/" . $item->prog_info->session_id . "/" . $item->prog_info->prog_name, $item->prog_info->prog_name); ?></td>
                    <td><?php echo $item->prog_info->session_name; ?></td>
                    <td><?php echo $item->prog_info->graded; ?></td>
                    <td><?php echo $item->prog_info->deadline; ?></td>

                    <td><?php echo "-"; ?></td>
                    <td><?php echo $item->prog_info->possible; ?></td>
                    <td><?php echo '0%'; ?></td>
                    <td><button class="btn btn-primary" disabled>report</button></td>
                </tr>
            <?php endforeach; ?>

        </table>       

    <?php else: ?>
        <p><em><strong>No ungraded items for <?php echo $stud->first_name; ?>.</strong></em></p>
    <?php endif; ?>

    <hr />
    <button onclick="history.go(-1);" class="btn btn-primary btn-large">Back</button>
</div>
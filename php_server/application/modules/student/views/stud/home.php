
<div id="student_home" class="container">
    <h1>Student Home</h1>
    <hr>

    <h4>Welcome <?php echo $this->session->userdata('first_name'); ?> </h5>

        <div class="row">

            <div class="col-md-8">

                <p>Welcome to the <em>student panel</em>. Here you can take assignments, check your grades and even practice with some basic programming within our practice environment. 
                    As this site is still in development we will continually upgrade and add-on new features as we progress. 
                </p>	
                <p>	Please feel free to contact me, <?php echo safe_mailto('tapiwa.maruni@live.com', 'Tapiwa'); ?>, or email the site email with any feedback of concerns you may have. 
                    I hope that you will find this site, helpful, user friendly and beneficial to your work. Thanks, Tapiwa 
                </p>

                <hr />

                <div class="panel panel-primary">
                    <div class="panel-heading"><h4>My Courses</h4></div>

                    <?php if (isset($classes)): ?>
                        <table class="table table-hover table-striped">
                            <thead>
                            <th>Course Name</th>
                            <th>Term</th>
                            <th>Instructor</th>
                            </thead>
                            <?php foreach ($classes as $item): ?>
                                <tr>
                                    <td><?php echo anchor('student/classes/' . $item->id, $item->class_name); ?></td>
                                    <td><?php echo $item->term; ?></td>
                                    <td><?php echo $item->instructor; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <p><strong>No Active Classes Yet</strong></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-4">
                <?php $this->load->view('includes/student_sidebar'); ?>
            </div>


        </div>

</div>
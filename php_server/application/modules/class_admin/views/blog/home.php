<div class="container">
    <h2>Lab blogs</h2>
    <h4>Lab: <?php echo $sessioninfo[0]->session_name; ?></h4>

    <div class="row">

        <div class="col-md-8">

            <?php if (validation_errors() != false) : ?>
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <br />
                    <fieldset id="error">
                        <legend><h2>Sorry, something went wrong...</h2></legend>
                        <?php echo validation_errors('<p class="error">'); ?>
                    </fieldset>
                </div>	
            <?php endif; ?>

            <?php if (isset($error)) : ?>
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <br />
                    <fieldset id="error">
                        <legend><h2>Sorry, something went wrong...</h2></legend>
                        <pre class="error"><?php echo $error; ?></pre>
                    </fieldset>
                </div>	
            <?php endif; ?>

            <!----------------------------------------------------->

            <?php if (isset($blogs)): ?>

                <table class="table">

                    <thead>
                    <th>Title</th>
                    <th>Posted</th>
                    <th>Last Updated</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    </thead>

                    <?php foreach ($blogs as $blog): ?>

                        <tr>
                            <td><?php echo $blog->title; ?></td>
                            <td><?php echo $blog->created; ?></td>
                            <td><?php echo $blog->updated; ?></td>
                            <td><?php echo anchor("class_admin/blog/view/$class_id/$session_id/" . $blog->id, 'View', 'class="btn btn-primary"'); ?></td>
                            <td><?php echo anchor("class_admin/blog/edit/$class_id/$session_id/" . $blog->id, 'Edit', 'class="btn btn-primary"'); ?></td>
                            <td><?php echo anchor("class_admin/blog/delete/$class_id/$session_id/" . $blog->id, 'Delete', 'class="btn btn-primary"'); ?></td>
                        </tr>

                    <?php endforeach; ?>
                </table>

            <?php else: ?>
                <p>No posts yet for this lab.</p>
            <?php endif; ?>
            
            <?php echo anchor("class_admin/sessions/$class_id/$session_id", 'Back to lab', 'class = "btn btn-primary"') . "     "; ?>
            <?php echo anchor("class_admin/blog/create_option/$class_id/$session_id", 'Create a blog', 'class="btn btn-primary"'); ?>

        </div>

        <div class="col-md-4">
            <ul class="nav nav-list">
                <li><h2 class="nav-header">Lab Management Menu</h2></li>
                <?php
                echo '<li>' . anchor('class_admin/create_assignment_option/' . $class_id . '/' . $session_id, 'Create an Assignment' ) . '</li>';
                echo '<li>' . anchor("class_admin/blog/create_option/$class_id/$session_id", 'Create a blog' ) . '</li>' ;
                echo '<li>' . anchor('class_admin/import/index/' . $class_id . '/' . $session_id, 'Import an item' ) . '</li>';
                
                echo '<li class="divider"></li>';
                
                echo '<li>' . anchor('class_admin/manage/assignments/' . $class_id . '/' . $session_id, 'Manage Assignments') . '</li>';
                echo '<li>' . anchor('class_admin/blog/index/' . $class_id . '/' . $session_id, 'Manage Blogs') . '</li>';
                echo '<li>' . anchor('class_admin/manage/session_dates/' . $class_id . '/' . $session_id, 'Change Dates') . '</li>';

                if ($sessioninfo[0]->active == 1)
                    echo '<li>' . anchor('class_admin/manage/deactivate_session/' . $class_id . '/' . $session_id, 'Deactivate Lab') . '</li>';
                else
                    echo '<li>' . anchor('class_admin/manage/activate_session/' . $class_id . '/' . $session_id, 'Activate Lab') . '</li>';
                ?>
                <li class="divider"></li>
            </ul>

            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: "textarea"
    });
</script>
<div id="admin_home" class="container">

    <h1>Welcome <?php echo $this->session->userdata('first_name') . '<br>'; ?> </h1>
    <p>Here you will find most major Admin Controls. To get started simply create a class, or if you already have one, simply click on one of your courses.</p>
    <hr />
    <div class="row">
        <div class="col-md-8">

            <h4><em><strong>Classes</strong></em></h4>
            <div class="list-group">

                <?php if (isset($classes)) : ?>
                    <?php
                    foreach ($classes as $item):
                        if (!empty($item)):
                            ?>

                            <a class="list-group-item" href="<?php echo site_url('class_admin/classes/' . $item->id) ?>">
                                <div class="list-group-item-heading"><?php echo $item->class_name; ?></div>
                                <div class="list-group-item-text">Term: <?php echo $item->term; ?></div>
                            </a>

                            <?php
                        endif;
                    endforeach;
                    ?>

                <?php else : ?>
                    <li>No Active Classes</li>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Management</div>
                <div class="list-group">
                <?php echo anchor('class_admin/create_class_option', 'Create a Class', 'class="list-group-item"'); ?></li>
                <?php echo anchor('class_admin/manage/classes', 'Manage Classes', 'class="list-group-item"'); ?>
                </div>
            </div>

            <?php $this->load->view('includes/main_sidebar'); ?>

            <div class="panel panel-default">
                <div class="panel-heading">User Related Information</div>
                <div class="list-group">
                    <?php echo anchor('site/profile', 'Profile', 'class="list-group-item"'); ?> 
                    <?php echo anchor('site/settings', 'Settings', 'class="list-group-item"'); ?> 
                </div>
            </div>
        </div>

    </div>
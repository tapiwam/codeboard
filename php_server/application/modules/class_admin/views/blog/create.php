<div class="container">
    <h2>Create a blog</h2>
    <h4>Lab: <?php echo $sessioninfo[0]->session_name; ?></h4>

    <div class="row">

        <div class="col-md-8">

            <?php
            if (isset($blog_item)) {
                echo form_open("class_admin/blog/update/$class_id/$session_id/" . $blog_item->id);
            } else {
                echo form_open("class_admin/blog/create/$class_id/$session_id");
            }

            echo form_hidden('class_id', $class_id);
            echo form_hidden('session_id', $session_id);

            if (isset($blog_item)) {
                $t = $blog_item->title;
                $d = $blog_item->description;
                $c = $blog_item->content;
            } else {
                $t = set_value('title');
                $d = set_value('description');
                $c = set_value('content');
            }
            ?>

            <hr />

            
            <legend>Title</legend>
            <div class="form-group">
            <?php echo form_input('title', $t, 'class="form-control"'); ?>
            </div>

            <legend>Description</legend>
            <div class="form-group">
            <?php echo form_input('description', $d, 'class="form-control"'); ?>
            </div>

            <legend>Content</legend>
            <div class="form-group">
            <?php echo form_textarea('content', $c, 'id="content"', 'class="form-control"'); ?>
            </div>
            
            <br />
            <?php echo anchor("class_admin/blog/index/$class_id/$session_id/", 'Back', 'class="btn btn-primary"');
            echo "   "; ?>
            <?php echo form_submit('submit', 'Post', 'class = "btn btn-primary"'); ?>
<?php echo form_close(); ?>

        </div>

        <div class="col-md-4">
            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>components/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>components/js/tiny.js"></script>
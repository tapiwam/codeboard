<div class="container">

    <h2><?php echo $blog_item->title; ?></h2>

    <div class="row">

        <div class="col-md-8">
            posted <?php echo $blog_item->created; ?> by <?php echo $blog_item->author; ?>
            <hr />

            <p><?php echo html_entity_decode($blog_item->content); ?></p>
            
            <hr />
            <a href="javascript:history.go(-1)" class="btn btn-primary btn-large">Back</a>
            
        </div>

        <div class="col-md-4">
            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>
        </div>

    </div>

</div>
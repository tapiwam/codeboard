<div class="container">

    <h2><?php echo $blog_item->title; ?></h2>
    posted <strong><em><?php echo date("D F j, Y",strtotime($blog_item->created)) ; ?></em></strong> 
    by <strong><em><?php echo $blog_item->author; ?></em></strong>
    <hr />
    
    <div class="row">
        <div class="col-md-8">
           <p><?php echo html_entity_decode($blog_item->content); ?></p>
            <hr />
            
            <?php echo anchor("student/sessions/$class_id/$session_id", 'Back', 'class="btn btn-primary"'); echo "   "; ?>
            
        </div>

        <div class="col-md-4">
            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/student_sidebar', $data);
            $this->load->view('includes/student_class_sidebar', $data); 
            ?>
        </div>

    </div>

</div>
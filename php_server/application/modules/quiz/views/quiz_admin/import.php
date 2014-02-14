<div class="container">

    <h2>Import a question</h2>

    <div class="row-fluid">
        <div class="span8">

            <h4><strong><em>Search for questions</em></strong></h4>

            <?php echo form_open('', 'class="form-horizontal span12 well pull-left"'); ?>

            <div class="control-group">
                <label class="control-label" for="title">Search terms:</label>
                <div class="controls">
                    <?php echo form_input('keyword', set_value('keyword'), 'class="search-query" placeholder="Search" action'); ?>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label" for="title">Question type:</label>
                <div class="controls">
                    <?php  
                    echo form_radio('type', 'program'). " program<br />";
                    echo form_radio('type', 'multiple'). " multiple choice<br />";
                    echo form_radio('type', ''). " any"; 
                    ?>
                </div>
            </div>
            
            <div class="control-group">
                
                <div class="controls">
                    <?php  
                    echo '<button type="submit" class="btn btn-primary">Search</button>';
                    ?>
                </div>
            </div>

            <?php echo form_close(); ?>
            
            <hr />
            
            <div id="content"></div>

        </div>

        <div class="span4">
            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>
        </div>
    </div>
</div>
<div class="container">

    <h2>Create a question</h2>

    <div class="row-fluid">
        <div class="span8">
            
            <?php if(validation_errors() != false) : ?>
            <div class="alert">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <br />
                    <fieldset id="error">
                        <legend><h2>Sorry, something went wrong...</h2></legend>
                        <?php echo validation_errors('<p class="error">'); ?>
                    </fieldset>
                </div>	
            <?php endif; ?>

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

            <?php 
            $u4 = $this->uri->segment(4);
            $u5 = $this->uri->segment(5);
            $u6 = $this->uri->segment(6);
            $item ="";
              
            if( (isset($class_id) && isset($session_id) && isset($quiz_id))){
                $item = "$class_id/$session_id/$quiz_id";
            } else if( !empty($u4) && !empty($u5) && !empty($u6) ){
                $item = "$u4/$u5/$u6";
            }
            
            echo form_open("quiz/create_ques/add_eval/$item", 'class="form-horizontal well"'); 
            ?>

            <div class="control-group">
                <label class="control-label" for="">Question Title/Description</label>
                <div class="controls">
                    <?php echo form_input('title', set_value('title'), 'class="input-xlarge"'); ?>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label" for="type">Question type</label>
                <div class="controls">
                    <?php echo form_radio('type', 'multiple', true). " Multiple Choice"; ?><br />
                    <?php echo form_radio('type', 'program'). " Programming"; ?><br />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label" for="points">Question Points</label>
                <div class="controls">
                    <?php echo form_input('points', set_value('points'), 'class="input-xlarge"'); ?>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label" for="terms">Question search terms</label>
                <div class="controls">
                    <?php echo form_input('terms', set_value('terms'), 'class="input-xlarge"'); ?>
                </div>
            </div>

            <div class="form-actions">
                <?php echo form_submit('submit', 'Proceed to Answers >>', 'class="btn btn-primary btn-large"'); ?>
            </div>
                
            <?php echo form_close(); ?>


        </div>


        <div class="span4">
            Sidebar
        </div>
    </div>
</div>
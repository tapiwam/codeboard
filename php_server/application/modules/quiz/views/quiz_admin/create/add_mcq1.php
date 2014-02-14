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
            
            <?php echo form_open("quiz/create_ques/add_mcq/$class_id/$ques_id", 'class="form-horizontal well"'); ?>

            <div class="control-group">
                <label class="control-label" for="ques">Question</label>
                <div class="controls">
                    <?php echo form_input('ques', set_value('ques'), 'class="input-xlarge"'); ?>
                </div>
            </div>
            
            <div id="answers"></div>
            
            <hr />
            <div class="control-group">
                <div class="controls">
                    <button id="addOption" class="btn btn-primary" onclick="return false;">Add multiple choice option</button>
                </div>
            </div>

            <div class="form-actions">
                <?php echo form_submit('submit', 'Submit question >>', 'class="btn btn-primary btn-large"'); ?>
            </div>
                
            <?php echo form_close(); ?>
            
        </div>
        
        <div class="span4">
            Sidebar
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    var counter=1;
   $('#addOption').click(function(){
       addItem(counter);
       counter ++;
   }); 
});

function addItem(num){
    
    var opt = '<div class="control-group">';
   opt = opt + '<label class="control-label" for="type">option_'+ num +'</label>';
   opt = opt + '<div class="controls">';
   opt = opt + '<input type="text" name="option_'+ num +'" class="input-xlarge" /><br />';
   opt = opt + '<input type="hidden" name="correct_'+num+'" value="0">';
   opt = opt + '<input type="checkbox" name="correct_'+num+'" value="1"> Is this a/the correct option?';
   opt = opt + '</div></div>';
   
    $('#answers').append(opt);
}

function removeItem(name){
}

</script>    
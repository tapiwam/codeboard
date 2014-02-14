<script src="<?php echo base_url(); ?>components/js/apps/progApp.js"></script>
<style type="text/css" media="screen">
    .ace_editor  {
        height : 500px;
    }
</style>

<input type="hidden" name="class_id" id="class_id" value="<?php echo $class_id; ?>">
<input type="hidden" name="session_id" id="session_id" value="<?php echo $session_id; ?>">
<input type="hidden" name="prog_name" id="prog_name" value="<?php echo $prog_name; ?>">
<input type="hidden" name="base_url" id="base_url" value="<?php echo site_url(); ?>">

<div ng-app="progApp" >
    <div class="container">

    <h2><?php echo $sessioninfo[0]->session_name; ?></h2>
    <h4><?php echo $prog[0]->prog_name; ?></h4>
    <hr />

    
    <div class="row" ng-controller="progController">
        <div class="col-md-8">
            
            <tabset>
                <tab heading="Description">
                    <br />
                    <div ng-bind-html="description"></div>
                    <br />
                    <?php 
                    echo anchor("student/review/".$prog[0]->class_id.'/'.$prog[0]->session_id . '/' . $prog[0]->prog_name , "Analytics", 'class="btn btn-primary btn-large"'). "   " ;
                    echo anchor("student/sessions/".$prog[0]->class_id.'/'.$prog[0]->session_id , "Lab", 'class="btn btn-primary btn-large"'). "   " ;
                    echo anchor("student/classes/".$prog[0]->class_id , "Class", 'class="btn btn-primary btn-large"'). "   " 
                    ?>
                </tab>
                
                <tab ng-repeat="file in files">
                    <tab-heading>
                        {{ file.file_name }}
                    </tab-heading>
                    <br />
                    <div ng-if="error_check" class="alert alert-warning">
                        <br />
                        <fieldset id="error">
                            <legend><h2>Sorry, something went wrong...</h2></legend>
                            <pre ng-bind-html="error_response"></pre>
                        </fieldset>
                    </div>
                    
                    <div ng-model="file.file_content" ui-ace="{
                             useWrapMode : true,
                             showGutter: false,
                             theme:'twilight',
                             mode: 'c_cpp',
                             onLoad: codeLoaded
                             }"></div>
                    <br />
                    <button class="btn btn-primary" ng-click="submit()">Submit</button>
                </tab>
                
                <tab heading="Results" active="data.static">
                    <div class="row">
                        <br />
                        <tabset>
                            <tab ng-repeat="r in results" >
                                <tab-heading>
                                    Attempt {{r.submits}}
                                </tab-heading>
                                <div ng-bind-html="r.report"></div>
                            </tab>
                        </tabset>
                        <br />
                        <?php 
                        echo anchor("student/review/".$prog[0]->class_id.'/'.$prog[0]->session_id . '/' . $prog[0]->prog_name , "Analytics", 'class="btn btn-primary btn-large"'). "   " ;
                        echo anchor("student/sessions/".$prog[0]->class_id.'/'.$prog[0]->session_id , "Lab", 'class="btn btn-primary btn-large"'). "   " ;
                        echo anchor("student/classes/".$prog[0]->class_id , "Class", 'class="btn btn-primary btn-large"'). "   " 
                        ?>
                    </div>
                    
                </tab>
            </tabset>
        </div>

        <div class="col-md-4">
            <?php $this->load->view('includes/student_sidebar'); ?>
            <?php $this->load->view('includes/student_class_sidebar', array('class_id' => $class_id)); ?>
        </div>

    </div>
</div>
</div>



<div class="container"><?php $page = 3; ?>

    <style type="text/css" media="screen">
        #editor { 
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }
    </style>

    <?php $this->load->view('tc/bread_crumbs', array('info' => $info, 'page' => $page)); ?>

    <h2>Content for Sources and Input files</h2>
    <p>In this section you can enter in the content for any source files and any additional input files that that only need to be entered in once.</p>

    <?php echo form_open('class_admin/tc/filecontent_single/' . $info[0]->class_id . '/' . $info[0]->session_id . '/' . $info[0]->prog_name, 'class="form-horizontal well"'); ?>

    <fieldset>

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

        <div ng-app="tc3">

        <?php
        // pick the single part files and display
        if (isset($fileinfo)) {
            foreach ($fileinfo as $file) {
                if ($file->multi_part == 0 || $file->stream_type == "source" && $file->stream_type != "output") {
                    
                    if ($file->file_content != "")
                        $r_val = $file->file_content;
                    else
                        $r_val = set_value($file->id);
                    echo form_textarea($file->id, $r_val, "class=\"tbs\" id=\" $file->id \" ng-init=\"tabs.$file->id\" hidden=true");
                }
            }
        }
        ?>
        
        
            <div ng-controller="tabController">
                <tabset>
                    <tab ng-repeat="tab as tabs">
                        <tab-heading>{{tab.name}}</tab-heading>
                        <div ui-ace="{
                             useWrapMode : true,
                             showGutter: false,
                             theme:'twilight',
                             mode: 'c_cpp',
                             onLoad: codeLoaded
                             }" ng-model=tab.code id=tab.value></div>
                    </tab>
                </tabset>
            </div>
        </div>

        <div class="form-actions">
            <?php echo anchor("class_admin/tc/stage2/" . $info[0]->class_id . '/' . $info[0]->session_id . '/' . $info[0]->prog_name, 'Back', 'class = "btn btn-large btn-primary"'); ?>
            <?php echo form_submit('submit', 'Next step...', 'class = "btn btn-large btn-primary"'); ?>

        </div>

    </fieldset>

    <?php echo form_close(); ?>

</div>

<script>
var app = angular.module('tc3', ['ui.bootstrap', 'ui.ace']);
app.controller('tabController', function ideController($scope) {
    $scope.tabs = [];
    
    var elements = document.getElementsByClassName("tbs");
    for(var i=0; i<elements.length; i++) {
        var r = {
            name: elements[i].name,
            value: elements[i].value
        }
       $scope.tabs.push(r);
    }
    
    $scope.codeLoaded = function(_editor) {
        // Editor part
        cosole.log(_editor);
        
        var _session = _editor.getSession();
        var _renderer = _editor.renderer;

        // Options
        _editor.setReadOnly(false);
        _session.setUndoManager(new ace.UndoManager());
        _renderer.setShowGutter(true);

        // Events
        _editor.on("changeSession", function() {
        });
        _session.on("change", function() {
            $scope.data = _session.getValue();
        });
    };
}

</script>



<?php if (isset($fileinfo)) : ?>
    <script>
    <?php foreach ($fileinfo as $file): ?>
        <?php if ($file->admin_file == 0 && $file->stream_type != "output" && $file->multi_part == 0): ?>
                var editor = CodeMirror.fromTextArea(document.getElementById("<?php echo $file->id; ?>"), {
                    lineNumbers: true,
                    matchBrackets: true,
            <?php if ($file->stream_type == "source") {
                echo 'mode: "text/x-c++src",';
            } ?>
                    theme: "ambiance"
                });
        <?php endif; ?>
    <?php endforeach; ?>
    </script>
<?php endif; ?>


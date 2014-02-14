<script src="<?php echo base_url(); ?>components/js/apps/ideApp.js"></script>
<style type="text/css" media="screen">
    .ace_editor  {
        height : 400px;
    }
</style>

<input id="ip" type="hidden" value="<?php echo $id; ?>" >
<div ng-app="ideApp" >
    <div class="container">
        <h2>Basic IDE</h2>
        <h4>C/C++/Python</h4>
        <hr />
        
        <div class="row" ng-controller="ideController">

            <div class="col-md-12" >
                <tabset>
                    <tab heading="Main">
                        <div ui-ace="{
                             useWrapMode : true,
                             showGutter: false,
                             theme:'twilight',
                             mode: 'c_cpp',
                             onLoad: codeLoaded
                             }" ng-model="code" id="code"></div>

                        <br>
                        <button class="btn" ng-click="saveFile('code')">Download Code</button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" ng-model="data.lang" btn-radio="'c'">C</button>
                            <button type="button" class="btn btn-primary" ng-model="data.lang" btn-radio="'cpp'">C++</button>
                            <button type="button" class="btn btn-primary" ng-model="data.lang" btn-radio="'py'">Python</button>
                        </div>
                    </tab>
                    
                    <tab heading="Input">
                        <div ui-ace="{
                             useWrapMode : true,
                             showGutter: false,
                             theme:'twilight',
                             mode: 'markdown',
                             onLoad: inputLoaded
                             }" ng-model="data.input" id="in"></div>

                        <br />
                        <button class="btn" ng-click="saveFile('data.input')">Download Input</button>
                    </tab>

                    <tab select="submit()">
                        <tab-heading>
                            <i class="glyphicon glyphicon-send"></i> Submit!
                        </tab-heading>
                        <textarea readonly placeholder="Results - still blank or no output is produced!" 
                                  style="margin-top: 15px;
                                  padding: 10px; 
                                  width: 600px; 
                                  height: 400px; 
                                  border: 3px solid #cccccc; 
                                  font-family: Tahoma, sans-serif;" 
                                  ng-model="output"></textarea>
                        <br>
                        
                    </tab>

                    <tab>
                        <tab-heading>
                            {{data.lang}}
                        </tab-heading>
                        <p style="padding:10px; margin-bottom: 50px;">Pick your compile language. More will be added eventually</p>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" ng-model="data.lang" btn-radio="'c'">C</button>
                            <button type="button" class="btn btn-primary" ng-model="data.lang" btn-radio="'cpp'">C++</button>
                            <button type="button" class="btn btn-primary" ng-model="data.lang" btn-radio="'py'">Python</button>
                        </div>
                    </tab>
                </tabset>
            </div>
        </div>
    </div>
</div>



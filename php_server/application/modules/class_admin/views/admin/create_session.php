<script src="<?php echo base_url(); ?>components/js/apps/createLab.js"></script>

<input type="hidden" name="class_id" id="class_id" value="<?php echo $classinfo[0]->id; ?>">
<input type="hidden" name="base_url" id="base_url" value="<?php echo site_url(); ?>">

<div class="container">

    <h2>Create a Lab</h2> 

    <div class="row" ng-app="createDateApp">
        <div class="col-md-8" ng-controller="dateCtrl">
            
            <fieldset>
                <div ng-if="error_check" class="alert alert-warning">
                    <br />
                    <fieldset id="error">
                        <legend><h2>Sorry, something went wrong...</h2></legend>
                        <pre ng-bind-html="error_response"></pre>
                    </fieldset>
                </div>
                
                <div class="form-group">
                    <label class="control-label" for="session_name">Lab Name</label>
                    <?php echo form_input('session_name', set_value('session_name'), 'class="form-control" type="text" ng-model="session_name"'); ?>
                </div>
                <hr />

                <div class="row">
                    <div class="col-md-4">
                        <label>Start date</label>
                    </div>

                    <div class="col-md-6">

                        <p class="input-group">
                            <input type="text" class="form-control" datepicker-popup="{{format}}" ng-model="start" is-open="opened1" min="minDate" datepicker-options="dateOptions" date-disabled="disabled(date, mode)" ng-required="true" close-text="Close" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" ng-click="open1($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                            </span>
                        </p>

                        <div ng-model="start" ng-change="changed(start)" style="display:inline-block;">
                            <timepicker hour-step="hstep" minute-step="mstep" show-meridian="ismeridian"></timepicker>
                        </div>
                    </div>
                </div>
                <hr />
                <!---------------------------------------->

                <div class="row">
                    <div class="col-md-4">
                        <label>Submission date</label>
                    </div>

                    <div class="col-md-6">

                        <p class="input-group">
                            <input type="text" class="form-control" datepicker-popup="{{format}}" ng-model="end" is-open="opened2" min="minDate" datepicker-options="dateOptions" date-disabled="disabled(date, mode)" ng-required="true" close-text="Close" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" ng-click="open2($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                            </span>
                        </p>

                        <div ng-model="end" ng-change="changed(end)" style="display:inline-block;">
                            <timepicker hour-step="hstep" minute-step="mstep" show-meridian="ismeridian"></timepicker>
                        </div>
                    </div>
                </div>
                <hr />
                <!----------------------------------------->

                <div class="row">
                    <div class="col-md-4">
                        <label>Late submission date</label>
                    </div>

                    <div class="col-md-6">

                        <p class="input-group">
                            <input type="text" class="form-control" datepicker-popup="{{format}}" ng-model="late" is-open="opened3" min="minDate" datepicker-options="dateOptions" date-disabled="disabled(date, mode)" ng-required="true" close-text="Close" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" ng-click="open3($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                            </span>
                        </p>

                        <div ng-model="late" ng-change="changed(start)" style="display:inline-block;">
                            <timepicker hour-step="hstep" minute-step="mstep" show-meridian="ismeridian"></timepicker>
                        </div>
                    </div>
                </div>

                <!----------------------------------------->

                <button ng-click="submit()" class="btn btn-primary">Submit</button>

            </fieldset>
        </div>

        <div class="col-md-4">
            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>
        </div>
    </div>



</div>
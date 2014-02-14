<script src="<?php echo base_url(); ?>components/js/apps/changeLabDate.js"></script>

<input type="hidden" name="class_id" id="class_id" value="<?php echo $class_id; ?>">
<input type="hidden" name="session_id" id="session_id" value="<?php echo $session_id; ?>">
<input type="hidden" name="base_url" id="base_url" value="<?php echo site_url(); ?>">

<input type="hidden" name="start" id="start" value="<?php echo $sessioninfo[0]->start; ?>">
<input type="hidden" name="end" id="end" value="<?php echo $sessioninfo[0]->end; ?>">
<input type="hidden" name="late" id="late" value="<?php echo $sessioninfo[0]->late; ?>">


<div class="container" ng-app="changeDateApp">

    <h2>Manage lab dates and times</h2> 
    <hr />

    <div class="row" >
        <div class="col-md-8" ng-controller="dateCtrl">

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
            
            
        </div>

        <div class="col-md-4">
            <?php
            $data['class_id'] = $class_id;
            $this->load->view('includes/class_sidebar', $data);
            ?>
        </div>

    </div>

    <script type="text/javascript">
        
    </script>

</div>
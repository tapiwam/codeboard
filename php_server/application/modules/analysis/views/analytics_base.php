

<input type="hidden" name="class_id" id="class_id" value="<?php echo $class_id; ?>">
<input type="hidden" name="session_id" id="session_id" value="<?php echo $session_id; ?>">
<input type="hidden" name="prog_name" id="prog_name" value="<?php echo $prog_name; ?>">
<input type="hidden" name="base_url" id="base_url" value="<?php echo site_url(); ?>">

<div ng-app="myApp" >
    <div ng-controller="chartCtrl">
        <div id="chartContainer"  style="max-width: 700px; height: 400px;"></div>
    </div>
</div>


<script>
    var app = angular.module('myApp', []);

    app.controller('chartCtrl', function ideController($scope) {
        var chartOptions = {
            dataSource: [
                {company: 'ExonMobile', 2004: 277.02, 2005: 362.53, 2006: 398.91},
                {company: 'General ELectric', 2004: 328.02, 2005: 348.45, 2006: 364.41},
                {company: 'Microsoft', 2004: 297.02, 2005: 279.02, 2006: 272.68},
                {company: 'CitiGroup', 2004: 255.3, 2005: 230.93, 2006: 246.37},
                {company: 'P&G', 2004: 173.54, 2005: 203.52, 2006: 216.37},
                {company: 'ROyal Dutch Shell', 2004: 131.09, 2005: 197.12, 2006: 200.32}
            ],
            series: [
                {valueField: '2004', name: '2004', type: 'area'},
                {valueField: '2005', name: '2005', type: 'area'},
                {valueField: '2006', name: '2006', type: 'spline', color: 'mediumvioletred'}
            ],
            commonSeriesSettings: {
                argumentField: 'company',
                area: {color: 'cornflowerblue'}
            },
            legend: {
                verticalAlignment: 'bottom',
                horizontalAlignment: 'center'
            },
            title: 'Corporations market value'
        }
        
        $("#chartContainer").dxChart(chartOptions);
    });

    /*$("#chartContainer").dxChart({
     dataSource: [
     {day: "Monday", oranges: 3},
     {day: "Tuesday", oranges: 2},
     {day: "Wednesday", oranges: 3},
     {day: "Thursday", oranges: 4},
     {day: "Friday", oranges: 6},
     {day: "Saturday", oranges: 11},
     {day: "Sunday", oranges: 4} ],
     
     series: {
     argumentField: "day",
     valueField: "oranges",
     name: "My oranges",
     type: "bar",
     color: '#ffa500'
     }
     });
     */
</script>
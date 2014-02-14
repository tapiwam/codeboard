'use strict';

var app = angular.module('changeDateApp', ['ui.bootstrap']);

app.controller('dateCtrl', function($scope, $http) {
    // Get basic variable info from page
    $scope.class_id = document.getElementById("class_id").value;
    $scope.session_id = document.getElementById("session_id").value;
    $scope.base = document.getElementById("base_url").value;

    var start = document.getElementById("start").value;
    $scope.start = new Date(parseDatetime(start));
    
    var end = document.getElementById("end").value;
    $scope.end = new Date(parseDatetime(end));

    var late = document.getElementById("late").value;
    $scope.late = new Date(parseDatetime(late));

    $scope.hstep = 1;
    $scope.mstep = 15;
    $scope.ismeridian = true;
    $scope.showWeeks = true;

    $scope.today = function() {
        $scope.dt = new Date();
    };
    $scope.today();
    
    // Disable weekend selection
    $scope.disabled = function(date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.toggleMin = function() {
        $scope.minDate = ($scope.minDate) ? null : new Date();
    };
    // $scope.toggleMin();

    $scope.open1 = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened1 = true;
    };
    $scope.open2 = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened2 = true;
    };
    $scope.open3 = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened3 = true;
    };

    $scope.dateOptions = {
        'year-format': "'yy'",
        'starting-day': 1
    };

    $scope.changed = function(mytime) {
        console.log('Time changed to: ' + $scope.mytime);
    };

    $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'shortDate'];
    $scope.format = $scope.formats[0];
    
    $scope.submit = function(){
        var url = $scope.base+"/class_admin/manage/set_lab_time_api/"+$scope.class_id+"/"+$scope.session_id;
        
        var postData = {
            start: $scope.start.getTime()/ 1000,
            end:  $scope.end.getTime()/ 1000,
            late: $scope.late.getTime()/ 1000,
        };

        $http({
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            url: url,
            method: "POST",
            data: $.param(postData)
        }).success(function(data, status, headers, config) {

            $scope.respose = data.result;
            $scope.success = data.success;
            $scope.error = data.error;

            if ($scope.success == 1) {
                console.log('Submitted times');
                alert('Submitted times');
                var rdt = $scope.base+ "/class_admin/sessions/"+$scope.class_id+"/"+$scope.session_id;
                document.location = rdt;
            } else if ($scope.error > 1) {
                console.log($scope.respose);
                alert($scope.respose);
            }
        }).error(function(data, status, headers, config) {
            $scope.success = 0;
            alert("Sorry, something went wrong with the connection");
        });
    }
    
    function parseDatetime(value) {
        var a = /^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/.exec(value);
        if (a) {
            return new Date(+a[1], +a[2] - 1, +a[3], +a[4], +a[5], +a[6]);
        }
        return null;
    }
    
    function sqlDatetime(date) {
        var x = calcTime(date, -5);
        // alert(date+ " to "+x);
        var x1 = x.toISOString().slice(0, 19).replace('T', ' ');
        // alert(x1);
        return x1;
    }

    function calcTime(d, offset) {
        // convert to msec
        // add local time zone offset 
        // get UTC time in msec
        var utc = d.getTime() + (d.getTimezoneOffset() * 60000);
        return new Date(utc + (3600000 * offset));
    }
});
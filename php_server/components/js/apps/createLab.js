'use strict';

var app = angular.module('createDateApp', ['ui.bootstrap']);

app.controller('dateCtrl', function($scope, $http) {
    // Get basic variable info from page
    $scope.class_id = document.getElementById("class_id").value;
    $scope.base = document.getElementById("base_url").value;
    $scope.session_name = "";

    $scope.start = new Date();
    $scope.end = new Date();
    $scope.late = new Date();

    $scope.hstep = 1;
    $scope.mstep = 15;
    $scope.ismeridian = true;
    $scope.showWeeks = true;
    $scope.error_check = false;

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

    $scope.submit = function() {
        var url = $scope.base + "/class_admin/admin_create/create_session_api";

        var postData = {
            class_id: $scope.class_id,
            session_name: $scope.session_name,
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
                $scope.session_id = data.session_id;
                console.log('Submitted times');
                alert('Submitted times');
                var rdt = $scope.base + "/class_admin/sessions/" + $scope.class_id + "/" + $scope.session_id;
                //alert(rdt);
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
        alert(date+ " to "+x);
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
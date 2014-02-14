'use strict';

var app = angular.module('analyticsApp', ['ui.bootstrap']);

app.controller('chartCtrl', function ideController($scope, $http) {

    // Get basic variable info from page
    var class_id = document.getElementById("class_id").value;
    var session_id = document.getElementById("session_id").value;
    var prog_name = document.getElementById("prog_name").value;
    var base = document.getElementById("base_url").value;
    var uid = document.getElementById("uid").value;
    
    $scope.formats = [
        {link: 'grades_time', name: 'Grades/time'},
        {link: 'lines_time', name: 'Code growth/time'}
    ];
    
    $scope.grades = function(){
        var url1 = base + '/analysis/prog_grades_time/'+ uid+'/'+class_id +'/'+session_id+'/'+prog_name;
        chart2(url1);
        
        //$scope.div2.show = true;
        var url2 = base + '/analysis/prog_grades_events/'+ uid+'/'+ class_id + '/' + session_id + '/' + prog_name;
        chart1(url2);
    }
    
    $scope.all_grades  = function() {
        var url = base + '/analysis/all_prog_grades_time/'+ uid+'/'+ class_id + '/' + session_id + '/' + prog_name;
        chart2(url);
        
        var url = base + '/analysis/all_prog_grades_submits_bar/'+ uid+'/'+ class_id + '/' + session_id + '/' + prog_name;
        chart1(url);
    }
    
    $scope.lines = function() {
        var url1 = base + '/analysis/prog_lines_time/'+ uid+'/'+ class_id + '/' + session_id + '/' + prog_name;
        chart2(url1);
    
        var url2 = base + '/analysis/prog_lines_submits/'+ uid+'/'+ class_id + '/' + session_id + '/' + prog_name;
        chart1(url2);
    }
    
    // ===============================================
    // Individual Charts
    // ===============================================

    $scope.grades_time = function() {
        var url = base + '/analysis/prog_grades_time/'+ uid+'/'+ class_id + '/' + session_id + '/' + prog_name;
        chart1(url);
    }
    
    $scope.grades_submits = function() {
        var url = base + '/analysis/prog_grades_events/'+ uid+'/'+ class_id + '/' + session_id + '/' + prog_name;
        chart1(url);
    }
    
    $scope.all_grades_time  = function() {
        var url = base + '/analysis/all_prog_grades_time/'+ uid+'/'+ class_id + '/' + session_id + '/' + prog_name;
        chart1(url);
    }
    
    $scope.all_grades_submits = function() {
        var url = base + '/analysis/all_prog_grades_submits_bar/'+ uid+'/'+ class_id + '/' + session_id + '/' + prog_name;
        chart1(url);
    }
    
    // ==================================================
    $scope.lines_time = function() {
        var url = base + '/analysis/prog_lines_time/'+ uid+'/'+ class_id + '/' + session_id + '/' + prog_name;
        chart1(1, url);
    }
    
    $scope.lines_submits = function() {
        var url = base + '/analysis/prog_lines_submits/'+ uid+'/'+ class_id + '/' + session_id + '/' + prog_name;
        chart1(url);
    }
    
    var chart1 = function(url){
        $http({
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            url: url,
            method: "GET"
        }).success(function(data, status, headers, config) {

            $scope.chartOptions = data.result;
            $scope.success = data.success;
            $scope.error = data.error;

            if ($scope.success == 1) {
                $("#chartContainer1").dxChart();
                $("#chartContainer1").dxChart($scope.chartOptions);
            } else if ($scope.error == 1) {
                alert("No data recieved!");
            }
        }).error(function(data, status, headers, config) {
            $scope.success = 0;
            alert("Sorry, something went horribly wrong!");
        });
    }
    
    var chart2 = function(url){
        $http({
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            url: url,
            method: "GET"
        }).success(function(data, status, headers, config) {

            $scope.chartOptions = data.result;
            $scope.success = data.success;
            $scope.error = data.error;

            if ($scope.success == 1) {
                $("#chartContainer2").dxChart();
                $("#chartContainer2").dxChart($scope.chartOptions);
            } else if ($scope.error == 1) {
                alert("No data recieved!");
            }
        }).error(function(data, status, headers, config) {
            $scope.success = 0;
            alert("Sorry, something went horribly wrong!");
        });
    }
    
    $scope.grades();
});
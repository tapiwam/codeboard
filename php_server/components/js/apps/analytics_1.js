'use strict';

var app = angular.module('analyticsApp', ['ui.bootstrap']);

app.controller('chartCtrl', function ideController($scope, $http) {

    // Get basic variable info from page
    var class_id = document.getElementById("class_id").value;
    var session_id = document.getElementById("session_id").value;
    var prog_name = document.getElementById("prog_name").value;
    var base = document.getElementById("base_url").value;

    $scope.grades_time = function() {
        var url = base + '/student/analysis/prog_grades_time/' + class_id + '/' + session_id + '/' + prog_name;
        update_chart(url);
    }
    
    $scope.grades_submits = function() {
        var url = base + '/student/analysis/prog_grades_events/' + class_id + '/' + session_id + '/' + prog_name;
        update_chart(url);
    }
    
    $scope.all_grades_submits = function() {
        var url = base + '/student/analysis/all_prog_grades_events_bar/' + class_id + '/' + session_id + '/' + prog_name;
        update_chart(url);
    }
    
    $scope.all_grades_time = function() {
        var url = base + '/student/analysis/all_prog_grades_events_bar/' + class_id + '/' + session_id + '/' + prog_name;
        update_chart(url);
    }
    
    // ==================================================
    $scope.lines_time = function() {
        var url = base + '/student/analysis/prog_lines_time/' + class_id + '/' + session_id + '/' + prog_name;
        update_chart(url);
    }
    
    $scope.lines_submits = function() {
        var url = base + '/student/analysis/prog_lines_submits/' + class_id + '/' + session_id + '/' + prog_name;
        update_chart(url);
    }

    // =================================================
    $scope.test = function() {
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

    }
    
    var update_chart = function(url){
        $http({
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            url: url,
            method: "GET"
        }).success(function(data, status, headers, config) {

            $scope.chartOptions = data.result;
            $scope.success = data.success;
            $scope.error = data.error;

            if ($scope.success == 1) {
                $("#chartContainer").dxChart($scope.chartOptions);
            } else if ($scope.error == 1) {
                alert("No data recieved!");
            }
        }).error(function(data, status, headers, config) {
            $scope.success = 0;
            alert("Sorry, something went horribly wrong!");
        });
    }
    
    // $scope.grades();
});
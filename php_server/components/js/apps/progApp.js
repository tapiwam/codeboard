var app = angular.module('progApp', ['ui.bootstrap', 'ui.ace', 'ngSanitize']);

app.filter('_uriseg', function($location) {
    return function(segment) {
        // Get URI and remove the domain base url global var
        var query = $location.absUrl().replace(BASE_URL, "");
        // To obj
        var data = query.split("/");
        // Return segment *segments are 1,2,3 keys are 0,1,2
        if (data[segment - 1]) {
            return data[segment - 1];
        }
        return false;
    }
});

var template = "#include <iostream>\n\
#include <string>\n\
using namespace std;\n\
int main()\n\
{\n\
    return 0;\n\
}";

app.controller('progController', function ideController($scope, $sce, $http) {

    var class_id = document.getElementById("class_id").value;
    var session_id = document.getElementById("session_id").value;
    var prog_name = document.getElementById("prog_name").value;
    var base = document.getElementById("base_url").value;
    var url = base + '/student/api/prog/' + class_id + '/' + session_id + '/' + prog_name;

    $scope.r_active = false;

    $http({
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        url: url,
        method: "GET"
    }).success(function(data, status, headers, config) {
        $scope.class_id = data.class_id;
        $scope.classinfo = data.classinfo; // assign  $scope.persons here as promise is resolved here 
        $scope.session_id = data.session_id;
        $scope.sessioninfo = data.sessioninfo;
        $scope.prog_id = data.prog_id;
        $scope.prog_info = data.prog_info;
        
        if (data.files instanceof Array){
            $scope.files = data.files;
        } else {
            $scope.files = json2array(data.files);
        }
        
        $scope.student_data = data.student_data;
        $scope.results = data.results;

        $scope.success = data.success;
        $scope.error = data.error;

        //$scope.$apply();  // Push everything to the view if possible

        if ($scope.success == 1) {
            // alert("Submitted!");
            $scope.description = $sce.trustAsHtml($scope.prog_info.description);
        } else if ($scope.error == 1) {
            alert("Bad data or connection!");
            $scope.error = 0;
        } else {
            alert("Sorry, something is funky!");
        }
    }).error(function(data, status, headers, config) {
        $scope.success = 0;
        $scope.output = data.result;
        alert("Sorry, something went horribly wrong!");
    });


    $scope.submit = function() {
        //alert($scope.files[0].content);

        // Grab the file data from the IDE and comp
        var sendData = {};
        for (var i = 0; i < $scope.files.length; i++) {
            sendData[$scope.files[i].id] = $scope.files[i].file_content;
        }

        sendData = $.param(sendData);

        var base = document.getElementById("base_url").value;
        var url = base + '/student/api/submit/' + class_id + '/' + session_id + '/' + prog_name;

        //alert( JSON.stringify(sendData) );

        $http({
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            url: url,
            data: sendData,
            method: "POST"
        }).success(function(data, status, headers, config) {
            $scope.success = data.success;
            $scope.error = data.error;

            if ($scope.success == 1) {
                // var result = $sce.trustAsHtml(data.respose);
                $scope.error_check = "";
                $scope.results = data.results;
                alert(data.respose);
                $scope.data.static = true;
            } else if ($scope.error == 3) {
                $scope.error_response = data.response;
                alert("Late submission!");
            }else if ($scope.error == 1) {
                $scope.error_response = data.response;
                alert("Problem submitting data!");
            } else if ($scope.error > 1) {
                $scope.error_response = data.response;
                $scope.error_check = true;
                alert("Something went wrong with your prog!");
            }
        });
    };

    $scope.codeLoaded = function(_editor) {
        // Editor part
        var _session = _editor.getSession();
        var _renderer = _editor.renderer;

        // Options
        _editor.setReadOnly(false);
        _session.setUndoManager(new ace.UndoManager());
        _renderer.setShowGutter(true);
    };

    $scope.render = function(e) {
        return $(e).html();
    }


});

function json2array(json){
    var result = [];
    var keys = Object.keys(json);
    keys.forEach(function(key){
        result.push(json[key]);
    });
    return result;
}
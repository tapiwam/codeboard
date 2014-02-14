var ideApp = angular.module('ideApp', ['ui.bootstrap', 'ui.ace']);
var template = "#include <iostream>\n\
#include <string>\n\
using namespace std;\n\
int main()\n\
{\n\
    return 0;\n\
}";

ideApp.controller('ideController', function ideController($scope, $http) {

    var t = document.getElementById("ip").value; // ID generated from IP Address
    $scope.code = template;
    $scope.output = "";
    $scope.data = {
        lang: "cpp",
        id: t,
        input: ""
    }
    
    $scope.$watchCollection('data.lang',
            function() {
                console.log('language changed')
            }
    );

    $scope.submit = function() {
        send($scope.data.id, $scope.code, $scope.data.input, $scope.data.lang);
    };

    $scope.codeLoaded = function(_editor) {
        // Editor part
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
            $scope.code = _session.getValue();
        });
    };

    $scope.inputLoaded = function(_editor) {
        // Editor part
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
            $scope.data.input = _session.getValue();
        });
    };

    var send = function(id, code, input, lang) {
        var postData = $.param({
            code: code,
            input: input,
            lang: lang,
            id: id
        });
        var url = 'submit_ide';

        $http({
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            url: url,
            method: "POST",
            data: postData

        }).success(function(data, status, headers, config) {
            $scope.output = data.result; // assign  $scope.persons here as promise is resolved here 
            $scope.success = data.success;
            $scope.error = data.error;
            // $scope.$apply();  // Push everything to the view if possible
            
            if ($scope.success == 2) {
                alert("Could not run!");
            } else if ($scope.success == 1) {
                alert("Submitted!");
            } else if ($scope.error == 1) {
                alert("Could not compile or run. Check your work!");
                $scope.error = 0;
            } else {
                alert("Sorry, something is funky!");
            }
        }).error(function(data, status, headers, config) {
            $scope.success = 0;
            $scope.output = data.result;
            alert("Sorry, something went horribly wrong!");
        });


    };

    $scope.saveFile = function(item) {
        $scope.$apply(); 
        if (item == 'code')
        {
            var fileNameToSaveAs = "main."+$scope.data.lang;
            var textToWrite = $scope.code;
        }
        else
        {
            var fileNameToSaveAs = "input.txt";
            var textToWrite = $scope.data.input;
        }
        var textFileAsBlob = new Blob([textToWrite], {type: 'text/plain'});

        var downloadLink = document.createElement("a");
        downloadLink.download = fileNameToSaveAs;
        downloadLink.innerHTML = "Download File";
        if (window.webkitURL != null)
        {
            // Chrome allows the link to be clicked
            // without actually adding it to the DOM.
            downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
        }
        else
        {
            // Firefox requires the link to be added to the DOM
            // before it can be clicked.
            downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
            downloadLink.onclick = destroyClickedElement;
            downloadLink.style.display = "none";
            document.body.appendChild(downloadLink);
        }

       downloadLink.click();
    };

});
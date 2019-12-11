<script> 
var myapp = angular.module('myApp', ['ui.bootstrap']);

myapp.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});
myapp.controller('getAbbsysInfo', ['$http','$scope', '$timeout', function ($http, $scope,$timeout) 
{   
	$scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
        $http.get($scope.base_url+'Resources/get_aabsys_info').
        success(function(data, status)
        {  
            $scope.aabsysInfo = data;
            //$scope.data.dt_event_date = Date.parse($scope.data.dt_event_date);
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 20; //max no of items to display in a page
            $scope.filteredItems = $scope.aabsysInfo.length; //Initially for no filter  
            $scope.totalItems = $scope.aabsysInfo.length;
        })
        .
        error(function(data, status)
        {
          $scope.status = status; 
        });
        $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
        };
        $scope.filter = function() {
            $timeout(function() { 
                $scope.filteredItems = $scope.filtered.length;
            }, 10);
        };
        $scope.sort_by = function(predicate) {
            $scope.predicate = predicate;
            $scope.reverse = !$scope.reverse;
        };
    }; 
}])
myapp.controller('getGuideLine', ['$http','$scope', '$timeout', function ($http, $scope,$timeout) 
{     
	$scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
        $http.get($scope.base_url+'Resources/get_guidelines').
        success(function(data, status)
        {  
            $scope.guideline = data; 
            $scope.filteredItems = $scope.guideline.length; //Initially for no filter  
            $scope.totalItems = $scope.guideline.length;
        })
        .
        error(function(data, status)
        {
          $scope.status = status; 
        }); 
    }; 
}]) 
myapp.controller('getStaffRule', ['$http','$scope', '$timeout', function ($http, $scope,$timeout) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
        $http.get($scope.base_url+'Resources/get_staff_format_rules').
        success(function(data, status)
        {  
            $scope.rules = data;  
            $scope.filteredItems = $scope.rules.length; //Initially for no filter  
            $scope.totalItems = $scope.rules.length;
        })
        .
        error(function(data, status)
        {
          $scope.status = status; 
        }); 
    };
}])  
</script>
<script> 
var myapp = angular.module('myApp', []);

myapp.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});
myapp.controller('assignshift', ['$http','$scope', '$timeout', function ($http, $scope,$timeout) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
		$scope.asignShifts = [{
            "id": "GS",
            "label": "General Shift"
        },{
            "id": "MS",
            "label": "Morning Shift"
        },{
            "id": "ES",
            "label": "Evening Shift"
        },{
            "id": "NS",
            "label": "Night Shift"
        }];
        $http.get($scope.base_url+'hr_help_desk/get_assign_shift').
        success(function(data, status)
        {  
            $scope.getcustname = data;
            //$scope.data.dt_event_date = Date.parse($scope.data.dt_event_date);
			//$scope.selectOptions = $scope.getcustname.sel;
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 20; //max no of items to display in a page
            $scope.filteredItems = $scope.getcustname.length; //Initially for no filter  
            $scope.totalItems = $scope.getcustname.length;
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
</script>
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
myapp.controller('MyleaveApplication', ['$http','$scope', '$timeout', function ($http, $scope, $timeout) 
{    
    $scope.init = function(base_url,data)
    {
		$scope.entryLimit = 10;
        $scope.base_url=base_url; 
        $http.get($scope.base_url+'Timesheet/get_my_leave_application')
        .success(function(data, status)
        {  
            $scope.getcustname = data;
            //$scope.data.dt_event_date = Date.parse($scope.data.dt_event_date);
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.getcustname.length; //Initially for no filter  
            $scope.totalItems = $scope.getcustname.length;
        })
        .error(function(data, status)
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
		
		//var d = new Date();
		//$scope.searchYear = d.getFullYear();
        $scope.advanceFilter = function() {
			var formdata = new FormData();
			formdata.append('searchMonth', ($scope.searchMonth)? $scope.searchMonth : "");
			formdata.append('searchYear', ($scope.searchYear)? $scope.searchYear : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'Timesheet/get_my_leave_application_search',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).
			success(function(response) {
				$scope.getcustname = response;
				$scope.currentPage = 1; //current page
				$scope.entryLimit = 10; //max no of items to display in a page
				$scope.filteredItems = $scope.getcustname.length; //Initially for no filter  
				$scope.totalItems = $scope.getcustname.length;
			}).
			error(function(response) {
				console.log(response);
			});
        };
    }; 
}]) 
</script>
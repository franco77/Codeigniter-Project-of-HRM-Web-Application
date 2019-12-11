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
myapp.controller('myRegulariseApplication', ['$http','$scope', '$timeout', function ($http, $scope,$timeout) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
		/* $scope.dates = {};
		$scope.years = [{value: 'Year', disabled: true}];
		var d = new Date();
		var n = d.getFullYear();
		for(var i = n; i >= 2009; i--){
			$scope.years.push({value: i});
		}
		$scope.dates.searchYear = n; */
		$scope.entryLimit = 10;
		var yr = (new Date()).getFullYear();
		$scope.searchYear = yr.toString();
		var mm = (new Date()).getMonth();
		mm = mm+1;
		if(mm < 10){
			mm = ('0'+mm).toString();
		}
		$scope.searchMonth = mm.toString();
		
        $http.get($scope.base_url+'Timesheet/get_my_regularise_application').
        success(function(data, status)
        {  
            $scope.getcustname = data;
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
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
		
		//advance search
        $scope.advanceFilter = function() {
			var formdata = new FormData();
			formdata.append('searchMonth', ($scope.searchMonth)? $scope.searchMonth : "");
			formdata.append('searchYear', ($scope.searchYear)? $scope.searchYear : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'Timesheet/get_my_regularise_application_search',
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
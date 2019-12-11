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
myapp.controller('leaveprovision', ['$http','$scope', '$timeout',  '$window', function ($http, $scope,$timeout, $window) 
{   
    $scope.init = function(base_url,data)
    {
		var yr = (new Date()).getFullYear().toString();
		var yrr = (parseInt(yr)+parseInt(1)).toString();
		var yrrr = yr+'-'+yrr;
		$scope.searchYear = yrrr;
		$scope.text = yrr;
        $scope.base_url=base_url;
        $http.get($scope.base_url+'accounts_admin/get_leave_provision').
        success(function(data, status)
        {  
            $scope.leaveprovision = data;
            //$scope.data.dt_event_date = Date.parse($scope.data.dt_event_date);
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.leaveprovision.length; //Initially for no filter  
            $scope.totalItems = $scope.leaveprovision.length;
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
	
	// advance filter
		$scope.advanceFilter = function() {
			var formdata = new FormData();
			formdata.append('searchYear', ($scope.searchYear)? $scope.searchYear : "");
			formdata.append('searchName', ($scope.searchName)? $scope.searchName : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'accounts_admin/get_leave_provision_search',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).success(function(response) {
			$scope.leaveprovision = response;
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.leaveprovision.length; //Initially for no filter  
            $scope.totalItems = $scope.leaveprovision.length;
			}).error(function(response) {
				console.log(response);
			});
        };
		
		//export to excel
		$scope.exportToExcel = function() {
			var formdata = new FormData();
			formdata.append('searchYear', ($scope.searchYear)? $scope.searchYear : "");
			var searchYear = ($scope.searchYear)? $scope.searchYear : "";
			var aaYear = "?aaYear="+searchYear;
			$window.open( $scope.base_url+'en/hr/get_leave_provision_export'+aaYear , '_blank');
        };
		 
}]);

 
</script>
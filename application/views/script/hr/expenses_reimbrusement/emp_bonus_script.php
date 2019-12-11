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
myapp.controller('empBonus', function ($http, $scope,$timeout, $location, $anchorScroll, $window) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
		$scope.entryLimit = 10;
		//$scope.searchYear = '2017-2018';
		var yr = (new Date()).getFullYear();
		var yr1 = yr+1;
		$scope.searchYear = yr+"-"+yr1;
        $http.get($scope.base_url+'en/hr/get_emp_bonus').
        success(function(data, status)
        {  
            $scope.getempBonus = data;
			
            //$scope.data.dt_event_date = Date.parse($scope.data.dt_event_date);
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.getempBonus.length; //Initially for no filter  
            $scope.totalItems = $scope.getempBonus.length;
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
		$scope.scrollTo = function(login_id) {
			$location.hash(login_id);
			$anchorScroll();
		};
		
		//advance filter
		$scope.advanceFilter = function() {
			var formdata = new FormData();
			formdata.append('searchYear', ($scope.searchYear)? $scope.searchYear : "");
			formdata.append('searchName', ($scope.searchName)? $scope.searchName : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/get_emp_bonus_search',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).success(function(response) {
				$scope.getempBonus = response;
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.getempBonus.length; //Initially for no filter  
            $scope.totalItems = $scope.getempBonus.length;
			}).error(function(response) {
				console.log(response);
			});
        };
		
		//export to excel
		$scope.exportToExcel = function() {
			var formdata = new FormData();
			formdata.append('searchYear', ($scope.searchYear)? $scope.searchYear : "");
			formdata.append('searchName', ($scope.searchName)? $scope.searchName : "");
			var searchYear = ($scope.searchYear)? $scope.searchYear : "";
			var searchName = ($scope.searchName)? $scope.searchName : "";
			var aaYear = "";
			if(searchYear != ""){
				aaYear = "?aaYear="+searchYear;
			}
			$window.open( $scope.base_url+'en/hr/emp_bonus_export'+aaYear , '_blank');
        };
		
		
		
		
		
    }; 
})
</script>
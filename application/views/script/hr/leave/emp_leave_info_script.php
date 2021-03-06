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
myapp.controller('empleaveinfo', function ($http, $scope,$timeout, $location, $anchorScroll, $window) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
		$scope.entryLimit = 10;
		var yr = (new Date()).getFullYear();
		//var yr1 = yr+1;
		$scope.searchYear = yr.toString();
        $http.get($scope.base_url+'en/hr/get_emp_leave_info').
        success(function(data, status)
        {  
            $scope.getleaveinfolist = data;
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.getleaveinfolist.length; //Initially for no filter  
            $scope.totalItems = $scope.getleaveinfolist.length;
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
			formdata.append('searchEmpCode', ($scope.searchEmpCode)? $scope.searchEmpCode : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/get_emp_leave_info_search',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).success(function(response) {
				$scope.getleaveinfolist = response;
				$scope.currentPage = 1; //current page
				$scope.entryLimit = 10; //max no of items to display in a page
				$scope.filteredItems = $scope.getleaveinfolist.length; //Initially for no filter  
				$scope.totalItems = $scope.getleaveinfolist.length;
			}).error(function(response) {
				console.log(response);
			});
        };
		
		//export to excel
		$scope.exportToExcel = function() {
			var formdata = new FormData();
			formdata.append('searchYear', ($scope.searchYear)? $scope.searchYear : "");
			formdata.append('searchName', ($scope.searchName)? $scope.searchName : "");
			formdata.append('searchEmpCode', ($scope.searchEmpCode)? $scope.searchEmpCode : "");
			var searchYear = ($scope.searchYear)? $scope.searchYear : "";
			var searchName = ($scope.searchName)? $scope.searchName : "";
			var aaYear = "";
			if(searchYear != ""){
				aaYear = "?aaYear="+searchYear;
			}
			$window.open( $scope.base_url+'en/hr/emp_leave_info_export'+aaYear , '_blank');
        };
		
		
		
		
		
    }; 
})
</script>
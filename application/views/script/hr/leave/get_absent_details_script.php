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
myapp.controller('absentdetails', ['$http','$scope', '$timeout',  '$window', function ($http, $scope,$timeout,$window) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
		$scope.entryLimit = 10;
        $http.get($scope.base_url+'en/hr/get_all_absent_details').
        success(function(data, status)
        {  
            $scope.getabsentdeatils = data;
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.getabsentdeatils.length; //Initially for no filter  
            $scope.totalItems = $scope.getabsentdeatils.length;
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
			formdata.append('searchEndDate', ($scope.searchEndDate)? $scope.searchEndDate : "");
			formdata.append('searchName', ($scope.searchName)? $scope.searchName : "");
			formdata.append('searchStartDate', ($scope.searchStartDate)? $scope.searchStartDate : "");
			formdata.append('searchEmpCode', ($scope.searchEmpCode)? $scope.searchEmpCode : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/get_all_absent_details_search',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).success(function(response) {
				$scope.getabsentdeatils = response;
				$scope.currentPage = 1; //current page
				$scope.entryLimit = 10; //max no of items to display in a page
				$scope.filteredItems = $scope.getabsentdeatils.length; //Initially for no filter  
				$scope.totalItems = $scope.getabsentdeatils.length;
			}).error(function(response) {
				console.log(response);
			});
        };
		
		
		
		//export to excel
		$scope.exportToExcel = function() {
			var formdata = new FormData();
			formdata.append('searchEndDate', ($scope.searchEndDate)? $scope.searchEndDate : "");
			formdata.append('searchName', ($scope.searchName)? $scope.searchName : "");
			formdata.append('searchStartDate', ($scope.searchStartDate)? $scope.searchStartDate : "");
			formdata.append('searchEmpCode', ($scope.searchEmpCode)? $scope.searchEmpCode : "");
			var searchStartDate = ($scope.searchStartDate)? $scope.searchStartDate : "";
			var searchEndDate = ($scope.searchEndDate)? $scope.searchEndDate : "";
			var aaYear = "";
			if(searchStartDate != "" && searchEndDate !=""){
				aaYear = "?sdate="+searchStartDate+'&edate='+searchEndDate;
			}
			$window.open( $scope.base_url+'en/hr/get_all_absent_details_export'+aaYear , '_blank');
        };
    }; 
}]) 
</script>
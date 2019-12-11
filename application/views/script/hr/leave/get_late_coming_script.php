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
myapp.controller('latecoming', ['$http','$scope', '$timeout', '$window', function ($http, $scope,$timeout,$window) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
		$scope.entryLimit = 10;
		var yr = (new Date()).getFullYear();
		$scope.searchYear = yr.toString();
		var mm = (new Date()).getMonth();
		mm = mm+1;
		if(mm < 10){
			mm = ('0'+mm).toString();
		}
		$scope.searchMonth = mm.toString();
		$scope.searchMonth = '04';
        $http.get($scope.base_url+'en/hr/get_all_late_coming').
        success(function(data, status)
        {  
            $scope.getlatecoming = data;
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.getlatecoming.length; //Initially for no filter  
            $scope.totalItems = $scope.getlatecoming.length;
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
			formdata.append('searchMonth', ($scope.searchMonth)? $scope.searchMonth : "");
			formdata.append('searchYear', ($scope.searchYear)? $scope.searchYear : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/get_all_late_coming_search',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).success(function(response) {
				$scope.getlatecoming = response;
				$scope.currentPage = 1; //current page
				$scope.entryLimit = 10; //max no of items to display in a page
				$scope.filteredItems = $scope.getlatecoming.length; //Initially for no filter  
				$scope.totalItems = $scope.getlatecoming.length;
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
			var searchMonth = ($scope.searchMonth)? $scope.searchMonth : "";
			var searchYear = ($scope.searchYear)? $scope.searchYear : "";
			var aaYear = "";
			if(searchStartDate != "" && searchEndDate !="" && searchMonth !="" && searchYear !="" ){
				aaYear = "?sdate="+searchStartDate+'&edate='+searchEndDate+'&month='+searchMonth+'&year='+searchYear;
			}
			else if(searchMonth !="" && searchYear !="" ){
				aaYear = '?month='+searchMonth+'&year='+searchYear;
			}
			$window.open( $scope.base_url+'en/hr/get_all_late_coming_export'+aaYear , '_blank');
        };
		
    }; 
}]) 
</script>
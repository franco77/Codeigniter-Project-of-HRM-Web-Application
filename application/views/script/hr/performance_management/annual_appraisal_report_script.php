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
myapp.controller('AnnualAppraisalReport', function ($http, $scope,$timeout, $location, $anchorScroll,$window) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
		$scope.entryLimit = 10;
		//$scope.searchYear = '2017-2018';
		var yr = (new Date()).getFullYear();
		var yr1 = yr-1;
		$scope.searchYear = yr1+"-"+yr;
        $http.get($scope.base_url+'en/hr/get_annual_appraisal_report').
        success(function(data, status)
        {  
            $scope.getAnnualAppraisalReport = data;
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.getAnnualAppraisalReport.length; //Initially for no filter  
            $scope.totalItems = $scope.getAnnualAppraisalReport.length;
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
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/get_annual_appraisal_report_search',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).
			success(function(response) {
				$scope.getAnnualAppraisalReport = response;
				$scope.currentPage = 1; //current page
				$scope.entryLimit = 10; //max no of items to display in a page
				$scope.filteredItems = $scope.getAnnualAppraisalReport.length; //Initially for no filter  
				$scope.totalItems = $scope.getAnnualAppraisalReport.length;
			}).
			error(function(response) {
				console.log(response);
			});
        };
		
		//advance filter
		$scope.exportToExcel = function() {
			var  searchYear = ($scope.searchYear)? $scope.searchYear : "";
			$window.location.href = $scope.base_url+'en/hr/annual_appraisal_report?type=export&aaYear='+searchYear;
        };
    }; 
})
</script>
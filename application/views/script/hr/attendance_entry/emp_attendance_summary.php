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
myapp.controller('empattendancesummary', function ($http, $scope,$timeout, $location, $anchorScroll, $window) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
		$scope.entryLimit = 10;
		var yr = (new Date()).getFullYear();
		var mm = (new Date()).getMonth();
		$scope.searchYear = yr.toString();
		mm = mm+1;
		if(mm < 10){
			mm = '0'+mm.toString();
		}
		$scope.searchMonth = mm.toString();
        $http.get($scope.base_url+'en/hr/get_emp_attendance_summary').
        success(function(data, status)
        {  
            $scope.getempAttendance = data;
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.getempAttendance.length; //Initially for no filter  
            $scope.totalItems = $scope.getempAttendance.length;
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
		
		// advance filter
		$scope.advanceFilter = function() {
			var formdata = new FormData();
			formdata.append('dd_month', ($scope.searchMonth)? $scope.searchMonth : "");
			formdata.append('dd_year', ($scope.searchYear)? $scope.searchYear : "");
			formdata.append('emp_code', ($scope.emp_code)? $scope.emp_code : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/get_attendance_search',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).success(function(response) {
				$scope.getempAttendance = response;
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.getempAttendance.length; //Initially for no filter  
            $scope.totalItems = $scope.getempAttendance.length;
			}).error(function(response) {
				console.log(response);
			});
        };
		
		//export to excel
		$scope.exportToExcel = function() {
			var formdata = new FormData();
			formdata.append('searchYear', ($scope.searchYear)? $scope.searchYear : "");
			formdata.append('searchMonth', ($scope.searchMonth)? $scope.searchMonth : "");
			var searchYear = ($scope.searchYear)? $scope.searchYear : "";
			var searchMonth = ($scope.searchMonth)? $scope.searchMonth : "";
			var aaYear = "";
			if(searchYear != ""){
				aaYear = "?aaYear="+searchYear+"&month="+searchMonth;
			}
			$window.open( $scope.base_url+'en/hr/emp_attendance_export'+aaYear , '_blank');
        };
		 
		
		//Attendance export excel
		$scope.exportAttendance = function() {
			var formdata = new FormData();
			formdata.append('searchYear', ($scope.searchYear)? $scope.searchYear : "");
			formdata.append('searchMonth', ($scope.searchMonth)? $scope.searchMonth : "");
			var searchYear = ($scope.searchYear)? $scope.searchYear : "";
			var searchMonth = ($scope.searchMonth)? $scope.searchMonth : "";
			var aaYear = "";
			if(searchYear != ""){
				aaYear = "?aaYear="+searchYear+"&month="+searchMonth;
			}
			$window.open( $scope.base_url+'en/hr/emp_attendance_report_export'+aaYear , '_blank');
        };
		
		
    }; 
})
</script>
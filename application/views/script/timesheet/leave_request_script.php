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
myapp.controller('LeaveRequest', function ($http, $scope,$timeout,$window) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
        $http.get($scope.base_url+'Timesheet/get_my_leave_request').
        success(function(data, status)
        {  
            $scope.getcustname = data;
            //$scope.data.dt_event_date = Date.parse($scope.data.dt_event_date);
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
			formdata.append('searchStatus', ($scope.searchStatus)? $scope.searchStatus : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'Timesheet/get_my_leave_request_search',
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
		
		//leaveApporved
		$scope.errmsgApprove="";
        $scope.leaveApporved = function(application_id) {
			var formdata = new FormData();
			formdata.append('application_id', application_id);
			$http({
				method: 'POST', 
				url: $scope.base_url+'Timesheet/update_leave_request_approved',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).
			success(function(response) {
				if(response == 1){
					$window.location.reload();
				}
				else if(response == 0){
					$scope.errmsgApprove="Sorry Insufficient Available Leave";
					$timeout( function(){
						$scope.errmsgApprove="";
					}, 4000 );
					
				}
				else {
					$window.location.reload();
				}
			}).
			error(function(response) {
				console.log(response);
			});
        };
		
		//leaveRejected
        $scope.leaveRejected = function(application_id) {
			var formdata = new FormData();
			formdata.append('application_id', application_id);
			var reason = 'reason_'+application_id;
			formdata.append('reject_reason', $('#'+reason).val());
			$http({
				method: 'POST', 
				url: $scope.base_url+'Timesheet/update_leave_request_rejected',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).
			success(function(response) {
				$window.location.reload();
			}).
			error(function(response) {
				console.log(response);
			});
        };
    }; 
}) 
</script>
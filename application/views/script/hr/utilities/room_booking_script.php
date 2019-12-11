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
myapp.controller('roomBookingList', ['$http','$scope', '$timeout', '$window', function ($http, $scope,$timeout,$window)
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
        $http.get($scope.base_url+'en/hr/get_room_booking').
        success(function(data, status)
        {  
            $scope.getprofilelist = data;
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.getprofilelist.length; //Initially for no filter  
            $scope.totalItems = $scope.getprofilelist.length;
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
			formdata.append('searchMonth', ($scope.searchMonth)? $scope.searchMonth : "");
			formdata.append('searchYear', ($scope.searchYear)? $scope.searchYear : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/get_room_booking_search',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).success(function(response) {
				$scope.getprofilelist = response;
				$scope.currentPage = 1; //current page
				$scope.entryLimit = 10; //max no of items to display in a page
				$scope.filteredItems = $scope.getprofilelist.length; //Initially for no filter  
				$scope.totalItems = $scope.getprofilelist.length;
			}).error(function(response) {
				console.log(response);
			});
        };
		
		//status change
		$scope.statusApporved = function(id) {
			var formdata = new FormData();
			formdata.append('id', id);
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/get_room_booking_staus_approved_update',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).success(function(response) {
				$window.location.reload();
			}).error(function(response) {
				console.log(response);
			});
        };
		$scope.statusRejected = function(id) {
			var formdata = new FormData();
			formdata.append('id', id);
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/get_room_booking_staus_rejected_update',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).success(function(response) {
				$window.location.reload();
			}).error(function(response) {
				console.log(response);
			});
        };
		
    }; 
}]) 
</script>
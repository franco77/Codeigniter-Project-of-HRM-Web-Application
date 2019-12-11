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
myapp.controller('holidaylist', function ($http, $scope,$timeout) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
		$scope.entryLimit = 10;
		var yr = (new Date()).getFullYear();
		$scope.searchyear = yr.toString();
        $http.get($scope.base_url+'en/hr/get_list_holiday').
        success(function(data, status)
        {  
            $scope.getprofilelist = data;
            //$scope.data.dt_event_date = Date.parse($scope.data.dt_event_date);
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.getprofilelist.length; //Initially for no filter  
            $scope.totalItems = $scope.getprofilelist.length;
			angular.element(document).ready(function () {
				$('.datepickerShow').datepicker({
					dateFormat: 'dd-mm-yy'
				});
			});
        }).
		
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
		
		
		//advance filter
		$scope.advanceFilter = function() {
			var formdata = new FormData();
			formdata.append('searchyear', ($scope.searchyear)? $scope.searchyear : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/get_list_holiday_search',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).
			success(function(response) {
				$scope.getprofilelist = response;
				$scope.currentPage = 1; //current page
				$scope.entryLimit = 10; //max no of items to display in a page
				$scope.filteredItems = $scope.getprofilelist.length; //Initially for no filter  
				$scope.totalItems = $scope.getprofilelist.length;
			}).
			error(function(response) {
				console.log(response);
			});
		};
		
		$scope.delete_ = function(val) {
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/delete_holiday',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},  
				data: 'id='+val
			}).
			success(function(response) {
				$('#successMsg').html(response);
				
			}).
			error(function(response) {
				console.log(response);
			});
		};
		
		
    }; 
	
	
})
</script>
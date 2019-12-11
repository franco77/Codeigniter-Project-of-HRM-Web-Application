<script> 
var myapp = angular.module('myApp', []);

myapp.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});
myapp.controller('goalApproveReject', ['$http','$scope', '$timeout', '$window', function ($http, $scope,$timeout,$window) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
        $http.get($scope.base_url+'Hr_help_desk/get_goal_approve_reject').
        success(function(data, status)
        {  
            $scope.getcustname = data;
            //$scope.data.dt_event_date = Date.parse($scope.data.dt_event_date);
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 20; //max no of items to display in a page
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
    }; 
	
	
		
	//goalApporved
	$scope.goalApporved = function(login_id) {
		var formdata = new FormData();
		formdata.append('login_id', login_id);
		$http({
			method: 'POST', 
			url: $scope.base_url+'en/hr_help_desk/update_goal_sheet_approved_rm',
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
	
	
	
}]) 
</script>
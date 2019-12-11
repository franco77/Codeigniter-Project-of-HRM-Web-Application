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
myapp.controller('accounts', ['$http','$scope', '$timeout',  '$window', function ($http, $scope,$timeout,$window) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
		var mm = (new Date()).getMonth();
		var yy = (new Date()).getFullYear();
		if( mm >= 3){
			var yr = yy;
		}
		else{
			var yr = yy-1;
		}
		var yr1 = yr+1;
		$scope.searchYear = yr+"-"+yr1;
        $http.get($scope.base_url+'accounts_admin/get_all_estimated_tax_computation').
        success(function(data, status)
        {  
            $scope.getestimated = data;
            //$scope.data.dt_event_date = Date.parse($scope.data.dt_event_date);
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 20; //max no of items to display in a page
            $scope.filteredItems = $scope.getestimated.length; //Initially for no filter  
            $scope.totalItems = $scope.getestimated.length;
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
		
		
		$scope.openNewWindow = function(tid,login_id) {
            $window.open($scope.base_url+'accounts_help_desk/estimated_tax_compution_sheet_result_print?id='+login_id+'&tid='+tid, 'NewWindow', 'width=980,height=600,left=0,top=0,scrollbars=1');
        };
		
		//getFYData filter
		$scope.getFYData = function() {
			var formdata = new FormData();
			formdata.append('searchYear', ($scope.searchYear)? $scope.searchYear : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/accounts_admin/get_all_estimated_tax_computation_fy',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).success(function(response) {
				$scope.getestimated = response;
				$scope.currentPage = 1; //current page
				$scope.entryLimit = 20; //max no of items to display in a page
				$scope.filteredItems = $scope.getestimated.length; //Initially for no filter  
				$scope.totalItems = $scope.getestimated.length;
			}).error(function(response) {
				console.log(response);
			});
        };
    }; 
}]) 
</script>
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
myapp.controller('mymidyearreview', ['$http','$scope', '$timeout', '$window', function ($http, $scope,$timeout,$window) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
        $http.get($scope.base_url+'Hr_help_desk/get_my_midyear_review').
        success(function(data, status)
        {  
            $scope.getcustname = data;
            //$scope.data.apply_date = Date.parse($scope.data.apply_date);
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
		
		
		//advance filter
		$scope.advanceFilter = function() {
			var formdata = new FormData();
			formdata.append('searchYear', ($scope.searchYear)? $scope.searchYear : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/Hr_help_desk/get_my_midyear_review_search',
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
		
        $scope.openNewWindow = function(mid,login_id,applydate) {
            $window.open($scope.base_url+'hr_help_desk/midyear_review_print?id='+login_id+'&mid='+mid, 'NewWindow', 'width=980,height=600,left=0,top=0,scrollbars=1');
        };
    }; 
}]) 
</script>
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
myapp.controller('shortlistedList', function ($http, $scope,$timeout) 
{   
	$scope.searchResumeType = "applicants";
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
        $http.get($scope.base_url+'en/hr/get_interview_candidate').
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
		
		
		
		//advance filter
		$scope.advanceFilter = function() {
			var formdata = new FormData();
			formdata.append('searchResumeType', ($scope.searchResumeType)? $scope.searchResumeType : "");
			formdata.append('searchJobCode', ($scope.searchJobCode)? $scope.searchJobCode : "");
			formdata.append('searchStartDate', ($scope.searchStartDate)? $scope.searchStartDate : "");
			formdata.append('searchEndDate', ($scope.searchEndDate)? $scope.searchEndDate : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/interview_candidate_search',
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
		
    }; 
	
	
})
</script>
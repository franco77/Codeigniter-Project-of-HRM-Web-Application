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
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
		$scope.mrf_status = "1";
		$scope.refreshDiv = function() {
			$http.get($scope.base_url+'en/hr/get_online_mrf_detail').
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
		};
		$scope.refreshDiv();
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
		
		$http.get($scope.base_url+'en/hr/get_departments').
        success(function(data, status)
        {
            $scope.getDepartments = data; 
        })
        .error(function(data, status)
        {
          $scope.status = status; 
        });
		
		//Designation fetch
		$scope.designationFetch = function() {
			var dept = ($scope.searchDepartment)? $scope.searchDepartment : "";
			if(dept !=""){
				var formdata = new FormData();
				formdata.append('searchDepartment', ($scope.searchDepartment)? $scope.searchDepartment : "");
				$http({
					method: 'POST', 
					url: $scope.base_url+'en/hr/get_designation',
					headers: {'Content-Type': undefined},  
					data: formdata
				}).
				success(function(response) {
					$scope.getDesignations = response; 
				}).
				error(function(response) {
					console.log(response);
				});
			}
			else{
				$scope.getDesignations = []; 
			}
        };
		
		
		//advance filter
		$scope.advanceFilter = function() {
			var formdata = new FormData();
			formdata.append('searchDepartment', ($scope.searchDepartment)? $scope.searchDepartment : "");
			formdata.append('searchName', ($scope.searchName)? $scope.searchName : "");
			formdata.append('searchDesignation', ($scope.searchDesignation)? $scope.searchDesignation : "");
			formdata.append('searchEmpCode', ($scope.searchEmpCode)? $scope.searchEmpCode : "");
			formdata.append('mrf_status', ($scope.mrf_status)? $scope.mrf_status : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/online_mrf_search',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).
			success(function(response) {
				$scope.getprofilelist = response;
				$scope.currentPage = 1; //current page
				$scope.entryLimit = 10; //max no of items to display in a page
				$scope.filteredItems = $scope.getprofilelist.length; //Initially for no filter  
				$scope.totalItems = $scope.getprofilelist.length;
			});
			error(function(response) {
				console.log(response);
			});
		};
		
    }; 
	
	
})
</script>
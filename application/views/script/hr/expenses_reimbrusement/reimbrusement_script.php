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
myapp.controller('ExpensesReimbrusement', function ($http, $scope,$timeout, $location, $anchorScroll) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
        $http.get($scope.base_url+'en/hr/get_reimbrusement').
        success(function(data, status)
        {  
            $scope.getexpensesReimbrusement = data;
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.getexpensesReimbrusement.length; //Initially for no filter  
            $scope.totalItems = $scope.getexpensesReimbrusement.length;
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
		//get departments
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
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/get_reimbrusement_search',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).
			success(function(response) {
				$scope.getexpensesReimbrusement = response;
				$scope.currentPage = 1; //current page
				$scope.entryLimit = 10; //max no of items to display in a page
				$scope.filteredItems = $scope.getexpensesReimbrusement.length; //Initially for no filter  
				$scope.totalItems = $scope.getexpensesReimbrusement.length;
			}).
			error(function(response) {
				console.log(response);
			});
        };
		
		
    }; 
})
</script>
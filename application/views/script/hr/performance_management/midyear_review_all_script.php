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
myapp.controller('midYear', function ($http, $scope,$timeout, $location, $anchorScroll, $window) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
		$scope.entryLimit = 10;
		var mm = (new Date()).getMonth();
		var yy = (new Date()).getFullYear();
		if( mm >= 4){
			var yr = yy;
		}
		else{
			var yr = yy-1;
		}
		var yr1 = yr+1;
		$scope.searchYear = yr+"-"+yr1;
        $http.get($scope.base_url+'en/hr/get_midyear_review_all').
        success(function(data, status)
        {  
            $scope.getMidYear = data;
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.getMidYear.length; //Initially for no filter  
            $scope.totalItems = $scope.getMidYear.length;
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
			formdata.append('searchYear', ($scope.searchYear)? $scope.searchYear : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/get_midyear_review_all_search',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).
			success(function(response) {
				$scope.getMidYear = response;
				$scope.currentPage = 1; //current page
				$scope.entryLimit = 10; //max no of items to display in a page
				$scope.filteredItems = $scope.getMidYear.length; //Initially for no filter  
				$scope.totalItems = $scope.getMidYear.length;
			}).
			error(function(response) {
				console.log(response);
			});
        };
		
		
		//Update remark
		$scope.updateRemark = function($event, mid, indx) {
			var formdata = new FormData();
			formdata.append('remark', ($event.target.value)? $event.target.value : "");
			formdata.append('mid', mid);
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/update_midyear_review_remark',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).
			success(function(response) {
				
			}).
			error(function(response) {
				console.log(response);
			});
        };
		
        $scope.openNewWindow = function(mid,login_id,applydate) {
            $window.open($scope.base_url+'en/hr/midyear_review_print?id='+login_id+'&mid='+mid, 'NewWindow', 'width=980,height=600,left=0,top=0,scrollbars=1');
        };
		
		$scope.exportToExcel = function() {
			var department = $('#searchDepartment').val();
			var name = $('#searchName').val();
			var designation = $('#searchDesignation').val();
			var emp_code = $('#searchEmpCode').val();
			var review_year = $('#searchYear').val();
			$window.location.href = $scope.base_url+'en/hr/midyear_review_report_export?department='+department+'&name='+name+'&designation='+designation+'&emp_code='+emp_code+'&review_year='+review_year;
		};
    }; 
})
</script>
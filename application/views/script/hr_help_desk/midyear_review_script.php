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
myapp.controller('midyearreview', ['$http','$scope', '$timeout', '$window', function ($http, $scope,$timeout,$window) 
{
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
		$scope.entryLimit = 10;
		//$scope.searchYear = '2017-2018';
		var yr = (new Date()).getFullYear();
		var yr1 = yr+1;
		$scope.searchYear = yr+"-"+yr1;
        $http.get($scope.base_url+'Hr_help_desk/get_midyear_review').
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
				url: $scope.base_url+'en/Hr_help_desk/get_midyear_review_search',
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
		
		//Update remark
		$scope.updateRemark = function($event, mid, indx) {
			var formdata = new FormData();
			formdata.append('remark', ($event.target.value)? $event.target.value : "");
			formdata.append('mid', mid);
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr_help_desk/update_midyear_review_remark',
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
            $window.open($scope.base_url+'hr_help_desk/midyear_review_print?id='+login_id+'&mid='+mid, 'NewWindow', 'width=980,height=600,left=0,top=0,scrollbars=1');
        };
		
		
		
		//loanApporved
        $scope.midYearApporved = function(mid) {
			var formdata = new FormData();
			formdata.append('mid', mid);
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr_help_desk/update_mid_year_review_approved_dh',
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
		
		//loanRejected
        $scope.midYearRejected = function(mid) {
			var formdata = new FormData();
			formdata.append('mid', mid);
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr_help_desk/update_mid_year_review_rejected_dh',
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
}]) 
</script>
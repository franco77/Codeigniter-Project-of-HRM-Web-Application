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
myapp.controller('AllLoan', function ($http, $scope,$timeout, $location, $anchorScroll,$window) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
		
		
		//get default datas
        $http.get($scope.base_url+'en/accounts_admin/get_loan_advance_approve_reject').
        success(function(data, status)
        {  
            $scope.getLoanAdvanceReject = data; 
            //$scope.data.dt_event_date = Date.parse($scope.data.dt_event_date);
            $scope.currentPage = 1; //current page
            $scope.maxSize = 600; //current page
            $scope.entryLimit = 10; //max no of items to display in a page 
            $scope.filteredItems = $scope.getLoanAdvanceReject.length; //Initially for no filter  
            $scope.totalItems = $scope.getLoanAdvanceReject.length;
			
			$scope.nameArray=[];
			$scope.skip=0
			for(var i=0;i<500;i++)
			{
				if($scope.skip==9)
				{
					$scope.nameArray.push(i+1);
					$scope.skip=0;					
				}
				else
				{	
					$scope.skip++;
				}
			}
			$scope.selValue=10;			
			//console.log(JSON.stringify(nameArray));
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
		}
		
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
				url: $scope.base_url+'en/accounts_admin/get_loan_advance_approve_reject_search',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).
			success(function(response) {
				$scope.getLoanAdvanceReject = response; 
				$scope.currentPage = 1; //current page
				$scope.maxSize = 600; //current page
				$scope.entryLimit = 10; //max no of items to display in a page 
				$scope.filteredItems = $scope.getLoanAdvanceReject.length; //Initially for no filter  
				$scope.totalItems = $scope.getLoanAdvanceReject.length;
			}).
			error(function(response) {
				console.log(response);
			});
        };
		
		//loanApporved
        $scope.loanApporved = function(lid) {
			var formdata = new FormData();
			formdata.append('lid', lid);
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/accounts_admin/update_loan_advance_approved_accounts',
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
        $scope.loanRejected = function(lid) {
			var formdata = new FormData();
			formdata.append('lid', lid);
			var reason = 'reason_'+lid;
			formdata.append('reject_reason', $('#'+reason).val());
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/accounts_admin/update_loan_advance_rejected_accounts',
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
		
		 $scope.openNewWindow = function(mid,login_id) {
            $window.open($scope.base_url+'accounts_admin/print_loan_adv?login_id='+login_id+'&lid='+mid, 'NewWindow', 'width=980,height=600,left=0,top=0,scrollbars=1');
        };
		
    }; 
})
</script>
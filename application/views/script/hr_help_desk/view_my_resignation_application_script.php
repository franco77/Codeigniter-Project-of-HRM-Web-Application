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
myapp.controller('reginationApprove', ['$http','$scope', '$timeout', '$window', function ($http, $scope,$timeout,$window) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
        $http.get($scope.base_url+'hr_help_desk/get_rm_resignation_application_details').
        success(function(data, status)
        {  
            $scope.getcustname = data;
            //$scope.data.dt_event_date = Date.parse($scope.data.dt_event_date);
			//$scope.selectOptions = $scope.getcustname.sel;
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
		$http.get($scope.base_url+'en/home/get_departments').
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
					url: $scope.base_url+'en/home/get_designation',
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
				url: $scope.base_url+'en/Hr_help_desk/get_rm_resignation_application_details_search',
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
		
		
		
		//resignationApporved
        $scope.resignationApporved = function(rid, dis) { 
			//console.log(dis.reasond);
			var formdata = new FormData();
			formdata.append('rid', rid);
			formdata.append('message', dis.reasond);
			if(dis.reasond)
			{
				$http({
					method: 'POST', 
					url: $scope.base_url+'en/hr_help_desk/update_resignation_application_approved_rm',
					headers: {'Content-Type': undefined},  
					data: formdata
				}).
				success(function(response) {
					$('#messages').css('color','green');
					$('#messages').css('display','inherit');
					$('#messages').text("Applied Successfully");
					setTimeout(function(){					
						$window.location.reload();
					}, 1000);
				}).
				error(function(response) {
					console.log(response);
				});	
			}
			else{
				$('#messages').css('color','red');
				$('#messages').css('display','inherit');
				$('#messages').text("Please Enter Reason.");
			}
			
        };
		
		//resignationRejected
        $scope.resignationRejected = function(rid) {
			var formdata = new FormData();
			formdata.append('rid', rid);
			var reason = 'reason_'+rid;
			formdata.append('reject_reason', $('#'+reason).val());
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr_help_desk/update_resignation_application_rejected_rm',
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
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
myapp.controller('regination', ['$http','$scope', '$timeout', '$window', function ($http, $scope,$timeout,$window) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
        $http.get($scope.base_url+'hr_help_desk/get_my_resignation_application').
        success(function(data, status)
        {  
            $scope.getcustname = data;
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
		
		
        $scope.updateReginationEmpStatus = function(rid) {
            var formdata = new FormData();
			formdata.append('rid', rid);
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr_help_desk/update_regination_emp_status',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).
			success(function(response) {
				$('#myModal_'+rid).modal('hide');
				$window.location.reload();
			}).
			error(function(response) {
				console.log(response);
			});
        };
    }; 
}]) 
</script>
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
myapp.controller('shortlistedList', function ($http, $scope,$timeout,$window) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
        $http.get($scope.base_url+'en/hr/get_resume_databank').
        success(function(data, status)
        {  
            $scope.getprofilelist = data;
            $scope.contact_status = "";
            $scope.email_category = "";
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.searchResumeType = 'applicants';
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
		$scope.advanceFilter = function() { //console.log($scope.parent.searchStartDate);
			var formdata = new FormData();
			formdata.append('searchResumeType', ($scope.searchResumeType)? $scope.searchResumeType : "");
			formdata.append('searchJobCode', ($scope.searchJobCode)? $scope.searchJobCode : "");
			formdata.append('searchStartDate', ($scope.searchStartDate)? $scope.searchStartDate : "");
			formdata.append('searchEndDate', ($scope.searchEndDate)? $scope.searchEndDate : "");
			formdata.append('contact_status', ($scope.contact_status)? $scope.contact_status : "");
			formdata.append('full_name', ($scope.full_name)? $scope.full_name : "");
			formdata.append('skills', ($scope.skills)? $scope.skills : "");
			formdata.append('searchcurremployee', ($scope.searchcurremployee)? $scope.searchcurremployee : "");
			formdata.append('searchcurrdesignation', ($scope.searchcurrdesignation)? $scope.searchcurrdesignation : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/resume_databank_search',
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

		$scope.send_email_toALL = function() {
			var formdata = new FormData();
			formdata.append('searchResumeType', ($scope.searchResumeType)? $scope.searchResumeType : "");
			formdata.append('searchJobCode', ($scope.searchJobCode)? $scope.searchJobCode : "");
			formdata.append('searchStartDate', ($scope.searchStartDate)? $scope.searchStartDate : "");
			formdata.append('searchEndDate', ($scope.searchEndDate)? $scope.searchEndDate : "");
			formdata.append('contact_status', ($scope.contact_status)? $scope.contact_status : "");
			formdata.append('full_name', ($scope.full_name)? $scope.full_name : "");
			formdata.append('skills', ($scope.skills)? $scope.skills : "");
			formdata.append('searchcurremployee', ($scope.searchcurremployee)? $scope.searchcurremployee : "");
			formdata.append('searchcurrdesignation', ($scope.searchcurrdesignation)? $scope.searchcurrdesignation : "");
			formdata.append('email_category', ($scope.email_category)? $scope.email_category : "");
			formdata.append('email_template', ($scope.email_template)? $scope.email_template : "");
			$http({
				method: 'POST', 
				url: $scope.base_url+'en/hr/sendtoALLemail',
				headers: {'Content-Type': undefined},  
				data: formdata
			}).
			success(function(response) {
				$scope.successmessage = response;
			}).
			error(function(response) {
				console.log(response);
			});
		};
		
		$scope.export_resume_databank = function() {
			var formdata = new FormData();
			formdata.append('searchResumeType', ($scope.searchResumeType)? $scope.searchResumeType : "");
			formdata.append('searchJobCode', ($scope.searchJobCode)? $scope.searchJobCode : "");
			formdata.append('searchStartDate', ($scope.searchStartDate)? $scope.searchStartDate : "");
			formdata.append('searchEndDate', ($scope.searchEndDate)? $scope.searchEndDate : "");
			formdata.append('contact_status', ($scope.contact_status)? $scope.contact_status : "");
			formdata.append('searchcurremployee', ($scope.searchcurremployee)? $scope.searchcurremployee : "");
			formdata.append('searchcurrdesignation', ($scope.searchcurrdesignation)? $scope.searchcurrdesignation : "");
			var searchResumeType = ($scope.searchResumeType)? $scope.searchResumeType : "";
			var searchJobCode = ($scope.searchJobCode)? $scope.searchJobCode : "";
			var searchStartDate = ($scope.searchStartDate)? $scope.searchStartDate : "";
			var searchEndDate = ($scope.searchEndDate)? $scope.searchEndDate : "";
			var contact_status = ($scope.contact_status)? $scope.contact_status : "";
			var searchcurremployee = ($scope.searchcurremployee)? $scope.searchcurremployee : "";
			var searchcurrdesignation = ($scope.searchcurrdesignation)? $scope.searchcurrdesignation : "";
			var aaYear = "";
			if(searchResumeType != ""){
				aaYear = '?resume_type='+searchResumeType+'&searchJobCode='+searchJobCode+'&searchStartDate='+searchStartDate+'&searchEndDate='+searchEndDate+'&contact_status='+contact_status+'&searchcurremployee='+searchcurremployee+'&searchcurrdesignation='+searchcurrdesignation;
			}
			$window.open( $scope.base_url+'en/hr/resume_databank_export'+aaYear , '_blank');
		};
		
		$scope.sendEmailtoSelected = function() {
			var books = [];
            for (var i = 0; i < $scope.data.length; i++) {
                    if ($scope.data[i].Selected) {
                        console.log($scope.data[i].appid);
                    }
                }
		};
		
    }; 
	
	
})
</script>
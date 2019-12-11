<script> 
var myapp = angular.module('myApp', []);

myapp.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});
myapp.controller('regination', ['$http','$scope', '$timeout', function ($http, $scope,$timeout) 
{   
    $scope.init = function(base_url,data)
    {
        $scope.base_url=base_url; 
        $http.get($scope.base_url+'hr_help_desk/resignation_a_r').
        success(function(data, status)
        {  
            if(action=='approve'){
				alert("Resignation has been Approved.");
				window.location.reload();
			}
			if(action=='reject'){
				alert("Resignation has been Rejected.");
				window.location.reload();
			}
			if(action=='cancel'){
				alert("Your Resignation Application has been Canceled.");
				window.location.reload();
			}
        })
        .
        error(function(data, status)
        {
          $scope.status = status; 
        }); 
    }; 
}]) 
</script> 
<script type="text/javascript">
  var $k=jQuery.noConflict();
  $k(function() {
	  $k('.resignation').each(function(){
			$k(this).click(function(){
				var appID = $k(this).attr("id");
				$("#lightbox_form").modal('show');
				//$k.colorbox({scrolling:false, overlayClose: false, escKey: false, opacity: 0.8});
			});
	   }); 
});
</script>
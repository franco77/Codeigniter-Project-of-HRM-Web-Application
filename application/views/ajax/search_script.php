<script type="text/javascript">
function userController($scope,$http) {
     $scope.users = [];
     $http.get('<?php echo site_url('angularjs/get_list'); ?>').success(function($data){ $scope.users=$data; });
} 
</script>
<script>
    (function(angular) {
	    var imagesUploadApp = angular.module('{{ $appName }}',[]);

        imagesUploadApp.controller('imageController', function($scope,$filter){
            $scope.tmpImage = [];

			var sortable_table = document.getElementById('images-table-body');
            var sortable = Sortable.create(sortable_table, {
                onSort: function (evt) {
                    var tmpImage = $scope.tmpImage[evt.newIndex];
                    $scope.tmpImage[evt.newIndex] = $scope.tmpImage[evt.oldIndex];
                    $scope.tmpImage[evt.oldIndex] = tmpImage;
                    $scope.$apply();
            	}
            });

            $scope.deleteImage = function(index){
                $scope.tmpImage.splice(index, 1);
            }
        });
        
	}(window.angular));
</script>
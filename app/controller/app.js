       //var app = angular.module('app', ['ui.bootstrap']);
	  // angular.module('app', ['ui.bootstrap']);
app.controller('PopupDemoCont',function ($scope, $uibModal) {
//javascript code for simple popup 
   $scope.simplepopup = function (){
        var uibModalInstance = $uibModal.open({
            templateUrl: 'template/Popup.html',
        });
	}
//javascript code for popup with close button
	$scope.popupwithclose = function (){
	                var uibModalInstance = $uibModal.open({
            templateUrl: 'template/Popup1.html',
			  controller: 'PopupCont',
        });
	}
	
	//javascript code for popuup with passing parameter
		$scope.popupwithparameter = function (titlename){	
		   var uibModalInstance = $uibModal.open({
                templateUrl: 'template/Popup2.html',
                controller: 'PopupCont1',
                resolve: {
                    titlename2: function () {
                        return titlename;
                    }
                }
            });
		}
});
		
		    // controller for popup1.html view for close button
app.controller('PopupCont',function ($scope, $uibModalInstance) {
    $scope.close = function () {
        $uibModalInstance.dismiss('cancel');
    };
});
//angular.module('app')
app.controller('PopupCont1',function ($scope, $uibModalInstance, titlename2) {
    $scope.title1 = titlename2;
    $scope.close = function () {
        $uibModalInstance.dismiss('cancel');
    };
});
// create the controller and inject Angular's $scope
app.directive('fileModel', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;
            
            element.bind('change', function(){
                scope.$apply(function(){
                    modelSetter(scope, element[0].files[0]);
                });
            });
        }
    };
});

app.directive("adddivs", function($compile){
    return function(scope, element){
        element.bind("click", function(){
            angular.element(document.getElementById('space-for-buttons')).append($compile('<div class="space"><label>Category : </label><select ng-model="category" name="category" id="category" class="form-control" placeholder="" ng-change="selectPro(category)"><option ng-repeat="x in cat" value="{{x.CATEGORY_CODE}}">{{x.CATEGORY_NAME}}</option></select></div>')(scope));
        });
    };
});

//Directive that returns an element which adds divs
app.directive("adddiv", function(){
    return {
        restrict: "E",
        template: "<button adddivs class=\"btn btn-info btn-sm\">Add Category</button>"
    }
});
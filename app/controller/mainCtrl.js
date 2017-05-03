app.controller('mainController', function($scope, $http, $location, $route) {
	$scope.nama = sessionStorage.getItem('nama');
	$scope.amount = sessionStorage.getItem('amount');
	$scope.comp_id = sessionStorage.getItem('comp_id');
	$scope.user_id = sessionStorage.getItem('user_id');
	$scope.title = sessionStorage.getItem('title');
	$scope.pro_id = sessionStorage.getItem('pro_id');
	$scope.budget = sessionStorage.getItem('budget');

	$http({
	    method: 'POST',
	    url: base_url+'main/viewProject',
	    headers: {'Content-Type': 'application/json'},
	}).success(function (data) {
		$scope.rows = sessionStorage.getItem('rows');
	    $scope.listProject = data;
	});

	$scope.pro_id = sessionStorage.getItem('pro_id');
	$scope.title = sessionStorage.getItem('title');
	$scope.pro_user_id = sessionStorage.getItem('pro_user_id');
	$scope.pro_des = sessionStorage.getItem('pro_des');
	$scope.pro_budget = sessionStorage.getItem('pro_budget');
	$scope.owner = sessionStorage.getItem('owner');
	$scope.pic = sessionStorage.getItem('pic');
	$scope.comp = sessionStorage.getItem('comp');
	$scope.location = sessionStorage.getItem('location');
	$scope.country = sessionStorage.getItem('country');
	$scope.regional = sessionStorage.getItem('regional');
	$scope.cat = sessionStorage.getItem('cat');
	$scope.status = sessionStorage.getItem('status');
	$scope.startdate = sessionStorage.getItem('startdate');
	$scope.enddate = sessionStorage.getItem('enddate');

	$scope.detail = function(id){
		$http({
		    method: 'POST',
		    url: base_url+'main/detailProject',
		    headers: {'Content-Type': 'application/json'},
		    data: { id:id },
		}).success(function (data) {
		    //console.log(data['result'][0]['PRO_TITLE']);
		    $scope.pro_id = data['result'][0]['PRO_ID'];
		    $scope.title = data['result'][0]['PRO_TITLE'];
		    $scope.pro_user_id = data['result'][0]['PRO_USER_ID'];
		    $scope.pro_des = data['result'][0]['PRO_DESC'];
		    $scope.pro_budget = data['result'][0]['PRO_BUDGET'];
		    $scope.owner = data['result'][0]['OWNER'];
		    $scope.pic = data['result'][0]['PIC'];
		    $scope.comp = data['result'][0]['COMP'];
		    $scope.location = data['result'][0]['PRO_LOCATION'];
		    $scope.country = data['result'][0]['COUNTRY'];
		    $scope.regional = data['result'][0]['REGIONAL'];
		    $scope.cat = data['result'][0]['CAT'];
		    $scope.status = data['result'][0]['STATUS'];
		    $scope.startdate = data['result'][0]['PRO_STARTDATE'];
		    $scope.enddate = data['result'][0]['PRO_ENDDATE'];

		    sessionStorage.setItem('pro_id',$scope.pro_id);
		    sessionStorage.setItem('title',$scope.title);
		    sessionStorage.setItem('pro_user_id',$scope.pro_user_id);
		    sessionStorage.setItem('pro_des',$scope.pro_des);
		    sessionStorage.setItem('pro_budget',$scope.pro_budget);
		    sessionStorage.setItem('owner',$scope.owner);
		    sessionStorage.setItem('pic',$scope.pic);
		    sessionStorage.setItem('comp',$scope.comp);
		    sessionStorage.setItem('location',$scope.location);
		    sessionStorage.setItem('country',$scope.country);
		    sessionStorage.setItem('regional',$scope.regional);
		    sessionStorage.setItem('cat',$scope.cat);
		    sessionStorage.setItem('status',$scope.status);
		    sessionStorage.setItem('startdate',$scope.startdate);
		    sessionStorage.setItem('enddate',$scope.enddate);

		    $location.path('/detailProject');
		    window.location.reload();
		});
	}

	$scope.publishProject = function(id){
		$http({
		    method: 'POST',
		    url: base_url+'main/upStatus',
		    headers: {'Content-Type': 'application/json'},
		   	data: { id:id },
		}).success(function (data) {
		    console.log(data);
		});
	}

	$scope.nm_project = $scope.title;
	$scope.pro_id = $scope.pro_id;
	$scope.comp_id = $scope.comp_id;
	$scope.user_id = $scope.user_id;
	$scope.budgetProject = $scope.budget;
	$scope.amount = $scope.amount;

	$scope.bidding = function(id, budget, title){
		//alert(id);
		$scope.amount = sessionStorage.getItem('amount');
		$scope.comp_id = sessionStorage.getItem('comp_id');
		$scope.user_id = sessionStorage.getItem('user_id');
		$scope.title = title;
		var selisih = budget * 0.1;
		if ($scope.amount >= selisih) {
			sessionStorage.setItem('pro_id', id);
			sessionStorage.setItem('title', title);
			sessionStorage.setItem('budget', budget);
			/**/
			var param = {};
			param['pro_id'] = id;
			param['comp_id'] = $scope.comp_id;
			param['user_id'] = $scope.user_id;
			param['over_price'] = budget;
			param['over_desc'] = selisih;
			var response = JSON.stringify(param);
			$http({
			    method: 'POST',
			    url: base_url+'main/getInBid',
			    headers: {'Content-Type': 'application/json'},
			    data: { data:response },
			}).success(function (data) {
			    console.log(data);
			   	
			    $location.path('/bid');
			});
			$location.path('/bid');
			alert('Credit Anda Mencukupi! Proses Bid Sedang di Proses');
		} else {
			alert('Credit Anda Tidak Mencukupi!');
		} 
	}

	$scope.insertBid = function(budget, msg, pro_id, comp_id, user_id, budgetProject, amount){

		var param = {};
		param['pro_id'] = pro_id;
		param['comp_id'] = comp_id;
		param['user_id'] = user_id;
		param['over_price'] = budget;
		param['over_desc'] = msg;
		param['budgetProject'] = budgetProject;
		param['amount'] = amount;
		var response = JSON.stringify(param);
		console.log(response);
		/**/
		$http({
		    method: 'POST',
		    url: base_url+'main/inBid/save',
		    headers: {'Content-Type': 'application/json'},
		    data: { data:response },
		}).success(function (data) {
		    console.log(data);
		   	
		    //$location.path('/bid');
		});
	}
});

app.controller('loginController', function($scope, $location, $http, $route){
	$location.path('/login');
	
	$scope.login = function(email, password){
		//var user_id = sessionStorage.getItem('user_id');
		var param = {};
    	param["email"] = email;
    	param["password"] = password;
    	var response = JSON.stringify(param);
    	$http({
    		method: 'POST',
    		url: base_url+'main/loginUser',
    		headers: {'Content-Type': 'application/json'},
		    data: { data:response },
		}).success(function (data) {
			var email = data['result'];
			var ck = data['value'];
			if(ck == 1){
				alert("Berhasil Login");
				var user_id = data['result'][0]['USER_ID'];
				var nama = data['result'][0]['USER_FULLNAME'];

				$http({
				    method: 'POST',
				    url: base_url+'main/dataUser',
				    headers: {'Content-Type': 'application/json'},
				    data: { user_id:user_id },
				}).success(function (data) {
				    var amount = data['result'][0]['AMOUNT'];
				    var company = data['result'][0]['COMPANY'];
				    var comp_id = data['result'][0]['COMP_ID'];
				    var rows = data['data'];
				    sessionStorage.setItem('amount', amount);
				    sessionStorage.setItem('company', company);
				    sessionStorage.setItem('rows', rows);
				    sessionStorage.setItem('comp_id', comp_id);
				});
				sessionStorage.setItem('LOGGED', ck);
				sessionStorage.setItem('user_id', user_id);
				sessionStorage.setItem('nama', nama);
				$location.path('/');
				//$route.reload();
				window.location.reload();
			} else {
				alert("Gagal Login");
				$location.path('/login');
				//$route.reload();
				window.location.reload();
			}
		});
	}
});

app.controller('registerController', function($scope, $http, $location) {
	$location.path('/register');
	$scope.registerForm = function(nama_lengkap,email,password,phone,jk,tgl_lahir){
		//alert("sini");
		var param = {};
		param['nama_lengkap'] = nama_lengkap;
		param['email'] = email;
		param['password'] = password;
		param['jk'] = jk;
		param['phone'] = phone;
		param['tgl_lahir'] = tgl_lahir;
		var response = JSON.stringify(param);
		console.log(param);
		$http({
		    method: 'POST',
		    url: base_url+'main/registerUser',
		    headers: {'Content-Type': 'application/json'},
		    data: { data:response},
		}).success(function (data) {
			var result = data['result'];
			var value = data['value'];
			if (value == 1) {
				alert(data['result']);
				$location.path('/login');
				$route.reload();
			} else {
				alert(data['result']);
				$location.path('/register');
			}	
		});
	}
});

app.controller('projectController', function($scope, $http, $location, fileUpload) {
	//$location.path('/project');
	var user_id = sessionStorage.getItem('user_id');

	$http({
	    method: 'POST',
	    url: base_url+'main/list_project',
	    headers: {'Content-Type': 'application/json'},
	    data: { user_id:user_id },
	}).success(function (data) {
		$scope.project_by_user = data;
	});

/*
	$http({
	    method: 'POST',
	    url: base_url+'main/optCat',
	    headers: {'Content-Type': 'application/json'},
	   //data: JSON.stringify({name: $scope.name,city:$scope.city})
	}).success(function (data) {
	    //console.log(data);
	    $scope.cat = data;
	});

	$http({
	    method: 'POST',
	    url: base_url+'main/dataCountry',
	    headers: {'Content-Type': 'application/json'},
	   //data: JSON.stringify({name: $scope.name,city:$scope.city})
	}).success(function (data) {
	    //console.log(data);
	    $scope.datacountry = data;
	});

	$http({
	    method: 'POST',
	    url: base_url+'main/dataRegional',
	    headers: {'Content-Type': 'application/json'},
	   //data: JSON.stringify({name: $scope.name,city:$scope.city})
	}).success(function (data) {
	    //console.log(data);
	    $scope.dataReg = data;
	});

	$scope.insertProject = function(category,title,description,location,regional,country,startdate,enddate,budget){
		//alert(startdate+enddate);
		var file = $scope.myFile;
		var uploadUrl = srv_url+'srv_upload_project.php';//"/fileUpload";
		var user_id = sessionStorage.getItem('user_id');
		fileUpload.uploadFileToUrl(file, uploadUrl, category,title,description,location,regional,country,startdate,enddate,budget,user_id);
	}*/
});

app.controller('addprojectController', function($scope, $http, $location, fileUpload) {
	//$location.path('/project');
	var user_id = sessionStorage.getItem('user_id');

	$http({
	    method: 'POST',
	    url: base_url+'main/optCat',
	    headers: {'Content-Type': 'application/json'},
	   //data: JSON.stringify({name: $scope.name,city:$scope.city})
	}).success(function (data) {
	    //console.log(data);
	    $scope.cat = data;
	});

	$http({
	    method: 'POST',
	    url: base_url+'main/dataCountry',
	    headers: {'Content-Type': 'application/json'},
	   //data: JSON.stringify({name: $scope.name,city:$scope.city})
	}).success(function (data) {
	    //console.log(data);
	    $scope.datacountry = data;
	});

	$http({
	    method: 'POST',
	    url: base_url+'main/dataRegional',
	    headers: {'Content-Type': 'application/json'},
	   //data: JSON.stringify({name: $scope.name,city:$scope.city})
	}).success(function (data) {
	    //console.log(data);
	    $scope.dataReg = data;
	});

	$scope.insertProject = function(category,title,description,location,regional,country,startdate,enddate,budget){
		//alert(startdate+enddate);
		var file = $scope.myFile;
		var uploadUrl = srv_url+'srv_upload_project.php';//"/fileUpload";
		var user_id = sessionStorage.getItem('user_id');
		fileUpload.uploadFileToUrl(file, uploadUrl, category,title,description,location,regional,country,startdate,enddate,budget,user_id);
	}
});


app.controller('companyController', function($scope, $location, $http, fileUploadCompany) {
	$location.path('/company');
	var user_id = sessionStorage.getItem('user_id');

	$http({
	    method: 'POST',
	    url: base_url+'main/list_company',
	    headers: {'Content-Type': 'application/json'},
	    data: { user_id:user_id },
	}).success(function (data) {
		$scope.company_by_user = data;
	});
});


app.controller('addcompanyController', function($scope, $location, $http, fileUploadCompany) {
	var user_id = sessionStorage.getItem('user_id');

	$http({
	    method: 'POST',
	    url: base_url+'main/dataCountry',
	    headers: {'Content-Type': 'application/json'},
	   //data: JSON.stringify({name: $scope.name,city:$scope.city})
	}).success(function (data) {
	    //console.log(data);
	    $scope.datacountry = data;
	});

	$http({
	    method: 'POST',
	    url: base_url+'main/dataRegional',
	    headers: {'Content-Type': 'application/json'},
	   //data: JSON.stringify({name: $scope.name,city:$scope.city})
	}).success(function (data) {
	    //console.log(data);
	    $scope.dataReg = data;
	});
	
	$scope.insertCompany = function(npwp,company,address,regional,country,telephone,fax){
		var file = $scope.picHead;
		var uploadUrl = srv_url+'srv_company.php';//"/fileUpload";
		var user_id = sessionStorage.getItem('user_id');
		fileUploadCompany.uploadFileToUrlCompany(file, uploadUrl, npwp, company, address, regional, country, telephone, fax, user_id);
	}
});

app.controller('aboutController', function($scope) {
	$scope.message = 'Look! I am an about page.';
});

app.controller('contactController', function($scope) {
	$scope.message = 'Contact us! JK. This is just a demo.';
});

app.controller('sessController', function($scope, $location, $route, $templateCache){
	$scope.nama = sessionStorage.getItem('nama');
	$scope.value = sessionStorage.getItem('LOGGED');
	$scope.user_id = sessionStorage.getItem('user_id');
	$scope.amount = sessionStorage.getItem('amount');
	$scope.rows = sessionStorage.getItem('rows');

	$scope.logout = function() {
	    sessionStorage.clear();
		$location.path('/login');
		//$location.reload();
		var clear = $route.current.templateUrl;
		$templateCache.remove(clear);
		//$templateCache.remove(current.templateUrl);
		$route.reload();
	};

	$scope.cekMenu = function(){
		var value = "hidden";
		if ($scope.value = sessionStorage.getItem('LOGGED') == '1') {
			///console.log('cekMenu true');
			/*var clear = $route.current.templateUrl;
			$templateCache.remove(clear);*/
			$templateCache.removeAll();
			value = "showing";
		}
		//console.log("cekMenu =  "+ value);
		return value;
	}

	$scope.cekMenuFalse = function(){
		var value = "hidden";
		if ($scope.value = sessionStorage.getItem('LOGGED') != '1') {
			//console.log('cekMenuFalse true');
			/*var clear = $route.current.templateUrl;
			$templateCache.remove(clear);*/
			$templateCache.removeAll();
			value = "showing";
		} 
		//console.log("cekMenuFalse =  "+ value);
		return value;
	}
});

app.controller('activateController', function($scope, $http, $location, $routeParams){

	var param = $location.search().param;
	$http({
	    method: 'GET',
	    url: base_url+'main/activate/'+param,
	    headers: {'Content-Type': 'application/json'},
	}).success(function (data) {
	    //console.log(data);
	    $scope.dataReg = data;
	});
});

app.controller('detailController', function($scope,$location){
	//$location.path('/detailProject');
});

app.controller('PopupDemoCont', ['$scope','$modal',function ($scope, $modal) {
	$scope.open = function (titlename) {
	var modalInstance = $modal.open({
		templateUrl: 'pages/modal.html',
		controller: 'PopupCont',
		resolve: {
			titlename2: function () {
				return titlename;
			}
		}
	});
}
}]);

app.controller('PopupCont',function ($scope, $modalInstance, titlename2) {
	$scope.title1 = titlename2;
	$scope.close = function () {
		$modalInstance.dismiss('cancel');
	};
});

app.service('fileUpload', function ($http) {
    this.uploadFileToUrl = function(file, uploadUrl, category,title,description,location,regional,country,startdate,enddate,budget,user_id,CodeCategory,windate){
        var fd = new FormData();
        fd.append('file', file);
        fd.append('category', category);
        fd.append('title', title);
        fd.append('description', description);
        fd.append('location', location);
        fd.append('regional', regional);
        fd.append('country', country);
        fd.append('startdate', startdate);
        fd.append('enddate', enddate);
        fd.append('budget', budget);
        fd.append('user_id', user_id);
        fd.append('CodeCategory', CodeCategory);
        fd.append('windate', windate);
        $http.post(uploadUrl, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        })
        .success(function(){
        	console.log(fd);
        })
        .error(function(){
        	console.log("error");
        });
    }
});

app.service('fileUploadCompany', function ($http) {
    this.uploadFileToUrlCompany = function(file, uploadUrl, npwp, company, address, regional, country, telephone, fax, user_id){
        var fd = new FormData();
        fd.append('file', file);
        fd.append('npwp', npwp);
        fd.append('company', company);
        fd.append('address', address);
        fd.append('regional', regional);
        fd.append('country', country);
        fd.append('telephone', telephone);
        fd.append('fax', fax);
        fd.append('user_id', user_id);
        $http.post(uploadUrl, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        })
        .success(function(){
            console.log(fd);
        })
        .error(function(){
            console.log("error");
        });
    }
});
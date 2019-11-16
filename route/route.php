<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
Route::group('api',function(){
    Route::get("bulkdata",'admin/Datas/BulkData');
	Route::group('admin',function(){

    	Route::post("login",'admin/Login/login')->header('Access-Control-Allow-Headers','Origin, X-Requested-With, Content-Type, Accept, authKey,sessionId')->allowCrossDomain();
    	Route::post("logout",'admin/Login/logout')->header('Access-Control-Allow-Headers','Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
     	Route::post("uppwd",'admin/Login/UpPwd')->header('Access-Control-Allow-Headers','Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();;
        Route::post("Upfile",'admin/File/Upfile')->header('Access-Control-Allow-Headers','Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();


        Route::group('user',function(){
            Route::post("useradd",'admin/User/AddOne');
            Route::post("userchange",'admin/User/UpdateOneById');
            Route::post("userdelete",'admin/User/DeleteOneById');
            Route::post("usergetone",'admin/User/GetOneById');
            Route::post("usergetalllist",'admin/User/GetAllList');
        })->header('Access-Control-Allow-Headers','Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();

        Route::group('data',function(){
            Route::post("dataadd",'admin/Datas/AddOne');
            Route::post("datachange",'admin/Datas/UpdateOneById');
            Route::post("datadelete",'admin/Datas/DeleteOneById');
            Route::post("datagetone",'admin/Datas/GetOneById');
            Route::post("datagetalllist",'admin/Datas/GetAllList');
            Route::post("databulk","admin/File/Bulk");
            Route::post("datalistlike","admin/Datas/GetAllListlike");
        })->header('Access-Control-Allow-Headers','Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        Route::group('file',function(){
            Route::post("pic",'admin/File/Upfile');
            Route::post("databulk","admin/File/Bulk");
            Route::post("video",'admin/File/Video');
        })->header('Access-Control-Allow-Headers','Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        Route::group('daohang',function(){
            Route::post("",'admin/Daohang/getdata');
            Route::post("daohangchange",'admin/Daohang/UpdateOneById');
        })->header('Access-Control-Allow-Headers','Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
	});
    Route::group('home',function(){
        Route::post("login",'home/Login/login')->header('Access-Control-Allow-Headers','Origin, X-Requested-With, Content-Type, Accept, authKey,sessionId')->allowCrossDomain();
        Route::group('data',function(){
            Route::post("datagetone",'home/Datas/GetOneById');
            Route::post("datagetalllist",'home/Datas/GetAllList');
            Route::post("datalistlike","home/Datas/GetAllListlike");
        })->header('Access-Control-Allow-Headers','Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
        Route::group('daohang',function(){
            Route::post("",'home/Daohang/getdata');
        })->header('Access-Control-Allow-Headers','Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId')->allowCrossDomain();
    });
});
Route::get("/",'home/Index/index');
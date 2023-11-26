<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\auth\AuthControllerApi;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TemplatesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\AttributesController;
use App\Http\Controllers\TemplateSecAttrValueController;
use App\Http\Controllers\UsersCvsController;
use App\Http\Controllers\classes\general\GeneralServiceController;

Route::get('/user',[AuthControllerApi::class,'user'])->middleware('CheckApiAuth');
Route::post('/login',[AuthControllerApi::class,'login_api']);

Route::group(['middleware'=>'changeLang'],function (){
    Route::post('/register',[AuthControllerApi::class,'register_post']);
    Route::post('/logout',[AuthControllerApi::class,'logout_api']);
    Route::post('/validate-user',[AuthControllerApi::class,'validate_user']);


    Route::group(['middleware'=>'guest'],function () {
        Route::resources([
            'countries'=>CountriesController::class, // countries Resource
            'cities'=>CitiesController::class // cities Resource
        ]);

    });

    Route::group(['prefix'=>'/templates'],function(){
        Route::get('/',[TemplatesController::class,'all_templates']);
    });

    Route::group(['prefix'=>'/sections'],function(){
        Route::get('/',[SectionsController::class,'index']);
    });
    Route::group(['prefix'=>'/attributes'],function(){
        Route::get('/',[AttributesController::class,'all_attributes']);
    });

    Route::group(['prefix'=>'/categories'],function(){
        Route::get('/',[CategoriesController::class,'index']);
    });
    Route::group(['prefix'=>'/template-sec-attr-value'],function(){
        Route::get('/',[TemplateSecAttrValueController::class,'all_template_sec_attr_data']);
        Route::post('search-attribute-values',[TemplateSecAttrValueController::class,'search_attribute_values']);
    });
    Route::group(['prefix'=>'/users-cvs','middleware'=>'CheckApiAuth'],function(){
        Route::get('/',[UsersCvsController::class,'index']);
        Route::post('save',[UsersCvsController::class,'save']);
    });



    Route::group(['prefix'=>'/dashboard','middleware'=>'CheckApiAuth'],function(){
        Route::post('/users',[DashboardController::class,'users']);
        Route::post('/templates/save',[DashboardController::class,'save_template']);
        Route::post('/templates-sections/save',[DashboardController::class,'save_template_sections']);
        Route::post('/sections/save',[DashboardController::class,'save_section']);
        Route::post('/attributes/save',[DashboardController::class,'save_attribute']);
        Route::post('/categories/save',[CategoriesController::class,'save']);
        Route::group(['prefix'=>'/languages'],function(){
            Route::get('/',[DashboardController::class,'all_languages']);
            Route::post('/save',[DashboardController::class,'save_lang']);
        });
        Route::group(['prefix'=>'/tables-privileges'],function(){
            Route::get('/',[DashboardController::class,'tables_privileges']);
            Route::post('/save',[DashboardController::class,'save_table_privilege']);
        });
        Route::group(['prefix'=>'/template-sec-attr-value'],function(){
            Route::post('/save',[DashboardController::class,'save_template_sec_attr_value']);
        });


        Route::group(['prefix'=>'/countries'],function(){
            Route::post('/',[DashboardController::class,'countries']);
            Route::post('/save',[DashboardController::class,'save_countries']);
        });
    });

    Route::group(['prefix'=>'/users','middleware'=>'CheckApiAuth'],function(){
        Route::post('/save',[UsersController::class,'save']);
    });


    Route::post('/deleteitem',[GeneralServiceController::class,'delete_item']);


    Route::post('/upload-excel',[GeneralServiceController::class,'upload']);
    Route::get('/notifications',[NotificationsController::class,'index']);




});

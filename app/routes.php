<?php
//App::bind('ShopCore\Product',function(){
//	return new ShopCore\Product(new ShopCore\product\ProductValidator(),new ShopCore\product\ProductRepository());
//});
//
//App::bind('ShopCore\Address',function(){
//	return new ShopCore\Address(new ShopCore\address\AddressValidator(), new ShopCore\address\AddressRepository());
//});
//
//App::bind('ShopCore\Order',function(){
//	return new ShopCore\Order(new ShopCore\order\OrderValidator(),new ShopCore\order\OrderRepository());
//});
//
//App::bind('ShopCore\image',function(){
//	return new ShopCore\Image(new ShopCore\image\ImageValidator(),new ShopCore\image\ImageRepository(),new ShopCore\product\ProductRepository());
//});
//
//App::bind('ShopCore\Permission',function(){
//	return new ShopCore\Permission(new ShopCore\permission\PermissionRepository(),new ShopCore\user\UserRepository());
//});
//
//App::bind('ShopCore\User',function(){
//	return new ShopCore\User(new ShopCore\user\UserRepository());
//});








Route::group(array('before'=>'auth'),function(){

    Route::resource('products', 'ProductsController');
    Route::resource('categories', 'CategoriesController');
    Route::resource('carts', 'CartsController');
    Route::resource('users', 'UsersController');
    Route::resource('tags', 'TagsController');
    Route::resource('orders', 'OrdersController');
    Route::resource('addresses', 'AddressesController');
    Route::resource('images', 'ImagesController');
	Route::resource('permissions', 'PermissionsController');
	Route::resource('groups','GroupsController');

    Route::get('addresses/default/{id}','AddressesController@setDefault');
    Route::get('checkout','OrdersController@getCheckout');
    Route::get('/',function(){
        return Redirect::to('/products');
    });
});


Route::group(array('prefix' => 'admin', 'before' => 'auth'), function(){
    Route::resource('products', 'ProductsController');
});





// User reset routes
Route::get('user/remind','RemindersController@getRemind');
Route::post('user/remind','RemindersController@postRemind');
Route::get('user/reset/{token}','RemindersController@getReset');
Route::post('user/reset','RemindersController@PostReset');

Route::get('user/login','UsersController@getLogin');
Route::get('user/logout',array('as'=>'users.logout','uses'=>'UsersController@getLogout'));
Route::post('user/login','UsersController@postLogin');



//Route::get('user/reset/{token}', 'UserController@getReset');
// User password reset
//Route::post('user/reset/{token}', 'UserController@postReset');


if (Config::get('database.log', false))
{
    Event::listen('illuminate.query', function($query, $bindings, $time, $name)
    {
        $data = compact('bindings', 'time', 'name');

        // Format binding data for sql insertion
        foreach ($bindings as $i => $binding)
        {
            if ($binding instanceof \DateTime)
            {
                $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
            }
            else if (is_string($binding))
            {
                $bindings[$i] = "'$binding'";
            }
        }

        // Insert bindings into query
        $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
        $query = vsprintf($query, $bindings);
        Log::info($query, $data);
    });
}

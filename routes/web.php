<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::get('/', function () {
return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index');
Route::group(['middleware' => 'auth'],function(){
	Route::get('users', function() {
		$num = 1;
		$users = App\User::paginate(10);
	return view('users',[
			'num' => $num,
			'users' => $users
		]);
	});Route::get('user', function() {
		$num = 1;
		$users = App\User::paginate(10);
	return view('user',[
			'num' => $num,
			'users' => $users
		]);
	});
	route::get('create', function() {
		return view('create');
	});
	Route::post('create', function(Request $data) {
		$users = $data->all();
		$save = new App\User;
		$save->name = $data->Input('name');
		$save->email = $data->Input('email');
		$save->password = bcrypt($data->Input('name'));
		$save->save();
		return redirect::to('users');
	});
	Route::post('new', function(Request $data) {
		$users = $data->all();
		$valid = Validator::make($users,[
				'name' => 'required | min: 6 | max: 100',
				'email' => 'required | min: 6 | max: 100'
			]);
		if($valid->fails())
		{
			return response()->json($valid);
		}
		$save = new App\User;
		$save->name = $data->Input('name');
		$save->email = $data->Input('email');
		$save->password = bcrypt($data->Input('name'));
		$save->save();
		return response()->json(['status' => 'true']);
	});
	Route::get('edited/{slugs}', function($slugs) {
		$id = base64_decode($slugs);
		$edited = App\User::find($id);
		return view('create',['edited' => $edited]);
	});
	Route::get('edit/{slugs}', function($slugs) {
		$id = base64_decode($slugs);
		$edited = App\User::find($id);
		return response()->json($edited);
	});
	Route::post('create/{slugs}', function(Request $data, $slugs) {
		$id = base64_decode($slugs);
		$update =[
			'name' => $data->Input('name'),
			'email' => $data->Input('email'),
			'password' => bcrypt($data->Input('name'))
		];
		App\User::where('id', $id)->update($update);
		return redirect::to('users');
	});
	Route::post('edit/{slugs}', function(Request $data, $slugs) {
		$id = base64_decode($slugs);
		$update =[
			'name' => $data->Input('name'),
			'email' => $data->Input('email'),
			'password' => bcrypt($data->Input('name'))
		];
		App\User::where('id', $id)->update($update);
		return response()->json(['status' => 'true']);
	});
	Route::get('deleted/{slugs}', function($slugs) {
		$id = base64_decode($slugs);
		$deleted = App\User::find($id);
		$success = $deleted->delete();
		if($success)
		{
			return redirect::to('users');
		}
	});
	Route::post('delete/{slugs}', function($slugs) {
		$id = base64_decode($slugs);
		$deleted = App\User::find($id);
		$success = $deleted->delete();
		if($success)
		{
			return response()->json(['status' => 'true']);
		}
	});
});
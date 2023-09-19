<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EmployeeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */
Route::post('/employee', [EmployeeController::class, 'import']);

Route::group(['namespace' => 'App\Http\Controllers\Admin'],
    function () {
        Route::post('login', [AuthController::class, 'login']);

        Route::group(['middleware' => ['auth:api', 'admin']], function () {
            Route::post('logout', [AuthController::class, 'logout']);

            Route::apiResource('/employee', EmployeeController::class)
                ->except(['store', 'update']);
        });

    });
/*
$curl = curl_init();

$file_path = 'D:\Programming-Studying\Laravel\Projects\Import-employees\import-employees-totally-new\importCopy.csv'; // Make sure to specify the correct path to your CSV file

$post_data = file_get_contents($file_path);

curl_setopt_array($curl, array(
CURLOPT_URL => "http://127.0.0.1:8000/api/employee",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30000,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "POST",
CURLOPT_POSTFIELDS => $post_data,
CURLOPT_HTTPHEADER => array(
'Content-Type: text/csv',
),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
echo "cURL Error #:" . $err;
} else {
print_r(json_decode($response));
}
 */

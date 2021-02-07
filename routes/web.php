<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('login');
});

Route::match(array('GET', 'POST'), '/login', [
    'uses' => 'Backend\LoginController@index',
    'as' => 'login'
]);
Route::get(
    '/logout',
    [
        'uses' => 'Backend\LoginController@logout',
        'as' => 'logout'
    ]
);

/* ACCESS CONTROL ALL */
Route::group(array('prefix' => 'home', 'middleware' => 'token_all'), function () {
    Route::get(
        '/dashboard',
        [
            'uses' => 'Backend\TrainingController@getDashboard',
            'as' => 'training.dashboard'
        ]
    );
    //    START TRAINING
    Route::get(
        '/training',
        [
            'uses' => 'Backend\TrainingController@index',
            'as' => 'training.index'
        ]
    );

    Route::get(
        '/training/datatable',
        [
            'uses' => 'Backend\TrainingController@datatable',
            'as' => 'training.datatables'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/training/add',
        [
            'uses' => 'Backend\TrainingController@addTraining',
            'as' => 'training.add'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/training/edit/{id}',
        [
            'uses' => 'Backend\TrainingController@updateTraining',
            'as' => 'training.update'
        ]
    );

    Route::post(
        '/training/delete',
        [
            'uses' => 'Backend\TrainingController@delete',
            'as' => 'training.delete'
        ]
    );

    Route::post(
        '/api/getalldoneplan',
        [
            'uses' => 'Backend\TrainingresController@getalldoneplan',
            'as' => 'trainingres.getalldoneplan'
        ]
    );

    Route::post(
        '/api/getselectkaryawan',
        [
            'uses' => 'Backend\TrainingresController@getselectkaryawan',
            'as' => 'trainingres.getselectkaryawan'
        ]
    );

    //    Start Position

    Route::get(
        '/position',
        [
            'uses' => 'Backend\PositionController@index',
            'as' => 'position.index'
        ]
    );

    Route::get(
        '/position/datatable',
        [
            'uses' => 'Backend\PositionController@datatable',
            'as' => 'position.datatables'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/position/add',
        [
            'uses' => 'Backend\PositionController@addPosition',
            'as' => 'position.add'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/position/edit/{id}',
        [
            'uses' => 'Backend\PositionController@updatePosition',
            'as' => 'position.update'
        ]
    );

    Route::post(
        '/position/delete',
        [
            'uses' => 'Backend\PositionController@delete',
            'as' => 'position.delete'
        ]
    );

    ///////START DEPARTMENT////////

    Route::get(
        '/department',
        [
            'uses' => 'Backend\DepartmentController@index',
            'as' => 'department.index'
        ]
    );

    Route::get(
        '/department/datatable',
        [
            'uses' => 'Backend\DepartmentController@datatable',
            'as' => 'department.datatables'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/department/add',
        [
            'uses' => 'Backend\DepartmentController@addDepartment',
            'as' => 'department.add'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/department/edit/{id}',
        [
            'uses' => 'Backend\DepartmentController@updateDepartment',
            'as' => 'department.update'
        ]
    );

    Route::post(
        '/department/delete',
        [
            'uses' => 'Backend\DepartmentController@delete',
            'as' => 'department.delete'
        ]
    );

    ///////START TRAINSPECS////////

    Route::get(
        '/spectrain',
        [
            'uses' => 'Backend\SpectrainController@index',
            'as' => 'spectrain.index'
        ]
    );

    Route::get(
        '/spectrain/datatable',
        [
            'uses' => 'Backend\SpectrainController@datatable',
            'as' => 'spectrain.datatables'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/spectrain/add',
        [
            'uses' => 'Backend\SpectrainController@addSpectrain',
            'as' => 'spectrain.add'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/spectrain/edit/{id}',
        [
            'uses' => 'Backend\SpectrainController@updateSpectrain',
            'as' => 'spectrain.update'
        ]
    );

    Route::post(
        '/spectrain/delete',
        [
            'uses' => 'Backend\SpectrainController@delete',
            'as' => 'spectrain.delete'
        ]
    );

    /// USER START //////////

    Route::get(
        '/user',
        [
            'uses' => 'Backend\UserController@Index',
            'as' => 'user.index'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/user/add',
        [
            'uses' => 'Backend\UserController@adduser',
            'as' => 'user.addUser'
        ]
    );

    Route::get(
        '/user/view/{id}',
        [
            'uses' => 'Backend\UserController@detailUser',
            'as' => 'user.detail'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/user/edit/{id}',
        [
            'uses' => 'Backend\UserController@updateuser',
            'as' => 'user.updateUser'
        ]
    );

    Route::post(
        '/user/delete',
        [
            'uses' => 'Backend\UserController@delete',
            'as' => 'user.delete'
        ]
    );

    Route::post(
        '/user/importexcel',
        [
            'uses' => 'Backend\UserController@import_excel',
            'as' => 'user.importexcel'
        ]
    );

    Route::get(
        '/user/datatables',
        [
            'uses' => 'Backend\UserController@datatable',
            'as' => 'user.datatables'
        ]
    );

    ////START TRAINING REQUEST
    /// //////////

    Route::get(
        '/trainingreq',
        [
            'uses' => 'Backend\TrainingreqController@Index',
            'as' => 'trainingreq.index'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/trainingreq/add',
        [
            'uses' => 'Backend\TrainingreqController@addtrainingreq',
            'as' => 'trainingreq.addTrainingreq'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/trainingreq/edit/{id}',
        [
            'uses' => 'Backend\TrainingreqController@updatetrainingreq',
            'as' => 'trainingreq.updateTrainingreq'
        ]
    );

    Route::post(
        '/trainingreq/delete',
        [
            'uses' => 'Backend\TrainingreqController@delete',
            'as' => 'trainingreq.delete'
        ]
    );

    Route::get(
        '/trainingreq/datatables',
        [
            'uses' => 'Backend\TrainingreqController@datatable',
            'as' => 'trainingreq.datatables'
        ]
    );

    Route::post(
        '/trainingreq/updatestatus',
        [
            'uses' => 'Backend\TrainingreqController@updatestatus',
            'as' => 'trainingreq.updatestatus'
        ]
    );


    ////START TRAINING RESULT
    /// //////////

    Route::get(
        '/trainingres',
        [
            'uses' => 'Backend\TrainingresController@Index',
            'as' => 'trainingres.index'
        ]
    );

    Route::get(
        '/trainingres/datatables',
        [
            'uses' => 'Backend\TrainingresController@datatable',
            'as' => 'trainingres.datatables'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/trainingres/add',
        [
            'uses' => 'Backend\TrainingresController@addTrainingRes',
            'as' => 'trainingres.add'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/trainingres/edit/{id}',
        [
            'uses' => 'Backend\TrainingresController@updateTrainingRes',
            'as' => 'trainingres.update'
        ]
    );

    Route::post(
        '/trainingres/delete',
        [
            'uses' => 'Backend\TrainingresController@delete',
            'as' => 'trainingres.delete'
        ]
    );

    ////START TRAINING ASSIGNMENT
    /// //////////

    Route::get(
        '/assignment',
        [
            'uses' => 'Backend\AssignmentController@Index',
            'as' => 'assignment.index'
        ]
    );

    Route::get(
        '/assignment/datatables',
        [
            'uses' => 'Backend\AssignmentController@datatable',
            'as' => 'assignment.datatables'
        ]
    );

    Route::post(
        '/assignment/assignkaryawan',
        [
            'uses' => 'Backend\AssignmentController@addassignment',
            'as' => 'assignment.addassign'
        ]
    );

    Route::post(
        '/assignment/hrdassignkaryawan',
        [
            'uses' => 'Backend\AssignmentController@hrdaddassignment',
            'as' => 'assignment.hrdaddassign'
        ]
    );

    ////START TRAINING REPORTS
    /// //////////

    Route::get(
        '/report',
        [
            'uses' => 'Backend\ReportController@Index',
            'as' => 'reports.index'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/report/datatables',
        [
            'uses' => 'Backend\ReportController@datatable',
            'as' => 'reports.datatables'
        ]
    );

    Route::get('/report/printpdfkaryawan/{id}', [
        'uses' => 'Backend\ReportController@printPdfKaryawan',
        'as' => 'reports.printpdfkar'
    ]);
    Route::get('/report/printpdftraining/{id}', [
        'uses' => 'Backend\ReportController@printPdfTraining',
        'as' => 'reports.printpdftrain'
    ]);

    ///////START MODULE TRAINING////////

    Route::get(
        '/module',
        [
            'uses' => 'Backend\ModuleController@index',
            'as' => 'module.index'
        ]
    );

    Route::get(
        '/module/datatable',
        [
            'uses' => 'Backend\ModuleController@datatable',
            'as' => 'module.datatables'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/module/add',
        [
            'uses' => 'Backend\ModuleController@addModule',
            'as' => 'module.add'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/module/edit/{id}',
        [
            'uses' => 'Backend\ModuleController@updateModule',
            'as' => 'module.update'
        ]
    );

    Route::post(
        '/module/delete',
        [
            'uses' => 'Backend\ModuleController@delete',
            'as' => 'module.delete'
        ]
    );

    Route::post(
        '/module/undonelane',
        [
            'uses' => 'Backend\ModuleController@getallundoneplan',
            'as' => 'module.getallundoneplan'
        ]
    );

    Route::get('module/redirect', 'Backend\ModuleController@redirect')->name("module.redirect");

    ///////START CERTIFICATE TRAINING////////

    Route::get(
        '/certificate',
        [
            'uses' => 'Backend\CertificateController@index',
            'as' => 'certificate.index'
        ]
    );

    Route::get(
        '/certificate/datatable',
        [
            'uses' => 'Backend\CertificateController@datatable',
            'as' => 'certificate.datatables'
        ]
    );

    Route::get(
        '/certificate/nocertificatedatatables',
        [
            'uses' => 'Backend\CertificateController@noCertificatedatatable',
            'as' => 'certificate.noCertificateDatatables'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/certificate/add/{id}',
        [
            'uses' => 'Backend\CertificateController@addCertificate',
            'as' => 'certificate.add'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/certificate/edit/{id}',
        [
            'uses' => 'Backend\CertificateController@updateCertificate',
            'as' => 'certificate.update'
        ]
    );

    Route::post(
        '/certificate/delete',
        [
            'uses' => 'Backend\CertificateController@delete',
            'as' => 'certificate.delete'
        ]
    );

    Route::post(
        '/certificate/undonelane',
        [
            'uses' => 'Backend\CertificateController@getallundoneplan',
            'as' => 'certificate.getallundoneplan'
        ]
    );

    Route::post(
        '/api/getkaryawan',
        [
            'uses' => 'Backend\CertificateController@getselectkaryawan',
            'as' => 'certificate.getselectkaryawan'
        ]
    );

    //AJAX
    Route::post(
        '/api/getallposition',
        [
            'uses' => 'Backend\PositionController@getallposition',
            'as' => 'position.getallposition'
        ]
    );

    Route::post(
        '/api/getallkaryawan',
        [
            'uses' => 'Backend\UserController@getallkaryawan',
            'as' => 'user.getallkaryawan'
        ]
    );

    //PROFILE
    Route::get(
        '/getprofile',
        [
            'uses' => 'Backend\UserController@getProfile',
            'as' => 'getprofile'
        ]
    );

    Route::get('redirect', 'Backend\ModuleController@redirect')->name("module.redirect");
    Route::post('training/import/excel', 'Backend\TrainingController@importTrainPlan')->name("training.import.excel");
    Route::get('matriks', 'Backend\MatriksController@index')->name("matriks.index");
    Route::match(
        array('GET', 'POST'),
        '/matriks/datatables',
        [
            'uses' => 'Backend\MatriksController@datatable',
            'as' => 'matriks.datatables'
        ]
    );
    Route::get('matriks/{id}', 'Backend\MatriksController@show')->name("matriks.show");

    Route::match(
        array('GET', 'POST'),
        '/matriks/{id}/datatables/trainings',
        [
            'uses' => 'Backend\MatriksController@datatableTrainings',
            'as' => 'matriks.datatableTrainings'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/matriks/{id}/datatables/trained',
        [
            'uses' => 'Backend\MatriksController@datatableTrained',
            'as' => 'matriks.datatableTrained'
        ]
    );

    Route::match(
        array('GET', 'POST'),
        '/matriks/{id}/datatables/train',
        [
            'uses' => 'Backend\MatriksController@datatableTrain',
            'as' => 'matriks.datatableTrain'
        ]
    );
});

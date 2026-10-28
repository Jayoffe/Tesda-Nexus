<?php

$router->get('/', 'Welcome::index');
$router->get('/login', 'AuthController::login');
$router->post('/login', 'AuthController::login');
$router->get('/apply', 'AuthController::apply');
$router->post('/apply', 'AuthController::apply');
$router->post('/logout', 'AuthController::logout');
$router->get('/logout', 'AuthController::logout');

$router->group(['middleware' => 'AuthMiddleware'], function ($router) {
    $router->get('/dashboard', 'Welcome::dashboard');
});

$router->group(['middleware' => ['AuthMiddleware', 'StudentRoleMiddleware']], function ($router) {
    $router->get('/profile', 'StudentController::index');
    $router->get('/student-profile', 'StudentController::profile');
    $router->post('/student-profile', 'StudentController::profile');
});

$router->group(['middleware' => ['AuthMiddleware', 'StaffAdminMiddleware']], function ($router) {
    $router->get('/show-users', 'UserController::showUsers');
    $router->get('/admin/users/create', 'UserController::create');
    $router->post('/admin/users/create', 'UserController::create');
    $router->get('/admin/users/edit/{id}', 'UserController::edit');
    $router->post('/admin/users/edit/{id}', 'UserController::edit');
    $router->post('/admin/users/delete/{id}', 'UserController::delete');
    $router->get('/admin/courses', 'CourseController::index');
    $router->get('/admin/courses/create', 'CourseController::create');
    $router->post('/admin/courses/create', 'CourseController::create');
    $router->get('/admin/courses/edit/{id}', 'CourseController::edit');
    $router->post('/admin/courses/edit/{id}', 'CourseController::edit');
    $router->post('/admin/courses/delete/{id}', 'CourseController::delete');
});

$router->group(['middleware' => ['AuthMiddleware', 'AdminMiddleware']], function ($router) {
    $router->get('/admin/centers', 'TrainingCenterController::index');
    $router->post('/admin/centers', 'TrainingCenterController::index');
});

$router->group(['middleware' => ['AuthMiddleware', 'CenterAdminMiddleware']], function ($router) {
    $router->get('/center/applications', 'CenterApplicationController::index');
    $router->post('/center/applications/{id}/approve', 'CenterApplicationController::approve');
    $router->post('/center/applications/{id}/reject', 'CenterApplicationController::reject');
});

$router->group(['middleware' => ['AuthMiddleware', 'InstructorMiddleware']], function ($router) {
    $router->get('/instructor', 'InstructorController::index');
    $router->post('/instructor/enroll', 'InstructorController::enroll');
    $router->post('/instructor/course/{id}', 'CourseController::update_delivery_details');
    $router->post('/instructor/course/{id}/materials', 'CourseController::add_material');
});

$router->group(['middleware' => ['AuthMiddleware', 'StudentRoleMiddleware']], function ($router) {
    $router->get('/my-courses', 'StudentController::courses');
});

$router->group(['middleware' => 'AuthMiddleware'], function ($router) {
    $router->get('/course/{id}', 'CourseController::detail');
});

// API authentication and product CRUD
$router->group(['prefix' => '/api'], function ($router) {
    $router->post('/login', 'ApiController::login');
    $router->post('/register', 'ApiController::register');
    $router->post('/logout', 'ApiController::logout');
    $router->post('/refresh', 'ApiController::refresh');
    $router->get('/profile', 'ApiController::profile');
    $router->get('/users', 'ApiController::list');
    $router->post('/users', 'ApiController::create');
    $router->put('/users/{id}', 'ApiController::update');
    $router->delete('/users/{id}', 'ApiController::delete');
    $router->get('/products', 'ApiController::products');
    $router->post('/products', 'ApiController::product_create');
    $router->put('/products/{id}', 'ApiController::product_update');
    $router->delete('/products/{id}', 'ApiController::product_delete');

    // Browser clients send OPTIONS before JSON requests.
    $router->options('/login', 'ApiController::login');
    $router->options('/register', 'ApiController::register');
    $router->options('/logout', 'ApiController::logout');
    $router->options('/refresh', 'ApiController::refresh');
    $router->options('/profile', 'ApiController::profile');
    $router->options('/users', 'ApiController::list');
    $router->options('/users/{id}', 'ApiController::update');
    $router->options('/products', 'ApiController::products');
    $router->options('/products/{id}', 'ApiController::product_update');
});

// lab 6 migrations
$router->get('/migration/create/{migration_class}', 'MigrationController::create_migration');
$router->get('/migration/migrate', 'MigrationController::migrate');
$router->get('/migration/rollback', 'MigrationController::rollback');
$router->get('/migration/rollback-all', 'MigrationController::rollback_all');
$router->get('/migration/refresh', 'MigrationController::refresh');
$router->get('/migration/status', 'MigrationController::status');

$router->group(['middleware' => 'AuthMiddleware'], function ($router) {
    $router->get('/products', 'ProductController::read');
    $router->any('/products/create', 'ProductController::create');
    $router->any('/products/edit/{id}', 'ProductController::edit');
    $router->get('/products/delete/{id}', 'ProductController::delete');
});

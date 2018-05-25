<?php

//Webpage
Route::get('/webpageindex', 'WebpageController@getIndex');

/* * ********************
 * ****** Publisher
 */
/*
 * Authentication Publishers
 */
Route::get('/login', 'AuthController@getLogin');
Route::get('/login_mf', 'AuthController@getLoginMF');
Route::post('/login', 'AuthController@setLogin');
Route::get('/logout', 'AuthController@getLogout');
Route::get('/register/{adserver?}/{media_buyer?}', 'AuthController@getRegister');
Route::get('/register_mf/{adserver?}', 'AuthController@getRegisterMF');
Route::post('/register', 'AuthController@setRegister');
Route::get('/activate/{id}/{platform}/{code}/{is_admin?}', 'AuthController@setActivate');
Route::post('/forgot_password', 'AuthController@setForgotPassword');
Route::get('/reset_password/{id}/{code}', 'AuthController@getResetPassword');
Route::post('/reset_password', 'AuthController@setResetPassword');
Route::get('/new_category', 'TestApiController@newCategory');

Route::group(array('before' => 'authPublisher'), function() {
    /*
     * Home
     */
    route::get('/', 'HomeController@getIndex');
    route::get('/revenue_by_date/{interval}', 'HomeController@getRevenueByDate');
    route::get('/actual_balance', 'PaymentsController@getActualBalance');

    /*
     * Profile
     */
    Route::get('/profile', 'ProfileController@getIndex');
    Route::post('/profile_update', 'ProfileController@setAccountInfo');
    Route::post('/email_update', 'ProfileController@setEmail');
    Route::post('/password_update', 'ProfileController@setNewPassword');
    Route::post('/payment_paypal_update', 'ProfileController@setPaypal');
    Route::post('/payment_bank_update', 'ProfileController@setBank');
    Route::get('/profile/setLang/{lang}', 'ProfileController@setLang');
    Route::get('/profile_notifications', 'ProfileController@getNotifications');
    route::get('/messages/get_message/{id}', 'ProfileController@getMessage');
    //Datos Fiscales
    Route::any('/tax_data_update', 'ProfileController@setTax');
    Route::get('/tax_data_usa_download', 'ProfileController@getTaxUsa');
    Route::get('/tax_data_other_download', 'ProfileController@getTaxOther');

    /*
     * Payment
     */
    Route::get('/payments', 'PaymentsController@getIndex');

    route::get('/admin/pagosprocesotodos', 'AdminPaymentsController@getPagosProcesoTodos');
    route::get('/admin/ingresosMensuales', 'AdminPaymentsController@getIngresosMensuales');
    route::get('/admin/pagosHistorial', 'AdminPaymentsController@getPagosHistorial');


    /*
     * Placements
     */
    route::get('/placements', 'PlacementController@getIndex');
    route::get('/placements_list/{id_site}', 'PlacementController@getPlacementsView');
    route::post('/create_site', 'PlacementController@createSite');
    route::post('/create_placement', 'PlacementController@createPlacement');
    route::get('/download_verification_file/{id_site}', 'PlacementController@downloadVerificationFile');
    route::post('/validate_site', 'PlacementController@validateSite');
    route::post('/add_domain_list', 'PlacementController@setDomainList');
    route::get('/placement_code/{adserverKey}/{siteName}/{placementName}/{placementAdserverName}/{size}/{height}/{width}/{aditionalKey}/{formatName}', 'PlacementController@getPlacementCode');
    route::get('/placements_codes/{site_id}', 'PlacementController@getAllPlacementsCodes');
    route::get('/imonomy_code/{site_id}', 'PlacementController@getImonomyCode');
    route::get('/placement_name/{site_id}/{size_id}', 'PlacementController@getPlacementName');

    /*
     * Reports
     */
    route::get('/report_table/{type}/{interval}', 'ReportsController@getTable');
    route::get('/report_imonomy_table/{type}/{interval}', 'ReportsController@getImonomyTable');
    route::get('/report/{type}/{interval}', 'ReportsController@getIndex');
    route::get('/report_imonomy/{type}/{interval}', 'ReportsController@getImonomyIndex');
    route::get('/report/export/{type}/{interval}/{format}', 'ReportsController@getExport');
    route::get('/report/export_imonomy/{type}/{interval}/{format}', 'ReportsController@getExportImonomy');
    route::get('/report_graph/{interval}/{group}', 'ReportsController@getGraph');
    route::get('/report_graph_map/{interval}', 'ReportsController@getGraphMap');
});

/* * ********************
 * ****** Admin
 */
Route::group(array('prefix' => 'admin'), function() {
    Route::group(array('before' => 'authAdmin'), function() {

        /*         * * Home ** */
        route::get('/', 'AdminHomeController@getIndex');
        route::get('/help', 'AdminHomeController@getHelp');

        /*         * * Profile ** */
        Route::get('/profile/setLang/{lang}', 'AdminProfileController@setLang');
        Route::get('/profile/paymentPreferences/{admin_id}', 'AdminProfileController@getPaymentPreferences');
        Route::post('/payment_paypal_update', 'AdminProfileController@setPaypal');
        Route::post('/payment_bank_update', 'AdminProfileController@setBank');



        /*         * * Messages ** */
        Route::group(array('before' => 'permission:messages'), function() {
            route::get('/messages', 'AdminMessagesController@getIndex');
            route::post('/messages/add_default', 'AdminMessagesController@addDefault');
            route::post('/messages/get_default', 'AdminMessagesController@getDefault');
            route::post('/messages/send_message', 'AdminMessagesController@sendMessage');
        });

        /*         * * Users ** */
        Route::group(array('before' => 'permission:users'), function() {
            route::get('/users', 'AdminUsersController@getIndex');
            route::get('/admin/user_details/{id}', 'AdminUsersController@getUserView');
            route::post('/create_administrator', 'AdminUsersController@createAdministrator');
            route::post('/activate_user', 'AdminUsersController@activateUser');
            route::post('/update_admin', 'AdminUsersController@updateAdministrator');
            route::get('/load_users_table', 'AdminUsersController@loadUsersTable');
            route::post('/add_user', 'AdminUsersController@addUser');
        });

        /*         * * Payments ** */
        Route::group(array('before' => 'permission:payments'), function() {
            route::get('/payments/{type?}', 'AdminPaymentsController@getPayments');
            route::get('/mediabuyer_commissions', 'AdminPaymentsController@getMediaBuyerCommissions');
            route::get('/mediabuyer_commissions_table', 'AdminPaymentsController@getMediaBuyerCommissionsTable');
            route::get('/rename_payments', 'AdminPaymentsController@renamePayments');
            route::get('/revert_payments/{id}/{type?}', 'AdminPaymentsController@deletePayment');
            route::post('/pyment_generate', 'AdminPaymentsController@setPayment');
            route::post('/billing_generate', 'AdminPaymentsController@newBilling');
            route::get('/pagosprocesotodos/{type?}', 'AdminPaymentsController@getPagosProcesoTodos');
            route::get('/ingresosMensuales/{type?}', 'AdminPaymentsController@getIngresosMensuales');
            route::get('/pagosHistorial/{type?}', 'AdminPaymentsController@getPagosHistorial');
            route::get('/item_billings/{id}/{type?}', 'AdminPaymentsController@getPublisherBillings');
        });
            route::get('/item_payments/{id}/{type?}', 'AdminPaymentsController@getPublisherPayments');


        /*         * * Publishers ** */
        Route::group(array('before' => 'permission:publishers'), function() {
            route::post('/save_imonomy', 'AdminPublishersController@saveImonomy');
            route::get('/publishers', 'AdminPublishersController@getIndex');
            route::get('/admin/publisher_details/{id}', 'AdminPublishersController@getPublisherView');
            route::get('/admin/assign_tester/{id}', 'AdminPublishersController@assingTester');
            route::post('/media_buyer_update', 'AdminPublishersController@setMediaBuyer');
            route::post('/admin/update_account_data/{id}', 'AdminPublishersController@updateAccountData');
            route::post('/admin/update_site_data/{id}', 'AdminSitesController@updateSiteData');
            route::post('/admin/hide_alert/{id}', 'AdminPublishersController@hideAlertAndDeleteYax');
            route::get('/download_tax_form/{file_name}', 'AdminPublishersController@getTaxForm');
            route::get('/export/publishers', 'AdminPublishersController@getExport');
            route::get('/admin/publisher_payments/{id}', 'AdminPaymentsController@getPublisherPayments');
            route::get('/admin/publisher_billings/{id}', 'AdminPaymentsController@getPublisherBillings');
            route::get('/load_publishers_table', 'AdminPublishersController@loadPublishersTable');
        });


        /*         * * Sites ** */
        Route::group(array('before' => 'permission:sites'), function() {
            route::get('/sites', 'AdminSitesController@getIndex');
            route::get('/admin/site_domains/{id}', 'AdminSitesController@getDomainList');
            route::get('/admin/site_categories/{id}', 'AdminSitesController@getSiteCategories');
            route::get('/admin/site_view/{id}', 'AdminSitesController@getSiteData');
            route::get('/admin/categorize/{id}/{categories}', 'AdminSitesController@categorize');
            route::get('/export/sites/{validated}', 'AdminSitesController@getExport');
            route::get('/load_sites_table', 'AdminSitesController@loadSitesTable');
            route::get('/load_unvalidated_sites_table', 'AdminSitesController@loadUnvalidatedSitesTable');
        });

        /*         * * Optimization** */
        Route::group(array('before' => 'permission:optimization'), function() {
            route::get('/publisher_optimize_details/{id}', 'AdminPublishersOptimizationController@getOptimization');
            route::get('/publisher_dfp_optimize_details/{id}/{type}', 'AdminPublishersOptimizationController@getOptimizationDfp');
            route::get('/optimize_publisher/{id}', 'AdminPublishersOptimizationController@optimize');
            route::get('/publishers_optimization', 'AdminPublishersOptimizationController@getIndex');
            route::get('/history_optimization/{range}', 'AdminPublishersOptimizationController@getHistoryRange');
            route::get('/load_publishers_optimization_table/{date?}', 'AdminPublishersOptimizationController@loadPublishersOptimizationTable');
            route::get('/load_publishers_optimization_dfp_table/{date?}', 'AdminPublishersOptimizationController@loadPublishersDfpOptimizationTable');
            route::get('/load_optimized_publisher_table/{publisherId}/{date?}', 'AdminPublishersOptimizationController@loadOptimizedPublisherTable');
            route::get('/load_payment_rules_table', 'AdminPublishersOptimizationController@loadPayemtRuleTable');
            route::get('/load_publisher_payment_rules_table/{id}', 'AdminPublishersOptimizationController@loadPayemtRuleTableByPublisher');
        });


        /*         * * Inventory ** */
        Route::group(array('before' => 'permission:inventory'), function() {
            route::get('/report_table/{type}/{interval}', 'AdminInventoryController@getTable');
            route::get('/report/{type}', 'AdminInventoryController@getIndex');
        });


        /*         * * Constants ** */
        Route::group(array('before' => 'permission:constants'), function() {
            route::get('/constants', 'AdminConstantsController@getIndex');
            route::post('/change_constant/{id}', 'AdminConstantsController@changeConstant');
        });
    });

    /*
     * Authentication Admin
     */
    Route::get('/login', 'AdminAuthController@getLogin');
    Route::post('/login', 'AdminAuthController@setLogin');
    Route::get('/logout', 'AdminAuthController@getLogout');
    Route::get('/register', 'AdminAuthController@getRegister');
    Route::post('/register', 'AdminAuthController@setRegister');
    Route::get('/activate/{id}/{code}', 'AdminAuthController@setActivate');
    Route::post('/forgot_password', 'AdminAuthController@setForgotPassword');
    Route::get('/reset_password/{id}/{code}', 'AdminAuthController@getResetPassword');
    Route::post('/reset_password', 'AdminAuthController@setResetPassword');
});

/* * *
 * Validaciones propias
 */
Validator::extend('curl_url', 'customValidation@curl_url');

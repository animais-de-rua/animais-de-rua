<?php

use App\Http\Controllers\Admin\AdopterCrudController;
use App\Http\Controllers\Admin\AdoptionCrudController;
use App\Http\Controllers\Admin\APICrudController;
use App\Http\Controllers\Admin\AppointmentCrudController;
use App\Http\Controllers\Admin\CampaignCrudController;
use App\Http\Controllers\Admin\DonationCrudController;
use App\Http\Controllers\Admin\FatCrudController;
use App\Http\Controllers\Admin\FriendCardModalityCrudController;
use App\Http\Controllers\Admin\GodfatherCrudController;
use App\Http\Controllers\Admin\HeadquarterCrudController;
use App\Http\Controllers\Admin\PartnerCategoryCrudController;
use App\Http\Controllers\Admin\PartnerCrudController;
use App\Http\Controllers\Admin\ProcessCrudController;
use App\Http\Controllers\Admin\ProtocolCrudController;
use App\Http\Controllers\Admin\ProtocolRequestCrudController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SponsorCrudController;
use App\Http\Controllers\Admin\StoreOrderCrudController;
use App\Http\Controllers\Admin\StoreProductCrudController;
use App\Http\Controllers\Admin\StoreStockCrudController;
use App\Http\Controllers\Admin\StoreTransactionCrudController;
use App\Http\Controllers\Admin\SupplierCrudController;
use App\Http\Controllers\Admin\TerritoryCrudController;
use App\Http\Controllers\Admin\TreatmentCrudController;
use App\Http\Controllers\Admin\TreatmentTypeCrudController;
use App\Http\Controllers\Admin\UserCrudController;
use App\Http\Controllers\Admin\VetCrudController;
use App\Http\Controllers\Admin\VoucherCrudController;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
], function () {

    // CRUD
    Route::crud('user', UserCrudController::class);
    Route::crud('adoption', AdoptionCrudController::class);
    Route::crud('adopter', AdopterCrudController::class);
    Route::crud('appointment', AppointmentCrudController::class);
    Route::crud('campaign', CampaignCrudController::class);
    Route::crud('donation', DonationCrudController::class);
    Route::crud('godfather', GodfatherCrudController::class);
    Route::crud('headquarter', HeadquarterCrudController::class);
    Route::crud('process', ProcessCrudController::class);
    Route::crud('territory', TerritoryCrudController::class);
    Route::crud('vet', VetCrudController::class);
    Route::crud('treatment', TreatmentCrudController::class);
    Route::crud('treatment-type', TreatmentTypeCrudController::class);
    Route::crud('friend-card-modality', FriendCardModalityCrudController::class);
    Route::crud('partner', PartnerCrudController::class);
    Route::crud('partner-category', PartnerCategoryCrudController::class);
    Route::crud('protocol', ProtocolCrudController::class);
    Route::crud('protocol-request', ProtocolRequestCrudController::class);
    Route::crud('sponsor', SponsorCrudController::class);
    Route::crud('fat', FatCrudController::class);

    // Store
    Route::crud('store/products', StoreProductCrudController::class);
    Route::crud('store/orders', StoreOrderCrudController::class);
    Route::crud('store/user/stock', StoreStockCrudController::class);
    Route::crud('store/user/transaction', StoreTransactionCrudController::class);
    Route::crud('store/supplier', SupplierCrudController::class);
    Route::crud('store/voucher', VoucherCrudController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'report'])->name('reports');
    Route::post('/reports/{type}/{action}', [ReportController::class, 'action']);

    // API
    Route::get('{entity}/ajax/{action}/{arg1?}/{arg2?}', [APICrudController::class, 'ajax']);
    Route::post('api/appointment/approve', [APICrudController::class, 'approveAppointment']);
    Route::post('api/treatment/approve', [APICrudController::class, 'approveTreatment']);
    Route::post('api/process/contact', [APICrudController::class, 'toggleProcessContacted']);
});

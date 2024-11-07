<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\TestController;
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
Route::group(['middleware' => ['auth']], function () {
	#TEST Modul
	# Admin 
	Route::prefix('test')->group(function (){
		Route::get('tinymce',[TestController::class,'viewHtml']);
	});
	Route::prefix('datasource')->group(function () {
		// Route::post('all-customer', [DataController::class, 'sourceDataCustomer'])->name('source-data-customer');
		Route::post('district', [DataController::class, 'sourceDataDistrict'])->name('source-data-district');
		Route::post('city', [DataController::class, 'sourceDataCities'])->name('source-data-city');
		Route::post('province', [DataController::class, 'sourceDataProvinces'])->name('source-data-province');
		#test & try
		Route::get('subdistrict', [DataController::class, 'sourceDataSubdistrict'])->name('source-data-subdistrict');
	});
	// Route::get('/', [HomeController::class, 'index']);
	Route::prefix('home')->group(function(){
		Route::get('/', [HomeController::class,'homeFunction']);
		Route::match(['get','post'],'source-chart-lead', [HomeController::class,'sourceChartLead'])->name('source-chart-lead');
		Route::match(['get','post'],'source-chart-opportunity', [HomeController::class,'sourceChartOpportunity'])->name('source-chart-opportunity');
		Route::match(['get','post'],'source-chart-purchase', [HomeController::class, 'sourceChartPurchase'])->name('source-chart-purchase');
	});
	Route::post('dropzone/store', [CustomerController::class, 'dropzoneStore'])->name('dropzone.store');
	# Customer
	Route::prefix('customer')->group(function(){
		Route::post('addr-districts', [DataController::class, 'dataDistricts'])->name('source-addr-districts');
		Route::post('addr-cities', [DataController::class, 'dataCities'])->name('source-addr-cities');
		Route::post('addr-province', [DataController::class, 'dataProvincies'])->name('source-addr-province');
		Route::match(['get','post'],'all-customer', [DataController::class, 'sourceDataCustomer'])->name('source-data-customer');
		#####
		Route::match(['get', 'post'], 'information', [CustomerController::class, 'actionPageCustomerInformation'])->name('customer-load-page-information');
		Route::match(['get', 'post'], 'activities', [CustomerController::class, 'actionPageCustomerActivities'])->name('customer-load-page-activities');
		Route::match(['get', 'post'], 'leads', [CustomerController::class, 'actionPageCustomerLeads'])->name('customer-load-page-leads');
		Route::match(['get', 'post'], 'opportunities', [CustomerController::class, 'actionPageCustomerOpportunities'])->name('customer-load-page-opportunities');
		Route::match(['get', 'post'], 'create-new-opportunity-cst/{id}', [OpportunityController::class, 'formNewOpportunityCst'])->name('form-new-opportunity');
		Route::match(['get', 'post'], 'purchased', [CustomerController::class, 'actionPageCustomerPurchased'])->name('customer-load-page-purchased');
		Route::get('detail-customer/{id}', [CustomerController::class, 'detailCustomer']);
		route::match(['get'], 'detail-customer-activities/{id}' , [CustomerController::class, 'activityCustomer']);
		route::match(['get'], 'detail-customer-opportunities/{id}' , [CustomerController::class, 'actionPageCustomerOpportunity']);
		#####
		Route::get('/', [CustomerController::class,'CustomerDataView']);
		Route::get('create-customer',[CustomerController::class,'viewFormCreateCustomer']);
		Route::get('create-customer/{id}', [CustomerController::class, 'viewFormCreateCustomerFixed']);
		Route::post('store-customer', [CustomerController::class, 'storeCreateCustomer'])->name('store-customer');
		Route::post('source-data-individu', [CustomerController::class, 'sourceDataInvidu'])->name('source-data-individu');
		Route::get('detail-customer/person-update/{id}', [CustomerController::class, 'updatePersonData']);
		Route::get('detail-customer/company-update/{id}', [CustomerController::class, 'updateCompanyData']);
		Route::patch('store-update-customer', [CustomerController::class, 'storeUpdateCustomer'])->name('store-update-customer');
		Route::patch('store-update-personal', [CustomerController::class, 'storeUpdatePersonal'])->name('store-update-personal');
		#####
		Route::match(['get', 'post'], 'source-data-lead-cst', [DataController::class, 'sourceDataLeadCst'])->name('source-data-lead-cst');
		Route::get('detail-customer-leads/{id}', [CustomerController::class, 'viewCustomerLead']);
		Route::match(['get', 'post'],'person-contact',[CustomerController::class,'actionGetPersonContact'])->name('customer-person-contact');
		Route::match(['get', 'post'],'activity-pic-contact',[CustomerController::class,'actionGeActivitiContact'])->name('activity-pic-contact');
		Route::match(['get', 'post'],'sub-customers',[CustomerController::class,'actionGetSubcustomer'])->name('sub-customers');
		Route::match(['get', 'post'],'action-get-city-select',[CustomerController::class,'actionGetCity'])->name('action-get-city-select');
		Route::match(['get', 'post'],'action-get-district-select',[CustomerController::class,'actionGetDistrict'])->name('action-get-district-select');
	
		// Route::post('source-data-customer',[DataController::class, 'sourceDataCustomer'])->name('source-data-customer');
	});
	# Leads
	Route::prefix('leads')->group(function(){
		#lead view
		Route::get('/', [LeadController::class,'LeadDataView']);
		Route::get('create-lead', [LeadController::class,'formCreateLead']);
		Route::get('detail-lead/{id}', [LeadController::class, 'LeadDataDetailView']);
		#lead source data
		Route::match(['get', 'post'],'source-data-leads', [DataController::class, 'sourceDataLeads'])->name('source-data-leads');
		#lead create
		Route::post('store-data-i', [LeadController::class, 'storeLead'])->name('store-lead-data');
		Route::post('store-data-ii', [LeadController::class, 'storeLeadVerII'])->name('store-lead-data-ver2');
		Route::post('store-data-follow-up', [LeadController::class, 'storeLeadFollowUp'])->name('store-lead-data-follow-up');
		Route::post('store-new-lead', [LeadController::class, 'storeLeadI'])->name('store-new-lead');
		#lead_detail
		Route::post('store-update-lead-status', [LeadController::class, 'storeUpdateStatusLead'])->name('store-update-lead-status');
		Route::post('store-update-base-value', [LeadController::class, 'storeUpdateBaseValue'])->name('store-update-base-value');
		Route::post('store-update-target-value', [LeadController::class, 'storeUpdateTargetValue'])->name('store-update-target-value');
		Route::post('store-change-sales-lead', [LeadController::class, 'storeChangeSales'])->name('store-change-sales-lead');
		Route::post('store-select-team', [LeadController::class, 'storeSelectTeam'])->name('store-select-team');
		Route::post('store-select-tech', [LeadController::class, 'storeSelectTech'])->name('store-select-tech');
		Route::post('store-identity-need', [LeadController::class, 'storeIdentificationQualification'])->name('store-identity-need');
		#lead_contact
		Route::post('store-add-lead-contact', [LeadController::class, 'storeContactLead'])->name('store-add-lead-contact');
		Route::post('remove-lead-contact', [LeadController::class, 'actionRemoveContact'])->name('remove-lead-contact');
		Route::post('optain-lead-project', [LeadController::class, 'actionGetCstProject'])->name('optain-lead-project');
		#lead_activities
		Route::match(['get', 'post'], 'lead-activities', [ActivityController::class, 'sourceLeadActivities'])->name('lead-activities');
		Route::post('lead-activities-detail-info', [ActivityController::class, 'sourceActivityInfo'])->name('lead-activities-detail-info');
		Route::post('lead-activities-detail', [ActivityController::class, 'sourceActivityDetail'])->name('lead-activities-detail');
		Route::post('lead-activities-update-info', [ActivityController::class, 'updateActivityInfo'])->name('lead-activities-update-info');
		Route::post('store-data-update-info', [ActivityController::class, 'actionUpdateActivityInfo'])->name('store-data-update-info');
		
	});
	#Activity
	Route::prefix('activity')->group(function () {
		#load view activity
		Route::get('/', [ActivityController::class, 'viewActivity']);
		Route::get('activity-detail/{id}', [ActivityController::class, 'viewActivityDetail']);
		#load data activity
		Route::match(['get', 'post'],'source-data-activity', [DataController::class, 'sourceActivities'])->name('source-data-activity');
		Route::match(['get', 'post'],'source-data-activity-instant', [DataController::class, 'sourceActivitiesInstant'])->name('source-data-activity-instant');
		Route::match(['get', 'post'],'source-data-activity-cst', [DataController::class, 'sourceActivitiesCst'])->name('source-data-activity-cst');
		Route::post('source-data-activity-calender', [ActivityController::class, 'sourceDataActivityCalender'])->name('source-data-activity-calender');
		Route::post('source-data-activity-calender-tck', [ActivityController::class, 'sourceDataTiketCalender'])->name('source-data-activity-calender-tck');
		Route::post('source-data-activity-calender-cst', [ActivityController::class, 'sourceDataActivityCalenderCst'])->name('source-data-activity-calender-cst');
		Route::match(['get', 'post'],'source-data-activity-detail-calender', [ActivityController::class, 'sourceDataActivityDetailCalender'])->name('source-data-activity-detail-calender');
		Route::match(['get', 'post'],'all-lead-activities', [ActivityController::class, 'sourceAllLeadActivities'])->name('all-lead-activities');
		#storing activity date
		Route::post('store-new-activty', [ActivityController::class, 'storeActivitiesNew'])->name('store-new-activty');
		Route::post('store-data-lead-activities', [ActivityController::class, 'storeActivitiesLead'])->name('store-data-lead-activities');
		Route::post('store-close-activity', [ActivityController::class, 'storeCloseActivities'])->name('store-close-activity');
		Route::post('store-ticket-activity', [ActivityController::class, 'storeTicketActivities'])->name('store-ticket-activity');
		#deleting data
		Route::post('delete-data-lead-activities', [ActivityController::class, 'deleteActivitiesLead'])->name('delete-data-lead-activities');
		#udating data
		Route::post('update-data-lead-activities', [ActivityController::class, 'updateActivitiesLead'])->name('update-data-lead-activities');
		Route::post('update-schedule-activities', [ActivityController::class, 'updateActivitiesScedule'])->name('update-schedule-activities');
		Route::post('update-close-activities', [ActivityController::class, 'updateCloseActivities'])->name('update-close-activities');
		Route::post('autosave-update-data-lead-activities', [ActivityController::class, 'autoSaveUpdateActivitiesLead'])->name('autosave-update-data-lead-activities');
		Route::post('update-status-lead-activities', [ActivityController::class, 'updateAStatusActivitiesLead'])->name('update-status-lead-activities');
		Route::post('update-describe-activity', [ActivityController::class, 'updateDescribeActivity'])->name('update-describe-activity');
		Route::post('update-result-activity', [ActivityController::class, 'updateResultActivity'])->name('update-result-activity');
	});
	#Opportunities
	Route::prefix('opportunities')->group(function () {
		Route::get('/', [OpportunityController::class, 'viewOpportunities']);
		Route::get('detail-opportunity/{id}', [OpportunityController::class, 'viewOpportunityDetail']);
		Route::get('create-new-opportunity', [OpportunityController::class, 'formNewOpportunity'])->name('form-new-opportunity');
		Route::match(['post','get'],'check-opportunity/{id}', [OpportunityController::class, 'help_viewOpportunityDetail']);
		//
		Route::post('store-new-opportunity', [OpportunityController::class, 'storeNewOpportunity'])->name('store-new-opportunity'); 
		Route::post('store-new-opportunity-cst', [OpportunityController::class, 'storeNewOpportunityCst'])->name('store-new-opportunity-cst');
		Route::post('store-opportunity-new-a', [OpportunityController::class, 'storeOpportunity_A'])->name('store-opportunity-new-a');
		Route::post('store-opportunity-notes', [OpportunityController::class, 'storeOprNotes'])->name('store-opportunity-notes');
		Route::post('store-subvalue-opportunity', [OpportunityController::class, 'storeOprValue'])->name('store-subvalue-opportunity');
		Route::post('store-value-opportunity-tax', [OpportunityController::class, 'storeOprValueTax'])->name('store-value-opportunity-tax');
		Route::post('store-value-opportunity-other', [OpportunityController::class, 'storeOprValueOther'])->name('store-value-opportunity-other');
		Route::post('store-value-opportunity-revenue', [OpportunityController::class, 'storeOprValueRevenue'])->name('store-value-opportunity-revenue');
		// 
		Route::match(['post','get'],'source-opportunities', [DataController::class, 'sourceDataOpportunities'])->name('source-opportunities');
		Route::post('source-opportunities-cst', [DataController::class, 'sourceDataOpportunitiesCst'])->name('source-opportunities-cst');
		Route::match(['post','get'],'store-product-opportunity', [OpportunityController::class, 'storeProductOpportunity'])->name('store-product-opportunity');
		Route::post('update-product-opportunity', [OpportunityController::class, 'updateProductOpportunity'])->name('update-product-opportunity');
		Route::post('product-principle', [OpportunityController::class, 'listProductPrinciple'])->name('product-principle');
		Route::post('update-status-opportunity', [OpportunityController::class, 'storeUpdateStatusOpr'])->name('update-status-opportunity');
		Route::match(['get', 'post'],'customer-project',[OpportunityController::class,'sourceCustomerProject'])->name('customer-project');
		Route::match(['get', 'post'],'source-product-opportunity',[OpportunityController::class,'sourceProductOppor'])->name('source-product-opportunity');
		// 
		Route::match(['get', 'post'],'source-product-value',[OpportunityController::class,'sourceProductValue'])->name('source-product-value');
		Route::match(['get', 'post'],'source-tax-value',[OpportunityController::class,'sourceTaxValue'])->name('source-tax-value');
		Route::match(['get', 'post'],'source-trigger-tax-value',[OpportunityController::class,'sourceTriggerTaxValue'])->name('source-trigger-tax-value');
		Route::match(['get', 'post'],'source-other-value-data',[OpportunityController::class,'sourceOtherValueData'])->name('source-other-value-data');
		Route::match(['get', 'post'],'source-total-value',[OpportunityController::class,'sourceTotalValue'])->name('source-total-value');
		Route::match(['get', 'post'],'source-opportunity-note',[OpportunityController::class,'sourceOpporNotes'])->name('source-opportunity-note');
		Route::match(['get', 'post'],'action-check-win-opportunity',[OpportunityController::class,'checkWinOpportunity'])->name('action-check-win-opportunity');
	});
	# Purchasing
	Route::prefix('purchased')->group(function(){
		Route::get('/', [PurchaseController::class,'PurchaseDataView']);
		Route::match(['get', 'post'],'source-data-purchase', [PurchaseController::class, 'sourceDataPurchase'])->name('source-data-purchase');
		Route::match(['get', 'post'],'store-data-purchase-i', [PurchaseController::class, 'storeDataPurchase_a'])->name('store-data-purchase-i');
		Route::match(['get', 'post'],'store-data-purchase-ii', [PurchaseController::class, 'storeDataPurchase_b'])->name('store-data-purchase-ii');
		Route::match(['get', 'post'],'action-check-purchase', [PurchaseController::class,'actionCheckPurchase'])->name('action-check-purchase');
		Route::match(['get'],'detail/{id}', [PurchaseController::class,'detailPurchase']);
		Route::match(['get', 'post'],'action-get-invoice-number', [PurchaseController::class,'actionCheckInvoice'])->name('action-get-invoice-number');
		Route::match(['get', 'post'],'store-invoice-number', [PurchaseController::class,'storeInvoiceNumber'])->name('store-invoice-number');
		Route::match(['get', 'post'],'action-get-purchase-date', [PurchaseController::class,'actionGetDatePurchase'])->name('action-get-purchase-date');
		Route::match(['get', 'post'],'store-purchase-date', [PurchaseController::class,'storeDatePurchase'])->name('store-purchase-date');
		Route::match(['get', 'post'],'source-data-purchased', [PurchaseController::class, 'sourceDataPurchased'])->name('source-data-purchased');
		Route::match(['get', 'post'],'action-check-opportunity', [PurchaseController::class, 'actionCheckOpr'])->name('action-check-opportunity');
		
	});
	# Product
	Route::prefix('product')->group(function(){
		Route::get('/', [ProductController::class,'viewProducts']);
		Route::get('all', [ProductController::class,'viewProducts']);
		Route::get('principle', [ProductController::class,'viewPrinciple']);
		Route::match(['get','post'],'source-product', [ProductController::class,'sourceProduct'])->name('source-product');
		Route::match(['get','post'],'source-principle', [ProductController::class,'sourcePrinciple'])->name('source-principle');
		Route::match(['get','post'],'action-check-product-item', [ProductController::class,'checkDataProductItem'])->name('action-check-product-item');
		Route::match(['get','post'],'action-check-principle', [ProductController::class,'checkDataPrinciple'])->name('action-check-principle');
		Route::match(['get','post'],'action-check-principle-item', [ProductController::class,'checkDataPrincipleItem'])->name('action-check-principle-item');
		Route::match(['get','post'],'store-data-product', [ProductController::class,'storeProduct'])->name('store-data-product');
		Route::match(['get','post'],'store-data-principle', [ProductController::class,'storePrinciple'])->name('store-data-principle');
		Route::match(['get','post'],'store-data-product-update', [ProductController::class,'storeProductUpdate'])->name('store-data-product-update');
		Route::match(['get','post'],'store-data-principle-update', [ProductController::class,'storePrincipleUpdate'])->name('store-data-principle-update');		
	});
	# Setting
	Route::prefix('setting')->group(function(){
		Route::get('user', [SettingController::class,'UserDataView']);
		Route::get('user/{id}', [SettingController::class,'UserDetailView']);
		Route::get('create-user', [SettingController::class,'createUser']);
		Route::get('user/detail-user/{id}', [SettingController::class,'viewUserDataDetail']);
		Route::get('instansi', [SettingController::class,'InstansiDataView']);
		Route::get('division', [SettingController::class,'viewDevision']);
		Route::get('division/{id}', [SettingController::class,'updateDivision']);
		Route::get('create-division', [SettingController::class,'createDivision']);
		Route::get('team', [SettingController::class,'viewTeam']);
		Route::get('team/{id}', [SettingController::class,'updateTeam']);
		Route::get('create-team', [SettingController::class,'createTeam']);
		# Post Data
		Route::post('store-users', [SettingController::class, 'storeDataUser'])->name('store-users');
		Route::post('store-update-users', [SettingController::class, 'storeDataUpdateUser'])->name('store-update-users');
		Route::post('store-division', [SettingController::class, 'storeDataDivision'])->name('store-division');
		Route::post('store-update-division', [SettingController::class, 'storeDataUpdateDivision'])->name('store-update-division');
		Route::post('store-team', [SettingController::class, 'storeDataTeam'])->name('store-team');
		Route::post('store-update-team', [SettingController::class, 'storeUpdateDataTeam'])->name('store-update-team');
		# Source Data
		Route::match(['get', 'post'], 'source-users', [SettingController::class, 'sourceDataUser'])->name('source-users');
		Route::match(['get', 'post'], 'team-division', [SettingController::class, 'actionGetTean'])->name('team-division');
		Route::match(['get', 'post'], 'source-division', [SettingController::class, 'sourceDataDevision'])->name('source-division');
		Route::match(['get', 'post'], 'source-team', [SettingController::class, 'sourceDataTeam'])->name('source-team');
	});
	# Product
	Route::prefix('product')->group(function () {
		Route::post('ajaxlink-product-value', [ProductController::class, 'ajaxProductValue'])->name('ajaxlink-product-value');
	});
	# Ticket
	Route::prefix('ticket')->group(function () {
		Route::get('/', [ActivityController::class, 'viewTicketActivity']);
		Route::get('ticket-detail/{id}', [ActivityController::class, 'viewTicketDetail']);
		Route::post('store-new-ticket', [ActivityController::class, 'storeTicketsNew'])->name('store-new-ticket');
		Route::match(['get', 'post'],'source-data-ticket', [DataController::class, 'sourceTickets'])->name('source-data-ticket');
	});
	# Managemet
	Route::prefix('management')->group(function () {
		Route::get('/', [ManagementController::class, 'viewManagingUsers']);
		Route::get('user-information/{id}', [ManagementController::class, 'viewManagingUsersDetail']);
		Route::get('user-activities/{id}', [ManagementController::class, 'viewActivitiesUser']);
		Route::get('user-leads/{id}', [ManagementController::class, 'viewLeadsUser']);
		Route::get('user-opportunities/{id}', [ManagementController::class, 'viewOpportunitiesUser']);
		Route::get('user-purchases/{id}', [ManagementController::class, 'viewPurchasesUser']);
		Route::match(['get', 'post'],'source-data-mgn-user', [ManagementController::class, 'sourceDataManagementUser'])->name('source-data-mgn-user');
		Route::match(['get', 'post'],'source-statistic-activity-user', [ManagementController::class, 'sourceStatisticActivityUser'])->name('source-statistic-activity-user');
		Route::match(['get', 'post'],'source-statistic-lead-user', [ManagementController::class, 'sourceStatisticLeadUser'])->name('source-statistic-lead-user');
		Route::match(['get', 'post'],'source-statistic-opportunity-user', [ManagementController::class, 'sourceStatisticOpportunityUser'])->name('source-statistic-opportunity-user');
		Route::match(['get', 'post'],'source-statistic-purchase-user', [ManagementController::class, 'sourceStatisticPurchaseUser'])->name('source-statistic-purchase-user');
		#
		Route::match(['get', 'post'],'source-data-activity-user', [DataController::class, 'sourceActivitiesUser'])->name('source-data-activity-user');
		Route::match(['get', 'post'],'source-data-activity-calender-usr', [ActivityController::class, 'sourceDataActivityCalenderUsr'])->name('source-data-activity-calender-usr');
		Route::match(['get', 'post'],'source-data-leads-user', [DataController::class, 'sourceDataLeadsUser'])->name('source-data-leads-user');
		Route::match(['get', 'post'],'source-data-opportunities-user', [DataController::class, 'sourceDataOpportunitiesUser'])->name('source-data-opportunities-user');
		Route::match(['get', 'post'],'source-data-purchases-user', [DataController::class, 'sourceDataPurchasesUser'])->name('source-data-purchases-user');
	});

	# CRUD
	Route::prefix('crud')->group(function(){
		Route::post('store-user', [ActionController::class,'storeUser'])->name('store-user');
		Route::post('store-update-user', [ActionController::class,'storeUpdateUser'])->name('store-update-user');
		Route::post('delete-user', [ActionController::class,'deleteUser'])->name('delete-user');
	});
	// Route::group(['middleware' => ['rulesystem:ADM']], function () {
	// });
});
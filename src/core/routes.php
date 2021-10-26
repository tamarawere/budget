<?php

declare(strict_types=1);

namespace Budget\Core;

$authController = 'Budget\Auth\AuthController';
$catController = 'Budget\Categories\CategoriesController';
$uomController = 'Budget\UOM\UomController';
$lotsController = 'Budget\LotUnits\LotUnitsController';
$modeController = 'Budget\PaymentModes\PaymentModesController';
$userController = 'Budget\Users\UsersController';
$itemGroupController = 'Budget\ItemGroups\ItemGroupsController';
$itemController = 'Budget\Items\ItemsController';
$homeController = 'Budget\Home\HomeController';

$uuidRegex = '[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}';

return [
    // AUTHENTICATION API
    ['GET', 'login', [$authController, 'showLoginPage',1]],
    ['POST', 'auth/login', [$authController, 'loginUser',1]],
    ['GET', 'register', [$authController, 'showRegisterPage',1]],
    ['POST', 'auth/register', [$userController, 'registerUser']],
    ['POST', 'change_pass', [$authController, 'changePassword']],
    
    // CATEGORIES API
    ['GET', 'categories', [$catController, 'showCategoryHomePage',1]],
    ['GET', 'categories/all', [$catController, 'showAllCategoriesPage']],
    ['GET', 'categories/{catId:'.$uuidRegex.'}', [$catController, 'showEditCategoryPage']],
    ['GET', 'categories/add', [$catController, 'showAddCategoryPage']],
    ['POST', 'categories/add', [$catController, 'addCategory']],
    ['POST', 'categories/{catId:'.$uuidRegex.'}', [$catController, 'updateCategory']],
    ['DELETE', 'categories/{catId:'.$uuidRegex.'}', [$catController, 'deleteCategory']],

    // UNITS OF MEASURE API
    ['GET', 'uom', [$uomController, 'getAllUoms']],
    ['GET', 'uom/home', [$uomController, 'showUomHomePage']],
    ['GET', 'uom/all', [$uomController, 'showAllUomsPage']],
    ['GET', 'uom/{uomId:'.$uuidRegex.'}', [$uomController, 'getUomById']],
    ['GET', 'uom/add', [$uomController, 'showAddUomPage']],
    ['POST', 'uom/add', [$uomController, 'addUom']],
    ['POST', 'uom/update/{uomId:'.$uuidRegex.'}', [$uomController, 'updateUom']],
    ['POST', 'uom/delete/{uomId:'.$uuidRegex.'}', [$uomController, 'deleteUom']],

    // UNITS OF MEASURE FOR PACKAGAES API
    ['GET', 'lotunits', [$lotsController, 'getAllLotUnits']],
    ['GET', 'lotunits/{lotUnitId:'.$uuidRegex.'}', [$lotsController, 'getLotUnitById']],
    ['POST', 'lotunits/add', [$lotsController, 'addLotUnit']],
    ['POST', 'lotunits/update/{lotUnitId:'.$uuidRegex.'}', [$lotsController, 'updateLotUnit']],
    ['POST', 'lotunits/delete/{lotUnitId:'.$uuidRegex.'}', [$lotsController, 'deleteLotUnit']],

    // ITEMS GROUPS API
    ['GET', 'groups', [$itemGroupController, 'getAllItemGroups']],
    ['GET', 'groups/{groupId:'.$uuidRegex.'}', [$itemGroupController, 'getItemGroupById']],
    ['POST', 'groups/add', [$itemGroupController, 'addItemGroup']],
    ['POST', 'groups/update/{groupId:'.$uuidRegex.'}', [$itemGroupController, 'updateItemGroup']],
    ['POST', 'groups/delete/{groupId:'.$uuidRegex.'}', [$itemGroupController, 'deleteItemGroup']],

    // ITEMS API
    ['GET', 'items', [$itemController, 'getAllItems']],
    ['GET', 'items/{itemId:'.$uuidRegex.'}', [$itemController, 'getItemById']],
    ['POST', 'items/add', [$itemController, 'addItem']],
    ['POST', 'items/update/{itemId:'.$uuidRegex.'}', [$itemController, 'updateItem']],
    ['POST', 'items/delete/{itemId:'.$uuidRegex.'}', [$itemController, 'deleteItem']],

    // USERS API 
    ['GET', 'users', [$userController, 'getAllUsers']],
    ['GET', 'users/{userId:'.$uuidRegex.'}', [$userController, 'getUserById']],
    ['POST', 'users/add', [$userController, 'addUser']],
    ['POST', 'users/update/{userId:'.$uuidRegex.'}', [$userController, 'updateUser']],
    ['POST', 'users/delete/{userId:'.$uuidRegex.'}', [$userController, 'deleteUser']],

    // PAYMENT MODES API 
    ['GET', 'modes', [$modeController, 'index']],
    //['GET', '/modes', [$modeController, 'getAllPaymentModes']],
    ['GET', 'modes/{modeId:'.$uuidRegex.'}', [$modeController, 'getPaymentModeById']],
    ['POST', 'modes/add', [$modeController, 'addPaymentMode']],
    ['POST', 'modes/update/{modeId:'.$uuidRegex.'}', [$modeController, 'updatePaymentMode']],
    ['POST', 'modes/delete/{modeId:'.$uuidRegex.'}', [$modeController, 'deletePaymentMode']],

    ['GET', 'places', ['Budget\Categories\PlacesController', 'index']],

    // DASHBORD API
    ['GET', '', [$homeController, 'index']],
    ['GET', 'dash', [$homeController, 'showMainDash']],
    ['GET', 'main_dash', [$homeController, 'showMainDash']],
    ['GET', 'income_dash', [$homeController, 'showIncomesDash']],
    ['GET', 'expense_dash', [$homeController, 'showExpensesDash']],
    ['GET', 'invest_dash', [$homeController, 'showInvestmentsDash']],
];
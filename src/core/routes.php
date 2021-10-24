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
    ['GET', 'uom/{id}', [$uomController, 'getUomById']],
    ['POST', 'uom/add', [$uomController, 'addUom']],
    ['POST', 'uom/update/{id}', [$uomController, 'updateUom']],
    ['POST', 'uom/delete/{id}', [$uomController, 'deleteUom']],

    // UNITS OF MEASURE FOR PACKAGAES API
    ['GET', 'lotunits', [$lotsController, 'getAllLotUnits']],
    ['GET', 'lotunits/{id}', [$lotsController, 'getLotUnitById']],
    ['POST', 'lotunits/add', [$lotsController, 'addLotUnit']],
    ['POST', 'lotunits/update/{id}', [$lotsController, 'updateLotUnit']],
    ['POST', 'lotunits/delete/{id}', [$lotsController, 'deleteLotUnit']],

    // ITEMS GROUPS API
    ['GET', 'groups', [$itemGroupController, 'getAllItemGroups']],
    ['GET', 'groups/{id}', [$itemGroupController, 'getItemGroupById']],
    ['POST', 'groups/add', [$itemGroupController, 'addItemGroup']],
    ['POST', 'groups/update/{id}', [$itemGroupController, 'updateItemGroup']],
    ['POST', 'groups/delete/{id}', [$itemGroupController, 'deleteItemGroup']],

    // ITEMS API
    ['GET', 'items', [$itemController, 'getAllItems']],
    ['GET', 'items/{id}', [$itemController, 'getItemById']],
    ['POST', 'items/add', [$itemController, 'addItem']],
    ['POST', 'items/update/{id}', [$itemController, 'updateItem']],
    ['POST', 'items/delete/{id}', [$itemController, 'deleteItem']],

    // USERS API 
    ['GET', 'users', [$userController, 'getAllUsers']],
    ['GET', 'users/{id}', [$userController, 'getUserById']],
    ['POST', 'users/add', [$userController, 'addUser']],
    ['POST', 'users/update/{id}', [$userController, 'updateUser']],
    ['POST', 'users/delete/{id}', [$userController, 'deleteUser']],

    // PAYMENT MODES API 
    ['GET', 'modes', [$modeController, 'index']],
    //['GET', '/modes', [$modeController, 'getAllPaymentModes']],
    ['GET', 'modes/{id}', [$modeController, 'getPaymentModeById']],
    ['POST', 'modes/add', [$modeController, 'addPaymentMode']],
    ['POST', 'modes/update/{id}', [$modeController, 'updatePaymentMode']],
    ['POST', 'modes/delete/{id}', [$modeController, 'deletePaymentMode']],

    ['GET', 'places', ['Budget\Categories\PlacesController', 'index']],

    // DASHBORD API
    ['GET', '', [$homeController, 'index']],
    ['GET', 'dash', [$homeController, 'showMainDash']],
    ['GET', 'main_dash', [$homeController, 'showMainDash']],
    ['GET', 'income_dash', [$homeController, 'showIncomesDash']],
    ['GET', 'expense_dash', [$homeController, 'showExpensesDash']],
    ['GET', 'invest_dash', [$homeController, 'showInvestmentsDash']],
];
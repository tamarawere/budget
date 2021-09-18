<?php

declare(strict_types=1);

namespace Budget\Core;

$catController = 'Budget\Categories\CategoriesController';
$uomController = 'Budget\UOM\UomController';
$lotsController = 'Budget\LotUnits\LotUnitsController';
$modeController = 'Budget\PaymentModes\PaymentModesController';
$userController = 'Budget\Users\UsersController';
$itemGroupController = 'Budget\ItemGroups\ItemGroupsController';
$itemController = 'Budget\Items\ItemsController';
$homeController = 'Budget\Home\HomeController';

return [
    // CATEGORIES API
    ['GET', '/categories', [$catController, 'getAllCategories']],
    ['GET', '/categories/{id}', [$catController, 'getCategoryById']],
    ['POST', '/categories/add', [$catController, 'addCategory']],
    ['POST', '/categories/update/{id}', [$catController, 'updateCategory']],
    ['POST', '/categories/delete/{id}', [$catController, 'deleteCategory']],

    // UNITS OF MEASURE API
    ['GET', '/uom', [$uomController, 'getAllUoms']],
    ['GET', '/uom/{id}', [$uomController, 'getUomById']],
    ['POST', '/uom/add', [$uomController, 'addUom']],
    ['POST', '/uom/update/{id}', [$uomController, 'updateUom']],
    ['POST', '/uom/delete/{id}', [$uomController, 'deleteUom']],

    // UNITS OF MEASURE FOR PACKAGAES API
    ['GET', '/lotunits', [$lotsController, 'getAllLotUnits']],
    ['GET', '/lotunits/{id}', [$lotsController, 'getLotUnitById']],
    ['POST', '/lotunits/add', [$lotsController, 'addLotUnit']],
    ['POST', '/lotunits/update/{id}', [$lotsController, 'updateLotUnit']],
    ['POST', '/lotunits/delete/{id}', [$lotsController, 'deleteLotUnit']],

    // ITEMS GROUPS API
    ['GET', '/groups', [$itemGroupController, 'getAllItemGroups']],
    ['GET', '/groups/{id}', [$itemGroupController, 'getItemGroupById']],
    ['POST', '/groups/add', [$itemGroupController, 'addItemGroup']],
    ['POST', '/groups/update/{id}', [$itemGroupController, 'updateItemGroup']],
    ['POST', '/groups/delete/{id}', [$itemGroupController, 'deleteItemGroup']],

    // ITEMS API
    ['GET', '/items', [$itemController, 'getAllItems']],
    ['GET', '/items/{id}', [$itemController, 'getItemById']],
    ['POST', '/items/add', [$itemController, 'addItem']],
    ['POST', '/items/update/{id}', [$itemController, 'updateItem']],
    ['POST', '/items/delete/{id}', [$itemController, 'deleteItem']],

    // USERS API 
    ['GET', '/users', [$userController, 'getAllUsers']],
    ['GET', '/users/{id}', [$userController, 'getUserById']],
    ['POST', '/users/add', [$userController, 'addUser']],
    ['POST', '/users/update/{id}', [$userController, 'updateUser']],
    ['POST', '/users/delete/{id}', [$userController, 'deleteUser']],

    // PAYMENT MODES API 
    ['GET', '/modes', [$modeController, 'getAllPaymentModes']],
    ['GET', '/modes/{id}', [$modeController, 'getPaymentModeById']],
    ['POST', '/modes/add', [$modeController, 'addPaymentMode']],
    ['POST', '/modes/update/{id}', [$modeController, 'updatePaymentMode']],
    ['POST', '/modes/delete/{id}', [$modeController, 'deletePaymentMode']],

    ['GET', '/places', ['Budget\Categories\PlacesController', 'index']],

    ['GET', '', [$homeController, 'index']],
];
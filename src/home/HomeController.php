<?php

declare(strict_types=1);

namespace Budget\Home;

use Budget\Home\HomeModel;
use Budget\Core\AppController;

class HomeController
{
    private $model, $controller;

    public function __construct(AppController $controller, HomeModel $homeModel)
    {
        $this->model = $homeModel;
        $this->controller = $controller;
    }

    public function show()
    {
        return $this->model->getAllUsers();
    }

    public function index()
    {
        $data = [
            'name' => 'the namess',
        ];
        return $this->controller->setResponse('dash', $data);
    }

    public function showMainDash()
    {
        $data = [
            'name' => 'the namess',
        ];
        return $this->controller->setResponse('main_dash', $data);
    }

    public function showIncomesDash()
    {
        $data = [
            'name' => 'the namess',
        ];
        return $this->controller->setResponse('income_dash', $data);
    }

    public function showExpensesDash()
    {
        $data = [
            'name' => 'the namess',
        ];
        return $this->controller->setResponse('expense_dash', $data);
    }

    public function showInvestmentsDash()
    {
        $data = [
            'name' => 'the namess',
        ];
        return $this->controller->setResponse('invest_dash', $data);
    }


}

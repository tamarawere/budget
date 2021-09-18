<?php

declare(strict_types=1);

namespace Budget\PaymentModes;

use Budget\Core\AppController;

class PaymentModesController extends AppController
{
    private $paymentModesModel, $responseBody;

    public function __construct()
    {
        parent::__construct();
        $this->paymentModesModel = new PaymentModesModel();
        $this->responseBody = $this->appResponse->getBody();
    }

    public function addPaymentMode()
    {
        if (empty($this->appRequest->getParsedBody())) {
            $modeDetails = json_decode(file_get_contents("php://input"));
        } else {
            $modeDetails = $this->appRequest->getParsedBody();
        }

        if (!empty($modeDetails)) {
            try {
                $this->paymentModesModel->addPaymentMode((array)$modeDetails);
                $this->responseBody->write('everything ok');
                return $this->appResponse->withBody($this->responseBody);
            } catch (\Exception $e) {
                $this->responseBody->write($e->getMessage());
                return $this->appResponse->withBody($this->responseBody);
            }
        } else {
            $this->responseBody->write('have to write somethin');
            return $this->appResponse
                ->withBody($this->responseBody)
                ->withHeader('Content-Type', 'text/plain')
                ->withStatus(400, 'no payment mode data received');
        }
    }

    public function getAllPaymentModes()
    {
        $modes = $this->paymentModesModel->getAllPaymentModes();

        $modesArray = [];
        foreach ($modes as $mode) {
            array_push($modesArray, $mode);
        }
        $result = json_encode($modesArray);
        $this->responseBody->write($result);

        return $this->appResponse->withBody($this->responseBody);
    }

    public function getPaymentModeById(array $id)
    {
        $mode = $this->paymentModesModel->getPaymentModeById($id['id']);
        $result = json_encode($mode);
        $this->responseBody->write($result);
        return $this->appResponse->withBody($this->responseBody);
    }

    public function updatePaymentMode(array $id)
    {
        if (empty($this->appRequest->getParsedBody())) {
            $modeDetails = json_decode(file_get_contents("php://input"));
        } else {
            $modeDetails = $this->appRequest->getParsedBody();
        }
        // print_r($categoryDetails->category_name);

        $mode['mode_name'] = $modeDetails->mode_name;
        $mode['mode_desc'] = $modeDetails->mode_desc;

        try {
            $mode['mode_id'] = $id['id'];
            $this->paymentModesModel->updatePaymentMode($mode);
            $this->responseBody->write('user updated successfully');
            return $this->appResponse->withBody($this->responseBody);
        } catch (\Exception $e) {
            $this->responseBody->write($e->getMessage());
            return $this->appResponse
                ->withBody($this->responseBody)
                ->withHeader('Content-Type', 'text/plain')
                ->withStatus(400, 'DATABASE EXCEPTION');
        }
    }

    public function deletePaymentMode(array $id)
    {
        try {
            $this->paymentModesModel->deletePaymentMode($id['id']);
            $this->responseBody->write('Payment Mode deleted successfully');
            return $this->appResponse->withBody($this->responseBody);
        } catch (\Exception $e) {
            $this->responseBody->write($e->getMessage());
            return $this->appResponse->withBody($this->responseBody);
        }
    }
}

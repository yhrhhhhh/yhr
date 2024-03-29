<?php

namespace Example\Started\Controller\Json;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Index implements HttpGetActionInterface
{
    protected JsonFactory $resultJsonFactory;

    public function __construct(JsonFactory $resultJsonFactory)
    {
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $data = ['message' => 'Hello, Magento'];
        return $result->setData($data);
    }
}

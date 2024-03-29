<?php

namespace Example\Started\Controller\Query;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

class BlockTwo implements HttpGetActionInterface
{
    private PageFactory $pageFactory;

    public function __construct(PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
    }

    /**
     * @inheritDoc
     *
     * @link http://start.kyoye.com/started/query/blocktwo
     */
    public function execute()
    {
        return $this->pageFactory->create()
            ->addHandle('example_started_category_list');
    }
}

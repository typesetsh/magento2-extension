<?php
namespace Typesetsh\Pdf\Model;

use Magento\Framework\App;
use Magento\Framework\View;
use Magento\Store;

class StoreRenderer
{
    /**
     * @var View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @var Store\Model\App\Emulation
     */
    private $storeEmulation;

    /**
     * @var App\State
     */
    private $appState;

    /**
     * @param View\Result\PageFactory $resultPageFactory
     * @param Store\Model\App\Emulation $storeEmulation
     * @param App\State $appState
     */
    public function __construct(
        View\Result\PageFactory $resultPageFactory,
        Store\Model\App\Emulation $storeEmulation,
        App\State $appState
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->storeEmulation = $storeEmulation;
        $this->appState = $appState;
    }

    /**
     * @param $storeId
     * @param \Closure $action
     *
     * @return string
     * @throws \Exception
     */
    public function render($storeId, \Closure $action): string
    {
        $this->storeEmulation->startEnvironmentEmulation($storeId, 'frontend', true);

        $output = '';
        try {
            $output = $this->appState->emulateAreaCode('frontend', function () use ($action) {
                return $action();
            });
        } finally {
            $this->storeEmulation->stopEnvironmentEmulation();
        }

        return $output;
    }
}

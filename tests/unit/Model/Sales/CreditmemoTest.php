<?php
namespace Typesetsh\Pdf\Model\Sales;

use Typesetsh\Pdf;
use Magento\Framework\View;
use Magento\Sales;

/**
 * @covers \Typesetsh\Pdf\Model\Sales\Creditmemo
 */
class CreditmemoTest extends Pdf\TestCase
{
    /**
     * 1. $invoice must be set to invoice-block
     * 2. Page result requires handle pdf_default and pdf_sales_invoice
     *
     * @test
     */
    public function retrieve_pdf()
    {
        $layout = new class extends View\Layout
        {
            public $blocks = [];
            public $handles = [];
            public function __construct() {}
            public function getBlock($name)
            {
                return $this->blocks[$name] = new class extends Pdf\Block\Creditmemo {
                    public function __construct() {}
                };
            }
        };

        $pageFactory = new class($layout) extends View\Result\PageFactory {
            public $page;
            public $layout;
            public function __construct($layout) {
                $this->layout = $layout;
            }
            public function create($isView = false, array $arguments = [])
            {
                return $this->page = new class($this->layout) extends Pdf\Model\Result\Page {
                    public $handles = [];
                    public $layout;
                    public function __construct($layout){
                        $this->layout = $layout;
                    }
                    public function addHandle($handle) {
                        $this->layout->handles[] = $handle;
                    }
                    public function renderPdf(): string
                    {
                        return 'PDF';
                    }
                };
            }
        };


        $storeRenderer = new class extends Pdf\Model\StoreRenderer {
            public function __construct() {}
            public function render($storeId, \Closure $action): string
            {
                if ($storeId !== 1) {
                    throw new \Exception("Expected store id `1` got `$storeId`");
                }
                return $action();
            }
        };


        $htmlConvert = new class extends HtmlConverter
        {
            public $output = [];
            public function __construct() {}
            public function append(string $html)
            {
                if ($html !== 'PDF') {
                    throw new \Exception("Expected \$html `PDF` got `$html`");
                }
                $this->output[] = $html;
            }
            public function render(): \typesetsh\Result
            {
                return new \typesetsh\Result(function () {});
            }
        };


        $output = [];
        $htmlConvert->output = &$output;



        $creditmemo = $this->getObject(Sales\Model\Order\Creditmemo::class);
        $creditmemo->setOrder($this->createOrder());
        $creditmemo->setStoreId(1);


        $creditmemoPdf = new Creditmemo($pageFactory, $htmlConvert, $storeRenderer);
        $actual = $creditmemoPdf->getPdf([$creditmemo]);

        $this->assertInstanceOf(Pdf\Model\ZendPdf::class, $actual);
        $this->assertSame($creditmemo, $layout->blocks['creditmemo']->getCreditmemo());
        $this->assertSame(['pdf_default','pdf_sales_creditmemo'], $layout->handles);
    }
}

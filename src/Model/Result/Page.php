<?php
namespace Typesetsh\Pdf\Model\Result;

use Magento\Framework;

class Page extends Framework\View\Result\Page
{
    /**
     * @return string
     * @throws \Exception
     */
    public function renderPdf(): string
    {
        $config = $this->getConfig();

        $this->assign([
            'headContent' => $this->pageConfigRenderer->renderHeadContent(),
            'htmlAttributes' => $this->pageConfigRenderer->renderElementAttributes($config::ELEMENT_TYPE_HTML),
            'headAttributes' => $this->pageConfigRenderer->renderElementAttributes($config::ELEMENT_TYPE_HEAD),
            'bodyAttributes' => $this->pageConfigRenderer->renderElementAttributes($config::ELEMENT_TYPE_BODY),
        ]);

        $output = $this->getLayout()->getOutput();
        $this->assign('layoutContent', $output);

        $output = $this->renderPage();

        return $output;
    }
}

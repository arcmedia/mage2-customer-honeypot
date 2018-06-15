<?php

namespace Arcmedia\CustomerHoneyPot\Plugin;

use Closure;
use Exception;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\UrlFactory;
use Magento\Customer\Controller\Account\CreatePost;
use Arcmedia\CustomerHoneyPot\Helper\Data as Helper;

class CreatePostPlugin
{
    protected $urlModel;
    protected $resultRedirectFactory;
    protected $helper;

    /**
     * RestrictCustomerEmail constructor.
     * @param UrlFactory $urlFactory
     * @param RedirectFactory $redirectFactory
     * @param Helper $helper
     */
    public function __construct(
        UrlFactory $urlFactory,
        RedirectFactory $redirectFactory,
        Helper $helper
    )
    {
        $this->urlModel = $urlFactory->create();
        $this->resultRedirectFactory = $redirectFactory;
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Customer\Controller\Account\CreatePost $subject
     * @param \Closure $proceed
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundExecute(
        CreatePost $subject,
        Closure $proceed
    )
    {
        try {
            $this->helper->spamCheck();
        } catch (Exception $ex) {
            //Spam Attempt, just stop all code execution
            $line = "-- Spam Attempt --\n"
                    . "POST: ".print_r($_POST, true)."\n"
                    . "SERVER: ".print_r($_SERVER, true);
            file_put_contents('spam.log', $line . PHP_EOL, FILE_APPEND);
            die();
        }
        
        return $proceed();
    }
}
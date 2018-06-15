<?php

namespace Arcmedia\CustomerHoneyPot\Helper;

use Exception;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{

    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }
    
    public function spamCheck()
    {
        $this->blockNamesTooLong();
        $this->blockRussionNames();
        $this->blockChineseNames();
    }
    
    protected function blockNamesTooLong()
    {
        $email = $_POST['email'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        if (strlen($firstname) > 30) {
            throw new Exception('fistname is too long');
        }
        if (strlen($lastname) > 30) {
            throw new Exception('lastname is too long');
        }
    }
    
    protected function blockRussionNames()
    {
        $email = $_POST['email'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        if (strpos($email, "@mail.ru")) {
            throw new Exception('Mail.ru e-mail submitted, not permitted');
        }
        if (preg_match('/[А-Яа-яЁё]/u', $firstname)) {
            throw new Exception('Russion characters in firstname');
        }
        if (preg_match('/[А-Яа-яЁё]/u', $lastname)) {
            throw new Exception('Russion characters in lastname');
        }
    }
    
    protected function blockChineseNames()
    {
        $email = $_POST['email'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        if (strpos($email, "@qq.com")) {
            throw new Exception('qq.com e-mail submitted, not permitted');
        }
        if (preg_match("/\p{Han}+/u", $firstname)) {
            throw new Exception('Chinese characters in firstname');
        }
        if (preg_match("/\p{Han}+/u", $lastname)) {
            throw new Exception('Chinese characters in lastname');
        }
    }
}
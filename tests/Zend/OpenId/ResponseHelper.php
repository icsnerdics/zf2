<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_OpenId
 */

namespace ZendTest\OpenId;

use Zend\Http\Response;
use Zend\OpenId\OpenId;

/**
 * Zend_OpenId
 */

/**
 * @todo code should be moved into test class
 */
OpenId::$exitOnRedirect = false;

/**
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage UnitTests
 */
class ResponseHelper extends Response
{
    private $_canSendHeaders;

    public function __construct($canSendHeaders)
    {
        $this->_canSendHeaders = $canSendHeaders;
    }

    public function canSendHeaders($throw = false)
    {
        return $this->_canSendHeaders;
    }

    public function sendResponse()
    {
    }
}


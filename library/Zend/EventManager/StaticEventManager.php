<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_EventManager
 */

namespace Zend\EventManager;

use Zend\Stdlib\CallbackHandler;

/**
 * Static version of EventManager
 *
 * @category   Zend
 * @package    Zend_EventManager
 */
class StaticEventManager extends SharedEventManager
{
    /**
     * @var StaticEventManager
     */
    protected static $instance;

    /**
     * Singleton
     *
     * @return void
     */
    protected function __construct()
    {
    }

    /**
     * Singleton
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Retrieve instance
     *
     * @return StaticEventManager
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Reset the singleton instance
     *
     * @return void
     */
    public static function resetInstance()
    {
        static::$instance = null;
    }
}

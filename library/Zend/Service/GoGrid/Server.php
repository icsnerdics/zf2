<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */

namespace Zend\Service\GoGrid;

class Server extends AbstractGoGrid
{
    const API_GRID_SERVER_LIST   = 'grid/server/list';
    const API_GRID_SERVER_GET    = 'grid/server/get';
    const API_GRID_SERVER_ADD    = 'grid/server/add';
    const API_GRID_SERVER_EDIT   = 'grid/server/edit';
    const API_GRID_SERVER_DELETE = 'grid/server/delete';
    const API_GRID_SERVER_POWER  = 'grid/server/power';
    const API_POWER_START        = 'start';
    const API_POWER_STOP         = 'stop';
    const API_POWER_RESTART      = 'restart';
    /**
     * Get Server List
     *
     * This call will list all the servers in the system.
     *
     * @param array $options
     * @return ObjectList
     */
    public function getList($options=array())
    {
        $result = parent::_call(self::API_GRID_SERVER_LIST, $options);
        return new ObjectList($result);
    }
    /**
     * Get Server
     *
     * This call will retrieve one or many server objects from your list of servers
     *
     * @param string|array $server
     * @return ObjectList
     * @throws Exception\InvalidArgumentException
     */
    public function get($server)
    {
        if (empty($server)) {
            throw new Exception\InvalidArgumentException("The server.get API needs a id/name server parameter");
        }
        $options=array();
        $options['server']= $server;
        $result= $this->_call(self::API_GRID_SERVER_GET, $options);
        return new ObjectList($result);
    }
    /**
     * Add Server
     *
     * This call will add a single server object to your grid.
     * To create an image sandbox pass the optional isSandbox parameter to true.
     * If isSandbox is set to true, the request parameter server.ram is ignored and non-mandatory.
     *
     * @param string $name
     * @param string $image
     * @param string $ram
     * @param string $ip
     * @return ObjectList
     * @throws Exception\InvalidArgumentException
     */
    public function add($name,$image,$ram,$ip, $options=array())
    {
        if (empty($name) || strlen($name)>20) {
            throw new Exception\InvalidArgumentException("You must specify the name of the server in a string of 20 character max.");
        }
        if (empty($image)) {
            throw new Exception\InvalidArgumentException("You must specify the server image's ID or name");
        }
        if (empty($ram) && (empty($options) || !$options['isSandbox'])) {
            throw new Exception\InvalidArgumentException("You must specify the ID or name of the desired RAM option");
        }
        if (empty($ip)) {
            throw new Exception\InvalidArgumentException("You must specify the IP address of the server");
        }
        $options['name']= $name;
        $options['image']= $image;
        if (!empty($ram)) {
            $options['server.ram']= $ram;
        }
        $options['ip']= $ip;
        $result= $this->_call(self::API_GRID_SERVER_ADD, $options);
        return new ObjectList($result);
    }
    /**
     * Edit Server
     *
     * This call will edit a single server object in your grid.
     * You can use this call to edit a server's:
     * RAM (Upgrade RAM)
     * Server Type (Change between Web/App Server and Database Server)
     * Description (Change freeform text description)
     *
     * @param string|array $server
     * @return ObjectList
     * @throws Exception\InvalidArgumentException
     */
    public function edit($server,$options=array())
    {
        if (empty($server)) {
            throw new Exception\InvalidArgumentException("The server.edit API needs a id/name server parameters");
        }
        $options['server']= $server;
        $result= $this->_call(self::API_GRID_SERVER_EDIT, $options);
        return new ObjectList($result);
    }
    /**
     * Power Server
     *
     * This call will issue a power command to a server object in your grid.
     * Supported power commands are: start, stop, and restart
     *
     * @param string $server
     * @param string $power
     * @return ObjectList
     * @throws Exception\InvalidArgumentException
     */
    public function power($server,$power)
    {
        if (empty($server)) {
            throw new Exception\InvalidArgumentException("The server.power API needs a id/name server parameter");
        }
        $power=strtolower($power);
        if (empty($power) || !in_array($power,array(self::API_POWER_START,self::API_POWER_STOP,self::API_POWER_RESTART))) {
            throw new Exception\InvalidArgumentException("The server.power API needs the power parameter (start,stop, or restart)");
        }
        $options=array();
        $options['server']= $server;
        $options['power']= $power;
        $result= $this->_call(self::API_GRID_SERVER_POWER, $options);
        return new ObjectList($result);
    }
    /**
     * Start a server
     *
     * @param string $server
     * @return ObjectList
     */
    public function start($server)
    {
       return $this->power($server,self::API_POWER_START);
    }
    /**
     * Stop a server
     *
     * @param string $server
     * @return ObjectList
     */
    public function stop($server)
    {
       return $this->power($server,self::API_POWER_STOP);
    }
    /**
     * Restart a server
     *
     * @param string $server
     * @return ObjectList
     */
    public function restart($server)
    {
       return $this->power($server,self::API_POWER_RESTART);
    }
    /**
     * Delete a server
     *
     * @param string $server
     * @return ObjectList
     * @throws Exception\InvalidArgumentException
     */
    public function delete($server)
    {
        if (empty($server)) {
            throw new Exception\InvalidArgumentException("The server.delete API needs an id/name server parameter");
        }
        $options=array();
        $options['server']= $server;
        $result= $this->_call(self::API_GRID_SERVER_DELETE, $options);
        return new ObjectList($result);
    }
}

<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage Apns
 */

namespace ZendService\Apple\Apns\Client;

use ZendService\Apple\Exception;
use ZendService\Apple\Apns\Message as ApnsMessage;
use ZendService\Apple\Apns\Response\Message as MessageResponse;

/**
 * Message Client
 *
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage Apns
 */
class Message extends AbstractClient
{
    /**
     * APNS URIs
     * @var array
     */
    protected $uris = array(
        'ssl://gateway.sandbox.push.apple.com:2195',
        'ssl://gateway.push.apple.com:2195',
    );

    /**
     * Send Message
     * 
     * 
     * @param  ZendService\Apple\Apns\Message          $message
     * @param int $waitTime in microseconds. The time during the script stops before reading response sent back by Apple serveurs
     * @throws Exception\RuntimeException
     * @return \ZendService\Apple\Apns\Response\Message
     */
    public function send(ApnsMessage $message, $waitTime = 0)
    {
        if (!$this->isConnected()) {
            throw new Exception\RuntimeException('You must first open the connection by calling open()');
        }

        $ret = $this->write($message->getPayloadJson());
        if ($ret === false) {
            throw new Exception\RuntimeException('Server is unavailable; please retry later');
        }

        if($waitTime > 0){
            usleep($waitTime);
        }
        
        return new MessageResponse($this->read());
    }
}

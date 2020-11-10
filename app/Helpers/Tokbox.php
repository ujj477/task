<?php

/**
 * Tokbox Library for CodeIgniter
 *
 * 
 * @package     Laravel
 * @category    Helpers
 * @subcategory Tokbox
 * @author      Ramayan prasad
 * @version     1.0
 * @Help_Url    #
 * 
 */

        
//Include the Tokbox api php libraries
//echo __DIR__;
//require __DIR__.'/vendor/autoload.php';
#include_once APPPATH."third_party/Tokbox/vendor/autoload.php";
    
namespace App\Helpers;
    
use OpenTok\OpenTok;
//use OpenTok\Session;
use OpenTok\Role;
use OpenTok\MediaMode;
//use OpenTok\ArchiveMode;


        
class Tokbox {

    private $apiKey;
    private $apiSecret;
    private $opentok;
    private $currentSession;

    public function __construct() {
        
        // Pankaj Account
        $this->apiKey = env('TOKBOX_API_KEY');
        $this->apiSecret = env('TOKBOX_API_SECRET');
        
        $this->opentok = new OpenTok($this->apiKey, $this->apiSecret);
    }
    
    
    
    /* @class : Tokbox
     * $function : createSession
     * @author : Ramayan prasad
     * @help url : 
     * @description : Create session
     */
    public function createSession() {
        
        // Create a session that attempts to use peer-to-peer streaming:
        $this->currentSession = $this->opentok->createSession(array( 'mediaMode' => MediaMode::RELAYED ));
        //return $session;
    }
    
    
    
    /* @class : Tokbox
     * $function : getSession
     * @author : Ramayan prasad
     * @help url : 
     * @description : Get session
     */
    public function getSession() {
        $this->createSession();
        return $this->currentSession->getSessionId();
    }
    
    
    
    /* @class : Tokbox
     * $function : generateToken
     * @author : Ramayan prasad
     * @help url : 
     * @description : Generate Token
     */
    public function generateToken() {
        //echo $this->currentSession->getSessionId();echo '<br/>';
        $tokenExpirationTime = $this->tokenExpirationTime();
        $tokenId = $this->currentSession->generateToken(array(
                        'role' => Role::MODERATOR,
                        //'expireTime' => time() + (7 * 24 * 60 * 60), // in one week
                        'expireTime' => $tokenExpirationTime, // in one yar
                        'data' => 'name=Ram'
                    ));
        
        return array(
            'tokenExpirationTime' => $tokenExpirationTime,
            'tokenId' => $tokenId
        );
    }
    
    
    
    /* @class : Tokbox
     * $function : tokenExpirationTime
     * @author : Ramayan prasad
     * @help url : 
     * @description : Token expiration time
     */
    private function tokenExpirationTime() {
        return (time() + (30 * 24 * 60 * 60)); // for one year
    }
}

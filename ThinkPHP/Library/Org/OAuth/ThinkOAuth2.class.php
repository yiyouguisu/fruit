<?php
/**
 * @category ORG
 * @package ORG
 * @author Leyteris
 * @version 2012.3.16
 */

// OAUTH2_DB_DSN  数据库连接DSN
// OAUTH2_CODES_TABLE 服务器表名称
// OAUTH2_CLIENTS_TABLE 客户端表名称
// OAUTH2_TOKEN_TABLE 验证码表名称
namespace Org\OAuth;
use Think\Db;
import("ORG.OAuth.OAuth2");

class ThinkOAuth2 extends OAuth2 {

	private $db;
	private $table;

	/**
	 * 构造
	 */
	public function __construct() {
		parent::__construct();
		$this -> db = Db::getInstance(C('OAUTH2_DB_DSN'));
		$this -> table = array(
			'auth_codes'=>C('OAUTH2_CODES_TABLE'),
			'clients'=>C('OAUTH2_CLIENTS_TABLE'),
			'tokens'=>C('OAUTH2_TOKEN_TABLE')
		);
	}

	/**
	 * 析构
	 */
	function __destruct() {
		$this->db = NULL; // Release db connection
	}

	private function handleException($e) {
		echo "Database error: " . $e->getMessage();
		exit;
	}

	/**
	 *
	 * 增加client
	 * @param string $client_id
	 * @param string $client_secret
	 * @param string $redirect_uri
	 */
	public function addClient($client_id, $client_secret, $redirect_uri, $appname) {
		$time = time();
		$sql = "INSERT INTO {$this -> table['clients']} (client_id, client_secret, redirect_uri, create_time, appname) VALUES ('{$client_id}', '{$client_secret}', '{$redirect_uri}','{$time}','{$appname})";
		$this -> db -> execute($sql);
	}

	/**
	 * Implements OAuth2::checkClientCredentials()
	 * @see OAuth2::checkClientCredentials()
	 */
	public function checkClientCredentials($client_id, $client_secret = NULL) {

		$sql = "SELECT client_secret FROM {$this -> table['clients']} WHERE client_id = '{$client_id}'";
		$result = $this -> db -> query($sql);
		if ($client_secret === NULL) {
			return $result !== FALSE;
		}
		//Log::write("checkClientCredentials : ".$result);
		//Log::write("checkClientCredentials : ".$result[0]);
		//Log::write("checkClientCredentials : ".$result[0]["client_secret"]);

		return $result[0]["client_secret"] == $client_secret;

	}

	/**
	 * Implements OAuth2::getRedirectUri().
	 * @see OAuth2::getRedirectUri()
	 */
	public function getRedirectUri($client_id) {
		$sql = "SELECT redirect_uri FROM {$this -> table['clients']} WHERE client_id = '{$client_id}'";
		$result = $this -> db -> query($sql);
		if ($result === FALSE) {
			return FALSE;
		}
		//Log::write("getRedirectUri : ".$result);
		//Log::write("getRedirectUri : ".$result[0]);
		//Log::write("getRedirectUri : ".$result[0]["redirect_uri"]);

		return isset($result[0]["redirect_uri"]) && $result[0]["redirect_uri"] ? $result[0]["redirect_uri"] : NULL;

	}
	public function getClientID($client_secret,$appname) {
      $sql = "SELECT `client_id` FROM `clients` WHERE `client_secret` = '{$client_secret}' AND `appname` = '{$appname}' ";
      $result = $this -> db -> query($sql);

      if ($result === FALSE){
      	return FALSE;
      }
      return isset($result['client_id']) && $result['client_id'] ? $result['client_id'] : NULL;
	    
	}
	/**
	 * Implements OAuth2::getAccessToken().
	 * @see OAuth2::getAccessToken()
	 */
	public function getAccessToken($access_token) {
		$sql = "SELECT client_id, uid, access_token, refresh_token, expires_in, scope FROM {$this -> table['tokens']} WHERE access_token = '{$access_token}'";
		$result = $this -> db -> query($sql);
		//Log::write("getAccessToken : ".$result);
		//Log::write("getAccessToken : ".$result[0]);

		return $result !== FALSE ? $result : NULL;

	}

	/**
	 * Implements OAuth2::setAccessToken().
	 * @see OAuth2::setAccessToken()
	 */
	public function setAccessToken($access_token, $uid, $client_id, $refresh_token, $expires, $scope = NULL) {
		$sql = "INSERT INTO {$this -> table['tokens']} (access_token, uid, client_id, refresh_token, expires_in, scope) VALUES ('{$access_token}', '{$uid}', '{$client_id}', '{$refresh_token}', '{$expires}', '{$scope}')";
		$this -> db -> execute($sql);
	}

	/**
	 * Overrides OAuth2::getSupportedGrantTypes().
	 * @see OAuth2::getSupportedGrantTypes()
	 */
	public function getSupportedGrantTypes() {
		return array(
			OAUTH2_GRANT_TYPE_AUTH_CODE
		);
	}

	/**
	 * Overrides OAuth2::getAuthCode().
	 * @see OAuth2::getAuthCode()
	 */
	public function getAuthCode($code) {
		$sql = "SELECT code, client_id, redirect_uri, expires, scope FROM {$this -> table['auth_codes']} WHERE code = '{$code}'";
		$result = $this -> db -> query($sql);
		//Log::write("getAuthcode : ".$result);
		//Log::write("getAuthcode : ".$result[0]);
		//Log::write("getAuthcode : ".$result[0]["code"]);
		return $result !== FALSE ? $result[0] : NULL;
	}

	/**
	 * Overrides OAuth2::setAuthCode().
	 * @see OAuth2::setAuthCode()
	 */
	public function setAuthCode($code, $client_id, $redirect_uri, $expires, $uid, $scope = NULL) {
		//$time = time();
		$sql = "INSERT INTO {$this -> table['auth_codes']} (code,  client_id, redirect_uri, expires, uid, scope) VALUES ('{$code}', '{$client_id}', '{$redirect_uri}', '{$expires}', '{$uid}', '{$scope}')";
		$result = $this -> db -> execute($sql);
    }
  
    public function checkUser($code){
	  	$sql = "SELECT uid FROM {$this -> table['auth_codes']} WHERE code = '{$code}'";
	  	$result = $this -> db -> query($sql);
	  	return $result !== FALSE ? $result[0] : NULL;
    }

  /**
   * Overrides OAuth2::checkUserCredentials().
   * @see OAuth2::checkUserCredentials()
   */
  public function checkUserCredentials($client_id, $username, $password){
  	return TRUE;
  }
}
<?php

/*
* Register Plugin in the Frontcontroller during application bootstraping
*
* protected function _initPlugins(){
*		$this->bootstrap('frontController');
*		$frontController = Zend_Controller_Front::getInstance();
*		$frontController->registerPlugin( new App_Plugin_SslRedirect( ['modules' => ['default', 'admin'], 'exit_now' => false] ) );
* }
*/

class App_Plugin_SslRedirect extends Zend_Controller_Plugin_Abstract{
	
	private $_modules = null;
	private $_exit_now = false;
	
	/**
	* Force http request to https
	*
	* The modules option sets redirect for specific module, otherwise all requests are forced
	* The exit_now option exits the script execution immediately
	*
	* @param array|null $option 
	*/
	public function __construct( $options = [] ){
		
		if( isset( $options['modules'] ) ){
			$modules = $options['modules'];
			if( is_string( $modules ) ) $modules = (array) $modules;
			if( is_array( $modules ) ) $this->_modules = $modules;
		}
		
		if( isset( $options['exit_now'] ) ) $this->_exit_now = (bool) $options['exit_now'];
	}
	
	public function routeShutdown( Zend_Controller_Request_Abstract $request ){
		if( APPLICATION_ENV != 'production' ) return;
		
		if( $this->_modules && ! in_array( $request->getModuleName(), $this->_modules  ) ) return;
		
		$serverUrl =  new Zend_View_Helper_ServerUrl();
		if( $serverUrl->getScheme() != 'https' ){
			/** @var Zend_Controller_Request_Http */
			$request = $this->getRequest();
			if( ! $request instanceof Zend_Controller_Request_Http ) return;
			
			$url = 'https://' .  $serverUrl->getHost() . $request->getRequestUri();
			$redirector = new Zend_Controller_Action_Helper_Redirector();
			$redirector->setExit( $this->_exit_now );
			$redirector->gotoUrl( $url );
		}
		
	}
	
}

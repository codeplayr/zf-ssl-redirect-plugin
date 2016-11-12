### SSL Redirect Plugin
------------------

Zend Framework plugin to redirect http requests for specific modules or all to https

##### Usage
------------------

Register Plugin in the Frontcontroller during application bootstraping

```php
protected function _initPlugins(){
		$this->bootstrap('frontController');
		$frontController = Zend_Controller_Front::getInstance();
		$plugin = new App_Plugin_SslRedirect( ['modules' => ['default', 'admin'], 'exit_now' => false] );
		$frontController->registerPlugin( $plugin );
}
```

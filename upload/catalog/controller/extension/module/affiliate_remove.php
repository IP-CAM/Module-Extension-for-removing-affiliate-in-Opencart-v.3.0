<?php
/**
 * @package		OpenCart Affiliate Remove Module
 * @version		1.0
 * @author		codefishcode
 * @link		https://github.com/codefishcode/oc-affiliate-remove-module
*/

class ControllerExtensionModuleAffiliateRemove extends Controller {
	protected $error = array();

    protected $enabled;

	public function __construct($registry) {
		parent::__construct($registry);
        $this->enabled = $this->config->get('module_affiliate_remove_status');
    }

    public function eventAffiliateRemoveRedirectView(&$route, &$data) {
        if($this->enabled == 1) {
            $route = 'extension/module/affiliate_remove/goHome';
        }
    }


    public function eventFooterAffiliateRemoveView(&$view, &$data){
        if($this->enabled == 1) {
            $view = str_replace('common/footer', 'extension/module/affiliate_remove/footer', $view);
        }
    }

    public function eventAccountAffiliateRemoveView(&$view, &$data){
        if($this->enabled == 1) {
            $view = str_replace('account/account', 'extension/module/affiliate_remove/account', $view);
        }
    }

    

    public function goHome(){
        $this->response->redirect('/',302);
    }

}
<?php
/**
 * @package		OpenCart Affiliate Remove Module
 * @version		1.0
 * @author		codefishcode
 * @link		https://github.com/codefishcode/oc-affiliate-remove-module
*/


class ControllerExtensionModuleAffiliateRemove extends Controller {
	protected $error = array();

	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->language('extension/module/affiliate_remove');
	}
	

	public function index() {

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_affiliate_remove', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/affiliate_remove', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/affiliate_remove', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_affiliate_remove_status'])) {
			$data['module_affiliate_remove_status'] = $this->request->post['module_affiliate_remove_status'];
		} else {
			$data['module_affiliate_remove_status'] = $this->config->get('module_affiliate_remove_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/affiliate_remove/affiliate_remove_setting', $data));

	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/affiliate_remove')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	
	public function install() {
		if (!$this->user->hasPermission('modify', 'extension/extension/module')) {
			return;
		}

		$this->load->model('setting/module');

		$this->load->model('setting/event');
		$this->model_setting_event->addEvent('module_affiliate_remove_view_footer_affiliate', 'catalog/view/common/footer/before', 'extension/module/affiliate_remove/eventFooterAffiliateRemoveView');
		$this->model_setting_event->addEvent('module_affiliate_remove_view_account_affiliate', 'catalog/view/account/account/before', 'extension/module/affiliate_remove/eventAccountAffiliateRemoveView');
		$this->model_setting_event->addEvent('module_affiliate_remove_account_controller_affiliate', 'catalog/controller/account/affiliate/*/before', 'extension/module/affiliate_remove/eventAffiliateRemoveRedirectView');
		$this->model_setting_event->addEvent('module_affiliate_remove_controller_affiliate', 'catalog/controller/affiliate/*/before', 'extension/module/affiliate_remove/eventAffiliateRemoveRedirectView');

	}
	
	public function uninstall() {
		if (!$this->user->hasPermission('modify', 'extension/extension/module')) {
			return;
		}

		$this->load->model('setting/module');
		$this->model_setting_module->deleteModulesByCode('module_affiliate_remove');
		
		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('module_affiliate_remove_view_footer_affiliate');
		$this->model_setting_event->deleteEventByCode('module_affiliate_remove_account_controller_affiliate');
		$this->model_setting_event->deleteEventByCode('module_affiliate_remove_controller_affiliate');

	}

}
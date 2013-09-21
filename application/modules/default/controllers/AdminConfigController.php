<?php
class AdminConfigController extends Zendvn_Controller_Action{
	//Mang tham so nhan duoc khi mot Action chay
	protected $_arrParam;
	
	//Duong dan cua Controller
	protected $_currentController;
	
	//Duong dan cua Action chinh
	protected $_actionMain;

	
	public function init(){
		//Mang tham so nhan duoc khi mot Action chay
		$this->_arrParam = $this->_request->getParams();
		
		//Duong dan cua Controller
		$this->_currentController = '/' . $this->_arrParam['module'] . '/' 
									. $this->_arrParam['controller'];
		
		//Duong dan cua Action chinh
		$this->_actionMain = '/' . $this->_arrParam['module'] . '/' 
								. $this->_arrParam['controller'] . '/index';
		
		//Truyen ra ngoai view
		$this->view->arrParam = $this->_arrParam;
		$this->view->currentController = $this->_currentController;
		$this->view->actionMain = $this->_actionMain;
		
		$siteConfig = Zend_Registry::get('siteConfig');
		$template_path = TEMPLATE_PATH . "/admin/" . $siteConfig['template']['admin'];
		$this->loadTemplate($template_path, 'template.ini', 'template');
	}
	
	public function indexAction(){
		$this->view->Title = 'Cấu hình hệ thống';
		$this->view->headTitle($this->view->Title, true);
		
		$tblLanguages = new Default_Model_Language();
		$this->view->slbLanguages = $tblLanguages->itemInSelectbox($this->_arrParam);
		
		$filename = CONFIG_PATH . '/config_system.ini';
		
		$section = 'site-settings';
		$siteSettings = new Zend_Config_Ini($filename, $section);
		$this->view->siteSettings = $siteSettings->toArray();
		
		if($this->_request->isPost()){
			$config = new Zend_Config_Ini($filename,$section,true);
			
			$config->config_site->sitename 			= $this->_arrParam['config_sitename'];
			$config->config_site->offline 			= $this->_arrParam['config_offline'];
			$config->config_site->offline_message 	= $this->_arrParam['config_offline_message'];
			$config->config_site->site_domain 		= $this->_arrParam['config_site_domain'];
			$config->config_site->site_url 			= $this->_arrParam['config_site_url'];
			$config->config_site->site_dir 			= $this->_arrParam['config_site_dir'];
			$config->config_site->site_logo 		= $this->_arrParam['config_site_logo'];
			$config->config_site->site_language_default 	= $this->_arrParam['config_site_language_default'];
			$config->config_site->site_cache 		= $this->_arrParam['config_site_cache'];
			
			$config->config_meta->description 		= $this->_arrParam['meta_description'];
			$config->config_meta->keywords 			= $this->_arrParam['meta_keywords'];
			$config->config_meta->refresh 			= $this->_arrParam['meta_refresh'];
			$config->config_meta->robots 			= $this->_arrParam['meta_robots'];
			$config->config_meta->language 			= $this->_arrParam['meta_language'];
			$config->config_meta->content_language	= $this->_arrParam['meta_content_language'];
			$config->config_meta->author 			= $this->_arrParam['meta_author'];
			$config->config_meta->revisit_after		= $this->_arrParam['meta_revisit_after'];
			$config->config_meta->copyright 		= $this->_arrParam['meta_copyright'];
			$config->config_meta->classification 	= $this->_arrParam['meta_classification'];
			
			$config->config_company->name 		= $this->_arrParam['company_name'];
			$config->config_company->address 	= $this->_arrParam['company_address'];
			$config->config_company->tell 		= $this->_arrParam['company_tell'];
			$config->config_company->fax 		= $this->_arrParam['company_fax'];
			$config->config_company->hotline 	= $this->_arrParam['company_hotline'];
			$config->config_company->email 		= $this->_arrParam['company_email'];
			$config->config_company->slogan 	= $this->_arrParam['company_slogan'];
			$config->config_company->yahoo	 	= $this->_arrParam['company_yahoo'];
			$config->config_company->skype	 	= $this->_arrParam['company_skype'];
			$config->config_company->facebook 	= $this->_arrParam['company_facebook'];
			$config->config_company->google 	= $this->_arrParam['company_google'];
			$config->config_company->youtube 	= $this->_arrParam['company_youtube'];
			$config->config_company->twitter 	= $this->_arrParam['company_twitter'];
			
			$config->config_mail->status 		= $this->_arrParam['mail_status'];
			$config->config_mail->mailer 		= $this->_arrParam['mail_mailer'];
			$config->config_mail->mailfrom 		= $this->_arrParam['mail_mailfrom'];
			$config->config_mail->fromname 		= $this->_arrParam['mail_fromname'];
			$config->config_mail->tomail 		= $this->_arrParam['mail_tomail'];
			$config->config_mail->smtpauth 		= $this->_arrParam['mail_smtpauth'];
			$config->config_mail->smtpsecure 	= $this->_arrParam['mail_smtpsecure'];
			$config->config_mail->smtpport 		= $this->_arrParam['mail_smtpport'];
			$config->config_mail->smtpuser 		= $this->_arrParam['mail_smtpuser'];
			$config->config_mail->smtppass 		= $this->_arrParam['mail_smtppass'];
			$config->config_mail->smtphost 		= $this->_arrParam['mail_smtphost'];
			
			$config->config_ftp->status 		= $this->_arrParam['ftp_status'];
			$config->config_ftp->host	 		= $this->_arrParam['ftp_host'];
			$config->config_ftp->port	 		= $this->_arrParam['ftp_port'];
			$config->config_ftp->username 		= $this->_arrParam['ftp_username'];
			$config->config_ftp->password 		= $this->_arrParam['ftp_password'];
			
			$config->config_google->analytics 		= $this->_arrParam['google_analytics'];
			$config->config_google->webmaster_tools = $this->_arrParam['google_webmaster_tools'];
			
			$write = new Zend_Config_Writer_Ini();
			
			if($this->_arrParam['ftp_status'] == 1){
				$filename = '/application/configs/config_system.ini';
				
				$ftp_server = $this->_arrParam['ftp_host'];
				$ftp_user_name = $this->_arrParam['ftp_username'];
				$ftp_user_pass = $this->_arrParam['ftp_password'];
				
				$conn_id = ftp_connect($ftp_server);
				$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

				if (ftp_chmod($conn_id, 0666, $filename) !== false) {
					$write->write($filename,$config);
				}
				
				@ftp_chmod($conn_id, 0400, $filename);
				
				// close the connection
				ftp_close($conn_id);
			}else{
				$write->write($filename,$config);
			}
			
			$language = new Zend_Session_Namespace('language');
			$language->unsetAll();
		
			$this->_redirect($this->_actionMain . '/type/save');
		}
	}
}




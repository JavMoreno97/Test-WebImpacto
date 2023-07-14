<?php
/**
* 2007-2023 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Weatherreport extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'weatherreport';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Javier Moreno';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Weather Report');
        $this->description = $this->l('Display information about the customer local weather in your shop header.');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
      Configuration::updateValue('WeatherReportAPIKEY', '');

      return parent::install() &&
        $this->registerHook('header') &&
        $this->registerHook('displayNavWeather');
    }

    public function uninstall()
    {
      Configuration::deleteByName('WeatherReportAPIKEY');
      return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitWeatherreportModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('APIKEY', Configuration::get('WeatherReportAPIKEY'));

        return $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitWeatherreportModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-key"></i>',
                        'desc' => sprintf(
                          $this->l('Enter you API key for %s'),
                          'https://www.weatherapi.com/',
                        ),
                        'name' => 'WeatherReportAPIKEY',
                        'label' => $this->l('API Key'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'WeatherReportAPIKEY' => Configuration::get('WeatherReportAPIKEY')
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    /**
     * Display actual weather information at the center of the navbar
     */
    public function hookDisplayNavWeather($params)
    {
      $curl = curl_init();

      $customerIP = $this->getCustomerIp();
      $APIKEY = Configuration::get('WeatherReportAPIKEY');
      
      if(!empty($APIKEY)){
        // Set new data
        $data = array(
          'key' => $APIKEY,
          'q' => $customerIP,
          'aqi' => 'no',
          'lang' => $this->context->language->iso_code
        );

        // Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.weatherapi.com/v1/current.json?' . http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
        ));
      
        // Execute the cURL request
        try{
          $response = curl_exec($curl);
        }
        catch (Exception $e) {
          echo "Error: " . $e->getMessage() . "\n";
        }

        $response = json_decode($response, true);

        $this->context->smarty->assign([
          'location' => $response['location'],
          'weather' => $response['current']
        ]);

        return $this->context->smarty->fetch($this->local_path.'views/templates/hook/weathermarquee.tpl');
      }
    }

    public function getCustomerIp()
    {
      if(!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
      elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
      elseif(!empty($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '::1')
        return $_SERVER['REMOTE_ADDR'];
      else
        return 'auto:ip';
    }
}

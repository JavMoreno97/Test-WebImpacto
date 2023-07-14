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

require_once dirname(__FILE__) . '/classes/CustomProductTextModel.php';

class Customproducttext extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'customproducttext';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Javier Moreno';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Custom Product Text');
        $this->description = $this->l('Display a custom text for each product inside the \"product information\" section.');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayProductAdditionalInfo') && 
            $this->registerHook('displayAdminProductsExtra') &&
            $this->registerHook('actionProductUpdate') &&
            $this->updatePosition(Hook::getIdByName('displayProductAdditionalInfo'), false, 1);
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        return true;
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
     * Add custom content to the "Product information" section
     */
    public function hookDisplayProductAdditionalInfo($params)
    {
      $id_product = (int) $params['product']['id_product'];
      $id_lang = $this->context->language->id;
      $customText = CustomProductTextModel::getCustomProductTextDisplayLang($id_product, $id_lang);

      if(!empty($customText)){
        $this->context->smarty->assign([
          'customText' => $customText
        ]);
        
        return $this->context->smarty->fetch($this->local_path.'views/templates/hook/customtext.tpl');
      }
    }

    /**
     * Add extra configuration fields to the BackOffice Product page
     */
    public function hookDisplayAdminProductsExtra($params){
      $customTextLang = CustomProductTextModel::getCustomProductTextByProductID($params['id_product']);

      $this->context->smarty->assign([
        'customTextLang' => $customTextLang,
        'languages'    => $this->context->controller->getLanguages(),
        'id_language'  => $this->context->language->id,
      ]);

      return $this->display(__FILE__,'views/templates/admin/form.tpl');
    }

    /**
     * This hook is fired after a product is updated.
     */
    public function hookActionProductUpdate($params){
      $id_product = Tools::getValue('id_product');
      $customText = Tools::getValue('customtext');

      $customTextObject = new CustomProductTextModel(CustomProductTextModel::getCustomProductTextIDByProductID($id_product));
      $customTextObject->id_product = $id_product;
      $customTextObject->custom_text = $customText;
      
      $customTextObject->save();
    }
}


<?php

class CustomProductTextModel extends ObjectModel
{
	public $id_product;
	public $custom_text;

	public static $definition = array(
		'table' => 'customproducttext',
		'primary' => 'id_customproducttext',
		'multilang' => true,
		'fields' => array(
			'id_product'    		=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'custom_text' 			  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'lang' => true)
		)
	);

	public	function __construct($id_customproducttext = null, $id_lang = null, $id_shop = null, Context $context = null) {
		parent::__construct($id_customproducttext, $id_lang, $id_shop);
	}

  public static function getCustomProductTextByProductID($id_product){
    return Db::getInstance()->executeS("
      SELECT cl.id_lang, cl.custom_text FROM " . _DB_PREFIX_ . "customproducttext_lang AS cl
      JOIN " . _DB_PREFIX_ . "customproducttext c ON cl.id_customproducttext = c.id_customproducttext
      WHERE c.id_product = " . (int) $id_product
    );
  }

  public static function getCustomProductTextDisplayLang($id_product, $id_lang){
    return Db::getInstance()->getValue("
      SELECT cl.custom_text FROM " . _DB_PREFIX_ . "customproducttext_lang AS cl
      JOIN " . _DB_PREFIX_ . "customproducttext c ON cl.id_customproducttext = c.id_customproducttext
      WHERE c.id_product = " . (int) $id_product . " AND cl.id_lang = " . (int) $id_lang
    );
  }

  public static function getCustomProductTextIDByProductID($id_product){
    return (int) Db::getInstance()->getValue("
      SELECT id_customproducttext FROM " . _DB_PREFIX_ . "customproducttext WHERE id_product = " . (int) $id_product
    );
  }
}

<?php
  include(dirname(__FILE__).'/src/functions.php');
  require_once(dirname(__FILE__).'../../../config/config.inc.php');
  require_once(dirname(__FILE__).'../../../init.php');

  $id_taxRates = [
    21 => 1, // ES Standard rate (21%)
    10 => 2 // ES Reduced rate (10%)
  ];
  
  $products = importDataFromCSV('./data/products', ',');

  foreach($products as $productData){
    // Check if product already exists
    $id_product = Product::getIdByReference($productData['Referencia']);
    if($id_product)
      continue;
    
    // Import Categories
    $id_categories = array(2);
    $categoriesName = explode(";", $productData['Categorias']);
    foreach($categoriesName as $categoryName)
      $id_categories[] = addCategory($categoryName);

    // Import Manufacturer
    $id_manufacturer = addManufacturer($productData['Marca']);
 
    // Create a new Product
    $product = new Product();
    $product->name = array_fill_keys(Language::getIDs(false), trim($productData['Nombre']));
    $product->link_rewrite = array_fill_keys(Language::getIDs(false), Tools::str2url(trim($productData['Nombre'])));
    $product->reference = $productData['Referencia'];
    $product->ean13 = $productData['EAN13'];
    $product->wholesale_price = $productData['Precio de coste'];
    $product->price = $productData['Precio de venta'];
    $product->id_tax_rules_group = $id_taxRates[(int) $productData['IVA']];
    $product->quantity = $productData['Cantidad'];

    $product->id_category_default = end($id_categories);
    $product->id_manufacturer = $id_manufacturer;
    
    $product->redirect_type = '301-category';
    $product->indexed = 1;
  
    $product->add();
    $product->updateCategories(array_unique($id_categories));

    Db::getInstance()->update('stock_available', [
      'quantity' => (int)$productData['Cantidad'],
      'physical_quantity' => (int)$productData['Cantidad'],
    ], 'id_product = ' . (int)$product->id, 1);
  }
  
  echo "All products have been imported sucessfully!";
  return true;
?>
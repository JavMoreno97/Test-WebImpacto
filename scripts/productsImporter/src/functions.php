
<?php
  /**
     * Retrieve all data from a CSV file.
     *
     * @param string $pathToFile Relative path to the CSV file
     * @param string $delimiter CSV columns separator
     *
     * @return array All the CSV data compiled into an array
  */
  function importDataFromCSV($pathToFile='', $delimiter = ',')
  {
    $allData = array();
    if(!empty($pathToFile)){
      if (($handle = fopen("$pathToFile.csv", "r")) !== FALSE) {
        $header = fgetcsv($handle, null, ",");;
        while (($data = fgetcsv($handle, null, ",")) !== FALSE)
          $allData[] = array_combine($header, $data);
        fclose($handle);
      }
    }

    return $allData;
  }

  /**
     * Adds current Category as a new Object to the database.
     *
     * @param string $categoryName Category name
     * @param int $id_parent Parent category ID
     *
     * @return int Category ID
  */
  function addCategory ($categoryName, $id_parent = 0){
    if(empty($categoryName))
      return null;

    $categoryName = trim($categoryName);

    if (!$id_category = getCategory($categoryName)) {
      // Create a new category
      $category = new Category();
      $category->id_parent = $id_parent ? $id_parent : 2;
      $category->id_shop_default = Configuration::get('PS_SHOP_DEFAULT');
      $category->active = true;
      $category->name = array_fill_keys(Language::getIDs(false), trim($categoryName));
      $category->link_rewrite = array_fill_keys(Language::getIDs(false), Tools::str2url(trim($categoryName)));
      
      // Save the category
      try {
        $category->add();
        return $category->id;
      } catch (Exception $e) {
        echo "Error creating category: " . $e->getMessage();
        exit;
      }
    }  

    return $id_category;
  }

  /**
     * Retrieve Category ID if exists (Search by Name).
     *
     * @param string $categoryName Category name
     * @param int $id_lang Language ID used to do the search
     *
     * @return int Category ID
  */
  function getCategory ($categoryName, $id_lang = 0){
    if(!$id_lang)
      $id_lang = Configuration::get('PS_LANG_DEFAULT');

    $id_category = Db::getInstance()->getValue('
      SELECT `id_category` FROM `'. _DB_PREFIX_ .'category_lang`
      WHERE `name` LIKE "%'.$categoryName.'%" 
        AND `id_lang` = ' . $id_lang);

    return $id_category;
  }

  /**
     * Adds current Manufacturer as a new Object to the database.
     *
     * @param string $manufacturerName Manufacturer name
     *
     * @return int Manufacturer ID
  */
  function addManufacturer ($manufacturerName){
    if(empty($manufacturerName))
      return null;

    $manufacturerName = trim($manufacturerName);

    if (!$id_manufacturer = getManufacturer($manufacturerName)) {
      // Create a new manufacturer
      $manufacturer = new Manufacturer();
      $manufacturer->active = true;
      $manufacturer->name = trim($manufacturerName);
      
      // Save the manufacturer
      try {
        $manufacturer->add();
        return $manufacturer->id;
      } catch (Exception $e) {
        echo "Error creating manufacturer: " . $e->getMessage();
        exit;
      }
    }  

    return $id_manufacturer;
  }

  /**
     * Retrieve Manufacturer ID if exists (Search by Name).
     *
     * @param string $manufacturerName Manufacturer name
     *
     * @return int Manufacturer ID
  */
  function getManufacturer ($manufacturerName){
    $id_manufacturer = Db::getInstance()->getValue('
      SELECT `id_manufacturer` FROM `'. _DB_PREFIX_ .'manufacturer`
      WHERE `name` LIKE "%'.$manufacturerName.'%"');

    return $id_manufacturer;
  }
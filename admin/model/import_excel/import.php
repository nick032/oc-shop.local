<?php
class ModelImportExcelImport extends Model {
    public function getProductNames(){
        $result = $this->db->query('SELECT * FROM '. DB_PREFIX .'product LIMIT 2');
        return $result->rows;
    }

    public function checkCategory($category_name) {
        $result = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category_description WHERE name = '". $category_name ."'");
        return (isset($result->row['category_id']))? $result->row['category_id']: 0;
    }

    public function checkManufacturer($brend){
        $result = $this->db->query("SELECT manufacturer_id FROM ". DB_PREFIX ."manufacturer WHERE name = '". $brend ."'");
        return isset($result->row['manufacture_id']) ? $result->row['manufacture_id'] : 0;
    }

    public function whriteManufacturer($brend){
        $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '". $this->db->escape($brend) ."', image = '', sort_order = '0'");
        //$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "'");
        return $this->db->getLastId();
    }

    public function checkProduct($product){
        //prduct_name, model
        $str = '';
        if(isset($product['model']) && $product['model'] != ''){
            $str = " OR " . DB_PREFIX."product.model = '".$product['model']."'";
        }
        $result = $this->db->query("SELECT ". DB_PREFIX ."product.product_id FROM ". DB_PREFIX ."product_description 
                                    LEFT JOIN ". DB_PREFIX ."product ON(". DB_PREFIX ."product.product_id = ". DB_PREFIX ."product_description.product_id) 
                                    WHERE ". DB_PREFIX ."product_description.name = '". $product['product_name'] ."'" . $str);
        return isset($result->row['product_id']) ? $result->row['product_id'] : 0;
    }
}
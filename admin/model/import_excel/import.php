<?php
class ModelImportExcelImport extends Model {
    public function getProductNames(){
        $result = $this->db->query('SELECT * FROM oc_product LIMIT 2');
        return $result->rows;
    }

    public function writeProductFromExcel(array $data){
        print_r($data);
        return "Данные записаны";
    }
}
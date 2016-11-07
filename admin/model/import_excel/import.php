<?php
class ModelImportExcelImport extends Model {
    public function getProductNames(){
        $result = $this->db->query('SELECT * FROM oc_product LIMIT 2');
        return $result->rows;
    }
}
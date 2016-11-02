<?php
class ControllerImportExcelImport extends Controller {
    public function add(){
        $uploads = DIR_APPLICATION . 'controller/import_excel/uploads/';
        $file =  $uploads . $_FILES[0]['name'];
        if(move_uploaded_file($_FILES[0]['tmp_name'], $file)){
            echo 'Файл загружен';
        }
        //echo '<pre>' . $file . '</pre>';
        exit;
    }
}
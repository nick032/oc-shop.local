<?php
require_once('Classes/PHPExcel.php');
class ControllerImportExcelImport extends Controller {
    private $file = '';
    public function add(){
        $uploads = DIR_APPLICATION . 'controller/import_excel/uploads/';
        $this->file =  $uploads . $_FILES[0]['name'];
        if(move_uploaded_file($_FILES[0]['tmp_name'], $this->file)){
            echo 'Файл загружен';
        }
        $this->getExcelFileData();
        unlink($this->file);

        //echo '<pre>' . $file . '</pre>';
        exit();
    }

    function getExcelFileData() {
        $cells_description = array('A' => 'category',
            'B' => 'sub_category',
            'C' => 'brend',
            'D' => 'model',
            'E' => 'ean',
            'F' => 'name',
            'G' => 'description',
            'H' => 'pic_1',
            'I' => 'pic_2',
            'J' => 'pic_3',
            'K' => 'pic_4',
            'L' => 'pic_5');

        $img_addr = array('H', 'I', 'J');
        try{
            $xlsObject = PHPExcel_IOFactory::load($this->file);
            $xlsObject->setActiveSheetIndex(0);
            $sheet = $xlsObject->getActiveSheet();
            $rowIterator = $sheet->getRowIterator();

            foreach($rowIterator as $row) {

                if($row->getRowIndex() != 0) {
                    $cellIterator = $row->getCellIterator();
                    foreach($cellIterator as $cell) {

                        echo "<br>" . $cell->getCalculatedValue();

                        $cellPath = $cell->getColumn();
                        /*if(isset($cells[$cellPath])) {
                            if(in_array($cellPath, $img_addr)) {
                                                        $link = getImgLink($cell->getValue());
                                //$data[0][$row->getRowIndex()][$cells[$cellPath]] = $link;
                                $data[$row->getRowIndex()]['images'][] = $link;
                            }else {
                                $data[$row->getRowIndex()][$cells[$cellPath]] = trim($cell->getCalculatedValue());
                            }
                        }*/
                    }
                }
            }
        }catch (ErrorException $e){}

        //return $data;
    }
}
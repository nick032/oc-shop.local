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
        $sheet = $this->getExcelFileData();
        $content = $this->getTable($sheet);
        unlink($this->file);
        echo $content;
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
            $data = [];

            foreach($rowIterator as $row) {

                if($row->getRowIndex() != 0) {
                    $cellIterator = $row->getCellIterator();
                    foreach($cellIterator as $cell) {
                        if($row->getRowIndex() == 1){
                            $data['head'][$row->getRowIndex()][] = $cell->getValue();
                            continue;
                        }
                        $data['body'][$row->getRowIndex()][] = $cell->getValue();
                        //echo "<br>". $cell->getColumn() . ' - ' . $cell->getValue();

                        //$cellPath = $cell->getColumn();
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
            return $data;
        }catch (ErrorException $e){}

        //return $data;
    }

    protected function getTable($sheet){
        $content =  '<div class="table-responsive">';
        $content .= '<table class="table table-bordered table-hover">';
        foreach ($sheet as $key => $row){
            if($key == 'head'){
                $content .= '<thead><tr><td>№</td>';
                foreach ($row as $row_index => $row_data){
                    foreach ($row_data as $data) {
                        $content .= '<td>' . $data . '</td>';
                    }
                    $content .= '</tr><tr>';
                    $count = count($row_data);
                    for ($i = 0; $i <= $count; $i++){

                        if($i == 0) {
                            $content .= '<td></td>';
                            continue;
                        }

                        $content .= '<td class="t1"><select class="select-field" name="cell_'. $i .'">';
                        $content .= '<option value="1">Занчение 1</option>';
                        $content .= '<option value="2">Занчение 2</option>';
                        $content .= '<option value="3">Занчение 3</option>';
                        $content .= '<option value="4">Занчение 4</option>';
                        $content .= '</select></td>';
                    }
                }


                $content .= '</tr>';
                $content .= '</thead>';
            }else{
                $content .= '<tbody>';
                foreach ($row as $row_index => $row_data) {
                    $content .= '<tr><td>' . ($row_index - 1) . '</td>';
                    foreach ($row_data as $data) {
                        $content .= '<td>' . $data . '</td>';
                    }
                    $content .= '</tr>';
                }
                $content .= '</tbody>';
            }
        }
        $content .= '</table></div>';

        return $content;
    }
}
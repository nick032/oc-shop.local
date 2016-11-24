<?php
class ControllerExtensionModuleImportExcel extends Controller{
    private $error = [];

    public function index(){
        $this->load->language('extension/module/import_excel');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addStyle('view/stylesheet/import_excel.css');
        $this->load->model('import_excel/import');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->model('catalog/product');
            $this->load->model('catalog/category');

            // Формируем структурированный массив данных из excel файла
            $products_xls = $this->buildDataArray($this->request->post);

            if(empty($products_xls)) $data['error'] = 'Не выбрано ни одно поле';
            foreach ($products_xls as $key => $product) {
                //var_dump($product);exit();
                // Проверка есть ли имя товара
                if(!isset($product['product_name'])) {
                    $data['error'] = 'Ошибка обработки файла, не все поля выбраны, поле "Имя товара" обязательно';
                    break;
                }
                // Проверка на существование категории и запись ее в БД
                $parent_id = 0;
                if(!empty($product['category_name'])) {
                    $parent_id = $this->model_import_excel_import->checkCategory($product['category_name']);
                    if(empty($parent_id)){
                        $category_data = $this->getCategoryArray($product['category_name']);
                        $parent_id = $this->model_catalog_category->addCategory($category_data);
                    }else{
                        $data['errors'][] = 'Категория с таким именем - '. $product['category_name'] .'  уже есть в БД';
                    }
                    if(!empty($product['sub_category_name'])){
                        if(empty($this->model_import_excel_import->checkCategory($product['sub_category_name']))){
                            $sub_category_data = $this->getCategoryArray($product['sub_category_name'], $parent_id);
                            $parent_id = $this->model_catalog_category->addCategory($sub_category_data);
                        }else{
                            $data['errors'][] = 'Категория с таким именем - '. $product['sub_category_name'] .'  уже есть в БД';
                        }
                    }
                }
                // TODO Разработать массив данных для записи товара в БД
                $data_product = $this->getProductArray($product, $parent_id);
                if($product_id = $this->model_import_excel_import->checkProduct($product)){
                    $this->model_catalog_product->editProduct($product_id, $data_product);
                }else{
                    $this->model_catalog_product->addProduct($data_product);
                }
                // Запись продукта в БД
            }
            $data['success'] = 'Данные успешно записаны в БД';
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_edit'] = $this->language->get('text_edit');

        $data['breadcrumbs'] = [];
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true)
        ];

        $data['action'] = $this->url->link('extension/module/import_excel', 'token='. $this->session->data['token'], true);
        $data['token'] = 'token=' . $this->session->data['token'];
        $data['ajax_action'] = $this->url->link('import_excel/import/add');

        $data['button_save'] = $this->language->get('button_save');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/import_excel', $data));
    }
    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/account')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }


        return !$this->error;
    }

    // Функция для формирования структурированного массива из данных excel файла
    protected function buildDataArray(array $data){
        $hName = $data['cell'];
        $products = [];
        $count = 0;
        foreach ($data as $row_key => $rows){
            if($row_key == 'cell') continue;
            foreach ($rows as $key => $row) {
                if(!empty($hName[$key])){
                    $products[$count][$hName[$key]] = trim(htmlspecialchars($row));
                }
            }
            $count++;
        }
        return $products;
    }

    // Функция формирует массив данных продукта для записи в БД
    protected function getProductArray($product, $parent_id){

        $manufacturer_id = isset($product['brend']) ? $this->writeManufacturer($product['brend']) : 0;
        $language_id = (int)$this->config->get('config_language_id');
        $data['product_description'][$language_id] = ['name' => $product['product_name'],
            'description' => '',
            'meta_title' => $product['product_name'],
            'meta_description' => '',
            'meta_keyword' => '',
            'tag' => ''
        ];
        $data['model'] = isset($product['model']) ? $product['model'] : '';
        $data['sku'] = '';
        $data['upc'] = '';
        $data['ean'] = '';
        $data['jan'] = '';
        $data['isbn'] = '';
        $data['mpn'] = '';
        $data['location'] = '';
        $data['price'] = isset($product['price']) ? $product['price'] : '';
        $data['tax_class_id'] = 0;
        $data['quantity'] = isset($product['quantity']) ? $product['quantity'] : 1;
        $data['minimum'] = 1;
        $data['subtract'] = 1;
        $data['stock_status_id'] = 6;
        $data['shipping'] = 1;
        $data['keyword'] = $this->str2url($product['product_name']);
        $data['date_available'] = date('Y-m-d');
        $data['length'] = '';
        $data['width'] = '';
        $data['height'] = '';
        $data['length_class_id'] = '';
        $data['weight'] = '';
        $data['weight_class_id'] = 1;
        $data['status'] = 1;
        $data['sort_order'] = 1;
        $data['manufacturer'] = '';
        $data['manufacturer_id'] = $manufacturer_id;
        $data['category'] = '';
        $data['product_category'][0] = $parent_id;
        $data['filter'] = '';
        $data['product_store'][0] = 0;
        $data['download'] = '';
        $data['related'] = '';
        $data['option'] = '';
        $data['image'] = '';
        $data['points'] = '';
        return $data;
    }

    protected function writeManufacturer($brend){
        if($manufacturer_id = $this->model_import_excel_import->checkManufacturer($brend)) {
            return $manufacturer_id;
        }else{
            $manufacturer_id = $this->model_import_excel_import->whriteManufacturer($brend);
            return $manufacturer_id;
        }

    }
    protected function getCategoryArray($category_name, $parent_id = 0) {
        $top = ($parent_id == 0)? 1 : 0;
        $language_id = (int)$this->config->get('config_language_id');
        $data['category_description'][$language_id] = ['name' => $category_name,
                                                        'description' => $category_name,
                                                        'meta_title' => $category_name,
                                                        'meta_description' => $category_name,
                                                        'meta_keyword' => $category_name
                                                        ];
        $data['path'] = '';
        $data['top'] = $top;
        $data['parent_id'] = $parent_id;
        $data['filter'] = '';
        $data['category_store'][0] = 0;
        $data['keyword'] = $this->str2url($category_name);
        $data['image'] = '';
        $data['column'] = 1;
        $data['sort_order'] = 0;
        $data['status'] = 1;
        $data['category_layout'][0] = '';
        return $data;
    }
    protected function rus2translit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }
    protected function str2url($str) {
        // переводим в транслит
        $str = $this->rus2translit($str);
        // в нижний регистр
        $str = strtolower($str);
        // заменям все ненужное нам на "-"
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        // удаляем начальные и конечные '-'
        $str = trim($str, "-");
        return $str;
    }
}
<?php
class ControllerExtensionModuleImportExcel extends Controller{
    private $error = [];

    public function index(){
        $this->load->language('extension/module/import_excel');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addStyle('view/stylesheet/import_excel.css');

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


        $data['token'] = 'token=' . $this->session->data['token'];
        $data['ajax_action'] = $this->url->link('import_excel/import/add');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/import_excel', $data));

    }
}
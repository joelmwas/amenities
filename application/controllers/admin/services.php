<?php
/**
 * Created by Britone Mwasaru
 * Date: 1/28/15
 * Time: 12:23 PM
 */

class Admin_services extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('services_model');

        if (!$this->session->userdata('is_logged_in')) {
            redirect('admin/login');
        }
    }

    public function index()
    {

        // posts sent by the view
        $order = $this->input->post('order');
        $search_string = $this->input->post('search_string');
        $order_type = $this->input->post('order_type');

        // pagination settings
        $config['per_page'] = 5;
        $config['base_url'] = base_url().'admin/services';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        //limit end
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        }

        //if order type was changed
        if($order_type){
            $filter_session_data['order_type'] = $order_type;
        }
        else{
            //we have something stored in the session?
            if($this->session->userdata('order_type')){
                $order_type = $this->session->userdata('order_type');
            }else{
                //if we have nothing inside session, so it's the default "Asc"
                $order_type = 'Asc';
            }
        }
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;

        //we must avoid a page reload with the previous session data
        //if any filter post was sent, then it's the first time we load the content
        //in this case we clean the session filter data
        //if any filter post was sent but we are in some page, we must load the session data

        //filtered && || paginated
        if($search_string !== false && $order !== false || $this->uri->segment(3) == true) {

            /*
            The comments here are the same for line 79 until 99

            if post is not null, we store it in session data array
            if is null, we use the session data already stored
            we save order into the the var to load the view with the param already selected
            */

            if ($search_string) {
                $filter_session_data['search_string_selected'] = $search_string;
            } else {
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search_string;

            if ($order) {
                $filter_session_data['order'] = $order;
            } else {
                $order = $this->session->userdata('order');
            }
            $data['order'] = $order;

            //save session data into the session
            $this->session->set_userdata($filter_session_data);

            // fetch services data into arrays
            $data['services'] = $this->services_model->get_all_services();

            $data['count_services'] = $this->services_model->count_services($search_string, $order);
            $data['total_rows'] = $data['count_services'];

            //fetch sql data into arrays
            if($search_string){
                if($order){
                    $data['services'] = $this->services_model->get_services($search_string, $order, $order_type, $config['per_page'],$limit_end);
                }else{
                    $data['services'] = $this->services_model->get_services($search_string, '', $order_type, $config['per_page'],$limit_end);
                }
            }else{
                if($order){
                    $data['services'] = $this->services_model->get_services('', $order, $order_type, $config['per_page'],$limit_end);
                }else{
                    $data['services'] = $this->services_model->get_services('', '', $order_type, $config['per_page'],$limit_end);
                }
            }

        } else {
            //clean filter data inside section
            $filter_session_data['search_string_selected'] = null;
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;
            $this->session->set_userdata($filter_session_data);

            // pre-selected options
            $data['search_string_selected'] = '';
            $data['order'] = 'id';

            //fetch sql data into arrays
            $data['count_services']= $this->services_model->count_services();
            $data['services'] = $this->services_model->get_services('', '', '', $order_type, $config['per_page'],$limit_end);
            $config['total_rows'] = $data['count_services'];
        }

        // initialize the pagination helper
        $this->pagination->initialize($config);

        //load the view
        $data['main_content'] = 'admin/services/list';
        $this->load->view('includes/template', $data);
    }

}
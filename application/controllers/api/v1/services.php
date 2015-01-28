<?php
/**
 * Created by Britone Mwasaru.
 * Date: 1/28/15
 * Time: 11:29 AM
 */

class Services extends REST_Controller {
    
    public function __construct()
    {
        parent::__construct();

        $this->load->model('service_model');
    }
    
    public function index_get()
    {
        $this->response($this->service_model->get_all());
    }

    public function edit_get($id = NULL)
    {
        if ( ! $id)
        {
            $this->response(array('status' => false, 'error_message' => 'No ID was provided.'), 400);
        }

        $this->response($this->service_model->get($id));
    }

    public function save_post($id = NULL)
    {
        if ( ! $id)
        {
            $new_id = $this->service_model->add($this->post());
            $this->response(array('status' => true, 'id' => $new_id, 'message' => sprintf('Project #%d has been created.', $new_id)), 200);
        }
        else
        {
            $this->service_model->update($id, $this->post());
            $this->response(array('status' => true, 'message' => sprintf('Project #%d has been updated.', $id)), 200);
        }
    }

    public function remove_delete($id = NULL)
    {
        if ($this->service_model->delete($id))
        {
            $this->response(array('status' => true, 'message' => sprintf('Project #%d has been deleted.', $id)), 200);
        }
        else
        {
            $this->response(array('status' => false, 'error_message' => 'This project does not exist!'), 404);
        }
    }
}
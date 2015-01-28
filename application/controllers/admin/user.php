<?php
/**
 * Created by Britone Mwasaru
 * Date: 1/28/15
 * Time: 12:19 PM
 */

class User extends CI_Controller {

    /* check if user is logged in, if not redirect to the login page */
    function index()
    {
        if($this->session->userdata('is_logged_in')) {
            redirect('admin/services');
        } else {
            $this->load->view('admin/login');
        }
    }

    /* loads signup view */
    function signup()
    {
        $this->load->view('admin/signup');
    }

    /* encript the password */
    function __encrypt_password($password) {
        return md5($password);
    }

    /* Create new user and store it in the database */
    function create_member()
    {
        $this->load->library('form_validation');

        // field name, error message, validation rules
        $this->form_validation->set_rules('first_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

        if($this->form_validation->run() == FALSE)
        {
            $this->load->view('admin/signup');
        }

        else
        {
            $this->load->model('Users_model');

            if($query = $this->Users_model->create_member())
            {
                $this->load->view('admin/signup_successful');
            }
            else
            {
                $this->load->view('admin/signup');
            }
        }

    }


    /* check the username and the password with the database */
    function validate_credentials()
    {

        $this->load->model('users_model');

        $user_name = $this->input->post('user_name');
        $password = $this->__encrypt_password($this->input->post('password'));

        $is_valid = $this->users_model->validate($user_name, $password);

        if($is_valid)
        {
            $data = array(
                'user_name' => $user_name,
                'is_logged_in' => true
            );
            $this->session->set_userdata($data);
            redirect('admin/services');
        }
        else // incorrect username or password
        {
            $data['message_error'] = TRUE;
            $this->load->view('admin/login', $data);
        }
    }
}
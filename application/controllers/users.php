<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends CI_Controller {
    
    public function __construct() {
        
        parent::__construct();
        
       // $this->auth->required();
        
        $this->table->set_template(array (
            "table_open" => '<table class="table table-striped">'
        ));
        
    }

    public function index() {

        redirect('users/signin');
        
    }
    
    public function authenticate() {
        
        $user_name = $this->input->post('user_name');
        $password = $this->input->post('password');

        if ($this->auth->authenticate($user_name, $password)) {
            $this->flash->success("You have been signed in");
            redirect('');
        } else {            
            $this->flash->danger("Invalid username/password combination");
            redirect('users/signin'); 
        } 
        
    }

    public function signin() {
        
        $this->template->set_master_template('sign-in');
        $this->template->write('title', 'Please sign in');        
        $this->template->render();
        
    }

    public function signout() {

        $this->auth->deauthenticate();
        
        $this->flash->success("You have been signed out");
        
        redirect();
        
    }
    
    
     public function register() {
         
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
         
        $view_data = array();
        $view_data["error"] = NULL;
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('passwordconf', 'Password Confirmation', 'required|matches[password]');
        $this->form_validation->set_rules('level', 'Account Type', 'required');
    
        // has not been run or there are validation errors
         if ($this->form_validation->run() == FALSE) {
            $title = "Register as new user";
            $this->template->write("title", $title);
            $this->template->write("heading", $title);
            $this->template->write_view("content", "view_register", $view_data);
            $this->template->render();
              //echo "if";
            // $this->load->view('view_register');
         }
         
         // everything good - process the form
         else {
             
             $newUser = new stdClass;
             
            $newUser->email = $this->input->post("email");
            $newUser->password = $this->input->post("password");
            $newUser->passwordconf = $this->input->post("passwordconf");
            
            if ($this->input->post("level") == 'admin') {
                $newUser->userlvl = 0;  // admin level
            }
            else {
                $newUser->userlvl = 9;  // basic user level
            }
                
            $newUser->institution_id = 1;    // ...for now...
            
            $auth = new Auth();

            if($auth->create_user($newUser)) {
                $this->flash->success("New Account successfully created.  You may now log-in above!");
                redirect(dashboard/home);
            }
            else {
                $data["error"] = "Error with registration. Please Try again.";
                redirect(users/register);
            }
             
         }
       
        
       
        
        
     }
     
     

}
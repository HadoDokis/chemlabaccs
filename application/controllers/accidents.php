<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accidents extends CI_Controller {

    public function __construct() {

        parent::__construct();

        $this->auth->required();

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
    }

    public function date_check($date) {

        if (valid_date($date) == false) {
            $this->form_validation->set_message("date_check", "%s is not valid");
            return false;
        }

        return true;
    }

    public function time_check($time) {

        if (valid_time($time) == false) {
            $this->form_validation->set_message("time_check", "%s is not valid");
            return false;
        }

        return true;
    }

    public function add($action = "") {
        
        if(count(get_sections()) < 1) {
            $this->flash->danger("You must first Join a Section in order to do that.");
            redirect("dashboard/home");
        }

        /*         * *********************************************************************************** */
        // Accident id's need to have random strings so we can relate our accident id's to our photo id's 
        $this->load->helper('string');
        
        $unique = false;
        while ($unique == false) {
            $testID = random_string('numeric', 7);
            
            if ($this->_accidents->isUnique($testID)) {
                $accident_id = $testID;
                $unique = true;
            }
            
        }

        /*         * *********************************************************************************** */


        $data = array();
        $data["error"] = NULL;

        $this->form_validation->set_rules('section', 'Section', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required|callback_date_check');
        $this->form_validation->set_rules('time', 'Time', 'required|callback_time_check');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('severity', 'Severity', 'required');
        $this->form_validation->set_rules('root', 'Root', 'required');
        $this->form_validation->set_rules('prevention', 'Prevention', 'required');

        if ($this->form_validation->run() && $action == "save") {

            $new = new stdClass;

            if ($this->input->post("revision_of")) {
                $new->revision_of = (int) $this->input->post("revision_of");
                $new->user = (int) $this->input->post("user");
                $new->modified_by = $this->auth->get_user_id();
            } else {
                $new->revision_of = 0;
                $new->user = $this->auth->get_user_id();
            }
            $secid = $this->input->post("section");
            $new->section_id = $secid;
            
            $date = date_human2mysql($this->input->post("date"));
            $new->date = $date;
            
            $time = time_human2mysql($this->input->post("time"));
            $new->time = $time;
            
            $description = $this->input->post("description");
            $new->description = $description;
            
            $new->severity = $this->input->post("severity");
            
            $new->root = $this->input->post("root");
            $new->prevention = $this->input->post("prevention");
            $new->id = $accident_id;
            $new->revision_of = $accident_id;

            /*************************************************************************************/
            /*************************************************************************************/
            // Modified by D.Cooper on 2/12/2014
            // Dependencies for the PhotoHandler
            // Used the CI string class to generate a user id so we can pass it to photohandler
            // So each photos is related to a a accident report
            $userid = $this->auth->get_user_id();
            $params = array('userid' => $userid, 'accidentid' => $accident_id);
            //$params = array($userid, $accident_id);
            $this->input->post("filefield");
            $this->input->post("dynamic_comment");
            /*             * ********************************************************************************** */
            if ($this->_accidents->add($new, $accident_id)) {
                // Move the photos and photo descriptions to the database.
                // Optional descriptions are sent by $this->input->post("dynamic_comment");
                $this->load->library('photohandler', $params);


                /*                 * ********************************************************************************** */
                //Modified by Davis
                //Adding Email to Admin functionality
                //Need to make new gmail account per site name
                //Need to verify if SSL is enabled in the php.ini file ( /xampp/php/php.ini)

                $this->load->library('email');
                $this->email->from('accidentreport@chemlabaccs.com', 'LARS Notification');
            //  $list = array('xxx@gmail.com'); <To include multiple receipients>
            //  $this->email->to($list);
                $secAdmin = get_admin($secid);
                $adminemail = get_email_id($secAdmin);
                $this->email->to($adminemail); 
                $this->email->subject('Lab Accident Notification');  
                $this->email->message("New Accident to Report in Section " . $secid . "<br><br>Date: " . $date . "<br>Time: " . $time . "<br>Description: " . $description);
                $this->email->send();
              //  echo $this->email->print_debugger();
            // End of modifiction by Davis
               // $adminofsec = implode("", $secAdmin);
                $this->flash->success("Report successfully added.");
                redirect("accidents/sectionResults/" . $secid);
            } else {
                $data["error"] = "Error adding report. Please Try again.";
            }
        }

        $title = "Add Accident Report";

        $this->template->write("title", $title);
        $this->template->write("heading", $title);
        $this->template->write_view("content", "accidents/add", $data);

        $this->template->render();
    }

     /*     * ********************************************************************************** */
    
    public function edit($id) {
        
        $data = array();
        $data["error"] = NULL;

    
        $this->form_validation->set_rules('date', 'Date', 'required|callback_date_check');
        $this->form_validation->set_rules('time', 'Time', 'required|callback_time_check');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('severity', 'Severity', 'required');
        $this->form_validation->set_rules('root', 'Root', 'required');
        $this->form_validation->set_rules('prevention', 'Prevention', 'required');
        
        $id = (int) $id;
        
        $accInfo = $this->_accidents->detail($id);
        
        $currentUserID = get_userID();
        
        if ($currentUserID != $this->_auth->get_admin($accInfo->section_id) AND $currentUserID != $accInfo->user) {
            $this->flash->danger("You do not have sufficient permission to edit this accident report."
                    . "<br><b>Reports can only be edited by their creator or the section Admin.</b>");
            redirect('accidents/detail/' . $id);
        }
        
        
        if ($this->form_validation->run() == FALSE) {
        
            $title = 'Editing Accident Report: <b>'  .  $id . "</b> <br>In Section: <i>" . $this->_section->get_name($accInfo->section_id) . "</i>";
            $this->template->write("title", $title);
            $this->template->write("heading", $title);
            $this->template->write_view("content", "accidents/edit", $accInfo);
            $this->template->render();
            
        }
        
        else {
            
            $newAcc = new stdClass();
            
            $newAcc->id = $id;
            $newAcc->section_id = $accInfo->section_id;
            $newAcc->revision_of = $accInfo->revision_of;
            $newAcc->user = $accInfo->user;
            $newAcc->created = $accInfo->created;
            
            $newAcc->modified_by = get_userID();
            
            $newAcc->date = date_human2mysql($this->input->post("date"));
            $newAcc->time = time_human2mysql($this->input->post("time"));
            $newAcc->description = $this->input->post("description");
            $newAcc->severity = $this->input->post("severity");
            $newAcc->root = $this->input->post("root");
            $newAcc->prevention = $this->input->post("prevention");
        
        
            if($this->_accidents->updateAccident($newAcc)) {
                $this->flash->success("You have successfully edited Accident Report <b>#" . $newAcc->id . "</b>");
                    redirect('accidents/sectionResults/' . $newAcc->section_id);

                }

                else {

                    $this->flash->danger("Problem editing Accident Report. Please try again.");
                    redirect('accidents/edit/' . $id);

                }
        }
        
        
    }
    
    /*     * ********************************************************************************** */
    
    public function delete($id) {
        
        $accDetails = $this->_accidents->detail($id);
        
        if ($this->_accidents->remove($id)) {
            $this->flash->success("You have successfully DELETED Accident Report <b>#" . $id . "</b>");
            redirect('accidents/sectionResults/' . $accDetails->section_id);      
        }
        else {
            $this->flash->danger("Problem deleting Accident Report.");
            redirect('accidents/edit/' . $id);
        }
        
    }
    
    
    
    
    
    
    /*     * ********************************************************************************** */

    public function detail($id) {

        $id = (int) $id;

        $data = array();

        $details = $this->_accidents->detail($id);
        
        $sectionInfo = $this->_section->detail($details->section_id);

        if ($details != NULL) {
            $data["details"] = $details;
            $data["sectionInfo"] = $sectionInfo;
        } else {
            redirect("dashboard");
        }

        $title = sprintf('<span class="label label-default">#%s</span> Accident Report Details', format_accident_report_number($details->id));

        $this->template->write("title", 'Accident Report Details');
        $this->template->write("heading", $title);
        $this->template->write_view("content", "accidents/detail", $data);

        $this->template->render();
    }

    /*     * ********************************************************************************** */

    public function results() {

        
        $secs = get_sections_ids();
        
        
        $search = $this->_accidents->search($secs);

        if (count($search) == 0) {
            $content = "No results found for specified criteria.";
        } else {
            
            if($this->agent->is_mobile()) {
                $content = generate_accident_listing_mobile($search, array("show_report#" => true));
            }
            else {
                $content = generate_accident_listing($search, array("show_report#" => true));
            }
        }

        $this->template->write("title", "Search Results");
        $this->template->write("heading", "Search Results");
        $this->template->write_view("content", "accidents/results", $content);
        $this->template->write("content", $content);
        $this->template->render();
        

        
    }

    /*     * ********************************************************************************** */

    public function revisions($id) {

        $revisions = $this->_accidents->revisions($id);

        if (count($revisions) == 0) {
            $content = "No results found";
        } else {
            $content = generate_accident_listing($revisions, array("show_revisions" => false));
        }

        $title = sprintf('<span class="label label-default">#%s</span> Accident Report Revisions (%d Total)', format_accident_report_number($revisions[0]->revision_of), count($revisions)
        );

        $this->template->write("title", 'Accident Report Revisions');
        $this->template->write("heading", $title);
        $this->template->write("content", $content);
        $this->template->render();
    }

    /*     * ********************************************************************************** */

    public function search($action = "") {
        
        if(count(get_sections()) > 0) {

            $data = array();

            $this->template->write("title", "Search Accident Reports");
            $this->template->write("heading", "Search Accident Reports");
            $this->template->write_view("content", "accidents/search", $data);

            $this->template->render();
        }
        else {
            $this->flash->danger("You must first Join a Section in order to do that.");
            redirect("dashboard/home");
        }
        
    }

    /*****************************************************************************************/
   // Created by D.Cooper 2/23/2014
    // Add a comment for an accident report 
    public function comment() {
        
        $id = $_POST['id'];
        $action = 'post';
        $comment = $_POST['comment_content'];
        $userid = $this->auth->get_user_id();
        $params = array('accidentid'=>$id,'comment'=>$comment,'userid'=>$userid,'action'=>$action);
        $this->load->library('commenthandler',$params);
    }
        /*****************************************************************************************/

    public function deletecomment()
    {
        $action = 'delete';
        $id = $_POST['id'];
        $user = $_POST['thisuser'];
        $userid = $this->auth->get_user_id();
         $params = array('commentid'=>$id,'theuser'=>$user,'action'=>$action);
                 $this->load->library('commenthandler',$params);

       
    }

    public function getcomment($id){
        
       $params = array('accidentid' => $id, 'cmd' => 'print');        
       $this->load->library('commentmodule', $params);

    }
    
     /********************************************************************************************/

    public function sectionResults($sec) {

        $search = $this->_accidents->searchSection($sec);

        if (count($search) == 0) {
            $content = "No results found for specified criteria.";
        } else {
            
            if($this->agent->is_mobile()) {
                $content = generate_accident_listing_mobile($search, array("show_report#" => true));
            }
            else {
                $content = generate_accident_listing($search, array("show_report#" => true));
            }
           
        }

        $secName = $this->_section->get_name($sec);
        
        $this->template->write("title", "Accidents");
        $this->template->write("heading", "Accidents in <i><b>" . $secName . "</b></i>");
        $this->template->write_view("content", "accidents/results", $content);
        $this->template->write("content", $content);
        $this->template->render();
    }
    
  
public function keywordResults() {

        $query = $_POST['keyword'];
      //  $results = array();
        
        $search = $this->_accidents->searchKeyword($query);

        if (count($search) == 0) {
            $content = "No results found for specified criteria.";
        } else {
            $content = generate_accident_listing($search, array("show_report#" => true));
        }

        $this->template->write("title", "Search Results");
        $this->template->write("heading", "Search Results");
        $this->template->write("content", $content);
        $this->template->render();
    }
    
public function userguide()
{
        $this->template->write("title", "User Guide");
        $this->template->write_view("content", "../views/userguide");
        $this->template->render();
        
}    

    
    
}


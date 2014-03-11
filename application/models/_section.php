<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Authentication model
 */
class _Section extends CI_Model {
    
    // table name
    private $table = '';

    /**
     * Section model construct
     */
    public function __construct() {

        parent::__construct();
        
        // get table name
        $this->table = $this->config->item('table_lab_user');
        
    }
    
    
    public function join_section($userSection) {
        
        $ids = get_all_section_ids();
        
        $inthere = 0;
        
        foreach($ids as $id) {
            //echo $id;
            if ($id == $userSection->section_id) {
                $inthere = 1;
            }
        }
        
       if ($inthere == 1) {
        
            $this->db->insert($this->table, $userSection);

             // was it inserted?
             return true;
       }
       
       else {
           return false;
       }
       
       
       
        
    }
    
    public function get_sections() {
        
        $auth = new Auth();
        
        $userid = $auth->get_user_id();
        
        $result = array();
        
        $sectionQ = $this->db->get('section');
        $userLabQ = $this->db->get('lab_user');
        
        if ($sectionQ->num_rows() > 0 and $userLabQ->num_rows() > 0) {
            foreach ($sectionQ->result() as $secRow) {
                foreach ($userLabQ->result() as $usRow) {
                    if ($secRow->id == $usRow->section_id and $usRow->user_id == $userid) {
                        $result[$secRow->id] = $secRow->name;
                    }
                }
    
            }
        }
        
        return $result;
        
    }
    
    public function get_sections_ids() {
        
        $auth = new Auth();
        
        $userid = $auth->get_user_id();
        
        $result = array();
        
        $sectionQ = $this->db->get('section');
        $userLabQ = $this->db->get('lab_user');
        
        if ($sectionQ->num_rows() > 0 and $userLabQ->num_rows() > 0) {
            foreach ($sectionQ->result() as $secRow) {
                foreach ($userLabQ->result() as $usRow) {
                    if ($secRow->id == $usRow->section_id and $usRow->user_id == $userid) {
                        $result[$secRow->id] = $secRow->id;
                    }
                }
    
            }
        }
        
        return $result;
        
    }
    
    public function get_sections_pass() {
        
        $auth = new Auth();
        
        $userid = $auth->get_user_id();
        
        $result = array();
        
        $sectionQ = $this->db->get('section');
        $userLabQ = $this->db->get('lab_user');
        
        if ($sectionQ->num_rows() > 0 and $userLabQ->num_rows() > 0) {
            foreach ($sectionQ->result() as $secRow) {
                foreach ($userLabQ->result() as $usRow) {
                    if ($secRow->id == $usRow->section_id and $usRow->user_id == $userid) {
                        $result[$secRow->id] = $secRow->password;
                    }
                }
    
            }
        }
        
        return $result;
        
    }
    
    public function get_admin($sectionID) {
         
        $where = array("id" => $sectionID);
        
        $query = $this->db->get_where('section', $where);
        
        if ($query->num_rows() == 1) {
            return $query->admin_id;
        }
        
        return NULL;
        
    }
    
    public function createSection($newSec) {
        
        $this->db->insert('section', $newSec);
        
        return $this->db->affected_rows() == 1;
        
    }
    
    public function detail($id) {
        
        $where = array("id" => $id);
        
        $query = $this->db->get_where('section', $where);
        
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        
        return NULL;
        
    }
    
    public function get_all_section_ids() {
     
        
        $result = array();
        
        $sectionQ = $this->db->get('section');
      
        
        if ($sectionQ->num_rows() > 0 ) {
            foreach ($sectionQ->result() as $secRow) {
                        $result[$secRow->id] = $secRow->id;
                    
                
    
            }
        }
        
        return $result;
        
    }
    
    
}
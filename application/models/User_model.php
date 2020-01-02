<?php
class User_model extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    public function is_registered($email,$isnotnull="Y"){
        if($isnotnull=="Y"){
            $query = $this->db->get_where('userRegister',"email='$email' and (phone is not null and branch is not null)" );

        }else{
            $query = $this->db->get_where('userRegister',"email='$email'" );

        }
        if( $query->num_rows() == 1 ){
            return TRUE;
        }
        return FALSE;
    }

    public function complete_signin($data){
        $data['google_user_name'] = $this->session->name;
        if($this->is_registered($this->session->email,"N")==FALSE){
            $data['email'] = $this->session->email;
            $data['hashcode'] =password_hash($data['email'],PASSWORD_BCRYPT);
            $this->db->insert('userRegister', $data);
        }
        else{
            $this->modify($this->session->email,$data);
        }
    }

    public function get_user_single($email){
      $this->db->select('*');
      $this->db->from('userRegister');
      $this->db->where('email', $email);
      $query=$this->db->get();
      return $query->result_array();
    }
    public function get_user_single_verify($email){
      $this->db->select('*');
      $this->db->from('userRegister');
      $this->db->where('email', $email);
      $query=$this->db->get();
      return $query->result_array()[0];
    }

}
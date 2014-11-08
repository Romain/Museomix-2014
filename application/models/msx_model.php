<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		Romain Biard
 * @copyright	Copyright (c) 2012 - 2013, Digital Lift
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://digitallift.fr
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter URL Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/url_helper.html
 */

// ------------------------------------------------------------------------

/**
 * This particular helper
 *
 * @package		User
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Romain Biard
 */

// ------------------------------------------------------------------------

class Msx_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();

        /**
        * 
        * Libraries to be loaded
        *
        */
        $this->load->library('encrypt');
        $this->load->database();
    }
    


    /**
    * _required method returns false if the $data array does not contain all of the keys assigned by the $required array.
    *
    * @param array $required
    * @param array $data
    * @return bool
    */

    function _required($required, $data){
        foreach($required as $field) if(!isset($data[$field])) return FALSE;
        return TRUE;
    }


    /**
     *
     * Method to get all the pictures
     * 
     * Params:
     * 
     * 
     *
     */

    public function get_pictures(){

        $this->db->select('*');
        $this->db->order_by("updated_at", "desc"); 
        
        $query = $this->db->get(DBPREFIX.'pictures');
        
        if($query->num_rows() > 0)
            return $query->result();
        else
            return FALSE;
    }


    /**
     *
     * Method to get the info of a specific picture
     * 
     * Params:
     * id or secret_id
     * 
     *
     */

    public function get_picture_info($params = array()){

        // required values
        if((!$this->_required(array('id'), $params))
            && (!$this->_required(array('secret_id'), $params))) return FALSE;

        $this->db->select('*');
        
        if(isset($params['id']))
            $this->db->where('id', $params['id']); 
        elseif(isset($params['secret_id']))
            $this->db->where('secret_id', $params['secret_id']);
        
        $query = $this->db->get(DBPREFIX.'pictures');
        
        if($query->num_rows() > 0)
            return $query->result();
        else
            return FALSE;
    }


    /**
     *
     * Method to create a new picture.
     * 
     * Params:
     * picture, created_at
     * 
     *
     */

    public function add_picture($params = array()){

        // required values
        if((!$this->_required(array('picture'), $params)) 
            || (!$this->_required(array('created_at'), $params)) ) 
            return FALSE;

        $this->db->insert(DBPREFIX.'pictures', $params);
        return $this->db->insert_id();
    }


    /**
     *
     * Method to delete a picture.
     * 
     * Params:
     * id
     * 
     *
     */

    public function delete_picture($params = array()) {

        // required values
        if(!$this->_required(array('id'), $params)) return FALSE;

        // On supprime l'inscrit
        $this->db->delete(DBPREFIX.'pictures', $params);
    
        return TRUE;
    }


    /**
     *
     * Method to edit the information of a picture.
     * 
     * Params:
     * id (mandatory)
     * 
     *
     */

    public function edit_picture($params = array()){

        // required values
        if(!$this->_required(array('id'), $params)) return FALSE;

        $this->db->where('id', $params['id']);
        unset($params['id']);
        $this->db->update(DBPREFIX.'pictures', $params);
        
        return TRUE;
    }

}
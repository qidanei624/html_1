<?php
class Mexcel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    /**
    * ����
    * @param ����
    * @param �Ա�
    * @param �绰
    * @return bool
    */ 
    function add_Excel($value)
    {   
        $data = array(
        'uname' => $value['uname'] ,
        'usex'  => $value['usex'],  
        'utel'  => $value['utel'],);
        return $this->db->insert('users', $data); 
    
    }
    
   /**
    * ����
    * @param ����
    * @param �Ա�
    * @param �绰
    * @param ID
    * @return bool
    */
    function upd_Excel($value='')
    {    
        $data = array(
                'uname' => $value['uname'] ,
                'usex'  => $value['usex'],  
                'utel'  => $value['utel'],);
        $this->db->where('uid', $value['uid']);
        return $this->db->update('users', $data); 
    
    }
    
    /**
     *��ѯ���е�ȫ������ 
     *@return data
     */
     function selUsers()
     {
        $query = $this->db->get('users');
         return $query->result_array();
     }  

}
?>
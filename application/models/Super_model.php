<?php
class super_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

     public function select_all($table)
    {
        $query = $this->db->get($table);
        return $query->result();
    }

      public function select_all_group($table,$group_id)
    {
        $this->db->from($table);
        $this->db->group_by($group_id);
        $query = $this->db->get();
        return $query->result();
    }


      public function select_all_column($column,$table)
    {
        $this->db->select($column);
        $query = $this->db->get($table);
        return $query->result();
        
    }

     public function select_all_order_by($table, $column, $order)
    {
        $this->db->order_by($column, $order);
        $query = $this->db->get($table);
        return $query->result();

    }

    public function select_row_where($table, $whr_clm, $whr_val)
    {
        $this->db->where($whr_clm, $whr_val);
        $query = $this->db->get($table);
        return $query->result();
    }

      public function select_row_where_group4($table, $whr_clm, $whr_val,$grp1,$grp2,$grp3,$grp4)
    {
        $this->db->where($whr_clm, $whr_val);
        $this->db->group_by(array($grp1,$grp2,$grp3,$grp4));
        $query = $this->db->get($table);
        return $query->result();
    }

   

    public function select_all_row($column,$table,$whr_clm, $whr_val)
    {
        $this->db->select($column);
        $this->db->where($whr_clm, $whr_val);
        $query = $this->db->get($table);
        return $query->result();
    }

    public function select_all_row_order_limit($column,$table,$whr_clm, $whr_val, $order, $limit)
    {
        $this->db->select($column);
        $this->db->where($whr_clm, $whr_val);
        $query = $this->db->get($table);
        return $query->result();
    }

    public function select_column_row_order_limit2($column,$table,$whr_clm, $whr_val,$ord_col, $order, $limit){
        $this->db->select($column);
        $this->db->where($whr_clm, $whr_val);
        $this->db->order_by($ord_col, $order);
        $this->db->limit($limit);
         $query = $this->db->get($table);
        foreach($query->result() as $result){
            return $result->$column;
        }
    }

     public function select_column_row_order_limit($column,$table, $order, $limit)
    {
        $this->db->select($column);
        $this->db->limit($limit);
        $query = $this->db->get($table);
        foreach($query->result() as $result){
            return $result->$column;
        }
    }


    public function select_custom_where($table, $where)
    {
        $this->db->where($where);
        $query = $this->db->get($table);
        return $query->result();
    }

      public function select_custom_where_group($table, $where, $group)
    {
        $this->db->where($where);
        $this->db->group_by($group);
        $query = $this->db->get($table);
        return $query->result();
    }

     public function select_join_where($table1,$table2, $where,$group_id)
    {
        $this->db->select($table1.'.*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$group_id .' = '.$table2.'.'.$group_id, 'left');
        $this->db->where($where);
        $this->db->group_by($table1.'.'.$group_id);
        $query = $this->db->get();
        return $query->result();
    }

     public function select_join_where_inner($table1,$table2, $where,$group_id)
    {
        $this->db->select($table1.'.*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$group_id .' = '.$table2.'.'.$group_id);
        $this->db->where($where);
        $this->db->group_by($table1.'.'.$group_id);
        $query = $this->db->get();
        return $query->result();
    }

     public function select_join($table1,$table2, $where,$group_id)
    {
        $this->db->select($table1.'.*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$group_id .' = '.$table2.'.'.$group_id, 'left');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }

    public function custom_query($q){
        $query = $this->db->query($q);
         return $query->result();
    }

    

    public function custom_query_single($column,$q){
        $query = $this->db->query($q);
          foreach($query->result() as $result){
            return $result->$column;
        }
    }
     public function select_column_join_where($column, $table1,$table2, $where,$group_id)
    {
        $this->db->select($table2.".".$column);
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$group_id .' = '.$table2.'.'.$group_id, 'left');
        $this->db->where($where);
        $this->db->group_by($table1.'.'.$group_id);
        $query = $this->db->get();
        foreach($query->result() as $result){
            return $result->$column;
        }
    }

     public function select_column_join_where_order_limit($column, $table1,$table2, $where,$group_id,$order,$limit)
    {
        $this->db->select($table1.".".$column);
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$group_id .' = '.$table2.'.'.$group_id, 'left');
        $this->db->where($where);
        $this->db->order_by($column, $order);
        $this->db->limit($limit);
        $query = $this->db->get();
        foreach($query->result() as $result){
            return $result->$column;
        }
    }

    public function select_column_innerjoin_where_order_limit($column, $table1,$table2, $where,$group_id,$order,$limit)
    {
        $this->db->select($table1.".".$column);
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$group_id .' = '.$table2.'.'.$group_id);
        $this->db->where($where);
        $this->db->order_by($column, $order);
        $this->db->limit($limit);
        $query = $this->db->get();
        foreach($query->result() as $result){
            return $result->$column;
        }
    }

      public function select_join_where_order($table1,$table2, $where,$group_id, $column, $order)
    {
        $this->db->select($table1.'.*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$group_id .' = '.$table2.'.'.$group_id, 'left');
        $this->db->where($where);
        $this->db->group_by($table1.'.'.$group_id);
        $this->db->order_by($column, $order);
        $query = $this->db->get();
        return $query->result();
    }



    public function select_row_where_order_by($table, $whr_clm, $whr_val, $column, $order)
    {
        $this->db->where($whr_clm, $whr_val);
        $this->db->order_by($column, $order);
        $query = $this->db->get($table);
        return $query->result();
    }

    public function select_column_where($table, $column, $whr_clm, $whr_val)
    {
        $this->db->select($column);
        $this->db->from($table);
        $this->db->where($whr_clm, $whr_val);
        $query = $this->db->get();
        foreach($query->result() as $result){
            return $result->$column;
        }
    }

    public function select_column_custom_where($table, $column, $where)
    {
        $this->db->select($column);
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        foreach($query->result() as $result){
            return $result->$column;
        }
    }

    public function select_count($table, $column, $value)
    {
        $this->db->select('COUNT('.$column.') as count');
        $this->db->from($table);
        $this->db->where($column, $value);
        $query = $this->db->get();
        foreach($query->result() as $result)
        {
            return $result->count;
        }
    }



    public function select_sum($table, $column, $where_col, $where_value)
    {
        $this->db->select('SUM('.$column.') as total');
        $this->db->from($table);
        $this->db->where($where_col, $where_value);
        $query = $this->db->get();
        foreach($query->result() as $result)
        {
            return $result->total;
        }
    }

  

    public function select_sum_where_group5($table, $column, $where,$grp1,$grp2,$grp3,$grp4,$grp5)
    {
        $this->db->select('SUM('.$column.') as total');
        $this->db->from($table);
        $this->db->where($where);
        $this->db->group_by(array($grp1,$grp2,$grp3,$grp4,$grp5));
        $query = $this->db->get();
        foreach($query->result() as $result)
        {
            return $result->total;
        }
    }


     public function select_sum_where($table, $column, $where)
    {
        $this->db->select('SUM('.$column.') as total');
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        foreach($query->result() as $result)
        {
            return $result->total;
        }
    }

       public function select_sum_between($table, $column, $where)
    {
        $this->db->select('SUM('.$column.') as total');
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        foreach($query->result() as $result)
        {
            return $result->total;
        }
    }

    

    public function select_ave($table, $column, $where_col, $where_value)
    {
        $this->db->select('AVG('.$column.') as ave');
        $this->db->from($table);
        $this->db->where($where_col, $where_value);
        $query = $this->db->get();
        foreach($query->result() as $result)
        {
            return $result->ave;
        }
    }


     public function select_sum_join($column,$table1,$table2, $where,$group_id)
    {
        $this->db->select('SUM('.$column.') as sumtotal');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$group_id .' = '.$table2.'.'.$group_id, 'left');
        $this->db->where($where);
        $query = $this->db->get();
        foreach($query->result() as $result)
        {
            return $result->sumtotal;
        }
    }

    public function select_sum_join_group($column,$table1,$table2, $where, $sameid,$group)
    {
        $this->db->select('SUM('.$column.') as sumtotal');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$sameid .' = '.$table2.'.'.$sameid, 'left');
        $this->db->where($where);
        $this->db->group_by($group);
        $query = $this->db->get();
        foreach($query->result() as $result)
        {
            return $result->sumtotal;
        }
    }


    public function select_count_join($table1,$table2, $where,$group_id)
    {
        
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$group_id .' = '.$table2.'.'.$group_id, 'left');
        $this->db->where($where);
        $query = $this->db->get();
        $rows=$query->num_rows();
        return $rows;
    }

      public function select_count_join_inner($table1,$table2, $where,$group_id)
    {
        
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$group_id .' = '.$table2.'.'.$group_id);
        $this->db->where($where);
        $query = $this->db->get();
        $rows=$query->num_rows();
        return $rows;
    }


    public function count_rows($table)
    {
        $this->db->from($table);
        $query = $this->db->get();
        $rows=$query->num_rows();
        return $rows;
    }

    public function count_rows_where($table,$column,$value)
    {
        $this->db->from($table);
        $this->db->where($column,$value);
        $query = $this->db->get();
        $rows=$query->num_rows();
        return $rows;
    }

     public function count_custom_where($table,$where)
    {
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        $rows=$query->num_rows();
        return $rows;
    }


     public function count_custom_query($query)
    {
       
        $query =  $this->db->query($query);
        $rows=$query->num_rows();
        return $rows;
    }

     public function count_join_where($table1,$table2, $where,$group_id)
    {
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$group_id .' = '.$table2.'.'.$group_id, 'left');
        $this->db->where($where);
        $this->db->group_by($table1.'.'.$group_id);
        $query = $this->db->get();
        $rows=$query->num_rows();
        return $rows;
    }

     public function count_join_where_order($table1,$table2, $where,$group_id,$column,$order)
    {
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$group_id .' = '.$table2.'.'.$group_id, 'left');
        $this->db->where($where);
        $this->db->group_by($table1.'.'.$group_id);
        $this->db->order_by($column, $order);
        $query = $this->db->get();
        $rows=$query->num_rows();
        return $rows;
    }

    public function get_max($table, $column){
         $this->db->select_max($column);
         $this->db->from($table);
         $query = $this->db->get();
         foreach($query->result() as $result){
            return $result->$column;
        }

    }

    public function get_max_where($table, $column,$where){
         $this->db->select_max($column);
         $this->db->from($table);
         $this->db->where($where);
         $query = $this->db->get();
         foreach($query->result() as $result){
            return $result->$column;
        }

    }

     public function get_min_where($table, $column,$where){
         $this->db->select_min($column);
         $this->db->from($table);
         $this->db->where($where);
         $query = $this->db->get();
         foreach($query->result() as $result){
            return $result->$column;
        }

    }

    public function get_table_columns($table){
        $fields = $this->db->list_fields($table);
        return $fields;
    }

    public function insert_into($table, $data)
    {
        $this->db->trans_begin();
        $this->db->insert($table, $data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return 0;
        }else{
            $this->db->trans_commit();
            return 1;
        }
    }

    public function update_where($table, $data, $column, $value)
    {
        $this->db->trans_begin();
        $this->db->where($column, $value);
        $this->db->update($table, $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
    }

     public function update_custom_where($table, $data, $where)
    {
        $this->db->trans_begin();
        $this->db->where($where);
        $this->db->update($table, $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
    }

    public function delete_where($table, $column, $value)
    {
        $this->db->trans_begin();
        $this->db->where($column, $value);
        $this->db->delete($table);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
    }

     public function delete_custom_where($table, $where)
    {
        $this->db->trans_begin();
        $this->db->where($where);
        $this->db->delete($table);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
    }

    function delete_data($id)
    {
       $this->db->delete('items', array('item_id' => $id)); 
       $this->db->delete('supplier_items', array('item_id' => $id));
       if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
    }

    /*function row_delete($id)
    {
       $this->db->where('supplier_id', $id);
       $this->db->delete('supplier'); 
    }*/

   /* public function login_user($username, $password){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username',$username);
        $this->db->where('password',$password);
        $query=$this->db->get();
        $rows=$query->num_rows();
        return $rows;
    }*/
    public function login_user($username, $password){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("username='$username' AND (password = '$password' OR password = '".md5($password)."')");
        $query=$this->db->get();
        $rows=$query->num_rows();
        return $rows;
    }

    function getsubcat($postData){
        $response = array();
        $this->db->select('subcat_id,subcat_name');
        $this->db->where('cat_id', $postData['category']);
        $q = $this->db->get('item_subcat');
        $response = $q->result_array();
        return $response;
    }
}
?>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Dbqueries {
	
	var $CI;
	
	function __construct()
	{
		$this->CI =& get_instance();
		
	}
        
        /**
            @Description: insert Records
            @Author: dhara
            @Input: - table, where  and data 
            @Date: 20-9-2017
        */
        
        public function insert($table, $data)
        {
            $this->CI->db->insert($table, $data);
            return $this->CI->db->insert_id();
        }
        
        /**
            @Description: Updaet Records
            @Author: Sanjay Rathod
            @Input: - table, where  and data 
            @Date: 11-8-2017
        */
        
        public function update($table, $data, $where = '', $wherestring = '')
        {
            if (!empty($where))
                $this->CI->db->where($where);
            if (!empty($wherestring))
                $this->CI->db->where($wherestring, NULL, FALSE);
            return $this->CI->db->update($table, $data);
        }
	
        /**
            @Description: return single active record
            @Author: Dhara
            @Input: - 
            @Output: - return single active record
            @Date: 11-8-2017
        */
	function find($table,$condition,$fields='*',$is_array=0)
	{
		$this->CI->db->select($fields);
                $this->CI->db->where($condition);
                $sql =  $this->CI->db->get($table);
                if($is_array==0)
                    return $sql->row();
                else
                    return $sql->row_array();
	}
        
        /**
            @Description: return multiple active active record
            @Author: Dhara
            @Input: - $table: table name,$fields: fields to be fetch,$condition: where condition
                      $order_by: order by ,$limit: limit,$offset: offset,$group_by: group by
            @Output: - return single active record
            @Date: 11-8-2017
        */
        function find_all($table,$fields='*',$condition='',$order_by=array(),$limit='',$offset='',$group_by=''){
                $this->CI->db->select($fields);
                if(!empty($order_by)){
                    foreach ($order_by as $key=>$order){
                        $this->CI->db->order_by($key,$order);
                    }
                }
                if(!empty($group_by)){
                    $this->CI->db->group_by($group_by);
                }
                if(!empty($condition)){
                    $this->CI->db->where($condition);
                }
                if(!empty($limit))
                    $this->CI->db->limit($limit);
                if(!empty($limit) && !empty($offset)){
                    $this->CI->db->limit(limit, $offset);
                }
                return $this->CI->db->get($table)->result();
        }
        
        /**
            @Description: delete record
            @Author: Dhara
            @Input: - condition and table name
            @Output: - return single active record
            @Date: 22-9-2017
        */
        function delete($table,$condition){
            $this->CI->db->where($condition);
            $this->CI->db->delete($table);
        }


        /**
            @Description: getmultiple tables Records
            @Author: Sanjay Rathod
            @Input: - table, where  and data 
            @Date: 15-9-2017
        */
        
        function getmultiple_tables_new($query_data) 
        {
            extract($query_data);

            if (!empty($fields)) {
                foreach ($fields as $coll => $value) {
                    $this->CI->db->select($value, FALSE);
                }
            }

            $this->CI->db->from($table, NULL, FALSE);

            if (!empty($join_tables)) {
                foreach ($join_tables as $coll => $value) {
                    $colldata = explode('jointype', $coll);
                    $coll = trim($colldata[0]);

                    if (!empty($colldata[1])) {
                        $join_type1 = trim($colldata[1]);
                        if ($join_type1 == 'direct')
                            $join_type1 = '';
                    }

                    if (isset($join_type1))
                        $this->CI->db->join($coll, $value, $join_type1);
                    else
                        $this->CI->db->join($coll, $value, $join_type);

                    unset($join_type1);
                }
            }

            if (!empty($condition) && $condition != null) {
                $this->CI->db->where($condition, FALSE);
            }

            if (!empty($wherestring) && $wherestring != '') {
                $this->CI->db->where($wherestring, NULL, FALSE);
            }

            if (!empty($where_in)) {
                foreach ($where_in as $key => $value) {
                    $this->CI->db->where_in($key, $value);
                }
            }

            if (!empty($or_where)) {
                foreach ($or_where as $key => $value) {
                    $this->CI->db->or_where($key, $value);
                }
            }

            if (!empty($group_by) && $group_by != null) {
                $this->CI->db->group_by($group_by);
            }

            if (!empty($having) && $having != null) {
                $this->CI->db->having($having, NULL, FALSE);
            }

            if (!empty($having_str) && $having_str != null) {
                $this->CI->db->having($having_str, NULL, FALSE);
            }

            if (!empty($orderby)) {
                if ($orderby != null && $sort != null && !empty($sort)) {
                    $this->CI->db->order_by($orderby, $sort);
                } else if ($orderby != null) {
                    if ($orderby == 'special_case')
                        $this->CI->db->order_by('is_done asc,task_date asc');
                    elseif ($orderby == 'special_case_task')
                        $this->CI->db->order_by('id desc');
                    else
                        $this->CI->db->order_by($orderby);
                }
            }

            if (!empty($where) && !empty($match_values) && $match_values != null && $compare_type != null) {
                $wherestr = '';

                foreach ($where as $key => $val) {
                    $wherestr .= $key . " = '" . $val . "' AND ";
                }

                $wherestr .= '(';

                foreach ($match_values as $key => $val) {
                    $wherestr .= $key . " " . $compare_type . " '%" . $val . "%' OR ";
                }

                $wherestr = rtrim($wherestr, 'OR ');

                $wherestr .= ')';

                $this->CI->db->where($wherestr, NULL, FALSE);
            } else {
                if (!empty($where))
                    $this->CI->db->where($where, NULL, FALSE);

                if (!empty($match_values) && $match_values != null && !empty($compare_type) && $compare_type != null)
                    $this->CI->db->or_like($match_values, $compare_type);
            }

            if (!empty($offset) && $offset != null && !empty($num) && $num != null)
                $this->CI->db->limit($num, $offset);
            elseif (!empty($num) && $num != null)
                $this->CI->db->limit($num);

            $query_FC = $this->CI->db->get();
            //echo $this->CI->db->last_query();//exit;
            if (!empty($totalrow))
                return $query_FC->num_rows();
            else
//                return $query_FC->result_array();
                return $query_FC->result();
        }
}

<?PHP
    
# MANTIS-ARTAS-New Requirement
# From ARTAS-Id get bug_id
    function get_bug_id_from_artas_id( $t_artas_id){
        # ATR or ACP
        if(substr($t_artas_id,0,3) == "ATR"){
             $t_cstm_field = 55;
             $t_artas_id = str_replace("ATR","",$t_artas_id);
        }else if(substr($t_artas_id,0,3) == "ACP") {
             $t_cstm_field = 56;
             $t_artas_id = str_replace("ACP","",$t_artas_id);
        }else{
             return "null";
        }
        list($t_artas_id_number,$t_version) = split("-",$t_artas_id);
        $t_version = str_replace("_"," ",$t_version);
        $t_custom_table = db_get_table( 'mantis_custom_field_string_table' );
        $t_bug_table = db_get_table( 'mantis_bug_table' );
        $query = 'SELECT bug_id FROM ' . $t_custom_table . ','.$t_bug_table.' WHERE ' . $t_custom_table . '.bug_id = '.$t_bug_table.'.id and field_id = ' . $t_cstm_field . ' AND value  = '.$t_artas_id_number.' and version = "'.$t_version.'"';
                                   
        

        
        $result = db_query_bound( $query, null );
        $rows = array();
        $i = 0;
        while( $row = db_fetch_array( $result )) {
            $rows[] = $row['bug_id'];
            $i++;
        }
        
       if($i == 1){
            return $rows[0];
       }else{
            return "null";
       }
                        
                                   
    }
                                   

#
?>
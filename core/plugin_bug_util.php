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
    
    function getValueFromTxt($txt,$search){
        $priorityTab = split(",",$txt);
        
        $i = sizeof($priorityTab)-1;
        while($i >= 0){
            $tmp = split(":",$priorityTab[$i]);
            if($tmp[1] == $search){
                return $tmp[0];
            }
            $i--;
        }
        return null;
    }
    
    function openTable(){
        print "<center> \n <table> \n";
        print " <tr> <td> Id </td><td> Status </td><td> Category </td><td> Summary </td><td> Reporter </td><td> Assigned to </td><td> </tr> \n";
    }
    
    function closeTable(){
        print "</table> \n </center>  \n";
    }
    
    function displayResultsCore($query,$fields){
        $result = db_query_bound( $query );
        while ( $row = db_fetch_array( $result )){
            $t_bug = bug_get($row['id']);
            print "<tr> \n";
            print "<td> ".get_artas_id($row['id'])." </td>\n";
            print "<td> ".string_display_line( get_enum_element( 'status', $t_bug->status) )." </td>\n";
            print "<td> ".category_get_row($t_bug->category_id)['name']." </td>\n";
            print "<td> ".$t_bug->summary." </td>\n";
            print "<td> ".user_get_field($t_bug->reporter_id, 'username')." </td>\n";
            if($t_bug->handler_id != null){
               print "<td> ".user_get_field($t_bug->handler_id , 'username')." </td>\n"; 
            }
            if(sizeof($fields) > 0){
                for($i=0;$i<sizeof($fields);$i++){
                print "<td> ".$row[$fields[$i]]." </td>\n";
                                }
            }
            print "</tr>\n";
        }

    }
    
    function displayResults($query){
        displayResultsCore($query,null);
        
    }
    
    function displayResultsWithFields($query,$fields){
        displayResultsCore($query,$fields);
    }

#
?>
<?php
    // Benjamin Cramet - benjamin.cramet@eurocontrol.int
    // 30/04/2014
    //
    // Creation of a plug to rank ACP per year according to User priority
    //
    
    
    /**
     * MantisBT Core API's
     */
	require_once( "core.php" );
    
	require_once( 'compress_api.php' );
	require_once( 'filter_api.php' );
	require_once( 'last_visited_api.php' );
    /**
	 * requires current_user_api
	 */
	require_once( 'user_api.php' );
	/**
	 * requires bug_api
	 */
	require_once( 'bug_api.php' );
	/**
	 * requires string_api
	 */
	require_once( 'string_api.php' );
	/**
	 * requires date_api
	 */
	require_once( 'date_api.php' );
	/**
	 * requires icon_api
	 */
	require_once( 'icon_api.php' );
	/**
	 * requires columns_api
	 */
	require_once( 'columns_api.php' );
    
    require_once("plugin_bug_api.php");
    
    // Make sure that the user is porperly authenticated and to not access directly the page from the outside by-passing security gate
	// auth_ensure_user_authenticated();
    
    // Avoid indexing of the page, although it does not do much as Google and co do not have access behind the Eurocontrol gate
    html_robots_noindex();
    
    // $_POST['bugid'] = "ATR2090-V8B2";
    $t_bug_id = 0;
    $t_bug = null;
    $t_bug_id = get_bug_id_from_artas_id( $_POST['bugid']);
    
	$t_bug = bug_get( $t_bug_id );
    
    print " { \"summary\" : \"".htmlentities($t_bug->summary)."\", ";
    print "\"reporterOrganisation\" : \"".user_get_organisation($t_bug->reporter_id)."\", ";
    print "\"category\" : \"".category_get_name($t_bug->category_id)."\", ";
    print "\"version\" : \"".$t_bug->version."\" } ";
    
    

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
    
    ?>


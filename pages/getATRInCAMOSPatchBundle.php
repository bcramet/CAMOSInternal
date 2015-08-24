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
	//auth_ensure_user_authenticated();
    
    // Avoid indexing of the page, although it does not do much as Google and co do not have access behind the Eurocontrol gate
    html_robots_noindex();
    // $_POST['bugid'] = "ATR1685-V8B1";
    
    
    if(!isset($_POST['bugid'])){
       $_POST['bugid'] = "07 – Target V8B3-U1";
    }

    
    
    $tab_bug_ids = tag_get_bugs_attached( tag_get_by_name( $_POST['bugid'] )['id'] );
    print "[ ";
    
    // print_r($tab_bug_ids);


    for($i=0;$i<count($tab_bug_ids);$i++){
    
        //print $tab_bug_ids[$i];
        
        //$t_bug_id = get_bug_id_from_artas_id( $tab_bug_ids[$i]);
    
        $t_bug = bug_get( $tab_bug_ids[$i] );
        
        print " { \"summary\" : \"".htmlentities($t_bug->summary)."\", ";
        $query = "SELECT value FROM `mantis_custom_field_string_table` WHERE bug_id = ".$t_bug->id." and field_id = 55";
        $result = db_query($query);
        $tab = db_fetch_array($result);
        if($tab['value'] == ""){
            $query = "SELECT value FROM `mantis_custom_field_string_table` WHERE bug_id = ".$t_bug->id." and field_id = 56";
            $result = db_query($query);
            $tab = db_fetch_array($result);
            $tab['value'] = "ACP".$tab['value']."-".$t_bug->version;
        }else{
            $tab['value'] = "ATR".$tab['value']."-".$t_bug->version;
        }
        
        print " \"ARTASBugId\" : \"".$tab['value']."\", ";
        //exit;
        print "\"reporterOrganisation\" : \"".user_get_organisation($t_bug->reporter_id)."\", ";
        print "\"category\" : \"".category_get_name($t_bug->category_id)."\", ";
        print "\"version\" : \"".$t_bug->version."\" } ";
        
        if($i<count($tab_bug_ids)-1){
            print ",";
        }
    }
    
    print "] ";

    ?>



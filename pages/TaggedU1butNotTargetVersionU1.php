<?php
    // Benjamin Cramet - benjamin.cramet@eurocontrol.int
    // 24/08/2015
    //
    // Build-up the Agenda for CAMOS Internal Meeting
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
    
    require_once("plugin_bug_util.php");
    
    // Make sure that the user is porperly authenticated and to not access directly the page from the outside by-passing security gate
    // auth_ensure_user_authenticated();
    
    // Avoid indexing of the page, although it does not do much as Google and co do not have access behind the // Make sure that the user is porperly authenticated and to not access directly the page from the outside by-passing security gate
    auth_ensure_user_authenticated();
    
    // Avoid indexing of the page, although it does not do much as Google and co do not have access behind the Eurocontrol gate
    html_robots_noindex();
    
    
    // re-use the default ARTAS Mantis header of the page
    html_page_top("CAMOS Internal - Tagged U1 but not Target Version U1");
    
    // display on the right top end the last issues visited in Mantis
    print_recently_visited();
    
    // List Issue Created during the last 2 weeks in CAMOS Support, ATR, ACP
    ?>
    <center><h2> Tagged U1 but not Target Version U1 </h2></center>
    <br>
    <br>
    <?php
        
        
        
        $PriorityHigh  = getValueFromTxt(config_get( 'priority_enum_string'),"High");
        $SeverityBlocking = getValueFromTxt(config_get( 'severity_enum_string'),"Blocking");
        $statusSubmitted = getValueFromTxt(config_get( 'status_enum_string'),"Submitted");
        $statusResolved = config_get( 'bug_resolved_status_threshold', null, null, 3 );
        
        // Get Priority High values
        
        
        $query = "SELECT * FROM mantis_artas.mantis_bug_tag_table, mantis_bug_table ".
                 "where tag_id = 66 ".
                 "and bug_id = id ".
                 "and project_id = 3 ".
                 "and target_version <> 'V8B3-U1'";
        
        //print $query;
        openTable();
        $nbRows = displayResults($query);
        
        
        closeTable();
        
        
    ?><br><center><h3>Nb of rows


<?php
    print $nbRows;

?></h3></center>


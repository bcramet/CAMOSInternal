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
    
    require_once("plugin_bug_api.php");
    
    // Make sure that the user is porperly authenticated and to not access directly the page from the outside by-passing security gate
    // auth_ensure_user_authenticated();
    
    // Avoid indexing of the page, although it does not do much as Google and co do not have access behind the // Make sure that the user is porperly authenticated and to not access directly the page from the outside by-passing security gate
    auth_ensure_user_authenticated();
    
    // Avoid indexing of the page, although it does not do much as Google and co do not have access behind the Eurocontrol gate
    html_robots_noindex();
    
    
    // re-use the default ARTAS Mantis header of the page
    html_page_top("CAMOS Internal Meeting");
    
    // display on the right top end the last issues visited in Mantis
    print_recently_visited();
    
    // List Issue Created during the last 2 weeks in CAMOS Support, ATR, ACP
    $query = "SELECT    id, datediff(now(), from_unixtime(date_submitted)) as s1 ".
             "FROM      mantis_bug_table ".
             "HAVING 	s1 < 70 ".
             "Order by date_submitted asc;";
    
    $result = db_query_bound( $query );
    while ( $row = db_fetch_array( $result )){
        print $row['id']."<br>";
    }
    
    
    //
        

        
        ?>


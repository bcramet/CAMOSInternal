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
    html_page_top("CAMOS Internal Meeting");
    
    // display on the right top end the last issues visited in Mantis
    print_recently_visited();
    
    // List Issue Created during the last 2 weeks in CAMOS Support, ATR, ACP
    ?>
<br>
<center><h1> Priority List V8B3-U2</h1></center>
<br>
<?php
    $statusResolved = config_get( 'bug_resolved_status_threshold', null, null, 3 );
    $statusOpened = getValueFromTxt(config_get( 'status_enum_string'),"Opened");
    $statusHolding = getValueFromTxt(config_get( 'status_enum_string'),"Holding");
    
    $tag_id = 68;
   
    // Get Issues sorted by Priority + Severity
    
    //ATR, ACP, ATS
    
    
        
        $query = "SELECT    id,  (priority + severity) as s1, from_unixtime(due_date) as duedate ".
        "FROM      mantis_bug_table, mantis_bug_tag_table ".
        "where     project_id IN (3, 4, 1) ".
        "and       mantis_bug_table.id =  mantis_bug_tag_table.bug_id ".
        "and       tag_id = 68 ".
        "ORDER by s1 desc        ";
        
        //print $query;
        $fields = array( "duedate", "s1");
        openTable();
        displayResultsWithFields($query,$fields);
        closeTable();
        
?>
<br>
<center><h1> Actions due before V8B3-U2 Release <i></i></h1></center>
<br>
<?php
    $query = "SELECT    mantis_bug_table.id as id, from_unixtime(date_order) as dateorder, due_date, from_unixtime(due_date) as duedate ".
             "FROM      mantis_bug_table, mantis_project_version_table ".
             "where 	mantis_bug_table.project_id = 9 ".
             "and 	    due_date < date_order ".
             "and 	    due_date > 1 ".
             "and 	    mantis_project_version_table.id = 207 ".
             "ORDER by duedate asc";
    
    //print $query;
    $fields = array( "duedate", "dateorder");
    openTable();
    displayResultsWithFields($query,$fields);
    closeTable();
?>

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
<center><h2> Blocking / High Issues </h2></center>
<br>
<br>
<?php
    
    
    
    $PriorityHigh  = getValueFromTxt(config_get( 'priority_enum_string'),"High");
    $SeverityBlocking = getValueFromTxt(config_get( 'severity_enum_string'),"Blocking");
    $statusSubmitted = getValueFromTxt(config_get( 'status_enum_string'),"Submitted");
    $statusResolved = config_get( 'bug_resolved_status_threshold', null, null, 3 );
    
    // Get Priority High values
    
    
    $query = "SELECT    id ".
    "FROM      mantis_bug_table ".
    "where     priority = $PriorityHigh ".
    "and       severity = $SeverityBlocking ".
    "and       project_id = 3 ".
    "and       status < $statusResolved ".
    "Order by date_submitted asc;";
    
    //print $query;
    openTable();
    displayResults($query);
    
    $statusResolved = config_get( 'bug_resolved_status_threshold', null, null, 4 );
    $query = "SELECT    id ".
    "FROM      mantis_bug_table ".
    "where     priority = $PriorityHigh ".
    "and       severity = $SeverityBlocking ".
    "and       project_id = 4 ".
    "and       status < $statusResolved ".
    "Order by date_submitted asc;";
    
    //print $query;
    displayResults($query);
    
    closeTable();
    ?>
<br><br><center><h2> Issues submitted 2 weeks ago </h2></center>

<?php
    $query = "SELECT    id, datediff(now(), from_unixtime(date_submitted)) as s1 ".
    "FROM      mantis_bug_table ".
    "where     status = $statusSubmitted  ".
    "HAVING 	s1 < 20 ".
    "Order by date_submitted asc;";
    
    
    
    openTable();
    displayResults($query);
    closeTable();
    
    $statusResolved = config_get( 'bug_resolved_status_threshold', null, null, 9 );
    
    ?>



<br><br>
<center><h2> Milestones in the last 2 weeks </h2></center>
<br>
<br>
<?php
    $query = "SELECT    id, from_unixtime(due_date) as duedate, datediff(now(), from_unixtime(due_date)) as s1 ".
    "FROM      mantis_bug_table ".
    "where     project_id = 9  ".
    "and       category_id = 64 ".
    "and       status < $statusResolved ".
    "HAVING    s1 < 20 and s1 > 0 ".
    "Order by  due_date asc;";
    
    
    
    openTable(array("Due date"));
    displayResultsWithFields($query, array("duedate"));
    closeTable();
    
    ?>

<br><br><center><h2> Milestones in the next 2 weeks </h2></center>
<br>
<br>
<?php
    $query = "SELECT    id, from_unixtime(due_date) as duedate, datediff(now(), from_unixtime(due_date)) as s1 ".
    "FROM      mantis_bug_table ".
    "where     project_id = 9  ".
    "and       category_id = 64 ".
    "and       status < $statusResolved ".
    "HAVING    s1 >= -15 and s1 <= 0 ".
    "Order by  due_date asc;";
    
    
    
    openTable(array("Due date"));
    displayResultsWithFields($query, array("duedate"));
    closeTable();
    
    ?>


<br><br><center><h2> Issue Overdue </h2></center>

<?php
    $query = "SELECT id, datediff( from_unixtime(due_date), now() ) as s1,from_unixtime(due_date) as duedate ".
    "FROM      mantis_bug_table ".
    "where     due_date > 1 ".
    "HAVING    s1 < 0 ".
    "Order by  s1 desc;";
    
    openTable();
    displayResultsWithFields($query,array ("duedate"));
    closeTable();
    
    ?>

<?php
    // display the bottom of the page
    html_page_bottom1( __FILE__ );
    
    ?>


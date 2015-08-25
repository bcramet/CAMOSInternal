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
    ?>
    <center><h2> Blocking / High Issues </h2></center>
    <br>
    <br>
    <?php
        
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
            print "<center> \n <table> ";
            print " <tr> <td> Id </td><td> Status </td><td> Category </td><td> Summary </td><td> Reporter </td><td> Assigned to </td><td> </tr>";
        }
        
        function closeTable(){
            print "</table> \n </center>  ";
        }
        
        function displayResults($query){

            $result = db_query_bound( $query );
            while ( $row = db_fetch_array( $result )){
                $t_bug = bug_get($row['id']);
                print "<tr> ";
                print "<td> ".get_artas_id($row['id'])." </td>";
                print "<td> ".$t_bug->status." </td>";
                print "<td> ".category_get_row($t_bug->category_id)['name']." </td>";
                print "<td> ".$t_bug->summary." </td>";
                print "<td> ".$t_bug->reporter_id." </td>";
                print "<td> ".$t_bug->handler_id." </td>";
                print "</tr>";
            }
            
        }
        
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
    <center><h2> Issues submitted 2 weeks ago </h2></center>
    <br>
    <br>

    <?php
    $query = "SELECT    id, datediff(now(), from_unixtime(date_submitted)) as s1 ".
             "FROM      mantis_bug_table ".
             "where     status = $statusSubmitted  ".
             "HAVING 	s1 < 70 ".
             "Order by date_submitted asc;";
    
    
    
        openTable();
        displayResults($query);
        closeTable();

    ?>

    <center><h2> Milestones per version </h2></center>
    <br>
    <br>


    <center><h2> Issue Overdue </h2></center>
    <br>
    <br>

<?php
    

?>


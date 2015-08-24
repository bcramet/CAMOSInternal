<?php
    class CamosInternalPlugin extends MantisPlugin {
        function register() {
            $this->name = 'Camos Internal';    # Proper name of plugin
            $this->description = 'Dashboard aimning at piloting the maintenance';    # Short description of the plugin
            $this->page = 'InternalCamos.php';           # Default plugin page
            
            $this->version = '1.0';     # Plugin version string
            $this->requires = array(    # Plugin dependencies, array of basename => version pairs
                                    'MantisCore' => '1.2',  #   Should always depend on an appropriate version of MantisBT
                                    );
            
            $this->author = 'Benjamin Cramet / CAMOS';         # Author/team name
            $this->contact = 'benjamin.cramet@eurocontrol.int';        # Author/team e-mail address
            $this->url = 'http://www.eurocontrol.int/artas';            # Support webpage
        }
        
        function init() {
            //mantisgraph_autoload();
            
            
            $t_path = config_get_global('plugin_path' ). plugin_get_current() . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR;
            
            set_include_path(get_include_path() . PATH_SEPARATOR . $t_path);
        }
    }
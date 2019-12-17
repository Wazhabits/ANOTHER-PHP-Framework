**Framework Project**

* Stack 
    
    
    1 : Index
        A : ENV_VAR
        
    2 : Loader
        A : Include all interfaces in Core
        B : Include all classes in Core
        C : Include all files from Root
    
    2 : Kernel
        A : Env
        B : Annotation
        C : Routing
    
    
* Norm


    1 : Upper Camel Case for classes
    
    2 : Lower Camel Case
    
    3 : NameSpace :
        Interface : Core\Functionnality
        Class : Core
        
    4 : LOG :
        Format : CLASS|Message of log
        
    5 : Routing :
        Format : 
            "siteDomain": {
                "route" => "controllerPath",
                ...
            }

* Documentation


    0 : Kernel -
        static class
        static function getEnvironment() | Return Env instance
        static function getAnnotation() | Return Annotation instance

    1 : Loader -
        static class
        static function explore($path, $needle, $constraint) | This function include all PHP files
        static $CLASSES | Array of loaded classes path
        
    2 : Env -
        Access from kernel
        public function get($key) | Return the associated value in configuration array or the all array
        
    3 : Files -
        static class
        static function read($path) | Return the content of file given
        static function put($path, $content) | Put at the end of file the $content given
        static function delete($path) | Delete the given file
        static function create($path, $content = null) | Create the file and add content if specified
        static function test($path) | Test if a $path exist, create the missing folder if there are
        
    4 : Annotation
        Acces from kernel
        public function getDocumentation($classname, $method = "") | Get the documentation array of specific class/method
        
    5 : Event
        static class
        static function add($eventName, $classnameAndMethod = null) | Add a listener to an event, create event if no listener gave
        static function exec($eventName, &$args) | Execute all listener associated to given event
        
    6 : Routing
        access from kernel
        public functon getCurrent() | Return the current route use and the response status code
        
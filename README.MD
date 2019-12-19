**Framework Project**

***Presentation***
This project want to provide the lighter PHP framework developer-oriented, multi-site, multi-lang, multi-application. Under GNU GPL v2.0

***Team***
Our team is composed by 2 developper : François Gazaube as PHP developper and Anatole Piveteau as Project Maintainer 

* Settings


    APPLICATION_ID : Usefull for API app
    
    APPLICATION_NAME : /
    
    APPLICATION_CONTEXT : Define the current context, Production by default, use Develop to get debug access
    
    SITES_DOMAINS : Comma separated list to declare mutli-domain app
    
    DATABASE_USER : User for DB
    
    DATABASE_PASS : Password for DB
    
    DATABASE_HOST : Host of DB
    
    DATABASE_PORT : Port of DB
    
    DATABASE_NAME : Database name
    
    LOG_FORMAT : Log file format name (to split by date)
    
    LOG_LEVEL : Write all log who had a level under this constant
    
    

* Stack 
    
    
    FRONT RENDERING
    
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
        
    3 : Controller
        A : Render
        
    4 : Template
        A : sectionnalize
        C : makeLoop
        B : setVars
        D : [Context=Develop]Debug
    
* Event Stack


    sort by execution
    core/kernel.boot
    core/routing.read
    core/routing.current
        core/routing.500
        core/routing.404
        core/routing.200
    core/template.preProcess
    core/template.preRender
    core/template.postRender
    core/template.postProcess
    
* Norm


    1 : Upper Camel Case for classes
    
    2 : Lower Camel Case
    
    3 : NameSpace :
        Interface : Core\Functionnality
        Class : Core
        
    4 : LOG :
        Format : 
            Key = class
            Message : CLASS|Message of log
            Status : 0 = Normal, 1 = Warning, 2 = Error
        
    5 : Routing :
        .routing Format File : 
            "siteDomain": {
                "route" => "controllerPath",
                ...
            }
        Annotation :
            use the "@route /my/route" flag into your phpdoc controller method
            
    6 : Event :
         use the "@event Namespace/class.event" flag into your phpdoc static method or the Event::add($eventName, &$args)

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
        
    7 : Template
        static class
        static function render($templatePath, $args) | This function render a template defined by path with given args
        static function build($buffer) | This function take a parameter containing the Template content and call other method to search and replace values
        static function setVars($buffer) | This function search and replace all {vars}/{vars[X]} into template
        static function sectionalize($buffer) | This function search and replace recursively section in templates
        static function makeLoop($buffer) | This function do a foreach in templates
        static function showArray($array) | This function show an array as ul>li HTML recursively
        static function debug(&$buffer) | This function add the marker {debug:varname} to frontend
        static function object_to_array($obj) | This function convert an object to an array recursively
        
        
* Template :

    
    {var} : Show a variable named var, it can be a string or an array, if it is an array, it is convert to a comma separated list
            If var is an array, you can show a single element by using {var[INDEX]}
    {section:sectionPath} : Include a section (sub-template) into template
    {foreach:message as key => element}
        {key} => {element}
    {end}
    {debug:var} : Show a full var, special value is __args who show all arguments in template

    
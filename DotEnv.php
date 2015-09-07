<?php
/**
 * User: Administrator
 * Date: 2015/6/4
 * Time: 14:30
 */

namespace Snowair\Dotenv;

class DotEnv {

    public function app_begin( &$params )
    {
        $path =  dirname(APP_PATH);
        $env_file = $path.'/.env';
        if (file_exists($env_file)) {
            $Loader = new Loader($env_file);
            $Loader->setFilters(['Snowair\Dotenv\DotArrayFilter'])
                ->parse()
                ->filter();
            if( $expect=C('DOTENV.expect') ){
                call_user_func_array(array($Loader,'expect'),explode(',',$expect));
            }
            if(C('DOTENV.toConst')){
                $Loader->define();
            }
            if(C('DOTENV.toServer')){
                $Loader->toServer(true);
            }
            if(C('DOTENV.toEnv')){
                $Loader->toEnv(true);
            }
            $env = $Loader->toArray();
            C($env);
        };
    }
}

const DS = DIRECTORY_SEPARATOR;
$vendorPath   = dirname(dirname(__DIR__));
$vendorParent = realpath(dirname($vendorPath));

function getProjectRoot($vendorParent){
    $dir = dirname($vendorParent);
    if ( file_exists($vendorParent. DS .'composer.json')
        || file_exists( $vendorParent.DS.'.git')
        || file_exists( $vendorParent.DS.'index.php' )
    )
    {
        return $vendorParent;
    }elseif( $dir!=$vendorParent && $dir!='.' ){
        return getProjectRoot($dir);
    }else{
        return false;
    }
}

$rootPath = getProjectRoot($vendorParent);
if ($rootPath) {
    $dir = dir($rootPath);
    $index = $basepath = false;
    while($d=$dir->read()){
        if($d=='index.php'){
            $basepath = $rootPath;
            $index = $rootPath.DS.'index.php';
            break;
        }
        if(file_exists($rootPath.DS.$d.DS.'index.php') ){
            $basepath = $rootPath.DS.$d;
            $index = $rootPath.DS.$d.DS.'index.php';
            break;
        }
    }
    if ($index && $basepath) {
        $content = file_get_contents($index);
        if (preg_match_all('/define.*[\'"](\w+)[\'"].*((?<=[\'"]).+(?=[\'"])|true|false)/im',$content,$matches,PREG_SET_ORDER)) {
            $const = array();
            foreach ($matches as $value) {
                $const[$value[1]]=$value[2];
            }
        }
        if ( preg_match( '{.*[\'"](.*ThinkPHP.php)[\'"]}',$content,$matches ) ) {
            $think_path = dirname(realpath($basepath.DS.$matches[1]));
            include_once $think_path.DS.'Library'.DS.'Think'.DS.'Hook.class.php';
            if (class_exists('Think\Hook')) {
                \Think\Hook::add('app_begin','Common\Behavior\DotEnvBehavior');
            }
        }
    }
}

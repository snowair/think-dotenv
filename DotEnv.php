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

if (class_exists( 'Snowair\Think\Behavior\HookAgent' )) {
    \Snowair\Think\Behavior\HookAgent::add('app_begin','Snowair\\Dotenv\\DotEnv');
}

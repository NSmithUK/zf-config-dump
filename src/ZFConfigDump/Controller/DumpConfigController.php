<?php
namespace ZFConfigDump\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;

use Symfony\Component\VarDumper\Cloner\VarCloner;

use ZFConfigDump\Dumper\CliDumper;

class DumpConfigController extends AbstractActionController {

    public function dumpAction(){

        if (!$this->getRequest() instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from the console');
        }

        //---

        $config = $this->getServiceLocator()->get('Config');

        $filter = $this->getRequest()->getParam('filter');

        if( $filter ){

            $filters = explode('.', $filter);

            foreach( $filters as $key ){

                if( array_key_exists( $key, $config ) ){
                    $config = $config[$key];
                } else {
                    die("Unable to find config value for key {$filter}");
                }

            }

        }

        (new CliDumper)->dump(
            (new VarCloner)->cloneVar( $config )
        );

    }

}

<?php

namespace App\EventListener;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class ControllerLoggerListener
{
    private string $logFile;
    private RequestStack $requestStack;
    private Security $security;

    public function __construct(string $logFile, RequestStack $requestStack, Security $security)
    {
        $this->logFile = $logFile;
        $this->requestStack = $requestStack;
        $this->security = $security;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        //$controller = $event->getController();

//        if (is_array($controller)) {
//            $controllerName = get_class($controller[0]) . '::' . $controller[1];
//        } elseif (is_object($controller)) {
//            $controllerName = get_class((object)$controller);
//        } else {
//            $controllerName = 'N/A';
//        }

        $user= $this->security->getUser();
        $userName=" ";
        if($user){
            $userName=$user->getEmail();
        }else{
            $userName="Niezalogowany";
        }

        $request = $this->requestStack->getCurrentRequest();
        $url=$request->getRequestUri();
        $time=date('Y-m-d H:i:s');

        $this->writeToFile([$time,$userName, $url]);
    }

    public function writeToFile(array $data): void
    {
        $fileExists=file_exists($this->logFile);
        $file= fopen($this->logFile, 'a');
        if(!$fileExists){
            fputcsv($file, ['Time','User','URL', 'Controller']);
        }
        fputcsv($file, $data);
        fclose($file);
    }
}
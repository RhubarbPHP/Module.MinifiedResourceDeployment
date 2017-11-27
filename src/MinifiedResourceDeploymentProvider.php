<?php

namespace Rhubarb\MinifiedResourceDeployment;

use JSMin\JSMin;
use Rhubarb\Crown\Application;
use Rhubarb\Crown\Deployment\RelocationResourceDeploymentProvider;
use Rhubarb\Crown\Exceptions\DeploymentException;

class MinifiedResourceDeploymentProvider extends RelocationResourceDeploymentProvider
{
    protected function deployFile($originalPath, $calculatePathOnly = false)
    {
        if (preg_match('/\.js$/', $originalPath, $match)) {
            $originalPath = realpath($originalPath);

            // Remove the current working directory from the resource path.
            $cwd = Application::current()->applicationRootPath;
            $urlPath = "/deployed" . str_replace("\\", "/", str_replace($cwd, "", $originalPath));

            $path = pathinfo($urlPath);
            $urlPath = $path['dirname'].'/'.$path['filename'].'.min.js';

            $deployedPath = PUBLIC_ROOT_DIR . $urlPath;

            if (!$calculatePathOnly) {
                if (!file_exists(dirname($deployedPath))) {
                    if (!mkdir(dirname($deployedPath), 0777, true)) {
                        throw new DeploymentException("The deployment folder could not be created. Check file permissions to the '" . dirname($deployedPath) . "' folder.");
                    }
                }

                if (!file_exists($deployedPath) || (filemtime($originalPath) > filemtime($deployedPath))) {
                    $result = file_put_contents($deployedPath, JSMin::minify(file_get_contents($originalPath)));

                    if (!$result) {
                        throw new DeploymentException("The file $originalPath could not be deployed. Please check file permissions.");
                    }
                }
            }

            return $urlPath;
        } else {
            return parent::deployFile($originalPath, $calculatePathOnly);
        }
    }
}
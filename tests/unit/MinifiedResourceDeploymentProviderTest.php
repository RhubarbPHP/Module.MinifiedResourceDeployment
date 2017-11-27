<?php

namespace Rhubarb\MinifiedResourceDeployment\Tests;

use Rhubarb\Crown\Application;
use Rhubarb\Crown\Tests\Fixtures\TestCases\RhubarbTestCase;
use Rhubarb\MinifiedResourceDeployment\MinifiedResourceDeploymentProvider;

class MinifiedResourceDeploymentProviderTest extends RhubarbTestCase
{
    public function testDeploymentMinifiesFiles()
    {
        $cwd = Application::current()->applicationRootPath;

        $deploymentPackage = new MinifiedResourceDeploymentProvider();
        $url = $deploymentPackage->deployResource(__DIR__.'/test.js');

        $deployedFile = $cwd."/deployed" . str_replace($cwd, "", __DIR__.'/test.min.js');

        $this->assertFileExists($deployedFile);
        $this->assertStringStartsWith("/deployed/tests/unit/test.min.js", $url);
        $this->assertEquals("function thisIsAFunction(){}
thisIsAFunction();", file_get_contents($deployedFile));

        unlink($deployedFile);
    }

    public function testDoesntMinifyCss()
    {
        $cwd = Application::current()->applicationRootPath;

        $deploymentPackage = new MinifiedResourceDeploymentProvider();
        $url = $deploymentPackage->deployResource(__DIR__.'/test.css');

        $deployedFile = $cwd."/deployed" . str_replace($cwd, "", __DIR__.'/test.css');

        $this->assertFileExists($deployedFile);
        $this->assertStringStartsWith("/deployed/tests/unit/test.css", $url);

        unlink($deployedFile);
    }
}
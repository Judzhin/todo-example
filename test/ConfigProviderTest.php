<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODOTest;

use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase;
use TODO\ConfigProvider;

/**
 * Class ConfigProviderTest
 * @package TODOTest
 */
class ConfigProviderTest extends TestCase
{
    /**
     *
     */
    public function testDependenciesMethod()
    {
        /** @var ConfigProvider $configProvider */
        $configProvider = new ConfigProvider;
        $this->assertThat($configProvider->getDependencies(), new IsType('array'));
    }

    /**
     *
     */
    public function testInvokeMethod()
    {
        /** @var ConfigProvider $configProvider */
        $configProvider = new ConfigProvider;
        $this->assertThat($configProvider->__invoke(), new IsType('array'));
    }
}

<?php

declare(strict_types=1);

namespace PhungDucKien\PHPStan\CakePHP;

use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Type\ObjectType;
use PHPStan\Type\NeverType;
use PhungDucKien\PHPStan\CakePHP\ModelProperty;

class ModelClassPropertyClassReflectionExtension implements PropertiesClassReflectionExtension, BrokerAwareExtension
{
    /** @var Broker */
    private $broker;

    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
    }

    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        $reflectionClass = $classReflection->getNativeReflection();

        if ($reflectionClass->hasProperty("modelClass")) {
            $modelClassProp = $reflectionClass->getProperty("modelClass");
            $modelClassProp->setAccessible(true);
            $modelClassValue = $modelClassProp->getValue($reflectionClass->newInstanceWithoutConstructor());

            if (is_array($modelClassValue)) {
                return in_array($propertyName, $modelClassValue);
            } elseif (is_string($modelClassValue)) {
                return $propertyName === $modelClassValue;
            }
        }

        return false;
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        $reflectionClass = $classReflection->getNativeReflection();
        $namespaceName = $reflectionClass->getNamespaceName();
        $seperatorPos = strrpos($namespaceName, "\\");
        if ($seperatorPos != false) {
            $namespaceName = substr($namespaceName, 0, $seperatorPos);
        }

        return new ModelProperty(
            $classReflection,
            new ObjectType("{$namespaceName}\\Model\\Table\\{$propertyName}Table"),
            new NeverType()
        );
    }
}

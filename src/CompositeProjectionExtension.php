<?php

declare(strict_types=1);

namespace Wwwision\DCBLibraryPHPStanExtension;

use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ObjectShapeType;
use PHPStan\Type\Type;
use Wwwision\DCBLibrary\Projection\CompositeProjection;
use Wwwision\DCBLibrary\Projection\Projection;

final class CompositeProjectionExtension implements DynamicStaticMethodReturnTypeExtension
{

    public function getClass(): string
    {
        return CompositeProjection::class;
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'create';
    }

    public function getTypeFromStaticMethodCall(MethodReflection $methodReflection, StaticCall $methodCall, Scope $scope): ?Type
    {
        $objectPropertyTypes = [];
        /** @var ConstantArrayType $projectionsType */
        $projectionsType = $scope->getType($methodCall->getArgs()[0]->value);
        foreach ($projectionsType->getConstantArrays() as $arrayType) {
            foreach ($arrayType->getKeyTypes() as $keyType) {
                /** @var GenericObjectType $projectionObjectType */
                $projectionObjectType = $arrayType->getOffsetValueType($keyType);
                $objectPropertyTypes[$keyType->getValue()] = $projectionObjectType->getTemplateType(Projection::class, 'S');
            }
        }
        return new GenericObjectType(CompositeProjection::class, [new ObjectShapeType($objectPropertyTypes, [])]);
    }
}
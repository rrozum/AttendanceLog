<?php

namespace System\AbstractFactory;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerInterface as PsrContainerInterface;
use ReflectionClass;
use ReflectionParameter;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\Stdlib\DispatchableInterface;

/**
 * Reflection-based factory.
 *
 * To ease development, this factory may be used for classes with
 * type-hinted arguments that resolve to services in the application
 * container; this allows omitting the step of writing a factory for
 * each controller.
 *
 * You may use it as either an abstract factory:
 *
 * <code>
 * 'service_manager' => [
 *     'abstract_factories' => [
 *         ReflectionBasedAbstractFactory::class,
 *     ],
 * ],
 * </code>
 *
 * Or as a factory, mapping a class name to it:
 *
 * <code>
 * 'service_manager' => [
 *     'factories' => [
 *         MyClassWithDependencies::class => ReflectionBasedAbstractFactory::class,
 *     ],
 * ],
 * </code>
 *
 * The latter approach is more explicit, and also more performant.
 *
 * The factory has the following constraints/features:
 *
 * - A parameter named `$config` typehinted as an array will receive the
 *   application "config" service (i.e., the merged configuration).
 * - Parameters type-hinted against array, but not named `$config` will
 *   be injected with an empty array.
 * - Scalar parameters will result in an exception being thrown, unless
 *   a default value is present; if the default is present, that will be used.
 * - If a service cannot be found for a given typehint, the factory will
 *   raise an exception detailing this.
 * - Some services provided by Zend Framework components do not have
 *   entries based on their class name (for historical reasons); the
 *   factory allows defining a map of these class/interface names to the
 *   corresponding service name to allow them to resolve.
 *
 * `$options` passed to the factory are ignored in all cases, as we cannot
 * make assumptions about which argument(s) they might replace.
 *
 * Based on the LazyControllerAbstractFactory from zend-mvc.
 */
class ReflectionBasedAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Maps known classes/interfaces to the service that provides them; only
     * required for those services with no entry based on the class/interface
     * name.
     *
     * Extend the class if you wish to add to the list.
     *
     * Example:
     *
     * <code>
     * [
     *     \Zend\Filter\FilterPluginManager::class       => 'FilterManager',
     *     \Zend\Validator\ValidatorPluginManager::class => 'ValidatorManager',
     * ]
     * </code>
     *
     * @var string[]
     */
    protected $aliases = [];

    /**
     * Constructor.
     *
     * Allows overriding the internal list of aliases. These should be of the
     * form `class name => well-known service name`; see the documentation for
     * the `$aliases` property for details on what is accepted.
     *
     * @param string[] $aliases
     */
    public function __construct(array $aliases = [])
    {
        if (!empty($aliases)) {
            $this->aliases = $aliases;
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return DispatchableInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $reflectionClass = new ReflectionClass($requestedName);

        if (null === ($constructor = $reflectionClass->getConstructor())) {
            return new $requestedName();
        }

        $reflectionParameters = $constructor->getParameters();

        if (empty($reflectionParameters)) {
            return new $requestedName();
        }

        $resolver = $container->has('config')
            ? $this->resolveParameterWithConfigService($container, $requestedName)
            : $this->resolveParameterWithoutConfigService($container, $requestedName);

        $parameters = array_map($resolver, $reflectionParameters);

        return new $requestedName(...$parameters);
    }

    /**
     * Returns a callback for resolving a parameter to a value, including mapping 'config' arguments.
     *
     * Unlike resolveParameter(), this version will detect `$config` array
     * arguments and have them return the 'config' service.
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return callable
     */
    private function resolveParameterWithConfigService(ContainerInterface $container, $requestedName)
    {
        return function (ReflectionParameter $parameter) use ($container, $requestedName) {
            if ($parameter->isArray() && $parameter->getName() === 'config') {
                return $container->get('config');
            }

            if ($parameter->getName() === 'container'
                && (
                    $parameter->getClass()->isSubclassOf(PsrContainerInterface::class)
                    || $parameter->getClass()->getName() === PsrContainerInterface::class
                )
            ) {
                return $container;
            }

            return $this->resolveParameter($parameter, $container, $requestedName);
        };
    }

    /**
     * Logic common to all parameter resolution.
     *
     * @param ReflectionParameter $parameter
     * @param ContainerInterface $container
     * @param $requestedName
     * @return mixed|null
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function resolveParameter(ReflectionParameter $parameter, ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config') ?? [];
        $value = $config['dependencies']['params'][$requestedName][$parameter->getName()] ?? null;

        if (!$parameter->isCallable() && is_callable($value)) {
            $value = call_user_func($value, $container);
        }

        if (!is_null($value) || $parameter->allowsNull()) {
            return $value;
        }

        if (!$parameter->getClass()) {
            if (!$parameter->isDefaultValueAvailable()) {
                throw new ServiceNotFoundException(sprintf(
                    'Unable to create service "%s"; unable to resolve parameter "%s"',
                    $requestedName,
                    $parameter->getName()
                ));
            }

            return $parameter->getDefaultValue();
        }

        $type = $parameter->getClass()->getName();
        $type = isset($this->aliases[$type]) ? $this->aliases[$type] : $type;

        if ($container->has($type)) {
            return $container->get($type);
        }

        if (!$parameter->isOptional()) {
            throw new ServiceNotFoundException(sprintf(
                'Unable to create service "%s"; unable to resolve parameter "%s" using type hint "%s"',
                $requestedName,
                $parameter->getName(),
                $type
            ));
        }

        // Type not available in container, but the value is optional and has a
        // default defined.
        return $parameter->getDefaultValue();
    }

    /**
     * Resolve a parameter to a value.
     *
     * Returns a callback for resolving a parameter to a value, but without
     * allowing mapping array `$config` arguments to the `config` service.
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return callable
     */
    private function resolveParameterWithoutConfigService(ContainerInterface $container, $requestedName): callable
    {
        return function (ReflectionParameter $parameter) use ($container, $requestedName) {
            return $this->resolveParameter($parameter, $container, $requestedName);
        };
    }

    /**
     * {@inheritDoc}
     */
    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        return class_exists($requestedName);
    }
}

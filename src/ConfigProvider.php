<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODO;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use MSBios\Doctrine\Factory\ObjectableFactory;
use Ramsey\Uuid\Uuid;
use Zend\Filter;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

/**
 * Class ConfigProvider
 * @package TODO
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'doctrine' => $this->getDoctrine(),
            'templates' => $this->getTemplates(),
            'websocket' => $this->getWebSocket(),
            'resolvers' => $this->getResolvers(),
            'input_filter_specs' => $this->getInputFilterSpecs()
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                // ...
            ],
            'factories' => [

                \Symfony\Component\Console\Application::class =>
                    ApplicationFactory::class,

                \Ratchet\Server\IoServer::class =>
                    IoServerFactory::class,

                Handler\HomePageHandler::class =>
                    Handler\HomePageHandlerFactory::class,

                MessageResolver::class =>
                    MessageResolverFactory::class,

                Resolver\PersistResolver::class =>
                    Resolver\ResolverFactory::class,
                Resolver\MergeResolver::class =>
                    Resolver\ResolverFactory::class,
                Resolver\RemoveResolver::class =>
                    Resolver\ResolverFactory::class,

                MessageComponent::class =>
                    MessageComponentFactory::class
            ],
        ];
    }

    /**
     * Returns doctrine configuration
     *
     * @return array
     */
    public function getDoctrine(): array
    {
        return [
            'cache_dir' => './data/Doctrine/Proxy',
            'driver' => [
                'orm_default' => [
                    'drivers' => [
                        'TODO\Entity' =>
                            __NAMESPACE__,
                    ],
                ],
                __NAMESPACE__ => [
                    'class' => AnnotationDriver::class,
                    'cache' => 'array',
                    'paths' => __DIR__ . '/Entity',
                ],
            ],
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app' => [
                    'templates/app'
                ],
                'error' => [
                    'templates/error'
                ],
                'layout' => [
                    'templates/layout'
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getWebSocket(): array
    {
        return [
            'port' => 9001
        ];
    }

    public function getInputFilterSpecs()
    {
        return [
            MessageComponent::class => [
                'operation' => [
                    'required' => true,
                    'filters' => [
                        [
                            'name' => Filter\StringTrim::class
                        ], [
                            'name' => Filter\StripTags::class
                        ]
                    ],
                    'validators' => [
                        [
                            'name' => Validator\NotEmpty::class,
                        ],
                    ],
                ],
                'data' => [
                    'type' => InputFilter::class,
                    'id' => [
                        'required' => true,
                        'filters' => [
                            [
                                'name' => Filter\StringTrim::class
                            ], [
                                'name' => Filter\StripTags::class
                            ], [
                                'name' => Filter\Callback::class,
                                'options' => [
                                    'callback' => function ($value) {
                                        // @codeCoverageIgnoreStart
                                        return Uuid::fromString($value);
                                        // @codeCoverageIgnoreEnd
                                    }
                                ]
                            ]
                        ],
                        'validators' => [
                            [
                                'name' => Validator\NotEmpty::class,
                            ],
                        ],
                    ],
                    'check' => [
                        'required' => true,
                        'filters' => [
                            [
                                'name' => Filter\ToInt::class
                            ],
                        ],
                        'validators' => [
                            [
                                'name' => Validator\NotEmpty::class,
                            ],
                        ],
                    ],
                    'description' => [
                        'required' => true,
                        'filters' => [
                            [
                                'name' => Filter\StringTrim::class
                            ], [
                                'name' => Filter\StripTags::class
                            ],
                        ],
                        'validators' => [
                            [
                                'name' => Validator\NotEmpty::class,
                            ],
                        ],
                    ],
                    'lockBy' => [
                        'required' => false,
                        'filters' => [
                            [
                                'name' => Filter\StringTrim::class
                            ], [
                                'name' => Filter\StripTags::class
                            ],
                        ],
                    ],
                    'lockAt' => [
                        'required' => false,
                        'filters' => [
                            [
                                'name' => Filter\StringTrim::class
                            ], [
                                'name' => Filter\StripTags::class
                            ], [
                                'name' => Filter\Callback::class,
                                'options' => [
                                    'callback' => function ($value) {
                                        // @codeCoverageIgnoreStart
                                        /** @var \DateTimeImmutable $lockAt */
                                        $lockAt = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value);
                                        if ($lockAt instanceof \DateTimeInterface) {
                                            return $lockAt;
                                        }
                                        return null;
                                        // @codeCoverageIgnoreEnd
                                    }
                                ]
                            ]
                        ],
                    ],
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function getResolvers(): array
    {
        return [
            Resolver\PersistResolver::class => 100,
            Resolver\MergeResolver::class => 300,
            Resolver\RemoveResolver::class => 200,
        ];
    }
}

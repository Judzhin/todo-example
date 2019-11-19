<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODO;

use Zend\Filter;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

/**
 * Class MessageInputFilter
 * @package TODO
 */
class MessageInputFilter extends InputFilter
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->add([
            'name' => 'operation',
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
        ])->add([
            'type' => InputFilter::class,
            'name' => 'data',

            'id' => [

            ],

        ]);
    }

}
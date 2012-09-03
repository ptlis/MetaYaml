<?php

namespace test\unit\RomaricDrigon\MetaYaml\NodeValidator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\NodeValidator\NumberNodeValidator as testedClass;
use RomaricDrigon\MetaYaml\SchemaValidator;

class NumberNodeValidator extends atoum\test
{
    public function testAll()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array('_metadata' => array('_required' => true)))
            ->then
                ->boolean($object->validate('test', $config, '10'))->isEqualTo(true)
                ->boolean($object->validate('test', $config, 10))->isEqualTo(true)
                ->boolean($object->validate('test', $config, 5.5))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('test', $config, 'test'); })
                    ->hasMessage('The node "test" is not numeric');
    }

    public function testStrict()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array('_metadata' => array('_required' => true, '_strict' => true)))
            ->then
                ->boolean($object->validate('test', $config, 10))->isEqualTo(true)
                ->boolean($object->validate('test', $config, 5.5))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('test', $config, '10'); })
                    ->hasMessage('The node "test" is not numeric')
                ->exception(function() use($object, $config) { $object->validate('test', $config, '5.5'); })
                    ->hasMessage('The node "test" is not numeric');
    }
}
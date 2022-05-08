<?php

namespace Root\SchemaRegistry;

use JsonSchema\Validator;

class SchemaValidator
{
    public static function check(array $data, string $domain, string $schemaName, int $version = 1): bool
    {
        $data = (object) $data;
        $schema = json_decode(file_get_contents(__DIR__."/Schemas/{$domain}/v{$version}/${schemaName}.json"));
        $validator = new Validator;
        $validator->validate($data, $schema);

        return $validator->isValid();
    }
}
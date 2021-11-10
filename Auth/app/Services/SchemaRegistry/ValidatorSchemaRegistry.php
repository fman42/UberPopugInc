<?php

namespace App\Services\SchemaRegistry;

use Illuminate\Support\Facades\Storage;

class ValidatorSchemaRegistry
{
    public static function check(array $data, string $domain, string $schemaName, int $version = 1)
    {
        $data = (object) $data;
        $schema = json_decode(Storage::get("UberPopugIncSchema/{$domain}/v{$version}/${schemaName}.json"));
        $validator = new \JsonSchema\Validator;
        $validator->validate($data, $schema, \JsonSchema\Constraints\Constraint::CHECK_MODE_APPLY_DEFAULTS);

        if ($validator->isValid()) {
            return true;
        } else {
            return false;
        }
    }
}
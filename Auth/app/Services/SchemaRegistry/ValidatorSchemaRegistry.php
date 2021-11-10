<?php

namespace App\Services\SchemaRegistry;

use Illuminate\Support\Facades\Storage;

class ValidatorSchemaRegistry
{
    public static function check(object $data, string $domain, string $schemaName, int $version = 1)
    {
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
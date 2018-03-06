<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Modules\System\Models\ListtypeModel::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'id' => $faker->PK_LISTTYPE,
        'code' => $faker->C_CODE,
        'name' => $faker->C_NAME,
        'xml_file_name' => $faker->C_XML_FILE_NAME,
        'status' => $faker->C_STATUS,
        'order' => $faker->C_ORDER,
    ];
});

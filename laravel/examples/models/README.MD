# Interacting With Database

[Back](../README.MD)

All interaction with the database should be done via Eloquent. Eloquent is an ORM that
abstracts all database specific code away leaving us to deal with PHP classes that represent
the underlying data.

### Links

[Laravel - Eloquent Models](https://laravel.com/docs/5.5/eloquent)  


## Creating Eloquent Models

Assuming we wanted to create a model that was stored in the database table 'test_entity'

From the command-line execute the following command.

```
cd /vagrant/laravel
php artisan make:model Models/TestEntity
```

This will create a file called `/laravel/app/Models/TestEntity`.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestEntity extends Model
{
    //
}
```

The first thing you need to do, is tell Laravel where in the database this model is
stored. Do this by overriding the `$table` variable from the parent class.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestEntity extends Model
{
    /** @var string $table */
    protected $table = 'test_entity';
}
```

Congratulations, that is all there is to creating a model and linking it to the database.

## Using A Model

```php

<?php

    use App\Models\TestEntity;

    class MyClass
    {
        public function createTestEntity()
        {
            $myEntity = new TestEntity();
            $myEntity->field = "value";
            $myEntity->save();          
        }        

        public function updateTestEntity()
        {
            $myEntity = TestEntity::where('id', '=', 25);
            $myEntity->field = "new value";
            $myEntity->save();          
        }   
        
        public function deleteTestEntity()
        {
            $myEntity = TestEntity::where('id', '=', 25);
            $myEntity->delete();
        }
        
        public function deleteAllTestEntity()
        {
            TestEntity::truncate();
        }      
    }

```

## Defining Relationships

[Laravel - Eloquent Relationships](https://laravel.com/docs/5.5/eloquent-relationships)

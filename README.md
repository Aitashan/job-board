# Job-Board-App -- A rough codeAlong guide.

1. Creating project using code below and then opening the folder in vscode.

```
composer create-project --prefer-dist laravel/laravel job-board
```

2. Configure db name, port e.t.c in the .env file.

3. Run migration to create the db and default tables. - check in phpMyAdmin later for db existence.

```
php artisan migrate
```

Note: These commands can be run from outside using desktop terminal or insdie the vs code terminal.

4. Create our first model.

```
php artisan make:model Job -mf
```

Note: Model name must be singular. -mf represnts two flags where 'm' runs migration and 'f' creates a factory.

5. Make a resource controller.

```
php artisan make:controller JobController --resource
```

Note: Must take care of naming convention how J and C is capital. --resource flag implements basic CRUD fn.s by default. If you are using the vscode terminal then you can ctrl click the created links for opeing files.

6. Adding columns to the migration table. These lines are added to the Schema funciton.

```
$table->string('title');
$table->text('description');
$table->unsignedInteger('salary');
$table->string('location');
$table->string('category');
$table->enum('experience', Job::$experience);
```

Note: a static array was created in the Job model as this experiece might come in handy in other places aswell.

```
public static array $experience = ['entry','intermediate','senior'];
```

Lastly an enum is a data type that enables as to have a set of constants for a variable.

7. Configure factory so we can have some fake data for testing.

```
            'title' => fake()->jobTitle,
            'description' => fake()->paragraphs(3, true),
            'salary' => fake()->numberBetween(5_000, 150_000),
            'location' => fake()->city,
            'category' => fake()->randomElement(Job::$category),
            'experience' => fake()->randomElement(Job::$experience)
```

Note: Dont forget to call the factory into the seeder before seeding the database. Take note of importing namesapces too or you can use full path instead.

```
Job::factory(100)->create(); // This will create 100 fake jobs
```

Finally to seed the db run the following command.

```
php artisan migrate:refresh --seed
```

Note: The migrate:refresh will overwirte all the old migrations dropping them down then populating the db with new ones.

8.

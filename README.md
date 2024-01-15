# Job-Board-App -- A rough codeAlong guide.

## Creating the project and seeding it with fake data for the first model.

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

## Installing debug-bar

1. This development tool displays additional info at the bottom of our laravel page and can simply be added using the following comamnd

```
composer require barryvdh/laravel-debugbar --dev
```

Note: --dev flag ensures its existence in the developemnt phase so when we go on to deploy our app it wont be there.

2. Run the server next and see if it looks fine.

## Installing Tailwind CSS for Laravel.

1. The most current installations can be found in the Tailwind docs. Here i used the following commands to intall it using Vite.

```
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

Note: make sure u have node installed. You can check by typing "node -v" in the terminal excluding quotes.

2. Next add this content into your tailwind.config.js file.

```
content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
```

Note: This basically add all the pathing to your template files. Taolwind will only include utility classes that were used, when saving the final package.

3. Next we need to add some tailwind directives to our app.css file in the resources folder.

```
@tailwind base;
@tailwind components;
@tailwind utilities;
```

Note: Dont worry about the errors just close the CSS file after pasting them in.

4. Finaly run this command in the terminal to compile all the assets

```
npm run dev
```

Note: dev is just a script which runs vite for now.

5. Finally add a vite directive to the head of html app layout.

```
@vite('resources/css/app.css')
```

Now tailwind can be used and the npm dev script runing in the backgroud refreshes the pages automatically.

## Making layouts using components.

1. To add a layout we will simply make a component with a --view flag as we dont need the corresponding php file for now.

```
php artisan make:component Layout --view
```

2. We will define a slot in our layout.

```
<body>
    {{$slot}}
</body>
```

3. Next we will add routes for the resource controller in our web.php file
   Route::resource('route_name', Controller::class)->only(['index']);
   only command limits the routes generated.

```
Route::resource('jobs', JobController::class);
```

dont forget to import the namespace, then check in the terminal using

```
php artisan route:list
```

4. Next we will make a job folder in views and make index.blade.php file for the genrated route.

5. Then we will define some actions in our exsting controllers. As we are using only index for now.
   we will add the following logic to the index function to display all jobs on the page.

```
return view('jobs.index', ['jobs' => Job::all()]);
```

6. Finaly to use the layout we can simply use the x-layout tag and use a foreach loop to display all the jobs.

```
<x-layout>
    for loop to display jobs
</x-layout>
```

## Adding a reusable card component.

1. we addded margins and set max width using tailwind classes.

2. we can also change background color simlarly something like "bg-slate-200" or text color to text-slate-700.

3. Next we will make some changes in the index compoment, we can give it a card design by adding following class properties:

```
class="rounded-md border border-slate-300 bg-white p-4 shadow-sm mb-4"
```

4. To reuse this card we can extract into a component by making another component via terminal.

```
php artisan make:component Card --view
```

Next we can just copy past the div into the new component and change parameter to $slot so we can pass data to it later.

5. The card componet can then be used again using <x-card> tag and if we want to pass some tailwind to it then it can be done using $attributes->merge() method on the template <div>

```
<div {{$attributes->merge(['class' => 'mb-4'])}}>
```

Note this can also be done by omiting class assignment and just using class method instead of merge. This gives us the oppertunity to use the classes as key and assign a value to them and class will be applied if the value is true.

```
<div {{$attributes->class(['rounded-md border border-slate-300', 'bg-white text-green-300' => $good, 'text-red' => $error])}}>
```

This should be done only once in the reusable template. Afterwards the clases can be added normally on the respective components.

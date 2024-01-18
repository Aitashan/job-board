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

## Styling and populating the Job index page.

1. To add things like job salary we can add a div with a flex class inside the x-card tag on the index blade file.

2. We can distinguish between the heading and other components by giving the title by setting its weight to medium and size to large.

3. Moreover we can give the salary container to have a more lighter shade of gray. We can also use the number_format() method around the salary and the currency symbol prefixed behind the php tag {{}}.

4. Now when we come to display the description we have set the paragraphs data-type which when displayed thorugh normal php notation does not show new lines and if we use a method like nl2br() which converts new lines to <br> line break tag. It does now get translated properly and insted of new lines <br> is seen written in text.

5. So to counter this problem we will use the {!! $code !!} instead of {{ $code }} to properly interpolate the tags.

```
{!! nl2br(e($job->description)) !!}
```

To be more rohbust and on the safe side a escaping method is also wraped around the data.

6. We can then give the description a smaller text size and lighter grey shade by using text-sm text-slate-500 class.

7. Finally we all 2 more flex containers for the remaning fields and style them accordingly. Here on the left Company Name and location is displayed. On the right in a more smaller text size expereince and category are displayed.

Note: To make the first letter capital Str::ucfirst() can be wraped around the desired data param. For Vertical alignment items-center class is used.

8. To reduce repetition we can make more components like a tag component to use for our category and experince fields.

Note: Dont forget to use the {{$attributes->class([' classes here '])}} for the template. Then simply wrap each field with its own <x-tag>. Eventhough attributes->class method was not necssary here but in future we wanted to add more classes then it will surely come in handy.

9. Lastly we can make a redirection in routes for the main page.
   this goes in in the web php

```
Route::get('', [JobController::class,'redirect'])
```

whereas this function can be added in JobConroller.

```
    public function redirect()
    {
        return redirect()->route('jobs.index');
    }
```

## Adding another page for Job details.

1. For displaying such page we first need to re-enable the show route in web php. We can do that by just adding another value in the only method array.

Note: You can confirm the route by using the php artisan route:list command in the terminal. The default routes are named automatically.

2. Make a show blade template for the show route.

3. Next we will modify the show action/ fn in the JobController by using route model binding

```
public function show(Job $job)
{
    return view('job.show', compact('job'))
}
```

Note: The compact method creates an array named 'job' using the $job variable.

4. Next we will go into the show blade file to set up the basic desing using the layout and card component.

5. Then we will make a link to the show page on the main index page. This can be done simply by adding a link below the description.

```
<a href={{route('jobs.show', $job)}}>Check Job</a> // just interpolating the result of route function
```

6. Aftwards we can simply style it as a button and extract all the styles into another component for resuability later.

7. When making the LinkButton view component we can $href instead of the absolute route fn. Then in the index view we can simply pass a param thorugh the tag using <x-link-button :href="route()">. To pass classes we will simply use another set of {{}} and pass in the $attributes->class(['']) method.

Note: we dont need php brackets when passing data thorugh the component variable as blade component is already expecting some php to be passed onto the variable. Just dont forget to use double quotes "".

8. To refactor further we can make yet another component for displaying simple job data JobCard view component.

9. In this way we can use the the <x-job-card :job="$job"> can be used on both index and show pages. The index page will also have the <x-link-button> nested inside. This way the index will display all the jobs and show will only display one job.

Note: If the variable name is same as attribute name like :job="$job" then we can shorthand it with ony using :$job. If no other data is passes you can also self close the tag.

## Implementing Navigation.

1. We will first test a basic nav deisgn on the show page then extract it later into a seprate component to be reused later.

2. Using a flex class on the list elements we can get a basic design that shows our current location on the site.

3. Next we make another component named Breadcrumbs to extract the navigations for reuse later this time we will need the class aswell.

4. If we want to pass classes to the component later then we can simply use the $attributes->class(['']) methhod again but if there are no classes in the template then we can also emit class and shorthand it to just $attributes.

5. To make this breadcrumbs universal and not just related to the job, we eed to pass an array variable on every page with links we need. Then using the foreach loop we can iterate over those links for every page differently. For instance the show page

```
<x-breadcrumbs :links="['Jobs' => route('jobs.index'), $job->title => '#']">
```

6. In the breadcrumbs template we will use the for loop like this

```
@foreach($links as $label => $link)
    <li>&rArr;</li>
    <li>
        <a href="{{ $link }}">{{ $label }}</a>
    </li>
@endforeach
```

7. Next in the breadcrumbs class we will have to pass the custom attributes in the construct fn. This due to the fact that we can not pass an array through the {{$attributes}} and we were trying to pass :links[..] which resulted in an error.

```
_construct(public array $links)
```

Note: As all the attributes that gets passed through the $attributes gets added to the attribute bag which only accepts strings so in order to tackle this we will add the said array manually to the breadcrumbs class.

8. We can simlarly now add the breadcrumbs to any page just by configuring the array of links as in case for index we wont be needing the single job link so we will omit that.

## Implementing custom textBoxes for filters.

1. First we will need to install a tailwind plugin for styling the forms.

```
npm install -D @tailwindcss/forms
```

Note: we will need to stop the run dev for awhile while we install and configure the plugin.

2. To configure we just need to add this line to the plugins array. Afterwards we just restart the dev again.

```
require("@tailwindcss/forms")
```

3. Next we will add a seperate card on top for filtering our jobs. This is done using a <div> inside the <x-card> with a grid class.

4. Then we will make a custom TextInput component with both views and class as we will be defining some attributes this time.

5. Inside the construct fn of the class we will define some properties as follows.

```
    public function __construct(
        public ?string $value = null,
        public ?string $name = null,
        public ?string $placeholder = null,
    )
```

Note: The values are intialized as null and the ? represents that they are nullable i.e they can be left empty.

6. Next we will jump into the blade view of the inputtext and create input.

```
<input type="text" placeholder="{{$placeholder}}" name="{{$name}}" value="{{$value}}" id="{{$name}}">
```

Note this will set the basic attributes so we can re-use this custom input later on other pages aswell. Id will come in handy later when we start using javascript.

7. Then we will add this custom component to the index layout and style it accordingly. Here we will not use : for variables as the data we are trying to pass through is just string there is no logic involved.

8. Similary we can modify the textInput for min and max salary by defining a flex container.

## Connecting the backend to our filter inputs.

1. Made a slight change in the layout a short description is shown on the index while full description of the job will be available on the show page.

2. To get the filtering to work we first need to wrap the contents of the <x-card> into a <form> with a get method and a action that submits to the jobs.index page.

Note: The GET method basically adds the form submision to the URL.

3. Next we will add a button at the end of the form and style it accordingly.

4. Then we will go to the index action in the JobController and specify the parameters for our query.

```
$jobs = Job::query();

// Conditional filters will now be as followed.

$jobs->when(request('search'), function ($query)
{
    $query->where('title', 'like', '%' . request('search') . '%')
});

return view ('jobs.index', ['jobs' => $jobs->get()]);
```

Note: The % sign make sure that when searching there could be text behind or after it.

5. To make the search inputs retain the searched values we just need to add the current request to the value parameter in the index page. For example the for title we can add value="{{request('search')}}"

6. Next we will add additonal when methods for the min/max salary in the JobController. Now as the and operator gets presedence over or we might get unexpected results. So in order to be able to search both by title and description, we will encapsulate the name queries into an where method.

```
$jobs->when(request('search'), function ($query)
        {
            $query->where(function ($query)
            {
                $query->where('title','LIKE','%'. request('search') .'%')
                ->orWhere('description','LIKE','%'. request('search') .'%');
            });
        })->when(request('min_salary'), function ($query)
        {
            $query->where('salary','>=', request('min_salary'));
        })->when(request('min_salary'), function ($query)
        {
            $query->where('salary','<=', request('max_salary'));
        });
```

## Adding RadioButtons.

1. Now to add radioButtons for experience and categories, we will start by adding a headings div.

2. Next we will duplicate a combination of label and input to display radio buttons. Later on we will extract it into a seperate component.

```
<label for="experience" class="mb-1 flex items-center">
    <input type="radio" name="experience" value="" />
    <span class="ml-2">All</span>
</label>
```

3. Then another when query is added to the index action for experience. To retain the checked button we will use the @checked directive in every input tag.

```
@checked(!request('experience'))

@checked(request('experience') === 'entry')
```

4. Next we will extract this chunk of code into a seperate RadioGroup component and assign some properties through the construct function on the class.

```
public string $name,
public array $options
```

5. Then we will make the template as follows.

```
<div>
    <label for="{{'b-all-' . $name}}" class="mb-1 flex items-center">
        <input id="{{'b-all-' . $name}}" type="radio" name="{{ $name }}" value="" @checked(!request($name)) />
        <span class="ml-2">All</span>
    </label>
    @foreach ($options as $bn => $option)
        <label for="{{ 'b-' . $bn . $name }}" class="mb-1 flex items-center">
            <input id="{{ 'b-' . $bn . $name }}" type="radio" name="{{ $name }}" value="{{ $option }}"
                @checked(request($name) === $option) />
            <span class="ml-2">{{ Str::ucfirst($option) }}</span>
        </label>
    @endforeach
</div>

```

Instead of using the laravel string method we can also do it in a purely php way by making a method in the component class which by using the values of an indexed array turns it into an associative array, then we can simply use the ucfirst flag with the array map method on the array lables. Eventhough this may seem redundant but it might come in handy later when we want to pass some complicated methods through a php :param.

```
array_combine(array_map('ucfirst', ['can', 'also', 'pass', '$var', 'here']), [same array again]);
```

Note: $bn(index) helps us in making a unique id for each button.

6. For adding this template to index we will just view template along with name and :options

```
<x-radio-group name="category" :options="\App\Models\Job::$category" />
```

7. Now we can just repeat the same line for category and a when/where method respectively in the controller.

Note: When is used to deal with input requests if it is not null the function containg where conditional will run.

## Adding clear 'x' to the inputs.

1. we can copy or download x or close icon svg from any site like heroicon e.t.c

2. Next we will go into our textInput template wrap the whole input tag in a <div> add a button and then we will postion it in an absolute manner using some css.

3. We will set the relative class on the encapsulating div so that the absolute positioning remains scoped to the input rather than the whole page.

4. We can make the button flex conatainer taking full height then use items-center to align it vertically. Afterwards we can add some padding to the input so that the text and button does not overlap.

5. After styling the icon and postion, we will now add a onclick handler on the button

```
onclick="document.getElementById('{{ $name }}').value=''"
```

6. To see the result i.e submit the form after clearing the input, we will add the formId var to the inputText class. We will also wrap the whole button with @if direcitve which only displays the button if the formId is present.

7. Next to pass the id we will set an id to the form tag, then pass that same id to all the input compoments turning the camelCase var to snake-case that is here the formId will look like form-id.

8. Lastly to submit the form we will pass in $formId with .submit method. So the whole inline js will look like this.

```
onclick="document.getElementById('{{ $name }}').value=''; document.getElementById('{{ $formId }}').submit();"
```

## Making some style changes, clickable categories/exp btns and installing alpine.

1. To make gradient background we can visit the tailwind docs and look for gradient colorstops copy from one of the exisiting examples. Afterwards we can simply replace our existing bg in the app.layout and customize it accordingly.

```
bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-90%
```

2. Next we will turn the tags into clickable links which will add the query param respectively by wraping tag contents in an <a> tag. A basic route and its query parameter is passed along with it.

```
<a href="{{route('jobs.index', ['experience' => $jobs->experience])}}">
```

3. We will make a view component for the big filter button. We will use $attributes->class() method in this view as we also want extra style classes added externally like 'w-full'.

4. Now we will go on to installing Alpine library for js. This can be done by simply using the npm command in the terminal. Afterwards we will add some config into the bootstrap.js file and then simply use the vite directive on with app.js file which is fundamently importing the bootstrap file.

Note: all these commands and instructions are provided onto the alpinjs docs.

5. Restart npm run dev.

## Using Alpine instead of vanila Js.

1. Now that we have Alpine instanlled and working we will replace the close button logic using this library in the text-input component. The main benefit of using frameworks like alpine is that we can seperate the data from logic while working in-line.

2. By defining x-data directive to a html block we can transform it into an alpine component. Then we can simply add some other directives or events within the scope of the block to make interactive logic.

3. So now first we will use the x-data directive on the x-card containing the from on index page. Then we will defeine a x-ref on the form instead of an id. Similarly we will change the formId param to fromRef in the textInput class. Finally we will also make this change on each seperate textInput.

4. Now in the textInput component we will start by first getting rid the inline Js and the alpine @click directive and set two $refs one for input value and the other for submit.

```
@click="$refs['input-{{ $name }}'].value=''; $refs['{{ $formRef }}'].submit();"
```

We will aslo set an x-ref directive on the the input for the value part to work.

## Defining local query scopes in the model.

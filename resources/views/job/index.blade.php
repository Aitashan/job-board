<x-layout>
    <x-breadcrumbs class="mb-4" :links="['Jobs' => route('jobs.index')]" />

    <x-card class="mb-4 text-sm">
        <form action="{{ route('jobs.index') }}" method="GET">
            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <div class="mb-1 font-semibold">Search</div>
                    <x-text-input name="search" value="{{ request('search') }}" placeholder="type here to search ...." />
                </div>
                <div>
                    <div class="mb-1 font-semibold">Salary</div>
                    <div class="flex space-x-2">
                        <x-text-input name="min_salary" value="{{ request('min_salary') }}" placeholder="From" />
                        <x-text-input name="max_salary" value="{{ request('max_salary') }}" placeholder="To" />
                    </div>
                </div>
                <div>
                    <div class="mb-1 font-semibold">Experience</div>
                    <label for="b-all" class="mb-1 flex items-center">
                        <input id="b-all" type="radio" name="experience" value=""
                            @checked(!request('experience')) />
                        <span class="ml-2">All</span>
                    </label>
                    <label for="b-entry" class="mb-1 flex items-center">
                        <input id="b-entry" type="radio" name="experience" value="entry"
                            @checked(request('experience') === 'entry') />
                        <span class="ml-2">Entry</span>
                    </label>
                    <label for="b-inter" class="mb-1 flex items-center">
                        <input id="b-inter" type="radio" name="experience" value="intermediate"
                            @checked(request('experience') === 'intermediate') />
                        <span class="ml-2">Intermediate</span>
                    </label>
                    <label for="b-senior" class="mb-1 flex items-center">
                        <input id="b-senior" type="radio" name="experience" value="senior"
                            @checked(request('experience') === 'senior') />
                        <span class="ml-2">Senior</span>
                    </label>
                </div>
                <div>4</div>
            </div>
            <button class="w-full">Filter</button>
        </form>
    </x-card>


    @foreach ($jobs as $job)
        <x-job-card class="" :$job>
            <p class="text-sm text-slate-500 mb-4">
                {{ str($job->description)->words(20) }}
            </p>
            <div>
                <x-link-button :href="route('jobs.show', $job)">
                    Show Job
                </x-link-button>
            </div>
        </x-job-card>
    @endforeach
</x-layout>

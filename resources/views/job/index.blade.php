<x-layout>
    <x-breadcrumbs class="mb-4" :links="['Jobs' => route('jobs.index')]" />

    <x-card class="mb-4 text-sm" x-data="">
        <div>{{ request()->getClientIp() }}</div>
        <form x-ref="h-filter" action="{{ route('jobs.index') }}" method="GET">
            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <div class="mb-1 font-semibold">Search</div>
                    <x-text-input name="search" value="{{ request('search') }}" placeholder="type here to search ...."
                        form-ref="h-filter" />
                </div>
                <div>
                    <div class="mb-1 font-semibold">Salary</div>
                    <div class="flex space-x-2">
                        <x-text-input name="min_salary" value="{{ request('min_salary') }}" placeholder="From"
                            form-ref="h-filter" />
                        <x-text-input name="max_salary" value="{{ request('max_salary') }}" placeholder="To"
                            form-ref="h-filter" />
                    </div>
                </div>
                <div>
                    <div class="mb-1 font-semibold">Experience</div>
                    <x-radio-group name="experience" :options="\App\Models\Job::$experience" />
                </div>
                <div>
                    <div class="mb-1 font-semibold">Category</div>
                    <x-radio-group name="category" :options="\App\Models\Job::$category" />
                </div>
            </div>
            <x-button class="w-full">Filter</x-button>
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

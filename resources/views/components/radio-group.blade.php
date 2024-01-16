<div>
    <label for="{{ 'b-all-' . $name }}" class="mb-1 flex items-center">
        <input id="{{ 'b-all-' . $name }}" type="radio" name="{{ $name }}" value=""
            @checked(!request($name)) />
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

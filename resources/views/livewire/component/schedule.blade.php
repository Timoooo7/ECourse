<div class="">
    <div class="flex mt-4">
        <flux:dropdown class="me-2">
            <flux:button icon:trailing="chevron-down" icon="funnel">Filter</flux:button>
            <flux:menu wire:init="loadTeacher">
                <div class="hidden! xl:block!">
                    <flux:menu.submenu heading="Day">
                        <flux:menu.radio.group wire:model.live="day_filter">
                            @foreach ($days as $i => $day)
                                <flux:menu.radio value="{{ $i }}">{{ $day }}</flux:menu.radio>
                            @endforeach
                        </flux:menu.radio.group>
                    </flux:menu.submenu>
                </div>
                <flux:menu.submenu heading="Teacher">
                    <flux:menu.radio.group wire:model.live="teacher_filter">
                        <flux:menu.radio value="0">All</flux:menu.radio>
                        @if ($teacher_list)
                            @foreach ($teacher_list as $teacher)
                                <flux:menu.radio value="{{ $teacher->id }}">{{ $teacher->name }}</flux:menu.radio>
                            @endforeach
                        @endif
                    </flux:menu.radio.group>
                </flux:menu.submenu>
            </flux:menu>
        </flux:dropdown>
        <flux:input placeholder="Search" icon="magnifying-glass" wire:model.live.debounce.500ms="search_filter" />
    </div>
    <div class="xl:hidden flex rounded-xl bg-zinc-100 p-2 mt-4">
        @foreach ($days as $i => $day)
            <span wire:click="setPhoneDay({{ $i }})"
                class="p-1 px-2 text-sm text-center rounded-lg {{ $phone_selected_day == $i ? 'bg-white w-full shadow-sm' : 'bg-zinc-100 ' }}">
                {{ substr($day, 0, 3) }}

            </span>
        @endforeach
    </div>
    <div class="grid xl:grid-cols-5 gap-4 p-2 bg-white border-t-4 border-t-lime-400 rounded-xl mt-4 shadow-md">
        @foreach ($batch_list->groupBy('day') as $d => $day_batch)
            <div class="{{ $phone_selected_day == $d || $phone_selected_day == 0 ? 'block' : 'hidden' }} xl:block">
                <p class="mb-0 text-sm text-center text-lime-600 font-semibold  p-1 rounded-t-lg">
                    <span class="hidden xl:block">{{ $days[$d] }}</span>
                    <span wire:loading.remove class="block xl:hidden">{{ $days[$d] }}</span>
                    @if ($phone_selected_day == $d)
                        <span wire:loading.class="text-zinc-400 text-sm xl:hidden block" wire:loading>Loading..</span>
                    @endif
                </p>
                @foreach ($day_batch as $batch)
                    <div class="border-t border border-zinc-200 mt-3 rounded-lg"
                        style="border-top-color:var(--color-{{ $batch->teacher ? ($batch->teacher->color ?: 'zinc') : 'zinc' }}-400)">
                        <div class="flex text-sm p-1 rounded-lg"
                            style="background-color:var(--color-{{ $batch->teacher ? ($batch->teacher->color ?: 'zinc') : 'zinc' }}-50)">
                            <span class="text-indigo-800 font-semibold ms-auto">{{ substr($batch->time, 0, 5) }}</span>
                            <span
                                class="ms-2 me-auto">{{ $batch->teacher ? ($batch->teacher->gender == 'male' ? 'Mr.' : 'Ms.') . $batch->teacher->name : 'Unset' }}</span>
                        </div>
                        <div class="grid grid-cols-2 p-2 gap-2">
                            @foreach ($batch->students as $student)
                                <span class="text-zinc-500">{{ $student->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>

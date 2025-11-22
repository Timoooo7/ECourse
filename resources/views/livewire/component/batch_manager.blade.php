<div class=" bg-white rounded-xl p-4 border border-zinc-200 shadow-md ">
    <div class="flex mb-2">
        <p class="xl:text-xl text-lg font-semibold my-auto">Study Group
            <span class="text-zinc-400 text-sm ms-2 animate-pulse" wire:loading>Loading...</span>
        </p>
        <flux:modal.trigger name="add-batch">
            <flux:button icon="squares-plus" class="p-2! ms-auto!"></flux:button>
        </flux:modal.trigger>
        {{-- Modal --}}
        <flux:modal name="add-batch" class=" bg-white rounded-xl p-4" wire:model.self="showAddBatchModal">
            <form wire:submit="addBatch">
                <p class="text-xl font-semibold text-indigo-800 mb-4">Add Study Group</p>
                <flux:select wire:model="b_day" label="Day" class="w-full mb-4" placeholder="Choose day">
                    <flux:select.option label="Monday" value="1" />
                    <flux:select.option label="Tuesday" value="2" />
                    <flux:select.option label="Wednesday" value="3" />
                    <flux:select.option label="Thursday" value="4" />
                    <flux:select.option label="Saturday" value="6" />
                </flux:select>

                <flux:input type="time" label="Time" wire:model="b_time" />
                <button type="submit"
                    class="rounded-lg bg-indigo-600 hover:bg-indigo-700 shadow-md hover:shadow-sm text-white text-center mt-4 w-full p-2">Submit</button>
            </form>
        </flux:modal>
    </div>
    <flux:input icon="magnifying-glass" placeholder="Search batch/teacher/student"
        wire:model.live.debounce.500ms="search_name" />
    <div class="hidden xl:block">
        <flux:radio.group wire:model.live="filter_day" variant="segmented">
            <flux:radio label="All" value="0" />
            @foreach ($days as $i => $day)
                <flux:radio label="{{ $day }}" value="{{ $i }}" />
            @endforeach
        </flux:radio.group>
    </div>
    <div class="xl:hidden flex rounded-xl bg-zinc-100 p-2 mt-4">
        @foreach ($days as $i => $day)
            <span wire:click="setPhoneFilterDay({{ $i }})"
                class="p-1 px-2 text-sm text-center rounded-lg {{ $filter_day == $i ? 'bg-white w-full shadow-sm' : 'bg-zinc-100 ' }}">
                {{ substr($day, 0, 3) }}
            </span>
        @endforeach
    </div>

    <div class="max-h-160 overflow-y-auto mt-6 rounded-xl">
        {{-- Batch --}}
        @if ($batch_list)
            @foreach ($batch_list as $batch)
                <div class="rounded-xl border border-zinc-200 p-2 mb-3">
                    <div class="flex px-2 bg-white">
                        @if ($filter_day == 0)
                            <span class="text-indigo-800 text-sm">{{ $days[$batch->day] }}</span>
                        @endif
                        <span class="ms-auto">{{ 'Batch ' . $batch->id }}</span>
                        <flux:separator vertical class="mx-2" />
                        <span class="text-indigo-800 me-auto">{{ substr($batch->time, 0, 5) }}</span>
                        <button class="px-1 rounded-lg bg-white hover:bg-zinc-50"
                            wire:click="deleteBatch({{ $batch->id }})"
                            wire:confirm.prompt="Confirm delete study group Batch {{ $batch->id }}?\n\nType DELETE to confirm|DELETE">
                            <flux:icon.trash class="inline size-4 text-zinc-500" />
                        </button>
                    </div>
                    <div class="flex px-2"
                        style="background-color:var(--color-{{ $batch->teacher ? $batch->teacher->color : 'yellow' }}-50)">
                        <span
                            class="text-sm">{{ $batch->teacher ? ($batch->teacher->gender == 'male' ? 'Mr.' : 'Ms.') . $batch->teacher->name : 'Unset Teacher' }}</span>
                    </div>
                    <div class="border-t "
                        style="border-top-color:var(--color-{{ $batch->teacher ? $batch->teacher->color : 'yellow' }}-400)">
                        @if ($batch->students)
                            @foreach ($batch->students as $i => $student)
                                <div class="flex">
                                    <span class="p-1 text-sm my-auto ">{{ $i + 1 }}</span>
                                    <flux:separator vertical class="mx-2 my-1" />
                                    <span class="my-auto text-green-800">{{ $student->name }}</span>
                                    <span
                                        class="my-auto ms-auto text-sm text-zinc-400">{{ $student->school->name }}</span>
                                </div>
                            @endforeach
                        @else
                            <span class="text-zinc-400 font-italic text-sm">Unregisted</span>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
        @endif
    </div>
</div>

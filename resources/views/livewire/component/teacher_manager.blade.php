<div class=" bg-white rounded-xl p-4 border border-zinc-200 shadow-md">
    <div class="flex mb-2">
        <p class="text-xl font-semibold my-auto">Teachers</p>
        <span class="py-1 px-2 bg-indigo-200 text-indigo-800 ms-auto rounded-xl ">{{ $teacher_list->count() }}
            <flux:icon.user-group variant="solid" class="inline pb-1" />
        </span>
    </div>
    <div class="flex mb-6">
        <flux:input icon="magnifying-glass" placeholder="Search teacher" wire:model.live.debounce.500ms="search_name" />
        <flux:modal.trigger name="add-teacher">
            <flux:button icon="user-plus" class="p-2! ms-2!"></flux:button>
        </flux:modal.trigger>
        {{-- Modal --}}

        <flux:modal name="add-teacher" class="w-auto md:w-96 bg-white rounded-xl p-4"
            wire:model.self="showAddTeacherModal">
            <form wire:submit="addTeacher">
                <p class="text-xl font-semibold text-indigo-800 mb-4">Add Teacher</p>
                <flux:input class="outline-indigo-200! mb-4" label="Name" placeholder="type here.."
                    wire:model="t_name" />
                <flux:radio.group wire:model="t_gender" label="Gender" variant="segmented" class="w-full ">
                    <flux:radio label="Male" value="male" />
                    <flux:radio label="Female" value="female" />
                </flux:radio.group>
                <button type="submit"
                    class="rounded-lg bg-indigo-600 hover:bg-indigo-700 shadow-md hover:shadow-sm text-white text-center mt-4 w-full p-2">Submit</button>
            </form>
        </flux:modal>
    </div>

    @if ($teacher_list)
        @foreach ($teacher_list as $index => $teacher)
            <div class="flex p-2 rounded-lg border border-zinc-200 mb-3 border-s-3"
                style="border-inline-start-color:var(--color-{{ $teacher->color ?: 'zinc' }}-400)">
                <span class="font-light p-2 rounded-lg"
                    style="background-color:var(--color-{{ $teacher->color ?: 'zinc' }}-50)">{{ $index + 1 }}</span>
                <flux:separator vertical class="mx-2" />
                <span
                    class="my-auto  text-{{ $teacher->gender == 'male' ? 'indigo' : 'pink' }}-800 p-1 rounded-lg">{{ $teacher->gender == 'male' ? 'Mr.' : 'Ms.' }}</span>
                <span class="block my-auto">{{ $teacher->name }}</span>
                <flux:modal.trigger name="assign-teacher" wire:init="loadBatch">
                    <button type="button"
                        class="bg-indigo-50 hover:bg-indigo-100 text-indigo-600 ms-auto rounded-s-lg px-2 text-sm"
                        wire:click="setTeacher({{ $teacher }})">Assign {{ $teacher->batchs->count() }}
                    </button>
                </flux:modal.trigger>
                <flux:modal.trigger name="edit-teacher">
                    <button type="button" class="ms-1 rounded-e-lg bg-zinc-100 p-2 hover:bg-zinc-200"
                        wire:click="setTeacher({{ $teacher }})">
                        <flux:icon.user style="color:var(--color-{{ $teacher->color ?: 'black' }}-800);" />
                    </button>
                </flux:modal.trigger>
            </div>
        @endforeach
    @else
        <div class="text-zinc-400" wire:loading>Loading...</div>
    @endif
    {{-- Modal --}}
    <flux:modal name="assign-teacher" class=" bg-white rounded-xl p-4" wire:model.self="showAssignTeacherModal">
        <form wire:submit="assignTeacher">
            <p class="text-xl font-semibold text-indigo-800 mb-4">
                Assign Teacher <span class="animate-pulse text-sm ms-2 font-normal" wire:loading>Loading...</span>
            </p>
            <div class="flex mb-4">
                <span class="text-zinc-400">Name</span>
                <span class="ms-2" wire:loading.remove wire:text="selected_teacher_name"></span>
            </div>
            <div class="flex">
                <span class="text-zinc-400">Assign to</span>
                <span class="ms-auto p-1 text-indigo-800 text-sm bg-indigo-200 rounded-lg">
                    {{ $selected_teacher ? $selected_teacher->batchs->count() : 0 }}
                    <flux:icon.squares-2x2 class="inline" variant="solid" />
                </span>
            </div>
            <div class="h-70 overflow-y-auto">
                @if ($batch_list->count() > 0)
                    @foreach ($batch_list->groupBy('day') as $day => $day_batch)
                        <p class="mb-0 p-1 bg-zinc-100 text-slate-700 text-center">
                            {{ $days[$day] }}
                        </p>
                        <div class="grid grid-cols-4 gap-1 mb-2">
                            @foreach ($day_batch as $batch)
                                <div wire:click="updateTeacherBatch({{ $batch->id }})"
                                    class="rounded-lg p-2 text-center border border-zinc-200  {{ $batch->teacher ? ($batch->teacher_id == $selected_teacher_id ? 'bg-indigo-50! hover:bg-indigo-100! text-indigo-800!' : 'bg-slate-100!  text-zinc-400!') : 'bg-white text-zinc-400 hover:bg-zinc-50' }}">
                                    <div class="mx-auto flex ">
                                        <span>{{ substr($batch->time, 0, 5) }}</span>
                                        <flux:separator vertical class="mx-1 " />
                                        <span>{{ $batch->teacher_id > 0 ? $batch->teacher->initials() : '' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @else
                    <span class="block mt-0 text-red-400 font-italic">Create a batch first</span>
                @endif
            </div>
            <span class="block text-sm text-zinc-400">note:</span>
            <div class="grid grid-cols-3 gap-1 ">
                <div class="rounded-lg p-1 text-sm text-center border border-zinc-200 bg-white ">
                    available
                </div>
                <div class="rounded-lg p-1 text-sm text-center border border-zinc-200 bg-slate-200 text-zinc-400 ">
                    unavailable
                </div>
                <div class="rounded-lg p-1 text-sm text-center border border-zinc-200 bg-indigo-50 text-indigo-800 ">
                    assigned
                </div>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="edit-teacher" class=" bg-white rounded-xl p-4" wire:model.self="showEditTeacherModal">
        <p class="text-xl font-semibold text-indigo-800 mb-4">
            <flux:icon.user class="inline-block bg-blue-600 rounded-lg p-1 fill-white text-white me-2" />
            Edit Teacher <span class="animate-pulse text-sm ms-2 font-normal" wire:loading>Loading...</span>
        </p>
        <flux:input type="text" wire:model="ut_name" label="Name" size="sm" class="mb-2" />
        <flux:radio.group wire:model="ut_gender" label="Gender" variant="segmented" class="mb-2">
            <flux:radio label="Male" value="male" />
            <flux:radio label="Female" value="female" />
        </flux:radio.group>
        <span class="mb-2 text-sm">Group Color</span> <span wire:text="ut_color"></span>
        <div class="grid grid-cols-5 gap-2 mt-1">
            @foreach ($colors as $color)
                <div wire:click="setColor('{{ $color }}')">
                    <div class="h-8 border-t-3 flex"
                        style="background:var(--color-{{ $color }}-50); border-top-color:var(--color-{{ $color }}-400);">
                        <flux:icon.check class="inline-block mx-auto {{ $color == $ut_color ? '' : 'hidden!' }}" />
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex mt-4" wire:loading.class="opacity-50 animate-pulse">
            <flux:button icon="bookmark" type="button" wire:click="editTeacher" class="w-full me-2">Save
            </flux:button>
            <flux:button icon="trash" type="button" wire:click="deleteTeacher('{{ $selected_teacher_id }}')"
                variant="danger">Delete
            </flux:button>
        </div>
    </flux:modal>

</div>

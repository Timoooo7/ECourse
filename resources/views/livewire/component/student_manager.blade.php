<div class=" bg-white rounded-xl p-4 border border-zinc-200 shadow-md">
    <div class="flex mb-2">
        <p class="text-xl font-semibold my-auto">Students</p>
        <span class="py-1 px-2 bg-green-200 text-green-800 ms-auto rounded-xl ">{{ $student_list->count() }}
            <flux:icon.user-group variant="solid" class="inline pb-1" />
        </span>
    </div>
    <div class="flex mb-6">
        <flux:input icon="magnifying-glass" placeholder="Search student"
            wire:model.live.debounce.500ms="search_name_student" />
        <flux:modal.trigger name="add-student">
            <flux:button icon="user-plus" class="p-2! ms-2!"></flux:button>
        </flux:modal.trigger>
        {{-- Modal --}}

        <flux:modal name="add-student" class="bg-white rounded-xl p-4" wire:model.self="showAddStudentModal">
            <form wire:submit="addStudent">
                <p class="text-xl font-semibold text-indigo-800 mb-4">Add Student</p>
                <flux:input class="outline-indigo-200! mb-4" label="Name" placeholder="type here.."
                    wire:model="s_name" />
                <flux:radio.group wire:model="s_gender" label="Gender" variant="segmented" class="w-full mb-4">
                    <flux:radio label="Male" value="male" />
                    <flux:radio label="Female" value="female" />
                </flux:radio.group>
                <flux:select label="School" wire:model="s_school" placeholder="Choose school..." wire:init="loadSchool">
                    @if ($school_list)
                        @foreach ($school_list as $school)
                            <flux:select.option value="{{ $school->id }}">{{ $school->name }}</flux:select.option>
                        @endforeach
                    @else
                        <span class="text-zinc-400 animate-pulse" wire:loading>Loading...</span>
                    @endif
                </flux:select>
                <button type="submit"
                    class="rounded-lg bg-indigo-600 hover:bg-indigo-700 shadow-md hover:shadow-sm text-white text-center mt-4 w-full p-2">Submit</button>
            </form>
        </flux:modal>
    </div>

    <div class="h-96 overflow-y-auto">
        @if ($student_list)
            @foreach ($student_list as $index => $student)
                <div class="flex p-2 rounded-lg border border-zinc-200 mb-3">
                    <span class="font-light p-2">{{ $index + 1 }}</span>
                    <flux:separator vertical class="mx-2" />
                    <span class="block my-auto">{{ $student->name }}</span>
                    <span class="block my-auto ms-auto text-sm text-zinc-400">{{ $student->school->name }}</span>
                    <flux:modal.trigger name="assign-student" wire:init="loadBatch">
                        <button type="button"
                            class="bg-green-50 hover:bg-green-100 text-green-600 ms-2 rounded-s-lg px-2 text-sm"
                            wire:click="setStudent({{ $student }})">Meets
                            {{ $student->batchs ? $student->batchs()->count() : 0 }}
                        </button>
                    </flux:modal.trigger>
                    <flux:modal.trigger name="edit-student" wire:init="loadSchool">
                        <button type="button"
                            class="bg-zinc-50 hover:bg-zinc-100 text-green-800 ms-1 rounded-e-lg px-2 text-sm"
                            wire:click="setStudent({{ $student }})">
                            <flux:icon.user />
                        </button>
                    </flux:modal.trigger>
                </div>
            @endforeach

            {{-- Modal --}}
            <flux:modal name="assign-student" class="bg-white rounded-xl p-4" wire:model.self="showAssignStudentModal">
                <p class="text-xl font-semibold text-green-800 mb-4">
                    Student Meets <span class="animate-pulse text-sm ms-2 font-normal" wire:loading>Loading...</span>
                </p>
                <div class="flex mb-4">
                    <span class="text-zinc-400">Name</span>
                    <span class="ms-2">
                        {{ $selected_student ? $selected_student->name : '' }}</span>
                </div>
                <div class="flex mb-4">
                    <span class="text-zinc-400">School</span>
                    <span class="ms-2">
                        {{ $selected_student ? $selected_student->school->name : '' }}</span>
                </div>
                <div class="flex">
                    <span class="text-zinc-400">Meets in</span>
                    <span class="ms-auto p-1 text-green-800 text-sm bg-green-200 rounded-lg">
                        {{ $selected_student ? ($selected_student->batchs ? $selected_student->batchs->count() : 0) : 0 }}
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
                                    <div wire:click="updateStudentBatch({{ $batch->id }})"
                                        class="rounded-lg py-2 flex text-sm border border-zinc-200  {{ $batch->students && $selected_student ? ($batch->students->find($selected_student->id) ? 'bg-green-50! hover:bg-green-100! text-green-800!' : 'bg-white text-zinc-400 hover:bg-zinc-50') : 'bg-white text-zinc-400 hover:bg-zinc-50' }}">
                                        <div class="mx-auto flex ">
                                            <span>{{ substr($batch->time, 0, 5) }}</span>
                                            <flux:separator vertical class="mx-1 " />
                                            <span>{{ $batch->teacher_id > 0 ? $batch->teacher->initials() : ' - ' }}</span>
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
                    <div class="rounded-lg p-1 text-sm text-center border border-zinc-200 bg-green-50 text-green-800 ">
                        joined
                    </div>
                </div>
            </flux:modal>
            <flux:modal name="edit-student" class=" bg-white rounded-xl p-4" wire:model.self="showEditStudentModal">
                <p class="text-xl font-semibold text-green-800 mb-4">
                    Edit Student <span class="animate-pulse text-sm ms-2 font-normal" wire:loading>Loading...</span>
                </p>
                <flux:input type="text" wire:model="us_name" label="Name" size="sm" class="mb-2" />
                <flux:radio.group wire:model="us_gender" label="Gender" variant="segmented" class="mb-2">
                    <flux:radio label="Male" value="male" />
                    <flux:radio label="Female" value="female" />
                </flux:radio.group>
                <flux:select label="School" wire:model="us_school" placeholder="Choose school..."
                    wire:init="loadSchool">
                    @if ($school_list)
                        @foreach ($school_list as $school)
                            <flux:select.option value="{{ $school->id }}">{{ $school->name }}</flux:select.option>
                        @endforeach
                    @else
                        <span class="text-zinc-400 animate-pulse" wire:loading>Loading...</span>
                    @endif
                </flux:select>
                <div class="flex mt-4" wire:loading.class="opacity-50 animate-pulse">
                    <flux:button icon="bookmark" type="button" wire:click="editStudent" class="w-full me-2">Save
                    </flux:button>
                    <flux:button icon="trash" type="button" wire:click="deleteStudent" variant="danger">Delete
                    </flux:button>
                </div>
            </flux:modal>
        @else
            <div class="text-zinc-400" wire:loading>Loading...</div>
        @endif
    </div>
</div>

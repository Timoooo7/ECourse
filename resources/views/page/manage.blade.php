<x-layouts.app :title="__('Manage')">
    <div class="flex">
        <flux:icon.adjustments-vertical class="ms-auto me-2 my-auto" /> <span
            class="block me-auto text-lg xl:text-2xl">Manage
            Schedule</span>
    </div>
    <div class="grid lg:grid-cols-2 gap-6 mt-6">
        <div class="row-span-2">
            <livewire:component.batch_manager />
        </div>
        <livewire:component.teacher_manager />
        <livewire:component.student_manager />
    </div>
</x-layouts.app>

<a href="#" class="text-gray-300 py-1 hover:text-white" onclick="event.preventDefault();" @click="listsDropdownOpen = !listsDropdownOpen">
    <i class="far fa-list-alt"></i>
    My Shopping Lists
</a>
<div
    class="md:absolute md:mt-8 bg-white rounded-md md:shadow-lg overflow-hidden z-20 w-full"
    :class="{'block':listsDropdownOpen, 'hidden':!listsDropdownOpen}"
    @click.away="listsDropdownOpen = false" style="margin-left:0">
    <div class="bg-white block items-center px-4 py-3 border-b">
        <h1 class="text-lg bg-white">Shopping Lists</h1>
    </div>

@forelse ($lists as $list)
    {{-- <div class="flex items-center px-4 py-3 border-b hover:bg-gray-100 -mx-2"> --}}
    <a href="/list/{{ $list->id }}">
        <div class="bg-white flex items-center px-4 py-3 border-b hover:bg-gray-100">
            {{ $list->name }}
            <span class="text-sm text-gray-500 italic pl-2">
                ({{ $list->validItemsCount }} items)
            </span>
        </div>
    </a>
@empty
    
@endforelse
    <div class="bg-white block text-center px-4 py-3 border-b hover:bg-gray-100 capitalize">
        <a href="/list/">See all shopping lists</a>
    </div>
</div>
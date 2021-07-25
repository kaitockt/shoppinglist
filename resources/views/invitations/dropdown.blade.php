<a href="#" class="text-gray-300 py-1 hover:text-white" onclick="event.preventDefault();" @click="invitationMenuOpen = !invitationMenuOpen">
    <i class="fas fa-envelope"></i>
    My Invitations
    @if (count($invitations)>0)
    ({{ count($invitations) }})
    @endif
</a>
<div
    class="md:absolute md:mt-8 bg-white rounded-md md:shadow-lg overflow-hidden z-20 w-full"
    :class="{'block':invitationMenuOpen, 'hidden':!invitationMenuOpen}"
    @click.away="invitationMenuOpen = false" style="margin-left:0">
    <div class="bg-white block items-center px-4 py-3 border-b">
        <h1 class="text-lg bg-white">Invitations</h1>
    </div>

@forelse ($invitations as $invitation)
    {{-- <div class="flex items-center px-4 py-3 border-b hover:bg-gray-100 -mx-2"> --}}
    <div class="bg-white flex items-center px-4 py-3 border-b hover:bg-gray-100">
        <span class="pr-5">
            {{ $invitation['inviter']['name'] }} would like to share this shopping list with you:<br>
            {{ $invitation['shoppinglist']['name'] }}
        </span>
        {{-- Accept --}}
        <form action="{{
            route('invitations.accept', [
                'list' => $invitation['shoppinglist']['id'],
                'uid' => Auth::id(),
                ]) }}" method="post">
            @csrf
            {{ method_field('PUT') }}
            <button class="text-white bg-green-500 hover:bg-green-600 p-2 w-30 uppercase mx-1 text-xs">
                Accept
            </button>
        </form>
        {{-- decline --}}
        <form action="{{
            route('invitations.decline', [
                'list' => $invitation['shoppinglist']['id'],
                'uid' => Auth::id(),
                ]) }}" method="post">
            @csrf
            {{ method_field('DELETE') }}
            <button class="text-white bg-red-500 hover:bg-red-600 p-2 w-30 uppercase mx-1 text-xs">
                Decline
            </button>
        </form>
    </div>
@empty
    
@endforelse
    <a href="{{ route('invitations.index') }}">
        <div class="bg-white block text-center px-4 py-3 border-b hover:bg-gray-100 capitalize">
            See all invitations
        </div>
    </a>
</div>
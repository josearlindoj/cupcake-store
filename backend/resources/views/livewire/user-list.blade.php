<div class="container mx-auto">
    <div class="grid grid-cols-2 w-3/12">
        <h2 class="mt-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl">User</h2>
    </div>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 mt-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
            <tr class="bg-gray-100">
                <th scope="col" class="px-6 py-3">Name</th>
                <th scope="col" class="px-6 py-3">Email</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $user->name }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $user->email }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

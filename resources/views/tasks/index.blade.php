<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@^3/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto p-4">

        <!-- Page Header -->
        <h1 class="text-3xl font-bold mb-4 text-center">Task List</h1>

        <!-- Task Creation Form -->
        <form action="{{ route('tasks.store') }}" method="POST" class="mb-6 bg-white p-6 rounded-lg shadow-md">
            @csrf
            <div class="mb-4">
                <input type="text" name="title" placeholder="Task Title" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <textarea name="description" placeholder="Task Description" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"></textarea>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Add
                Task</button>
        </form>

        <!-- Validation Errors -->
        @if ($errors->any())
        <div class="mb-4 p-4 bg-red-200 rounded-lg text-red-800">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Task Listing -->
        <ul class="bg-white p-6 rounded-lg shadow-md divide-y divide-gray-200">
            @forelse ($tasks as $task)
            <li class="py-4 flex justify-between items-center">
                <div>
                    <strong class="block text-xl">{{ $task->title }}</strong>
                    <span class="block text-gray-600">{{ $task->description }}</span>
                </div>
                <div class="flex space-x-2">
                    @if(!$task->completed)
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-lg">Mark as
                            Complete</button>
                    </form>
                    @else
                    <span class="px-4 py-2 bg-green-100 text-green-800 font-semibold rounded-lg">Completed</span>
                    @endif
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-bold rounded-lg">Delete</button>
                    </form>
                </div>
            </li>
            @empty
            <li class="py-4 text-center">No tasks available.</li>
            @endforelse
        </ul>
    </div>

</body>

</html>
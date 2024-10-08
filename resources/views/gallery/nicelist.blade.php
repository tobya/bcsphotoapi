<!DOCTYPE html>
<html>
<head>
    <title>Gallery for {{$Demo->Folder_HumanReadable}}</title>
    <meta property="og:title" content="Demo Photos for {{$Demo->Folder_HumanReadable}} ">
    <meta property="og:description"
          content="Photos of recipes cooked in demonstration on {{$Demo->Folder_HumanReadable}} at Ballymaloe Cookery School">
    <meta property="og:image" content="{{$Photos[0]->basesrc}}?w=100&h=100&fit=crop">
    @vite('resources/css/app.css')
</head>
<body>

<div class="bg-gray-300">
    <div class="mx-auto max-w-full px-4 lg:py-16  lg:max-w-7xl lg:px-8">
        <h1 class="text-blue-950 text-3xl font-bold p-2">Demo Photos for {{$Demo->Folder_HumanReadable}}</h1>
        <h2 class="sr-only">Demo photos</h2>

        <div class="grid grid-cols-1 gap-x-6 gap-y-10  lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
            @foreach ($Photos as $P)
                <a href="#" class="group">
                    <div class=" w-full overflow-hidden rounded-lg bg-gray-200 xl:aspect-h-8 xl:aspect-w-7">
                        <img src="{{$P->basesrc}}?w=500&h=500&fit=crop"
                             class="h-full w-full object-cover object-center group-hover:opacity-75"
                             alt="Plate of Food" >
                    </div>
                    <h3 class="mt-4 text-sm text-gray-700"></h3>
                    <p class="mt-1 text-lg font-medium text-gray-900"></p>
                </a>
            @endforeach
        </div>
    </div>
</div>


</body>
</html>

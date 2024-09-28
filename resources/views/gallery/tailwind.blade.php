<!DOCTYPE html>
<html>
<head>
  <title>Gallery for {{$Demo->Folder_HumanReadable}}</title>
   <meta property="og:title" content="Demo Photos for {{$Demo->Folder_HumanReadable}} ">
<meta property="og:description" content="Photos of recipes cooked in demonstration on {{$Demo->Folder_HumanReadable}} at Ballymaloe Cookery School">
<meta property="og:image" content="{{$Photos[0]->basesrc}}?w=100&h=100&fit=crop">
      @vite('resources/css/app.css')
</head>
<body>
  <h2  class="text-lg text-red-500">{{$Demo->Folder_HumanReadable}}</h2>
  <ul class=" p-2 m-6">
  @foreach ($Photos as $P)
    <li class="bg-green-100 p-2" > <img src="{{$P->basesrc}}?w=400&h=400&fit=crop">
      @endforeach
  </ul>
</body>
</html>

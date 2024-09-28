<!DOCTYPE html>
<html>
<head>
  <title>Gallery for {{$Demo->Folder_HumanReadable}}</title>
   <meta property="og:title" content="Demo Photos for {{$Demo->Folder_HumanReadable}} ">
<meta property="og:description" content="Photos of recipes cooked in demonstration on {{$Demo->Folder_HumanReadable}} at Ballymaloe Cookery School">
<meta property="og:image" content="{{$Photos[0]->basesrc}}?w=100&h=100&fit=crop">
</head>
<body>
  <h2>{{$Demo->Folder_HumanReadable}}</h2>
  <ul>
  @foreach ($Photos as $P)
    <li> <img src="{{$P->basesrc}}?w=400&h=400&fit=crop"><BR> {{$P->basesrc}}
      @endforeach
  </ul>  
</body>
</html>

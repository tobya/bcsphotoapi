
<!DOCTYPE html>
<html>
<head>
  <title>Gallery for {{$Demo->Folder_HumanReadable}}</title>
   <meta property="og:title" content="Demo Photos for {{$Demo->Folder_HumanReadable}} ">
<meta property="og:description" content="Photos of recipes cooked in demonstration on {{$Demo->Folder_HumanReadable}} at Ballymaloe Cookery School">
<meta property="og:image" content="{{$Photos[0]->basesrc}}?w=100&h=100&fit=crop">
    <style>
        th, td {
  padding: 15px;
}
    </style>
</head>
<body>
  <h1>Photos of Demonstration Recipes for {{$Demo->Folder_HumanReadable}}</h1>
<table>

<tr>


  @for($i = 1; $i <= count($Photos) -1; $i++)
      @php
      $P = $Photos[$i];
      @endphp
    <td padding="3"> <img src="{{$P->basesrc}}?w=300&h=300&fit=crop"><BR>
  </td>
      @if($i % 5 == 0)

            </tr>
            <tr>
        @endif
      @endfor
</table>
</body>
</html>

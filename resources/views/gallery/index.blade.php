<ul>


@foreach($allfiles as $file)

    <li>
        <a href="/gallery/{{$demodate}}/html/{{$file}}">{{$file}}</a>
    </li>
@endforeach
</ul>

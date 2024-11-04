<?php


    it('will get details from ',function (){
        $gallery = (new \App\Http\Controllers\PhotoController())->LoadGalleries();
        expect($gallery)->tobeString();
    });

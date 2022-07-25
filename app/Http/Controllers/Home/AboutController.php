<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\About;
use App\Models\MultiImage;
use Image;
use Illuminate\Support\Carbon;

class AboutController extends Controller
{
    public function AboutPage(){
        $aboutPage = About::find(1);
        return view('admin.about_page.about_page_all', compact('aboutPage'));
    }//end method

    public function UpdateAbout(Request $request){
        $about_id = $request->id;
        if($request->file('about_image')){
            $image = $request->file('about_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

            Image::make($image)->resize(523,605)->save('upload/home_about/'.$name_gen);
            $save_url = 'upload/home_about/'.$name_gen;

            About::findOrFail($about_id)->update([
                'title' => $request->title,
                'short_title' => $request->short_title,
                'short_description' => $request->short_description,
                'long_description' => $request->long_description,
                'about_image' => $save_url,

            ]);

            $notification = array(
                'message' => 'About Page Updated with Image Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->back()->with($notification);
        }else{
            About::findOrFail($about_id)->update([
                'title' => $request->title,
                'short_title' => $request->short_title,
                'short_description' => $request->short_description,
                'long_description' => $request->long_description,
                'about_image' => $request->about_image,
                
            ]);

            $notification = array(
                'message' => 'About Page Updated without Image Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->back()->with($notification);
        }// end else
    }//end method

    public function HomeAbout(){
        $aboutPage = About::find(1);
        return view('frontend.about_page', compact('aboutPage'));
    }//end method

    public function AboutMultiImage(){
       return view('admin.about_page.multimage');
    }//end method

    public function StoreMultiImage(Request $request){
        $image = $request->file('multi_image');

        foreach($image as $multi_image){
            $image = $request->file('about_image');
            $name_gen = hexdec(uniqid()).'.'.$multi_image->getClientOriginalExtension();

            Image::make($multi_image)->resize(220,220)->save('upload/multi/'.$name_gen);
            $save_url = 'upload/multi/'.$name_gen;

            MultiImage::insert([
               
                'multi_image' => $save_url,

            ]);
        }

            $notification = array(
                'message' => 'Multi Image Inserted Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->back()->with($notification);
        
    }// end method

    public function AllMultiImage(){
        $allMultiImage = MultiImage::all();
        return view('admin.about_page.all_multiimage', compact('allMultiImage'));
    }// end method
}

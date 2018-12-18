<?php 
use \Core\Controller;
use App\Models\Post;
class PostsController extends Controller 
{ 
 	public function index($req,$res,$args)	{ 
		 $post=Post::where("id",$args['id'])->first();
		 var_dump($post);
 	} 
}
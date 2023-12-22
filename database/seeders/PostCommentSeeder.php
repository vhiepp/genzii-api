<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use App\Services\UserService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Vhiepp\VNDataFaker\VNFaker;

class PostCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sum = 0;
        $postService = new PostService();
        $userService = new UserService();
        $posts = Post::whereIn('limit', ['all', 'friends'])->orderBy('updated_at', 'asc')->limit(500)->get();
        foreach ($posts as $post) {
            $users = User::where('id', '<>', $post->author->id)->inRandomOrder()->limit(rand(10, config('seeder.user_total')/4))->get();
            foreach ($users as $user) {
                try {
                    $comment = VNFaker::comment();
                    if ($post->limit == 'all' || ($userService->isFriend($post->authors()->first(), $user->id))) {
                        $postService->createComment(
                            $post,
                            $user,
                            $comment
                        );
                        if ($sum != 0) echo "\033[F\033[K";
                        echo "Tạo comments của post: " . (++$sum) . "\n";
                    }
                } catch (\Exception $exception) {}
            }
        }
        echo "Đã tạo " . $sum . " comments của post. \n";
    }
}

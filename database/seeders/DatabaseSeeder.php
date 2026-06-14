<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use App\Models\Post;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users & Profiles ──────────────────────────────────────────
        $usersData = [
            [
                'name'         => 'voidcryo',
                'email'        => 'voidcryo@Tenebris.ix',
                'display_name' => 'Void Cryo',
                'bio'          => 'just a dev lost in the void 🌑',
                'location'     => 'Jakarta, ID',
                'birthday'     => '2000-04-20',
            ],
            [
                'name'         => 'aurora',
                'email'        => 'aurora@Tenebris.ix',
                'display_name' => 'Aurora',
                'bio'          => 'chasing northern lights and good code ✨',
                'location'     => 'Bandung, ID',
                'birthday'     => '1999-11-03',
            ],
            [
                'name'         => 'nyx',
                'email'        => 'nyx@Tenebris.ix',
                'display_name' => 'Nyx',
                'bio'          => 'goddess of the night, enemy of deadlines',
                'location'     => 'Yogyakarta, ID',
                'birthday'     => '2001-06-15',
            ],
            [
                'name'         => 'cipher',
                'email'        => 'cipher@Tenebris.ix',
                'display_name' => 'Cipher',
                'bio'          => '01000011 01101111 01100100 01100101',
                'location'     => 'Surabaya, ID',
                'birthday'     => '1998-02-28',
            ],
            [
                'name'         => 'solaris',
                'email'        => 'solaris@Tenebris.ix',
                'display_name' => 'Solaris',
                'bio'          => 'warmth in a dark universe ☀️',
                'location'     => 'Medan, ID',
                'birthday'     => '2002-09-09',
            ],
        ];

        $users = [];
        foreach ($usersData as $data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make('password'),
            ]);

            Profile::create([
                'user_id'      => $user->id,
                'display_name' => $data['display_name'],
                'bio'          => $data['bio'],
                'location'     => $data['location'],
                'birthday'     => $data['birthday'],
            ]);

            $users[] = $user;
        }

        // ── Follows ───────────────────────────────────────────────────
        $followPairs = [
            [0, 1], [0, 2], [0, 3],
            [1, 0], [1, 2], [1, 4],
            [2, 0], [2, 3], [2, 4],
            [3, 1], [3, 4],
            [4, 0], [4, 2],
        ];

        foreach ($followPairs as [$followerIdx, $followingIdx]) {
            $users[$followerIdx]->followings()->attach($users[$followingIdx]->id);
        }

        // ── Posts ─────────────────────────────────────────────────────
        $postsData = [
            // voidcryo
            ['user' => 0, 'content' => 'shipped a new feature at 3am. worth it? probably not. did i do it anyway? absolutely.'],
            ['user' => 0, 'content' => 'neovim btw 🛸'],
            ['user' => 0, 'content' => 'drizzle orm type errors are my cardio'],

            // aurora
            ['user' => 1, 'content' => 'hot take: dark mode isn\'t a preference, it\'s a personality trait'],
            ['user' => 1, 'content' => 'just refactored 400 lines into 40. today was a good day ✨'],

            // nyx
            ['user' => 2, 'content' => 'the bugs aren\'t in the code, they\'re in the assumptions we made along the way'],
            ['user' => 2, 'content' => 'midnight deploy szn 🌙'],

            // cipher
            ['user' => 3, 'content' => 'if your code works first try, you\'re not writing hard enough code'],
            ['user' => 3, 'content' => 'git blame is just a way to find out who to apologize to'],

            // solaris
            ['user' => 4, 'content' => 'reminder: touch grass. (i haven\'t touched grass in 3 days)'],
            ['user' => 4, 'content' => 'laravel eloquent is genuinely magic and i will not be taking questions'],
        ];

        $posts = [];
        foreach ($postsData as $data) {
            $posts[] = Post::create([
                'user_id' => $users[$data['user']]->id,
                'content' => $data['content'],
            ]);
        }

        // ── Replies ───────────────────────────────────────────────────
        $repliesData = [
            // aurora replies ke post[0] (voidcryo)
            ['user' => 1, 'content' => 'lmao same. 3am energy hits different', 'parent' => $posts[0], 'root' => $posts[0]],
            // nyx replies ke post[0]
            ['user' => 2, 'content' => 'worth it if it works on the first prod deploy 🙏', 'parent' => $posts[0], 'root' => $posts[0]],
            // voidcryo replies ke aurora (reply dari nyx)
            ['user' => 0, 'content' => 'it did not work on first prod deploy', 'parent' => null, 'root' => null, 'reply_to_post' => $posts[0]],
            // cipher replies ke post[3] (aurora)
            ['user' => 3, 'content' => 'dark mode users rise up 🖤', 'parent' => $posts[3], 'root' => $posts[3]],
            // solaris replies ke post[5] (nyx)
            ['user' => 4, 'content' => 'this is so real it hurts', 'parent' => $posts[5], 'root' => $posts[5]],
        ];

        foreach ($repliesData as $data) {
            Post::create([
                'user_id'   => $users[$data['user']]->id,
                'content'   => $data['content'],
                'parent_id' => $data['parent']?->id,
                'root_id'   => $data['root']?->id,
            ]);
        }

        // ── Likes ─────────────────────────────────────────────────────
        $likePairs = [
            [$users[1], $posts[0]],
            [$users[2], $posts[0]],
            [$users[3], $posts[0]],
            [$users[0], $posts[3]],
            [$users[2], $posts[3]],
            [$users[0], $posts[4]],
            [$users[4], $posts[4]],
            [$users[1], $posts[5]],
            [$users[0], $posts[7]],
            [$users[2], $posts[10 - 1]],
        ];

        foreach ($likePairs as [$user, $post]) {
            $user->likedPosts()->attach($post->id);
        }
    }
}

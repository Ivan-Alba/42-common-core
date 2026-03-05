<?php

namespace Tests\Feature;

use App\Enums\Language;
use App\Enums\FriendshipStatus;
use App\Models\Friendship;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertSame;


class UserTest extends TestCase
{
    use RefreshDatabase;

    // It's not even worth to test: Page size, page, sort order. It's all trivially correct.
    public function test_get_users_page_size()
    {
        User::factory()->createMany(11);

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson("/v1/users");
        $response->assertOk();
        $data = $response->json();
        assertSame(count($data['data']), config('social.default_page_size'));
    }


    public function test_user_creation()
    {
        $response = $this->postJson("/register", [
            'username' => 'userexample',
            'email' => 'user@example.nexus',
            'password' => 'passwordabcd1234',
        ])
        ->assertCreated();

        $this->assertDatabaseHas('users', [
            'email' => 'user@example.nexus',
            'name' => 'userexample',
        ]);
    }

    public function test_get_own_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson("/v1/user")->assertOk();

        $response->assertJsonStructure([
            'id',
            'username',
            'email',
            'avatar',
            'bio',
            'language',
        ]);
    }

    public function test_get_user()
    {
        $target = User::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson("/v1/users/{$target->id}")->assertOk();

        $response->assertJsonStructure([
            'id',
            'username',
            'email',
            'avatar',
            'bio',
            'language',
        ]);
    }

    public function test_user_password_update()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($user);

        $response = $this->putJson("/v1/user/password/update", [
            'password' => 'newpassword',
            'password_confirm' => 'newpassword'
        ])
        ->assertOk();

        $this->assertTrue(
            Hash::check('newpassword', $user->fresh()->password)
        );
    }

    public function test_user_update()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->patchJson("/v1/user/update", [
            'username' => 'Updated Name',
            'email' => 'updated@example.com',
            'bio' => 'Updated bio',
            'language' => Language::SPANISH->value
        ])
        ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'bio' => 'Updated bio',
            'language' => Language::SPANISH->value,
        ]);
    }

    public function test_get_friends_list()
    {
        $user = User::factory()->create();
        $friends = User::factory()->count(3)->create();

        foreach ($friends as $friend) {
            Friendship::create([
                'user_id' => $user->id,
                'friend_id' => $friend->id,
                'requester_id' => $user->id,
                'status' => FriendshipStatus::ACCEPTED->value,
            ]);
        }

        $this->actingAs($user);

        $response = $this->getJson("/v1/users/{$user->id}/friends")
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'username', 'avatar', 'bio']
                ],
                'links',
                'meta'
            ]);
    }

    public function test_cannot_get_others_friends_list()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($user);

        $this->getJson("/v1/users/{$otherUser->id}/friends")
            ->assertForbidden();
    }

    public function test_get_friends_list_with_name_filter()
    {
        $user = User::factory()->create();
        $friend = User::factory()->create(['name' => 'SpecialFriend']);
        
        Friendship::create([
            'user_id' => $user->id,
            'friend_id' => $friend->id,
            'requester_id' => $user->id,
            'status' => FriendshipStatus::ACCEPTED->value,
        ]);

        $this->actingAs($user);

        $this->getJson("/v1/users/{$user->id}/friends?name=Special")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.username', 'SpecialFriend');
    }
}

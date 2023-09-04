<?php

namespace Tests\Feature;

use App\Models\ToDoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ToDoListTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     */

    protected $inputData;

    protected $factoryInvalidData;

    public function setUp(): void
    {
        parent::setUp();

        // Create a user using a factory
        $this->inputData = [
            'task' => $this->faker->sentence(),
            'is_compleate' => $this->faker->boolean(),
        ];

        $this->factoryInvalidData = [
            'is_compleate' => $this->faker->boolean(),
        ];
    }

    public function test_can_get_to_do_list(): void
    {
        // Prepare
        ToDoList::factory()->create($this->inputData);

        //perform
        $response = $this->getJson(route('todo.index'));

        //predict
        $this->assertDatabaseHas('to_do_list', $this->inputData);
    }

    public function test_can_create_to_do_list_item(): void
    {

        // Successful
        $response = $this->post(route('todo.store'), $this->inputData);
        $response->assertStatus(201);
        $this->assertDatabaseHas('to_do_list', $this->inputData);

        $response = $this->post(route('todo.store'), $this->factoryInvalidData);
        $response->assertStatus(302);

    }

    public function test_can_get_list_by_one(): void
    {

        $resourece = ToDoList::factory()->create($this->inputData);

        $response = $this->getJson(route('todo.show', $resourece->id));

        $response->assertStatus(201);
        $this->assertEquals($response->json('id'), $resourece->id);

    }

    public function test_can_update_to_do_list_item(): void
    {
        $resource = ToDoList::factory()->create($this->inputData);

        // Update with valid data input
        $updateData = [
            'task' => $this->faker->sentence(),
            'is_compleate' => $this->faker->boolean(),
        ];

        $response = $this->putJson(route('todo.update', $resource->id), $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('to_do_list', $updateData);

        // With incorrect id input
        $response = $this->putJson(route('todo.update', 0), $updateData);

        $response->assertStatus(404);

        // With invalid data inputs
        $updateData = [
            'is_compleate' => $this->faker->boolean(),
        ];

        $response = $this->putJson(route('todo.update', $resource->id), $updateData);

        $response->assertStatus(422);

    }

    public function test_can_delete_to_list_item(): void
    {
        $resource = ToDoList::factory()->create($this->inputData);

        $response = $this->deleteJson(route('todo.destroy', $resource->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('to_do_list', ['id' => $resource->id]);
    }
}
<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Task;
use App\Models\ToDoList;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // tables can't be truncated if they ara referenced in foreigh key
        // so disable fk and than enable after truncation
        Schema::disableForeignKeyConstraints();

        User::truncate(); //delting all records
        Category::truncate();
        ToDoList::truncate();
        Task::truncate();

        Schema::enableForeignKeyConstraints();

        $user1 = User::create(['username' => 'novicaT', 'password' => Hash::make('novicaT123'), 'email' => 'novica@gmail.com']);

        $category1 = Category::create(['name' => 'work', 'description' => 'do to list for work based tasks']);
        $category2 = Category::create(['name' => 'personal', 'description' => 'do to list for non-professional personal tasks']);
        $category3 = Category::create(['name' => 'shopping', 'description' => 'do to list for shopping groceries and other items']);


        $listArray1 = ToDoList::factory(2)->create(['user_id' => $user1->id, 'category_id' => $category1->id]);
        $listArray2 = ToDoList::factory(2)->create(['user_id' => $user1->id, 'category_id' => $category2->id]);
        $listArray3 = ToDoList::factory(2)->create(['user_id' => $user1->id, 'category_id' => $category3->id]);


        Task::factory(10)->create(['to_do_list_id' => $listArray1[0]->id]);
        Task::factory(10)->create(['to_do_list_id' => $listArray1[1]->id]);
        Task::factory(10)->create(['to_do_list_id' => $listArray2[1]->id]);
        Task::factory(10)->create(['to_do_list_id' => $listArray2[0]->id]);
        Task::factory(10)->create(['to_do_list_id' => $listArray3[1]->id]);
        Task::factory(10)->create(['to_do_list_id' => $listArray3[0]->id]);


        $users = User::factory(5)->create();
        $categories = Category::factory(10)->create();

        foreach ($categories as $category) {
            $randomUser = $users->random();
            $list = ToDoList::factory()->create(['category_id' => $category->id, 'user_id' => $randomUser->id]);
            Task::factory(10)->create(['to_do_list_id' => $list->id]);
        }

        // User::factory(5)->create();
        // Category::factory(10)->create();
        // ToDoList::factory(10)->create();
        // Task::factory(10)->create();
    }
}

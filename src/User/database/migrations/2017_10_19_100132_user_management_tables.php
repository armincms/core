<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Core\User\Models\Admin;

class UserManagementTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        $this->addUserTable('admin');
        $this->addUserTable('user');  

        $admin = Admin::create([
            'username' => 'superadministrator',
            'firstname' => 'Isamil',
            'lastname' => 'Zare',
            'displayname' => 'I Zare',
            'email' => 'zarehesmail@gmail.com',
            'password' => bcrypt('Admin@1234'),
            'status' => \Core\Crud\Statuses::key('activated'),
        ]);
 

        $admin->setMeta('mobile', '09010509130');
        $admin->save();

    }

    public function addUserTable($user_type)
    {  
        Schema::create(str_plural($user_type), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username', 50);
            $table->string('firstname', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('displayname', 100)->nullable();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('status')->default(\Core\Crud\Statuses::key('pending'));
            $table->tinyInteger('reset_password')->default(1);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        }); 
 
        Schema::create(str_plural($user_type). '_meta', function (Blueprint $table) use ($user_type) {  
            $table->bigIncrements('id');

            $table->unsignedBigInteger("{$user_type}_id");

            $table->string('type')->default('null'); 
            $table->string('key')->index();
            $table->text('value')->nullable();

            $table->timestamps();

            
            $table->foreign("{$user_type}_id")
                        ->references('id')
                        ->on(str_plural($user_type))
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {   
        Schema::dropIfExists('users_meta');
        Schema::dropIfExists('users');
        Schema::dropIfExists('admins_meta'); 
        Schema::dropIfExists('admins'); 
    }
}

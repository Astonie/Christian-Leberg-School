<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\StreamController;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStreamRequest;

class StreamLoggingTest extends TestCase
{
    use RefreshDatabase;

    public function test_controller_logs_and_metrics_on_create_exception()
    {
        $adminRole = Role::where('slug','admin')->first() ?? Role::create(['name'=>'Admin','slug'=>'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        // Spy on Log
        Log::shouldReceive('error')->once();

        // Create an anonymous controller subclass that throws during performCreate
        $mock = new class extends StreamController {
            protected function performCreate(array $data)
            {
                throw new \Exception('DB failure');
            }
        };

        $request = \Mockery::mock(StoreStreamRequest::class)->makePartial();
        $request->shouldReceive('validated')->andReturn(['class_id' => 1, 'name' => 'X', 'capacity' => 30]);
        $request->setUserResolver(function() use ($admin) { return $admin; });
        $request->setRouteResolver(function(){ return \Illuminate\Routing\Route::getRoutes()->getByName('streams.store'); });

        // Ensure there's an active year so the controller proceeds to create
        \App\Models\AcademicYear::create(['name'=>'2025','start_date'=>'2025-01-01','end_date'=>'2025-12-31','is_active'=>true]);

        $response = $mock->store($request);

        // On exception the controller returns a redirect response with error flash
        $this->assertTrue($response->getSession()->has('error'));
    }
}

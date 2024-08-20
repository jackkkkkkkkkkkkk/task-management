<?php

namespace App\Http\Controllers;

class TaskController extends Controller
{
    /**
     * @OA\Info(
     *     title="Task API",
     *     version="1.0.0",
     *     description="API documentation for managing tasks"
     * )
     *
     * @OA\Server(
     *     url="http://localhost:8000/api",
     *     description="Local API server"
     * )
     */

    public function index()
    {
        return view('tasks.list');
    }
}

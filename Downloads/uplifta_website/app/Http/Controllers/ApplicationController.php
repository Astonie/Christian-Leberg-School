<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applications = Application::orderBy('created_at', 'desc')->paginate(20);
        return view('applications.index', compact('applications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'country' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'years_in_business' => 'nullable|integer',
            'employees' => 'nullable|integer',
            'monthly_revenue' => 'nullable|numeric',
            'business_description' => 'required|string',
            'loan_amount' => 'required|numeric',
            'loan_term' => 'required|integer',
            'loan_purpose' => 'required|string|max:255',
            'loan_description' => 'required|string',
            'repayment_plan' => 'required|string',
        ]);
        Application::create($validated);
        return redirect('/')->with('success', 'Your application has been submitted!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        return view('applications.show', compact('application'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        $application->delete();
        return redirect()->route('applications.index')->with('success', 'Application deleted.');
    }
}

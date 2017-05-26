<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReport;
use App\Report;

/**
 * @resource Report
 *
 * All reports are handled here
 *
 * @package App\Http\Controllers
 */

class ReportController extends Controller
{
    /**
     * Display a listing of reports.
     *
     * Requires admin role
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = Report::all();
        return response()->json([
            'data' => $reports,
        ], 200);
    }

    /**
     * Store a newly created report in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReport $request)
    {
        $data = $request->all();

        $report = Report::create($data); //create Report object, store in db
        return response()->json([
            'message' => 'Report added successfully.',
            'data'    => $report,
        ], 200);
    }

    /**
     * Display the specified report.
     *
     * Requires admin role
     *
     */
    public function show($id)
    {
        $report = Report::find($id);
        if(!$report){
            return response()->json([
                'error'   => 'resource_not_found',
                'message' => 'Report does not exist',
            ], 404);
        }

        return response()->json([
            'data' => $report,
        ], 200);
    }

    /**
     * Set a report to Read
     *
     * Requires admin role
     *
     */
    public function setRead($id)
    {
        $report = Report::find($id);
        if(!$report){
            return response()->json([
                'error'   => 'resource_not_found',
                'message' => 'Report does not exist',
            ], 404);
        }

        $report->is_read = 1;
        $report->save();
        return response()->json([
            'message' => 'Successfully set is_read',
            'data' => $report,
        ], 200);
    }

    /**
     * Set a report to Replied
     *
     * Requires admin role
     *
     */
    public function setReplied($id)
    {
        $report = Report::find($id);
        if(!$report){
            return response()->json([
                'error'   => 'resource_not_found',
                'message' => 'Report does not exist',
            ], 404);
        }

        $report->has_replied = 1;
        $report->save();
        return response()->json([
            'message' => 'Successfully set has_replied',
            'data' => $report,
        ], 200);
    }
}

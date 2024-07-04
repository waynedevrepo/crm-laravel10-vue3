<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Models\Campaign;
use App\Models\CampaignDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{

    private function _allCampaigns()
    {
        return Campaign::all();
    }

    private function _validate(Request $request)
    {
        $request->validate([
            "name" => "required",
            "company" => "required",
            "category" => "required",
            "sub_category" => "required",
            "start_date" => "required",
            "end_date" => "required",
            "status" => "required",
            "today_sales" => "required",
            "total_sales" => "required"
        ]);
    }

    private function _instantiate(Request $request)
    {
        return new Campaign([
            "name" => $request->name,
            "company" => $request->company,
            "category" => $request->category,
            "sub_category" => $request->sub_category,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "status" => $request->status,
            "today_sales" => $request->today_sales,
            "total_sales" => $request->total_sales
        ]);
    }

    public function getAllCampaigns()
    {
        $campaigns = $this->_allCampaigns();

        return response()->json([
            "status" => "success",
            "campaigns" => $campaigns
        ]);
    }


    public function createCampaign(Request $request)
    {
        $this->_validate($request);
        $campaign = $this->_instantiate($request);
        $campaign->save();

        return response()->json([
            "status" => "success"
        ]);
    }

    public function importCampaignDetail(Request $request, $id)
    {
        $data = [];
        $request->validate([
            'csv' => 'required|file',
        ]);

        $file = $request->file('csv');
        if ($file) {
            $path = $file->store('uploads');
            $contents = Storage::get($path);

            $lines = explode("\n", $contents);
            $index = 0;
            foreach ($lines as $line) {
                if ($index++ == 0)
                    continue;

                if (empty($line))
                    continue;

                $fields = str_getcsv($line);
                if (count($fields) != 39) {
                    Log::info('Pri_no: ' . $fields[0] . ', ' . count($fields));
                    continue;
                }

                $data[] = [
                    'progressStatus' => $fields[1],
                    'progressSubStatus' => $fields[2],
                    'campaignAgentRemark' => $fields[3],
                    'currentstatusdate' => $fields[4],
                    'applicanttypename' => $fields[5],
                    'applicantidentity' => $fields[6],
                    'applicantcompany' => $fields[7],
                    'applicantbusinessregistrationnumber' => $fields[8],
                    'applicantname' => $fields[9],
                    'applicantgender' => $fields[10],
                    'racename' => $fields[11],
                    'applicantmobile' => $fields[12],
                    'applicantfax' => $fields[13],
                    'applicantotherphone' => $fields[14],
                    'applicantaddress1' => $fields[15],
                    'applicantaddress2' => $fields[16],
                    'applicantaddress3' => $fields[17],
                    'applicantpostcode' => $fields[18],
                    'applicantcity' => $fields[19],
                    'applicantstate' => $fields[20],
                    'applicantemail' => $fields[21],
                    'installationaddress1' => $fields[22],
                    'installationaddress2' => $fields[23],
                    'installationaddress3' => $fields[24],
                    'installationpostcode' => $fields[25],
                    'installationcity' => $fields[26],
                    'installationstate' => $fields[27],
                    'installationpropertytype' => $fields[28],
                    'installationcontactperson' => $fields[29],
                    'installationcontactnumber' => $fields[30],
                    'billingaddress1' => $fields[31],
                    'billingaddress2' => $fields[32],
                    'billingaddress3' => $fields[33],
                    'billingpostcode' => $fields[34],
                    'billingcity' => $fields[35],
                    'billingstate' => $fields[36],
                    'productgroup' => $fields[37],
                    'productname' => $fields[38],
                    'campaign_id' => $id
                ];
            }
        } else {
            return response()->json(['error' => 'File was not uploaded correctly'], 400);
        }
        $chunkSize = 100; // Adjust the chunk size as needed
        collect($data)->chunk($chunkSize)->each(function ($chunk) {
            CampaignDetail::insert($chunk->toArray());
        });
        return response()->json(['message' => 'Files uploaded and data inserted successfully']);
    }

    public function getCampaignDetail(Request $request, $id)
    {
        $status = $request->status;
        $sub_status = $request->sub_status;
        $where = [
            'campaign_id' => $id
        ];

        $counts = CampaignDetail::selectRaw('progressStatus, count(*) as count')
            ->where('campaign_id', $id)
            ->groupBy('progressStatus')
            ->get()
            ->toArray();

        $counts2 = CampaignDetail::selectRaw('progressStatus, progressSubStatus, count(*) as count')
            ->where('campaign_id', $id)
            ->groupBy('progressStatus', 'progressSubStatus')
            ->get()
            ->toArray();

        if (!isset($status) || $status == 'Default')
            $status = '';

        if (!isset($sub_status))
            $sub_status = '';

        $where['progressStatus'] = $status;

        if ($sub_status != 'All')
            $where['progressSubStatus'] = $sub_status;

        $list = CampaignDetail::where($where)->get();

        return response()->json([
            "counts_status" => $counts,
            "counts_substatus" => $counts2,
            'list' => $list
        ]);
    }

    public function getUnassignedCount(Request $request, $id)
    {
        $all = CampaignDetail::where('campaign_id', $id)->count();
        $unassigned = CampaignDetail::where('campaign_id', $id)->whereNull('assigned_user')->count();

        return response()->json([
            "status" => "success",
            "total_count" => $all,
            "unassigned_count" => $unassigned
        ]);
    }

    public function assign(Request $request, $id)
    {
        $request->validate([
            "leader" => 'required',
            "method" => 'required',
            "amount" => 'required'
        ]);

        if ($request->method == 'Normal')
            CampaignDetail::whereNull('assigned_user')
                ->limit($request->amount)
                ->update([
                    'assigned_user' => $request->leader
                ]);
        else
            CampaignDetail::whereNull('assigned_user')
                ->inRandomOrder()
                ->limit($request->amount)
                ->update([
                    'assigned_user' => $request->leader
                ]);

        return response()->json([
            "status" => "success"
        ]);
    }

    public function updateCampaign(Request $request)
    {
        $this->_validate($request);
        $request->validate(["id" => "integer"]);

        $campaign = $this->_instantiate($request);
        $campaign->id = $request->id;
        $campaign->save();

        return response()->json([
            "status" => "success"
        ]);
    }

    public function activateCampaign(Request $request, $id)
    {
        $campaign = Campaign::find($id);
        $campaign->status = $campaign->status == UserStatus::ACTIVE ?
            UserStatus::INACTIVE->value :
            UserStatus::ACTIVE->value;

        $campaign->save();
        $campaigns = $this->_allCampaigns();

        return response()->json([
            "status" => "success",
            "campaigns" => $campaigns
        ]);
    }

    public function deleteCampaign(Request $request, $id)
    {
        Campaign::find($id)->delete();

        $campaigns = $this->_allCampaigns();

        return response()->json([
            "status" => "success",
            "campaigns" => $campaigns
        ]);
    }
}

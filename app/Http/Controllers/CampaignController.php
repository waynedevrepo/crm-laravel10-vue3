<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
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

        $chunkSize = 100;
        collect($data)->chunk($chunkSize)->each(function ($chunk) {
            CampaignDetail::insert($chunk->toArray());
        });

        return response()->json(['message' => 'Files uploaded and data inserted successfully']);
    }

    public function getCampaignDetail(Request $request, $id)
    {
        $user = $request->user();

        $status = $request->status;
        $sub_status = $request->sub_status;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $where = [
            'campaign_id' => $id
        ];

        if ($user->role == UserRole::TEAM_LEADER)
            $where['assigned_leader'] = $user->id;

        if ($user->role == UserRole::AGENT)
            $where['assigned_user'] = $user->id;

        $counts = CampaignDetail::selectRaw('progressStatus, count(*) as count')
            ->where($where)
            ->groupBy('progressStatus')
            ->get()
            ->toArray();

        $counts2 = CampaignDetail::selectRaw('progressStatus, progressSubStatus, count(*) as count')
            ->where($where)
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

        $builder = CampaignDetail::where($where);
        if (isset($start_date) && isset($end_date)) {
            $builder = $builder->whereBetween('currentstatusdate', [
                $start_date,
                $end_date,
            ]);
        }

        $list = $builder->get();

        return response()->json([
            "counts_status" => $counts,
            "counts_substatus" => $counts2,
            'list' => $list
        ]);
    }

    public function updateCampaignDetailStatus(Request $request, $id)
    {
        $request->validate([
            "progressStatus" => 'required',
            "progressSubStatus" => 'required',
            "campaignAgentRemark" => 'required'
        ]);

        $campaign_detail = CampaignDetail::find($id);
        $campaign_detail->progressStatus = $request->progressStatus;
        $campaign_detail->progressSubStatus = $request->progressSubStatus;
        $campaign_detail->campaignAgentRemark = $request->campaignAgentRemark;

        $campaign_detail->save();

        return response()->json([
            "status" => "success",
            "campaign_detail" => $campaign_detail
        ]);
    }

    public function updateCampaignDetailRefNumber(Request $request, $id)
    {
        $request->validate([
            "ref_no" => 'required',
        ]);

        $campaign_detail = CampaignDetail::find($id);
        $campaign_detail->ref_no = $request->ref_no;

        $campaign_detail->save();

        return response()->json([
            "status" => "success",
        ]);
    }

    public function getCampaignDetailInfo($id)
    {
        $campaign_detail = CampaignDetail::find($id);

        return response()->json([
            "status" => "success",
            "campaign_detail" => $campaign_detail
        ]);
    }

    public function getUnassignedCount(Request $request, $id)
    {
        $user = $request->user();

        if ($user->role == UserRole::ADMIN) {
            $all = CampaignDetail::where('campaign_id', $id)->count();
            $unassigned = CampaignDetail::where('campaign_id', $id)->whereNull('assigned_leader')->count();
        } else {
            $where = [
                'campaign_id' => $id,
                'assigned_leader' => $user->id
            ];
            $all = CampaignDetail::where($where)->count();

            $unassigned = CampaignDetail::where($where)->whereNull('assigned_user')->count();
        }

        return response()->json([
            "status" => "success",
            "total_count" => $all,
            "unassigned_count" => $unassigned
        ]);
    }

    public function assign(Request $request, $id)
    {
        $user = $request->user();
        $isAdmin = $user->role == UserRole::ADMIN;

        $request->validate([
            "leader" => 'required',
            "method" => 'required',
            "amount" => 'required'
        ]);

        $where = [
            'campaign_id' => $id,
            'assigned_leader' => $user->id
        ];

        if ($request->method == 'Normal') {
            if ($isAdmin)
                CampaignDetail::where(['campaign_id' => $id])
                    ->whereNull('assigned_leader')
                    ->limit($request->amount)
                    ->update([
                        'assigned_leader' => $request->leader
                    ]);
            else
                CampaignDetail::where($where)
                    ->limit($request->amount)
                    ->update([
                        'assigned_user' => $request->leader
                    ]);
        } else {
            if ($isAdmin)
                CampaignDetail::where(['campaign_id' => $id])
                    ->whereNull('assigned_leader')
                    ->inRandomOrder()
                    ->limit($request->amount)
                    ->update([
                        'assigned_leader' => $request->leader
                    ]);
            else
                CampaignDetail::where($where)
                    ->limit($request->amount)
                    ->inRandomOrder()
                    ->update([
                        'assigned_user' => $request->leader
                    ]);
        }

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
        $campaign->status = $campaign->status == 'active' ?
            'inactive' :
            'active';

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

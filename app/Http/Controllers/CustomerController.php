<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientConService;

use Illuminate\Http\Request;
use DB;
use Auth;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::orderBy('name', 'asc')->get();

        return view('pages.customer.list', ['tabIndex' => 2, 'clients' => $clients]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function overview(Request $request)
    {
        
        $id = $request->id;
        $customer = Client::where('ID',$id)->first();
    
        session(['customerName' => $customer->name]);

        $data = array(
            'clientID' => $customer->ID,
            'clientName' => $customer->name,
            'clientStreet' => $customer->address,
            'clientPostcode' => $customer->zip,
            'clientState' => $customer->city,
            'clientLogo' => $customer->logo
        );

        $query = "select t2.ID, t1.ID as serviceID, t1.groupType, t1.name, t1.defaultValue, t2.value, t2.isFlatrate 
                from clients_con_services t1 
                left join clients_con_services_values t2 on (t1.ID = t2.conServiceID and t2.clientID = ?) 
                where t1.clientID = ? or t1.isConstant = 1"; 

        $result = DB::select($query, [$customer->ID, $customer->ID]);
        $hourlyServices = array();
        $fixedServices = array();
        $percentualServices = array();

        foreach ($result as $item) {
            
            if ($item->groupType == 'HOURLY_RATE') {
                array_push($hourlyServices, $item);
            }   
            else if ($item->groupType == 'FIXED_RATE') {
                array_push($fixedServices, $item);
            }
            else if ($item->groupType == 'PERCENTUAL_RATE') {
                array_push($percentualServices, $item);
            } 

        }
        
        $services = array(
            'hourlyServices' => $hourlyServices,
            'fixedServices' => $fixedServices,
            'percentualServices' => $percentualServices
        );
       
        return view('pages.customer.overview', 
                        ['tabIndex' => 2,
                         'data' => $data,
                         'services' => $services]);
    }

    /**
     * Show the application dashboard.
     *
     * @param 
     * @return \Illuminate\Http\Response
     */
    public function addContact(Request $request)
    {
        $customerName = $request->customer_name;
        $customerStreet = $request->customer_street;
        $customerPostcode = $request->customer_postcode;
        $customerState = $request->customer_state;
        
        $customerLogo = '/uploads/customer/placeholder.png';

        session(['customerName' => $customerName]);

        $client = new Client;
        $client->name = $customerName;
        $client->address = $customerStreet;
        $client->zip = $customerPostcode;
        $client->city = $customerState;
        $client->logo = $customerLogo;
        $client->save();

        return redirect('customer/overview?id='.$client->ID);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function editContact(Request $request)
    {
        $customerName = $request->name;
        $customerStreet = $request->street;
        $customerPostcode = $request->postcode;
        $customerState = $request->state;
        $customerID = $request->clientID;
        
        $customer = CLient::where('ID',$customerID)->first();
        $customer->name = $customerName;
        $customer->address = $customerStreet;
        $customer->zip = $customerPostcode;
        $customer->city = $customerState;

        //image uploading
        if ($request->image) {
            $file = $request->image;
            $destinationPath = 'uploads/customer';
            $fileName = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();

            $pos = strripos($fileName, $fileExtension);
            $onlyName = substr($fileName, 0, $pos - 1);

            $t = time();
            $logoName = $onlyName.'(GMT_'.$t.').'.$fileExtension;
            $request->file('image')->move($destinationPath, $logoName);

            $logoUrl = '/'.$destinationPath.'/'.$logoName;

            $customer->logo = $logoUrl;
        }
        
        $customer->save();

        $data = array( 'status' => 'success' );
        
        return response()->json($data); 
    }

    /**
     * Update service cost via ajax api call.
     *
     * @param $reqeust \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
     */
    public function updateCost(Request $request)
    {
        // Initalize group type
        $groupTypes = array(
                '1' => 'HOURLY_RATE',
                '2' => 'FIXED_RATE',
                '3' => 'PERCENTUAL_RATE'
            );

        // Get params from request
        $clientID = $request->clientID;
        $newDatas = $request->newDatas;
        $addDatas = $request->addDatas;
        $editDatas = $request->editDatas;
        
        // Initialize response array
        $respNews = array();
        $respAdds = array();
        
        // Check if the request params is valid
        if (!empty($newDatas)) {
            foreach ($newDatas as $newData) {
                $serviceID = DB::table('clients_con_services')->insertGetId(
                        ['clientID' => $clientID, 'groupType' => $groupTypes[$newData['catType']], 'name' => $newData['name']]
                );

                $serviceValID = DB::table('clients_con_services_values')->insertGetId(
                    ['clientID' => $clientID, 'conServiceID' => $serviceID, 'value' => $newData['value'],'isFlatrate' => $newData['isFlatRate']]
                    //['clientID' => $clientID, 'conServiceID' => $newData['conServiceID'], 'value' => $newData['value'], 'isFlatrate' => $newData['isFlatRate']]
                );

                $temp = array(
                        'ID' => $serviceValID,
                        'serviceID' => $serviceID
                    );

                array_push($respNews, $temp);
            }
        }

        //exit;
        if (!empty($addDatas)) {
            foreach ($addDatas as $addData) {
                $serviceValID = DB::table('clients_con_services_values')->insertGetId(
                        ['clientID' => $clientID, 'conServiceID' => $addData['conServiceID'], 'value' => $addData['value'], 'isFlatrate' => $addData['isFlatRate']]
                    );

                $temp = array(
                        'ID' => $serviceValID
                    );

                array_push($respAdds, $temp);
            }
        }

        if (!empty($editDatas)) {
            foreach ($editDatas as $editData) {
                DB::table('clients_con_services_values')->where('ID', $editData['ID'])
                    ->update(['value' => $editData['value'], 'isFlatrate'=>$editData['isFlatRate']]);
            }    
        }
        
        $resp = array(
                'status' => 'success',
                'msg' => 'Successfully updated',
                'data' => array(
                        'newDatas' => $respNews,
                        'addDatas' => $respAdds
                    )
            );

        return response()->json($resp);
    }
}

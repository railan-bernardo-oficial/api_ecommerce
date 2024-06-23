<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function findAddress($addres_id){
        $address = Address::find($addres_id);
        return response()->json($address);
    }

    public function store(Request $request){
        $validate =  Validator::make($request->all(), [
            "recipient_name" => "required",
            'cep' => 'required',
            'number' => 'required',
            'additional_info' => 'required',
            'neighborhood' => 'required',
            'city' => 'required',
            'state' => 'required',
        ], [
            "required" => [
                'recipient_name' => 'Destinatário é obrigatório!',
                'cep' => 'CEP é obrigatório!',
                'number' => 'Número é obrigatório!',
                'additional_info' => 'Complemento é obrigatório!',
                'neighborhood' => 'Bairro é obrigatório!',
                'city' => 'Data de Cidade é obrigatório!',
                'state' => 'Estado é obrigatório!',
            ],
        ]);


        if ($validate->fails()) return response()->json(['errors' => $validate->errors()], 422);

        $address = new Address;

        $address->recipient_name = $request->recipient_name;
        $address->cep = $request->cep;
        $address->number = $request->number;
        $address->additional_info = $request->additional_info;
        $address->neighborhood = $request->neighborhood;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->reference = $request->reference ?? null;
        $address->main_address = $request->main_address ?? 0;

        if (!$address->save()) return response()->json(['error' => 'Erro ao cadastrar endereço, tente mais tarde!'], 400);

        return response()->json(['succes', 'Endereço criada com sucesso!'], 201);
    }

    public function update(Request $request, $addres_id){
        $address = Address::find($addres_id);
        if (empty($address)) return response()->json(['error' => 'Endereço não existe.'], 404);
   
        $address->recipient_name = $request->recipient_name;
        $address->cep = $request->cep;
        $address->number = $request->number;
        $address->additional_info = $request->additional_info;
        $address->neighborhood = $request->neighborhood;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->reference = $request->reference ?? null;
        $address->main_address = $request->main_address ?? 0;

        if (!$address->save()) return response()->json(['error' => 'Erro ao atualizar endereço, tente mais tarde!'], 400);

        return response()->json(['succes', 'Endereço atualizado com sucesso!'], 201);
    }

    public function delete($addres_id){
        if (!$addres_id) return response()->json(['error' => 'Precisa de um endereço válido!'], 404);

        $address = Address::find($addres_id);

        if (empty($address)) return response()->json(['error' => 'Endereço inexistente.'], 404);

        $address->delete();

        return response()->json(['success' => 'Endereço excluido.']);
    }
}

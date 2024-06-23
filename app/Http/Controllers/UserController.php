<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Lista todos os usuários
    public function listUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Pega um usuário
    public function findUser($user_id)
    {
        $user = User::find($user_id);
        return response()->json($user);
    }

    // Cria um usuário
    public function store(Request $request)
    {

        $validate =  Validator::make($request->all(), [
            "name" => "required",
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|min:9',
            'gender' => 'required',
            'document' => 'required|min:11',
            'date_birth' => 'required',
            'password' => 'required',
        ], [
            "required" => [
                'name' => 'Nome é obrigatório!',
                'email' => 'E-mail é obrigatório!',
                'phone' => 'Telefone é obrigatório!',
                'gender' => 'Gênero é obrigatório!',
                'document' => 'CPF é obrigatório!',
                'date_birth' => 'Data de aniversário é obrigatório!',
                'password' => 'Senha é obrigatório!',
            ],
            "unique" => 'O :attribute já está em uso. Por favor, escolha outro.',
            "email" => 'O :attribute precisa ser um endereço de e-mail válido.',
            "min" =>  'O :attribute precisa ter no mínimo :min caracteres.',
        ]);


        if ($validate->fails()) return response()->json(['errors' => $validate->errors()], 422);

        $user = new User;

        $user->name = $request->name;
        $user->nick_name = $request->nick_name ?? explode(' ', $request->name)[0];
        $user->phone = str_replace([' ', '(', ')', '-'], '', $request->phone);
        $user->email = $request->email;
        $user->date_birth = Carbon::createFromFormat('d/m/Y', $request->date_birth)->format('Y-m-d');
        $user->gender = $request->gender;
        $user->document = str_replace(['.', '-'], '', $request->document);
        $user->password =  Hash::make($request->password);

        if (!$user->save()) return response()->json(['error' => 'Erro ao criar conta, tente mais tarde!'], 400);

        return response()->json(['succes', 'Conta criada com sucesso!'], 201);
    }

    // Atualiza usuário
    public function update(Request $request, $id)
    {
        $user =  User::find($id);

        if (empty($user)) return response()->json(['error' => 'Usuário inexistente.'], 404);

        $user->name = $request->name;
        $user->nick_name = $request->nick_name ?? explode(' ', $request->name)[0];
        $user->phone = str_replace([' ', '(', ')', '-'], '', $request->phone);
        $user->email = $request->email;
        $user->date_birth = Carbon::createFromFormat('d/m/Y', $request->date_birth)->format('Y-m-d');
        $user->gender = $request->gender;
        $user->document = str_replace(['.', '-'], '', $request->document);
        //$user->password =  Hash::make($request->password);

        if (!$user->save()) return response()->json(['error' => 'Erro ao atualizar conta, tente mais tarde!'], 400);

        return response()->json(['succes', 'Conta atualizada com sucesso!'], 201);
    }

    // Deleta usuário
    public function delete($id)
    {
        if (!$id) return response()->json(['error' => 'Precisa de um usuário válido!'], 404);

        $user = User::find($id);

        if (empty($user)) return response()->json(['error' => 'Usuário inexistente.'], 404);

        $user->delete();

        return response()->json(['success' => 'Conta deletada.']);
    }
}

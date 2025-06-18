<?php

namespace App\Domain\User\Controllers;

use App\Domain\User\Models\User;
use App\Domain\User\Requests\UserCsvImportRequest;
use App\Domain\User\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserCsvImportController extends Controller
{
    public function __invoke(UserCsvImportRequest $request): UserResource | JsonResponse
    {
        $file = $request->file('csv');

        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return response()->json(['error' => 'No se pudo abrir el archivo.'], 400);
        }

        $headers = fgetcsv($handle);

        while (!feof($handle)) {
            $row = fgetcsv($handle);

            if ($row === false || count($row) !== count($headers)) {
                continue;
            }

            $data = array_combine($headers, $row);

            $user = User::withTrashed()->where('email', $data['email'])->first();

            if ($user) {
                $user->update([
                    'name' => $data['name'],
                    'surname' => $data['surname'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                ]);

                if ($user->trashed()) {
                    $user->restore();
                }
            } else {
                $user = User::create([
                    'name' => $data['name'],
                    'surname' => $data['surname'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'password' => Hash::make(Str::random(10)),
                ]);
            }
        }

        fclose($handle);

        return UserResource::make($user);
    }
}

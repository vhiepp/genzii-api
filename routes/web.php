<?php

use Illuminate\Support\Facades\Route;
use App\Models\Account;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
//    $user = User::find('9ac0f5a7-1c3c-47af-86be-3fcc0c9f4beb');
//    $userService = new \App\Services\UserService();


//    $user->friendRequests()->syncWithoutDetaching(['9ac0f5a8-2aa8-40a2-b6a9-31a6fc7e0fb0']);
//    $userService->addFriend('9ac0f5a7-1c3c-47af-86be-3fcc0c9f4beb', User::find('9ac0f5a8-2c2c-4e5b-9f51-8ac97ccb42fe'));
//    $friends = $user->friends;
//    $friendRequests = $user->friendRequests;
//    return response()->json($user);

    $jwtToken = "eyJhbGciOiJSUzI1NiIsImtpZCI6IjBiYmQyOTllODU2MmU3MmYyZThkN2YwMTliYTdiZjAxMWFlZjU1Y2EiLCJ0eXAiOiJKV1QifQ.eyJuYW1lIjoiRHVvbmcgVmFuIEhpZXAiLCJpc3MiOiJodHRwczovL3NlY3VyZXRva2VuLmdvb2dsZS5jb20vc3Y1dC10dnUtY2E3NGIiLCJhdWQiOiJzdjV0LXR2dS1jYTc0YiIsImF1dGhfdGltZSI6MTcwMTU0MDE3MywidXNlcl9pZCI6IjdIOEY0M3Nlc3JXNURLYUM0Q1BXZUNaT3JMOTMiLCJzdWIiOiI3SDhGNDNzZXNyVzVES2FDNENQV2VDWk9yTDkzIiwiaWF0IjoxNzAxNTQwMTczLCJleHAiOjE3MDE1NDM3NzMsImVtYWlsIjoiMTEwMTIxMjA5QHN0LnR2dS5lZHUudm4iLCJlbWFpbF92ZXJpZmllZCI6ZmFsc2UsImZpcmViYXNlIjp7ImlkZW50aXRpZXMiOnsibWljcm9zb2Z0LmNvbSI6WyJhMGVlNmRiNy0yZjA5LTQxMWItYWQzMS1mOTEzOWY4ODE2YzgiXSwiZW1haWwiOlsiMTEwMTIxMjA5QHN0LnR2dS5lZHUudm4iXX0sInNpZ25faW5fcHJvdmlkZXIiOiJtaWNyb3NvZnQuY29tIn19.p9C7-WM4QsX9HQ8Pd1wCYIv_hEHUqDaKhVftX6HBZmgPXYqU4-V3ff_LVZT5_b3ziM56yb1nP5eMgEe4wryakZS15y3XGcOBlxv98Aa8IqWM9asRrQ6CK7LOa3khESlpG-yNxcd1cQlLxDOw3SMOjdgqyC9_Zhl6OoBse_eTF9ldbufUl6grN9zLEsMckRmwgBDnzm3MtSiL6jP1v3bWY0xnVfzk7dk9JDk9nNn6rRJ2imAOml-bwj0ENpM8ZIU7T5Pem-nrudV3x417CCGvGs1MPtv6NdLDApQD3oinlSaqUTHWQnDEL7Uq2rELIQUJL-SYbo6IoSv_qLTEUlLjTg";

//    $key = "-----BEGIN CERTIFICATE-----\nMIIDTDCCAjSgAwIBAgIIPAmsQcx+acIwDQYJKoZIhvcNAQEFBQAwSTFHMEUGA1UE\nAxM+ZmlyZWJhc2UtYWRtaW5zZGstNDNnd2wuc3Y1dC10dnUtY2E3NGIuaWFtLmdz\nZXJ2aWNlYWNjb3VudC5jb20wHhcNMjMwODE0MTE0MTA4WhcNMjUwOTEyMDIwMTM2\nWjBJMUcwRQYDVQQDEz5maXJlYmFzZS1hZG1pbnNkay00M2d3bC5zdjV0LXR2dS1j\nYTc0Yi5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbTCCASIwDQYJKoZIhvcNAQEBBQAD\nggEPADCCAQoCggEBAKRagn6vq0FLFBV6dxk5WvkD/bqreZCoiXl8ehyHqiylzFZi\nCBxN9Cy1jWYHiZ8FClG8SfGAsp1M7wpxqj3IAVwlMtO/KAZtCCYqZhAEkdxJsa95\nFVCRZUJXEupssGUPDL07nXgN8k+PzytjSLrbDk1rCbDYUPFxIkaR0JxyyCj5xFHV\nLgOVJC8OiP5cuOx+lB8juoID2goICI5feVRszCkxZYuld4RkQ/oRTOqtJqgLZaYY\n3VYMbIKJGZ/rfOdZ/GxVLuACKvRf4470Fx0MoP0D6w+UfREa4zbKYVP68Dk5PSWX\nbJm5qvoa4S13dh7EBh2OV5XLimrV6atVu/vudbsCAwEAAaM4MDYwDAYDVR0TAQH/\nBAIwADAOBgNVHQ8BAf8EBAMCB4AwFgYDVR0lAQH/BAwwCgYIKwYBBQUHAwIwDQYJ\nKoZIhvcNAQEFBQADggEBACJCFyWW1ZIjcHFsNFCfFjaYtU5VR0e2iF7e8QBuByp6\nyCjj5gMHPenKG26ZSuBY2Y9wWDS33HdkBo7D/5gzF8GcaJ3jE6/74Rt9qXW97raU\nLVMEq0Ne0lYPQc2J6ZRTS3arJVCHnmoGGjYLnN+SgGxWSaj1GAPY+LcVqL134hd5\nOWx89br7PxJvTBuODF+PUUHLy71d7Tk1MCq3bM5k87b2Kfd5lzBIWgSSgatN7n/m\nyn0V2Osqy0w+bIO62d0f303hEHcu2RBMWcLxn7iquQ9dcqQ5Q8uWI/D4LaVsqEsn\n8RVQJ9jR/mLG72OlsI1wD4ySS5aKw29C8ev05rxEoHc=\n-----END CERTIFICATE-----\n";

//    $res = openssl_get_privatekey("-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCdIQofgBuloIxx\nsPOKMmh1M4N59XHq/E3WIb5Nqd1tKsXJyoQSXa9D44E4BKA46zmXbOMMZnLTs+e2\nvE6KTJDqO+y5ck+1Qo4GawHayGEsHew1kKRqTgI7fHr0pf2pUH9EWQw6gKPckxJI\nz7sOTnX/QRdF8KVCFXcj9gl9Rg5xh7aI1t8XCQ4JXCnudkB65hFm18MJyqGsuJlg\nPb0owkXHzRuMDqfS2HUAHczg9wWXedVguJk3yNduFjnHj4hwYDoZRY5uyq93wG62\nUXu2yWecjsOapSLETbkidUlAAFztyonH5ko8k5eKM0QKJ7gRNf3RlaaJv/fPq5ES\nQMCa3FZjAgMBAAECggEADMhYzfo6hY1bj44xKc9y/wHbUsscYtz2qhJhrGjCujRO\nFd3zQ5k6fl/eLcP2ktrT+xUhHWuiMMXEdnG4Q/j5mwyX8/gwMtNAwZXn9zfw68ld\nY3gqX/mLpfI5AHkWezUwvUsvOv0nF3UheinoEkBgygyuC4MJtqgPYq4L1JSj7SlY\nAGGL/oCrEDWaJ9HZAta8+TjC5fGMoX17kaCqrd6jlzoGfXrWEJ/BVLFovHC4m7Z9\nxsF5hGDPkhZswmPqSr/N5DM6rPiU9GShGu8jwaX/vSH8PtKqrARAHle44nWqbCuJ\nKnazrUQ3nEBcy8Ag1h8hKuQex5yrzDJTj5wox3ZhXQKBgQDXzBZxtp/ouGMDw3eD\nfIq535M1b3Dyhqo+1jnekKUCU720VqZSVSDgDWRTpHy87ZYhV+2CsovkeCeuBBxv\nZlKr9i8AN73OODNLpYu8vH/ZWslzijQMqod83/ylpdNOZKljeq8gaGkMrRg6NV3J\nza8rFtNTk8QuWX5dnLKzv7gA3wKBgQC6Zut65BBuno9Z6NQC/iD2fSxMjgM6FR/y\n6glsuKd7HGaEyVrQrekKfrqnRG0sSf7ntlZALo1zgXfO+LEHxPoBePW33Bg9RejT\nF0p6fghIXNE6CKrTkKSX5QntFTEerVX/6ZZr4ILyXWINc3aFmYzDaUkGsxMs4tsg\n4T7N5onG/QKBgGla3mpN+uwneU/REnxDRYdRvBEOg1oknOFovYAHpkfokmmFFFaF\n8TLx1tVvLOcgJ3fH8dEpUlsMvnKa1zccAaiq3G+Cx0vy7Dg2bm6KqPB4/nlGM/sr\nFlLFGBfXSb6wLnK+5tkvoHnr2DQy8cMghDDJSAs5zUHDt4oEgsp3A2KJAoGBAIt9\nR0ygbJeLOnUq4bUXnW03gLYNM+b8NftfHgxpJpPfTeyijb5J1nHPIjjX4ObavNGD\nRoojDCFaymBtBHVu9zOw5BhhsRXxQejtrgN6WxJjuwK1x7gorrMoZ8QuLqLpTckD\ncVhrGzLppN2yAQi7PnZhTI+h5ICbhyXUyV3l3ka1AoGBAJZMYIZfdP9TjONZr5Cg\ne5QjjpSVe56XmL125k47xeOq7gaUHxI4GinV7UT9tLYWhWtJ9A209W3iJjm0n+NT\nk/izheTCWgrP0I/VL8bCYQFFHg5KMzC45GGbAkoVLhB4ySh0c8F3HjqFOM0p0VgA\nXDZqlXIl0C+0dnBXRp0fVzOt\n-----END PRIVATE KEY-----\n");
//    $details = openssl_pkey_get_details($res);
//    $publicKey = $details['key'];

    // Tách token thành ba phần: header, payload và signature
//    $segments = explode('.', $jwtToken);
//
//    // Lấy header
//    $header = base64_decode($segments[0]);
//
//    // Lấy payload
//    $payload = base64_decode($segments[1]);
//
//    // Lấy key
//    $key = 'secret-key';
//
//    // Khởi tạo hàm mã hóa HMAC
//    $hmac = new Lcobucci\JWT\Signer\Hmac\Sha256($key);
//
//    // Tạo chữ ký dự đoán
////    $signature_guess = $hmac->hash($header . '.' . $payload);
//    $signature_guess = $hmac->sign($payload, $key);
//    // So sánh chữ ký dự đoán với chữ ký thực tế
//    if ($signature_guess === $segments[2]) {
//        // Giải mã payload
//        $payload = json_decode(base64_decode($payload), true);
//
//        dd($payload);
//    }

//    $jwt_token = "YOUR_JWT_TOKEN_HERE"; // JWT token cần giải mã

// Tách các phần của chuỗi JWT
    $token_parts = explode('.', $jwtToken);

// Payload là phần thứ hai của chuỗi JWT
    $encoded_payload = $token_parts[1];

// Giải mã phần payload từ base64
    $decoded_payload = base64_decode($encoded_payload);

// Hiển thị thông tin trong payload
    dd(json_decode($decoded_payload));



//    $decoded = JWT::decode($jwtToken, null);
//
//    // $decoded chứa dữ liệu được giải mã từ JWT
//    print_r($decoded);
//    try {
//    } catch (\Exception $e) {
//        // Xử lý lỗi khi giải mã JWT thất bại
//        echo 'Exception: ' . $e->getMessage();
//    }

//    $publicKeyEndpoint = 'https://www.googleapis.com/service_accounts/v1/jwk/firebase-adminsdk-43gwl@sv5t-tvu-ca74b.iam.gserviceaccount.com';
//
//    $ch = curl_init($publicKeyEndpoint);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    $response = curl_exec($ch);
//
//    if ($response === false) {
//        echo 'Error: ' . curl_error($ch);
//    } else {
//        $publicKeys = json_decode($response, true);
////        print_r($publicKeys);
//        $decoded = JWT::decode($jwtToken, new Key($key, 'RS256'));
//        print_r($decoded);
//    }
//
//    curl_close($ch);


});
